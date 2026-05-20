<!DOCTYPE html>
<html lang="cs" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $fileName ?? 'Dokumentace' }} · dev-frame</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('vendor/dev-frame/dev-frame.css') }}">
</head>
<body class="h-full bg-slate-50" style="font-family: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif;">

    {{-- Top bar --}}
    <header class="bg-white border-b border-slate-200 h-14 px-6 flex items-center gap-4 sticky top-0 z-10">
        <a href="{{ url('/') }}"
           class="flex items-center gap-1.5 text-sm text-slate-500 hover:text-slate-800 transition-colors">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
            </svg>
            Zpět
        </a>
        <span class="text-slate-300">|</span>
        <span class="text-sm font-medium text-slate-700">{{ $fileName ?? 'Dokumentace' }}</span>
    </header>

    {{-- Content --}}
    <main class="max-w-3xl mx-auto px-6 py-10">
        <article class="prose prose-slate prose-sm max-w-none
            prose-headings:font-semibold
            prose-h1:text-2xl prose-h1:text-slate-800
            prose-h2:text-xl prose-h2:text-slate-700 prose-h2:border-b prose-h2:border-slate-200 prose-h2:pb-2
            prose-a:text-indigo-600 prose-a:no-underline hover:prose-a:underline
            prose-code:bg-slate-100 prose-code:text-slate-700 prose-code:px-1.5 prose-code:py-0.5 prose-code:rounded prose-code:text-[0.85em] prose-code:font-mono
            prose-pre:bg-slate-900 prose-pre:text-slate-100">
            {!! $htmlContent !!}
        </article>
    </main>

</body>
</html>
