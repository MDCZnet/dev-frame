<!DOCTYPE html>
<html lang="cs" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>dev-frame</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('vendor/dev-frame/dev-frame.css') }}">
</head>
<body class="h-full flex bg-slate-50" style="font-family: 'Inter', ui-sans-serif, system-ui, sans-serif;">

    {{-- Sidebar --}}
    <aside class="w-56 shrink-0 bg-slate-950 flex flex-col">

        {{-- Brand --}}
        <div class="px-4 h-14 flex items-center border-b border-slate-800/60">
            <div class="flex items-center gap-2.5">
                <div class="w-7 h-7 rounded-lg bg-indigo-500 flex items-center justify-center shrink-0">
                    <svg width="14" height="14" viewBox="0 0 16 16" fill="white">
                        <rect x="1" y="1" width="5.5" height="5.5" rx="1.2"/>
                        <rect x="9.5" y="1" width="5.5" height="5.5" rx="1.2"/>
                        <rect x="1" y="9.5" width="5.5" height="5.5" rx="1.2"/>
                        <rect x="9.5" y="9.5" width="5.5" height="5.5" rx="1.2"/>
                    </svg>
                </div>
                <span class="text-white font-semibold text-sm tracking-tight">dev-frame</span>
            </div>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 px-3 py-4 overflow-y-auto space-y-0.5">

            <p class="text-[10px] font-semibold text-slate-500 uppercase tracking-widest px-2 pb-1.5">Dokumentace</p>

            <a href="{{ url('/doc/readme') }}"               class="flex items-center gap-2.5 px-2.5 py-2 rounded-md text-slate-400 hover:text-white hover:bg-slate-800 transition-colors text-sm group">
                <svg class="w-4 h-4 shrink-0 text-slate-500 group-hover:text-indigo-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                README.md
            </a>

            <a href="{{ url('/doc/devlog') }}"               class="flex items-center gap-2.5 px-2.5 py-2 rounded-md text-slate-400 hover:text-white hover:bg-slate-800 transition-colors text-sm group">
                <svg class="w-4 h-4 shrink-0 text-slate-500 group-hover:text-indigo-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
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
            <p class="text-[11px] text-slate-600">dev-frame · v0.1</p>
        </div>
    </aside>

    {{-- Main --}}
    <div class="flex-1 flex flex-col overflow-hidden">

        {{-- Top bar --}}
        <header class="bg-white border-b border-slate-200 h-14 px-8 flex items-center justify-between shrink-0">
            <h1 class="text-sm font-semibold text-slate-700">Rozcestník projektu</h1>
            <span class="text-xs text-slate-400">{{ now()->format('d.m.Y') }}</span>
        </header>

        {{-- Content --}}
        <main class="flex-1 overflow-auto p-8">
            <div class="max-w-4xl">

                <div class="mb-8">
                    <h2 class="text-2xl font-semibold text-slate-800">Vítej</h2>
                    <p class="text-slate-500 mt-1 text-sm">Vyberte verzi aplikace nebo si prohlédněte dokumentaci.</p>
                </div>

                {{-- Docs --}}
                <p class="text-[11px] font-semibold text-slate-400 uppercase tracking-widest mb-3">Dokumentace</p>
                <div class="grid grid-cols-2 gap-4 mb-8">

                    <a href="{{ url('/doc/readme') }}"                       class="bg-white rounded-xl border border-slate-200 p-5 hover:border-indigo-300 hover:shadow-md transition-all group">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-9 h-9 rounded-lg bg-indigo-50 flex items-center justify-center group-hover:bg-indigo-100 transition-colors">
                                <svg class="w-5 h-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-slate-700">README.md</p>
                                <p class="text-xs text-slate-400">Dokumentace projektu</p>
                            </div>
                        </div>
                        <span class="text-xs font-medium text-indigo-500 group-hover:text-indigo-600">Otevřít →</span>
                    </a>

                    <a href="{{ url('/doc/devlog') }}"                       class="bg-white rounded-xl border border-slate-200 p-5 hover:border-violet-300 hover:shadow-md transition-all group">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-9 h-9 rounded-lg bg-violet-50 flex items-center justify-center group-hover:bg-violet-100 transition-colors">
                                <svg class="w-5 h-5 text-violet-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-slate-700">DEVLOG.md</p>
                                <p class="text-xs text-slate-400">Vývojářský deník</p>
                            </div>
                        </div>
                        <span class="text-xs font-medium text-violet-500 group-hover:text-violet-600">Otevřít →</span>
                    </a>
                </div>

                {{-- Versions --}}
                <p class="text-[11px] font-semibold text-slate-400 uppercase tracking-widest mb-3">Verze aplikace</p>
                @if(isset($versions) && count($versions) > 0)
                <div class="grid grid-cols-3 gap-4">
                    @foreach($versions as $version)
                    <a href="{{ url('/' . $version) }}"
                       class="bg-white rounded-xl border border-slate-200 p-5 hover:border-emerald-300 hover:shadow-md transition-all group">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-9 h-9 rounded-lg bg-emerald-50 flex items-center justify-center group-hover:bg-emerald-100 transition-colors">
                                <svg class="w-5 h-5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.429 9.75L2.25 12l4.179 2.25m0-4.5l5.571 3 5.571-3m-11.142 0L2.25 7.5 12 2.25l9.75 5.25-4.179 2.25m0 0L21.75 12l-4.179 2.25m0 0l4.179 2.25L12 21.75 2.25 16.5l4.179-2.25m11.142 0l-5.571 3-5.571-3"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-slate-700">{{ strtoupper($version) }}</p>
                                <p class="text-xs text-slate-400">UI prototyp</p>
                            </div>
                        </div>
                        <span class="text-xs font-medium text-emerald-500 group-hover:text-emerald-600">Spustit →</span>
                    </a>
                    @endforeach
                </div>
                @else
                <div class="bg-white rounded-xl border border-dashed border-slate-300 p-10 text-center">
                    <p class="text-slate-400 text-sm">Zatím nebyly vytvořeny žádné verze.</p>
                    <p class="text-slate-400 text-xs mt-1.5">Vytvořte složku ve <code class="bg-slate-100 px-1.5 py-0.5 rounded text-slate-500 font-mono text-[11px]">resources/views/versions/</code></p>
                </div>
                @endif

            </div>
        </main>
    </div>

</body>
</html>
