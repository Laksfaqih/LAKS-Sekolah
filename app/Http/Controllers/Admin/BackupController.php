<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\RestoreDatabaseRequest;
use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class BackupController extends Controller
{
    public function edit(): View
    {
        return view('admin.backup-restore.index', [
            'databaseDriver' => config('database.default'),
            'databaseName' => config('database.connections.'.config('database.default').'.database'),
            'databaseHost' => config('database.connections.'.config('database.default').'.host'),
            'databasePath' => $this->isSqlite() ? $this->databasePath() : null,
            'backups' => collect(Storage::disk('local')->files('backups'))
                ->map(fn (string $path) => [
                    'path' => $path,
                    'name' => basename($path),
                    'updated_at' => Storage::disk('local')->lastModified($path),
                    'size' => Storage::disk('local')->size($path),
                ])
                ->sortByDesc('updated_at')
                ->values(),
        ]);
    }

    public function backup(): StreamedResponse|RedirectResponse
    {
        if ($this->isMySql()) {
            $filename = 'laksbel-backup-'.now()->format('Ymd-His').'.sql';
            $relativePath = "backups/{$filename}";

            Storage::disk('local')->put($relativePath, $this->buildMySqlDump());

            return Storage::disk('local')->download($relativePath, $filename);
        }

        if (! $this->isSqlite()) {
            return back()->with('error', 'Driver database ini belum didukung untuk backup bawaan.');
        }

        $databasePath = $this->databasePath();

        if (! is_file($databasePath)) {
            return back()->with('error', 'File database SQLite tidak ditemukan.');
        }

        $filename = 'laksbel-backup-'.now()->format('Ymd-His').'.sqlite';
        $relativePath = "backups/{$filename}";

        Storage::disk('local')->put($relativePath, file_get_contents($databasePath));

        return Storage::disk('local')->download($relativePath, $filename);
    }

    public function download(Request $request): StreamedResponse
    {
        $path = $request->string('file')->toString();

        abort_unless(Storage::disk('local')->exists($path), 404);

        return Storage::disk('local')->download($path, basename($path));
    }

    public function restore(RestoreDatabaseRequest $request): RedirectResponse
    {
        if ($this->isMySql()) {
            try {
                $this->restoreMySqlDump((string) file_get_contents($request->file('database_file')->getRealPath()));
            } catch (QueryException $exception) {
                return back()->with('error', 'Restore MySQL gagal diproses: '.$exception->getMessage());
            }

            return redirect()->route('admin.backup-restore.edit')
                ->with('success', 'Database MySQL berhasil direstore dari file SQL.');
        }

        if (! $this->isSqlite()) {
            return back()->with('error', 'Driver database ini belum didukung untuk restore bawaan.');
        }

        $databasePath = $this->databasePath();
        $uploadedPath = $request->file('database_file')->getRealPath();

        DB::disconnect(config('database.default'));
        DB::purge(config('database.default'));

        copy($uploadedPath, $databasePath);

        DB::reconnect(config('database.default'));

        return redirect()->route('admin.backup-restore.edit')
            ->with('success', 'Database berhasil direstore dari file backup.');
    }

    private function isSqlite(): bool
    {
        return config('database.default') === 'sqlite';
    }

    private function isMySql(): bool
    {
        return in_array(config('database.default'), ['mysql', 'mariadb'], true);
    }

    private function databasePath(): string
    {
        $path = config('database.connections.sqlite.database');

        if ($path === null || $path === ':memory:') {
            return database_path('database.sqlite');
        }

        return str_starts_with($path, '/')
            ? $path
            : base_path($path);
    }

    private function buildMySqlDump(): string
    {
        $databaseName = config('database.connections.'.config('database.default').'.database');
        $tables = collect(DB::select('SHOW TABLES'))
            ->map(fn ($row) => (string) collect((array) $row)->values()->first())
            ->values();

        $dump = [
            '-- LAKS-Bel MySQL Backup',
            '-- Database: '.$databaseName,
            '-- Generated at: '.now()->format('Y-m-d H:i:s'),
            'SET FOREIGN_KEY_CHECKS=0;',
            '',
        ];

        foreach ($tables as $table) {
            $createTableRow = (array) DB::selectOne("SHOW CREATE TABLE `{$table}`");
            $createStatement = collect($createTableRow)
                ->filter(fn ($value, $key) => str_contains((string) $key, 'Create Table'))
                ->first();

            $dump[] = "DROP TABLE IF EXISTS `{$table}`;";
            $dump[] = (string) $createStatement.';';

            foreach ($this->tableInsertStatements($table) as $statement) {
                $dump[] = $statement;
            }

            $dump[] = '';
        }

        $dump[] = 'SET FOREIGN_KEY_CHECKS=1;';

        return implode("\n", $dump)."\n";
    }

    /**
     * @return Collection<int, string>
     */
    private function tableInsertStatements(string $table): Collection
    {
        $rows = collect(DB::table($table)->get()->map(fn ($row) => (array) $row));

        if ($rows->isEmpty()) {
            return collect();
        }

        $columns = array_keys($rows->first());
        $quotedColumns = collect($columns)->map(fn (string $column) => "`{$column}`")->implode(', ');
        $pdo = DB::connection()->getPdo();

        return $rows->map(function (array $row) use ($table, $columns, $quotedColumns, $pdo): string {
            $values = collect($columns)->map(function (string $column) use ($row, $pdo) {
                $value = $row[$column];

                if ($value === null) {
                    return 'NULL';
                }

                if (is_bool($value)) {
                    return $value ? '1' : '0';
                }

                if (is_int($value) || is_float($value)) {
                    return (string) $value;
                }

                return $pdo->quote((string) $value);
            })->implode(', ');

            return "INSERT INTO `{$table}` ({$quotedColumns}) VALUES ({$values});";
        });
    }

    private function restoreMySqlDump(string $sql): void
    {
        DB::disconnect(config('database.default'));
        DB::reconnect(config('database.default'));

        foreach ($this->splitSqlStatements($sql) as $statement) {
            $trimmed = trim($statement);

            if ($trimmed === '' || str_starts_with($trimmed, '--')) {
                continue;
            }

            DB::unprepared($trimmed);
        }
    }

    /**
     * @return list<string>
     */
    private function splitSqlStatements(string $sql): array
    {
        $statements = [];
        $buffer = '';
        $length = strlen($sql);
        $inSingleQuote = false;
        $inDoubleQuote = false;
        $isEscaped = false;

        for ($i = 0; $i < $length; $i++) {
            $char = $sql[$i];
            $buffer .= $char;

            if ($char === '\\' && ! $isEscaped) {
                $isEscaped = true;
                continue;
            }

            if ($char === "'" && ! $inDoubleQuote && ! $isEscaped) {
                $inSingleQuote = ! $inSingleQuote;
            } elseif ($char === '"' && ! $inSingleQuote && ! $isEscaped) {
                $inDoubleQuote = ! $inDoubleQuote;
            } elseif ($char === ';' && ! $inSingleQuote && ! $inDoubleQuote) {
                $statements[] = $buffer;
                $buffer = '';
            }

            $isEscaped = false;
        }

        if (trim($buffer) !== '') {
            $statements[] = $buffer;
        }

        return $statements;
    }
}
