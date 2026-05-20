<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preview - Verze {{ strtoupper($version) }}</title>
    <style>
        body, html {
            margin: 0;
            padding: 0;
            height: 100%;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background-color: #333;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .preview-toolbar {
            background-color: #1a1a1a;
            color: white;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.5);
            z-index: 100;
        }

        .toolbar-left, .toolbar-right {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .version-badge {
            background-color: #3498db;
            color: white;
            padding: 4px 10px;
            border-radius: 4px;
            font-weight: bold;
            font-size: 0.9em;
        }

        .btn {
            background-color: #444;
            color: white;
            border: 1px solid #555;
            padding: 6px 12px;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            font-size: 0.9em;
            transition: all 0.2s;
        }

        .btn:hover {
            background-color: #555;
        }

        .btn.active {
            background-color: #3498db;
            border-color: #3498db;
        }

        .device-switchers {
            display: flex;
            gap: 5px;
            background-color: #222;
            padding: 4px;
            border-radius: 6px;
        }

        .preview-container {
            flex-grow: 1;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            padding: 0; /* Odstraněn padding, aby desktop sahal až ke krajům */
            overflow: hidden; /* Zakážeme vnější scroll, scrollovat se má uvnitř iframe */
            background-color: #333;
        }

        .iframe-wrapper {
            background-color: white;
            transition: width 0.3s ease, height 0.3s ease, border-radius 0.3s ease, border 0.3s ease;
            position: relative;
        }

        /* Pro mobil a tablet přidáme padding zpět */
        .preview-container:has(.device-tablet),
        .preview-container:has(.device-mobile) {
            padding: 20px;
        }

        /* Device Presets */
        .device-desktop {
            width: 100%;
            height: 100%;
            max-width: 100%;
            border-radius: 0;
            box-shadow: none;
            transform: none !important;
        }

        .device-tablet {
            width: 1024px;
            height: 768px;
            transform-origin: top center;
            border-radius: 15px;
            overflow: hidden;
            border: 20px solid #111;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
            flex-shrink: 0;
            /* Zrušen statický margin, pozicování je řešené přes padding kontejneru a transform */
        }

        .device-mobile {
            width: 375px;
            height: 812px;
            transform-origin: top center;
            border-radius: 30px;
            overflow: hidden;
            border: 10px solid #111;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
            flex-shrink: 0;
            /* Zrušen statický margin */
        }

        iframe {
            width: 100%;
            height: 100%;
            border: none;
            background-color: white;
        }

        /* Skrýt scrollovací lišty uvnitř iframe pro mobil a tablet */
        .device-tablet iframe::-webkit-scrollbar,
        .device-mobile iframe::-webkit-scrollbar {
            display: none;
        }
        
        .device-tablet iframe,
        .device-mobile iframe {
            -ms-overflow-style: none;  /* IE and Edge */
            scrollbar-width: none;  /* Firefox */
        }
        
        /* Skrytí lišty a wrapperu na menších obrazovkách (Tablet/Mobil) a zobrazení pouze iframe fullscreen */
        @media (max-width: 1024px) {
            .preview-toolbar {
                display: none;
            }
            .preview-container {
                padding: 0;
            }
            .iframe-wrapper {
                width: 100% !important;
                height: 100% !important;
                border: none !important;
                border-radius: 0 !important;
                margin: 0 !important;
                box-shadow: none !important;
            }
        }
    </style>
</head>
<body>

    <div class="preview-toolbar">
        <div class="toolbar-left">
            <a href="{{ url('/') }}" class="btn">&larr; Rozcestník</a>
            <span class="version-badge">Verze {{ strtoupper($version) }}</span>
        </div>

        <div class="device-switchers">
            <button class="btn active" data-device="desktop" onclick="switchDevice('desktop')">🖥️ Desktop</button>
            <button class="btn" data-device="tablet" onclick="switchDevice('tablet')">📱 Tablet</button>
            <button class="btn" data-device="mobile" onclick="switchDevice('mobile')">📱 Mobil</button>
        </div>

        <div class="toolbar-right">
            <a href="{{ url($version) }}?preview=false" target="_blank" class="btn">Čisté zobrazení &nearr;</a>
        </div>
    </div>

    <div class="preview-container">
        <div id="iframe-wrapper" class="iframe-wrapper device-desktop">
            <iframe id="preview-frame" src="{{ url($version) }}?preview=false"></iframe>
        </div>
    </div>

    <script>
        function updateScale() {
            const wrapper = document.getElementById('iframe-wrapper');
            const container = document.querySelector('.preview-container');
            const isDesktop = wrapper.classList.contains('device-desktop');
            
            if (isDesktop) {
                wrapper.style.transform = 'none';
                return;
            }

            // Výpočet zmenšení pro tablety a mobily, pokud se nevejdou do obrazovky
            const padding = 40; // 20px padding z každé strany + prostor pro rámečky
            const availableWidth = container.clientWidth - padding;
            const availableHeight = container.clientHeight - padding;
            
            // Reálné rozměry zařízení (včetně rámečku 2x20px/2x10px)
            const isTablet = wrapper.classList.contains('device-tablet');
            const deviceWidth = isTablet ? 1064 : 395; 
            const deviceHeight = isTablet ? 808 : 832;

            // Vypočteme poměr zmenšení, jen pokud je okno menší než zařízení
            const scaleWidth = availableWidth < deviceWidth ? availableWidth / deviceWidth : 1;
            const scaleHeight = availableHeight < deviceHeight ? availableHeight / deviceHeight : 1;
            
            // Použijeme menší měřítko, aby se zařízení vešlo celé i s okraji
            const finalScale = Math.min(scaleWidth, scaleHeight, 1);
            
            wrapper.style.transform = `scale(${finalScale})`;
        }

        function switchDevice(device) {
            // Update buttons
            document.querySelectorAll('.device-switchers .btn').forEach(btn => {
                btn.classList.remove('active');
            });
            document.querySelector(`.device-switchers .btn[data-device="${device}"]`).classList.add('active');

            // Update wrapper class
            const wrapper = document.getElementById('iframe-wrapper');
            wrapper.className = 'iframe-wrapper device-' + device;
            
            // Update container padding
            const container = document.querySelector('.preview-container');
            if (device === 'desktop') {
                container.style.padding = '0';
            } else {
                container.style.padding = '20px';
            }

            // Přepočítat měřítko zobrazení
            updateScale();

            // Save preference
            localStorage.setItem('preferred-device', device);
        }

        // Při změně velikosti okna prohlížeče přepočítat měřítko
        window.addEventListener('resize', updateScale);

        // Load saved preference
        document.addEventListener('DOMContentLoaded', () => {
            const savedDevice = localStorage.getItem('preferred-device');
            if (savedDevice) {
                switchDevice(savedDevice);
            } else {
                updateScale();
            }
        });
    </script>
</body>
</html>
