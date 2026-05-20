<!DOCTYPE html>
<html lang="cs" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $fileName ?? 'Dokumentace' }} · DEV-frame</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('vendor/DEV-frame/DEV-frame.css') }}">
</head>
<body class="h-full flex bg-slate-50" style="font-family: 'Inter', ui-sans-serif, system-ui, sans-serif;">

    {{-- Sidebar --}}
    <aside class="w-56 shrink-0 bg-slate-950 flex flex-col">

        {{-- Brand --}}
        <a href="{{ url('/') }}" class="px-4 h-14 flex items-center border-b border-slate-800/60 hover:bg-slate-900 transition-colors">
            <div class="flex items-center gap-2.5">
                <div class="w-7 h-7 rounded-lg bg-slate-700 flex items-center justify-center shrink-0">
                    <svg width="14" height="14" viewBox="0 0 16 16" fill="none" stroke="white" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="4.5,3 1,8 4.5,13"/>
                        <polyline points="11.5,3 15,8 11.5,13"/>
                        <line x1="9.5" y1="2.5" x2="6.5" y2="13.5"/>
                    </svg>
                </div>
                <span class="text-white font-semibold text-sm tracking-tight"><span class="text-slate-300">DEV</span>-frame</span>
            </div>
        </a>

        {{-- Navigation --}}
        <nav class="flex-1 px-3 py-4 overflow-y-auto space-y-0.5">

            <p class="text-[10px] font-semibold text-slate-500 uppercase tracking-widest px-2 pb-1.5">Dokumentace</p>

            @php $activeDoc = strtolower(str_replace('.md', '', $fileName ?? '')); @endphp

            <a href="{{ url('/doc/readme') }}"
               class="flex items-center gap-2.5 px-2.5 py-2 rounded-md transition-colors text-sm group
                      {{ $activeDoc === 'readme' ? 'bg-slate-800 text-white' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                <svg class="w-4 h-4 shrink-0 {{ $activeDoc === 'readme' ? 'text-indigo-400' : 'text-slate-500 group-hover:text-indigo-400 transition-colors' }}"
                     fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                README.md
            </a>

            <a href="{{ url('/doc/devlog') }}"
               class="flex items-center gap-2.5 px-2.5 py-2 rounded-md transition-colors text-sm group
                      {{ $activeDoc === 'devlog' ? 'bg-slate-800 text-white' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                <svg class="w-4 h-4 shrink-0 {{ $activeDoc === 'devlog' ? 'text-indigo-400' : 'text-slate-500 group-hover:text-indigo-400 transition-colors' }}"
                     fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
                DEVLOG.md
            </a>

            @if(isset($versions) && count($versions) > 0)
            <p class="text-[10px] font-semibold text-slate-500 uppercase tracking-widest px-2 pb-1.5 pt-5">Verze</p>
            @foreach($versions as $version)
            <a href="{{ url('/' . $version) }}"
               class="flex items-center gap-2.5 px-2.5 py-2 rounded-md text-slate-400 hover:text-white hover:bg-slate-800 transition-colors text-sm group">
                <svg class="w-4 h-4 shrink-0 text-slate-500 group-hover:text-emerald-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.429 9.75L2.25 12l4.179 2.25m0-4.5l5.571 3 5.571-3m-11.142 0L2.25 7.5 12 2.25l9.75 5.25-4.179 2.25m0 0L21.75 12l-4.179 2.25m0 0l4.179 2.25L12 21.75 2.25 16.5l4.179-2.25m11.142 0l-5.571 3-5.571-3"/>
                </svg>
                {{ strtoupper($version) }}
            </a>
            @endforeach
            @endif
        </nav>

        {{-- Footer --}}
        <div class="px-4 py-3 border-t border-slate-800/60">
            <p class="text-[11px] text-slate-600">DEV-frame · v0.1</p>
        </div>
    </aside>

    {{-- Main --}}
    <div class="flex-1 flex flex-col overflow-hidden">

        {{-- Top bar --}}
        <header class="bg-white border-b border-slate-200 h-14 px-8 flex items-center justify-between shrink-0">
            <span class="text-sm font-semibold text-slate-700">{{ $fileName ?? 'Dokumentace' }}</span>
            <span class="text-xs text-slate-400">{{ now()->format('d.m.Y') }}</span>
        </header>

        {{-- Content --}}
        <main class="flex-1 overflow-auto">
            <div class="max-w-3xl mx-auto px-8 py-10">
                <article class="prose prose-slate max-w-none
                    prose-headings:font-semibold
                    prose-h1:text-2xl prose-h1:text-slate-800
                    prose-h2:text-xl prose-h2:text-slate-700 prose-h2:border-b prose-h2:border-slate-200 prose-h2:pb-2
                    prose-a:text-indigo-600 prose-a:no-underline hover:prose-a:underline
                    prose-code:bg-slate-100 prose-code:text-slate-700 prose-code:px-1.5 prose-code:py-0.5 prose-code:rounded prose-code:text-[0.85em] prose-code:font-mono
                    prose-pre:bg-slate-900 prose-pre:text-slate-100">
                    {!! $htmlContent !!}
                </article>
            </div>
        </main>

    </div>

</body>
</html>
