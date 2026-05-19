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
        @page { margin: 18mm 14mm; }
        body { font-family: Arial, sans-serif; color: #0f172a; margin: 0; }
        .report-header {
            display: table;
            width: 100%;
            border-bottom: 2px solid #0f172a;
            padding-bottom: 18px;
            margin-bottom: 20px;
        }
        .report-header__logo,
        .report-header__content {
            display: table-cell;
            vertical-align: top;
        }
        .report-header__logo {
            width: 90px;
            padding-right: 16px;
        }
        .report-header__logo img {
            display: block;
            width: 72px;
            height: 72px;
            object-fit: contain;
        }
        .report-header__content {
            width: auto;
        }
        .school-name {
            margin: 0;
            font-size: 20px;
            font-weight: 700;
            letter-spacing: 0.02em;
        }
        .school-meta,
        .report-subtitle {
            margin: 6px 0 0;
            color: #475569;
            font-size: 12px;
            line-height: 1.5;
        }
        .report-title {
            margin: 12px 0 0;
            font-size: 22px;
            font-weight: 700;
        }
        table { width: 100%; border-collapse: collapse; font-size: 12px; }
        th, td { border: 1px solid #cbd5e1; padding: 8px; text-align: left; vertical-align: top; }
        th { background: #f8fafc; }
        @media print {
            body { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        }
    </style>
</head>
<body>
    <div class="report-header">
        <div class="report-header__logo">
            <img src="{{ asset('logo-sekolah.png') }}" alt="Logo Sekolah">
        </div>
        <div class="report-header__content">
            @isset($school)
                <p class="school-name">{{ $school['name'] }}</p>
                @if ($school['meta'] !== '')
                    <p class="school-meta">{{ $school['meta'] }}</p>
                @endif
            @endisset
            <h1 class="report-title">{{ $title }}</h1>
            <p class="report-subtitle">{{ $subtitle }}</p>
        </div>
    </div>

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
