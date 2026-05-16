<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <script>
        window.addEventListener('load', () => window.print());
    </script>
    <style>
        body { font-family: Arial, sans-serif; color: #0f172a; margin: 32px; }
        h1 { margin: 0 0 8px; font-size: 24px; }
        p { margin: 0 0 20px; color: #475569; }
        table { width: 100%; border-collapse: collapse; font-size: 12px; }
        th, td { border: 1px solid #cbd5e1; padding: 8px; text-align: left; vertical-align: top; }
        th { background: #f8fafc; }
    </style>
</head>
<body>
    <h1>{{ $title }}</h1>
    @isset($school)
        <p>{{ $school['name'] }}</p>
        @if ($school['meta'] !== '')
            <p>{{ $school['meta'] }}</p>
        @endif
    @endisset
    <p>{{ $subtitle }}</p>

    <table>
        <thead>
            <tr>
                @foreach ($columns as $column)
                    <th>{{ $column }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @forelse ($rows as $row)
                <tr>
                    @foreach ($row as $cell)
                        <td>{{ $cell }}</td>
                    @endforeach
                </tr>
            @empty
                <tr>
                    <td colspan="{{ count($columns) }}">Belum ada data.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
