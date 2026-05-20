<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rozcestník Projektu</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 800px;
            margin: 0 auto;
            padding: 2rem;
            background-color: #f9f9f9;
        }
        h1 {
            color: #2c3e50;
            border-bottom: 2px solid #3498db;
            padding-bottom: 0.5rem;
        }
        h2 {
            color: #2c3e50;
            margin-top: 2rem;
        }
        .card {
            background: #fff;
            padding: 1.5rem;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 1.5rem;
        }
        ul {
            list-style-type: none;
            padding: 0;
        }
        li {
            margin-bottom: 0.5rem;
        }
        a {
            color: #3498db;
            text-decoration: none;
            font-weight: bold;
        }
        a:hover {
            text-decoration: underline;
        }
        .version-link {
            display: block;
            padding: 0.8rem;
            background-color: #ecf0f1;
            border-radius: 5px;
            color: #2c3e50;
            transition: background-color 0.2s;
        }
        .version-link:hover {
            background-color: #d5dbdb;
            text-decoration: none;
        }
        .empty-state {
            color: #7f8c8d;
            font-style: italic;
        }
    </style>
</head>
<body>
    <h1>Rozcestník Projektu</h1>

    <div class="card">
        <h2>Dokumentace</h2>
        <ul>
            <li><a href="{{ url('/doc/readme') }}" target="_blank">📄 Otevřít README.md</a></li>
            <li><a href="{{ url('/doc/devlog') }}" target="_blank">📝 Otevřít DEVLOG.md</a></li>
        </ul>
    </div>

    <div class="card">
        <h2>Verze aplikace</h2>
        @if(isset($versions) && count($versions) > 0)
            <ul>
                @foreach($versions as $version)
                    <li>
                        <a href="{{ url('/' . $version) }}" class="version-link">
                            🚀 Spustit verzi {{ strtoupper($version) }}
                        </a>
                    </li>
                @endforeach
            </ul>
        @else
            <p class="empty-state">Zatím nebyly vytvořeny žádné verze. Vytvořte složku ve <code>resources/views/versions/</code>.</p>
        @endif
    </div>
</body>
</html>
