<!DOCTYPE html>
<html lang="cs" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preview – {{ strtoupper($version) }} · dev-frame</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full flex flex-col bg-slate-50 overflow-hidden" style="font-family: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif;">

    {{-- Toolbar --}}
    <header class="bg-slate-950 border-b border-slate-800 h-14 px-4 flex items-center justify-between shrink-0 z-10">

        {{-- Left: back + badge --}}
        <div class="flex items-center gap-3">
            <a href="{{ url('/') }}"
               class="flex items-center gap-1.5 px-3 py-1.5 rounded-md text-slate-400 hover:text-white hover:bg-slate-800 transition-colors text-sm">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
                </svg>
                Rozcestník
            </a>
            <span class="inline-flex items-center px-2.5 py-1 rounded-md bg-indigo-500/20 text-indigo-300 text-xs font-semibold tracking-wide border border-indigo-500/30">
                {{ strtoupper($version) }}
            </span>
        </div>

        {{-- Center: device switcher --}}
        <div class="flex items-center gap-1 bg-slate-900 border border-slate-700/60 rounded-lg p-1">
            <button data-device="desktop" onclick="switchDevice('desktop')"
                    class="device-btn flex items-center gap-1.5 px-3 py-1.5 rounded-md text-xs font-medium transition-all text-slate-400 hover:text-white">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 17.25v1.007a3 3 0 01-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0115 18.257V17.25m6-12V15a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 15V5.25m18 0A2.25 2.25 0 0018.75 3H5.25A2.25 2.25 0 003 5.25m18 0H3"/>
                </svg>
                Desktop
            </button>
            <button data-device="tablet" onclick="switchDevice('tablet')"
                    class="device-btn flex items-center gap-1.5 px-3 py-1.5 rounded-md text-xs font-medium transition-all text-slate-400 hover:text-white">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5h3m-6.75 2.25h10.5a2.25 2.25 0 002.25-2.25v-15a2.25 2.25 0 00-2.25-2.25H6.75A2.25 2.25 0 004.5 4.5v15a2.25 2.25 0 002.25 2.25z"/>
                </svg>
                Tablet
            </button>
            <button data-device="mobile" onclick="switchDevice('mobile')"
                    class="device-btn flex items-center gap-1.5 px-3 py-1.5 rounded-md text-xs font-medium transition-all text-slate-400 hover:text-white">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 1.5H8.25A2.25 2.25 0 006 3.75v16.5a2.25 2.25 0 002.25 2.25h7.5A2.25 2.25 0 0018 20.25V3.75a2.25 2.25 0 00-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 15h3"/>
                </svg>
                Mobil
            </button>
        </div>

        {{-- Right: open clean --}}
        <a href="{{ url($version) }}?preview=false" target="_blank"
           class="flex items-center gap-1.5 px-3 py-1.5 rounded-md text-slate-400 hover:text-white hover:bg-slate-800 transition-colors text-sm">
            Čisté zobrazení
            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"/>
            </svg>
        </a>
    </header>

    {{-- Preview area --}}
    <div class="preview-container flex-1 flex justify-center items-start overflow-hidden">
        <div id="iframe-wrapper" class="iframe-wrapper device-desktop">
            <iframe id="preview-frame" src="{{ url($version) }}?preview=false" class="w-full h-full border-none bg-white"></iframe>
        </div>
    </div>

    <style>
        .preview-container { background-color: #f8fafc; }

        .iframe-wrapper {
            background: white;
            transition: width .3s ease, height .3s ease, border-radius .3s ease, border .3s ease;
            position: relative;
        }

        .device-desktop {
            width: 100%; height: 100%;
            border-radius: 0; box-shadow: none; transform: none !important;
        }
        .device-tablet {
            width: 1024px; height: 768px;
            transform-origin: top center;
            border-radius: 16px; overflow: hidden;
            border: 20px solid #0f172a;
            box-shadow: 0 20px 60px rgba(0,0,0,.6);
            flex-shrink: 0;
        }
        .device-mobile {
            width: 375px; height: 812px;
            transform-origin: top center;
            border-radius: 32px; overflow: hidden;
            border: 10px solid #0f172a;
            box-shadow: 0 20px 60px rgba(0,0,0,.6);
            flex-shrink: 0;
        }

        .preview-container:has(.device-tablet),
        .preview-container:has(.device-mobile) { padding: 24px; }

        .device-tablet iframe, .device-mobile iframe {
            -ms-overflow-style: none; scrollbar-width: none;
        }
        .device-tablet iframe::-webkit-scrollbar,
        .device-mobile iframe::-webkit-scrollbar { display: none; }

        /* Active device button */
        .device-btn.active {
            background-color: #334155;
            color: white;
        }

        @media (max-width: 1024px) {
            header { display: none; }
            .preview-container { padding: 0 !important; }
            .iframe-wrapper {
                width: 100% !important; height: 100% !important;
                border: none !important; border-radius: 0 !important;
                margin: 0 !important; box-shadow: none !important;
            }
        }
    </style>

    <script>
        function updateScale() {
            const wrapper = document.getElementById('iframe-wrapper');
            const container = document.querySelector('.preview-container');
            if (wrapper.classList.contains('device-desktop')) {
                wrapper.style.transform = 'none';
                return;
            }
            const padding = 48;
            const availableWidth  = container.clientWidth  - padding;
            const availableHeight = container.clientHeight - padding;
            const isTablet   = wrapper.classList.contains('device-tablet');
            const deviceWidth  = isTablet ? 1064 : 395;
            const deviceHeight = isTablet ?  808 : 832;
            const scaleW = availableWidth  < deviceWidth  ? availableWidth  / deviceWidth  : 1;
            const scaleH = availableHeight < deviceHeight ? availableHeight / deviceHeight : 1;
            wrapper.style.transform = `scale(${Math.min(scaleW, scaleH, 1)})`;
        }

        function switchDevice(device) {
            document.querySelectorAll('.device-btn').forEach(b => b.classList.remove('active'));
            document.querySelector(`.device-btn[data-device="${device}"]`).classList.add('active');

            const wrapper = document.getElementById('iframe-wrapper');
            wrapper.className = 'iframe-wrapper device-' + device;

            const container = document.querySelector('.preview-container');
            container.style.padding = device === 'desktop' ? '0' : '24px';

            updateScale();
            localStorage.setItem('preferred-device', device);
        }

        window.addEventListener('resize', updateScale);

        document.addEventListener('DOMContentLoaded', () => {
            const saved = localStorage.getItem('preferred-device');
            if (saved) {
                switchDevice(saved);
            } else {
                document.querySelector('.device-btn[data-device="desktop"]').classList.add('active');
                updateScale();
            }
        });
    </script>
</body>
</html>
