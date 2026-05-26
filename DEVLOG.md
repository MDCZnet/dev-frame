# Vývojářský deník (DEVLOG)

Zde jsou zaznamenány veškeré implementační kroky a architektura vytvořeného multi-verze rozcestníku.

## 26.05.2026 - Přidání ikony aplikace s automatickým přidáním widgetu

### Popis
Přidána `MainActivity` jako launcher aktivita — kliknutí na ikonu aplikace spustí systémový dialog pro přidání widgetu na plochu (`AppWidgetManager.requestPinAppWidget` přes reflection, API 26+). Na starších zařízeních se zobrazí toast s návodem pro ruční přidání.

### Změněné soubory
- `android-qr-widget/src/net/mdcz/qrpaywidget/MainActivity.kt` – nový soubor, launcher aktivita
- `android-qr-widget/AndroidManifest.xml` – přidána MainActivity s LAUNCHER intent-filter, versionCode 7, versionName 1.6
- `android-qr-widget/res/values/strings.xml` – přidán string `add_widget_manual`
- `android-qr-widget/qr-platba-widget.aab` – přestavěný AAB (verze 7)
- `android-qr-widget/qr-platba-widget.apk` – přestavěné APK (verze 7)

---

## 26.05.2026 - Oprava: widget nešel přidat na plochu (aapt vs aapt2 resource ID mismatch)

### Popis
Widget zmizel po přidání na plochu, protože build skript generoval R.java přes starý `aapt`, ale AAB resources přes `aapt2`. Tyto nástroje přiřazují různá resource ID — DEX kód hledal layout pod jiným ID než ho měl AAB → crash → widget zmizel.

Oprava: celý build nyní používá konzistentně `aapt2`. R.java se generuje přes `aapt2 link --java`, čímž jsou ID v DEX i v resources.pb shodná.

### Změněné soubory
- `android-qr-widget/build.sh` – kroky 1-2 přepsány: `aapt package` nahrazen `aapt2 compile` + `aapt2 link --java`
- `android-qr-widget/AndroidManifest.xml` – `versionCode` 3 → 4, `versionName` 1.2 → 1.3
- `android-qr-widget/qr-platba-widget.aab` – přestavěný AAB (verze 4)
- `android-qr-widget/qr-platba-widget.apk` – přestavěné APK (verze 4)

---

## 26.05.2026 - Zvýšení targetSdkVersion na 35 pro Google Play

### Popis
Oprava chyby při nahrávání AAB do Google Play Console pro interní testování. Google Play vyžaduje od nových uploadů minimálně `targetSdkVersion=35`.

### Změněné soubory
- `android-qr-widget/AndroidManifest.xml` – `targetSdkVersion` zvýšen z 34 na 35, `versionCode` z 2 na 3, `versionName` z 1.1 na 1.2
- `android-qr-widget/qr-platba-widget.aab` – přestavěný AAB bundle (verze 3)
- `android-qr-widget/qr-platba-widget.apk` – přestavěné APK (verze 3)

### Zbývající varování z Play Console (není třeba opravovat kódem)
- **Žádní testeři** – přidat v Play Console → Interní testování → Testeři
- **Chybí deobfuskační soubor** – app nepoužívá R8/ProGuard, varování lze ignorovat

---

## 26.05.2026 - Oprava ikony a úprava konfiguračního dialogu

### Změněné soubory
- `android-qr-widget/res/mipmap-*/ic_launcher.png` – přegenerovány ze zdroje `icon_play_store_512.png` (oprava: zobrazovalo se jen modré kolečko)
- `android-qr-widget/res/mipmap-*/ic_launcher_round.png` – totéž pro kulatou variantu
- `android-qr-widget/res/mipmap-anydpi-v26/ic_launcher.xml` – odstraněno (adaptive icon měl prázdný foreground placeholder → blue circle bug)
- `android-qr-widget/res/mipmap-anydpi-v26/ic_launcher_round.xml` – odstraněno
- `android-qr-widget/res/values/strings.xml` – `config_title` změněn z "Nastavení pro widget QR Platba" na "Zadejte číslo účtu příjemce platby"
- `android-qr-widget/res/layout/activity_widget_config.xml` – odstraněna duplicitní věta "Zadejte číslo účtu příjemce platby." ze druhého TextView
- `android-qr-widget/qr-platba-widget.apk` – nové APK

---

## 22.05.2026 - Android QR Platba Widget (APK)

Vytvořen kompletní Android widget pro generování platebních QR kódů (CZ SPD formát).

### Vytvořené soubory (`android-qr-widget/`)

- **`AndroidManifest.xml`** — manifest aplikace: deklarace widgetu, aktivit, poskytovatele souborů
- **`build.sh`** — build skript (aapt → kotlinc → dx → zipalign → apksigner)
- **`libs/zxing-core.jar`** — ZXing 3.5.2 pro generování QR kódů
- **`res/layout/widget_layout.xml`** — layout widgetu na ploše (2×1 buňky, modrý)
- **`res/layout/activity_enter_amount.xml`** — dialog zadání částky
- **`res/layout/activity_show_qr.xml`** — zobrazení QR kódu + tlačítko sdílení
- **`res/layout/activity_widget_config.xml`** — nastavení čísla účtu
- **`res/xml/widget_info.xml`** — metadata AppWidget provideru
- **`res/values/strings.xml`** — české lokalizační řetězce
- **`src/.../QRPaymentWidget.kt`** — AppWidgetProvider (klik → zadání částky)
- **`src/.../EnterAmountActivity.kt`** — zadání částky, validace, spuštění QR aktivity
- **`src/.../ShowQRActivity.kt`** — zobrazení QR kódu, sdílení přes systémový chooser
- **`src/.../WidgetConfigActivity.kt`** — nastavení + konverze CZ čísla účtu na IBAN
- **`src/.../IBANConverter.kt`** — převod CZ formátu (123456-1234567890/0800) na IBAN
- **`src/.../QRCodeHelper.kt`** — generování QR Bitmap přes ZXing, sestavení SPD řetězce
- **`src/.../QRFileProvider.kt`** — vlastní ContentProvider pro sdílení QR obrázku
- **`qr-platba-widget.apk`** — výsledný APK (minSdk 21, targetSdk 23, podepsán v1+v2+v3)

## 20.05.2026 - Převod na Composer package (mdcznet/dev-frame)
- Vytvořen `src/DevFrameServiceProvider.php` — registruje views (`dev-frame::`), routes a publikaci assetů.
- Vytvořen `src/Http/Controllers/DashboardController.php` pod namespace `DevFrame\`.
- Vytvořen `routes/package.php` — route soubor načítaný Service Providerem; `routes/web.php` odkazuje na něj.
- Aktualizován `composer.json`: `type: library`, `name: mdcznet/dev-frame`, auto-discovery přes `extra.laravel.providers`.
- Sestaven `dist/dev-frame.css` — precompilovaný Tailwind CSS bez font souborů; commitován do repozitáře.
- Přidán `vite.package.config.js` + `resources/js/package-entry.js` pro rebuilding (`npm run build:dist`).
- Views aktualizovány: `@vite` nahrazen Bunny Fonts CDN linkem + `asset('vendor/dev-frame/dev-frame.css')`.
- Přidán `public/vendor/` do `.gitignore` (generovaný příkazem `vendor:publish`).
- Přepsán `README.md` s kompletní instalační a uživatelskou dokumentací v angličtině.

## 20.05.2026 - Sjednocení designu device-preview toolbaru
- Přepsán `resources/views/layouts/device-preview.blade.php` — toolbar nyní používá stejnou `slate-950` paletu, Instrument Sans font a SVG ikonky jako dashboard.
- Tlačítka přepínače zařízení (Desktop/Tablet/Mobil) mají aktivní stav (`bg-slate-700`) konzistentní s ostatním UI.
- Rámeček zařízení změněn na `#0f172a` (slate-950) pro lepší sladění s tmavým tématem.

## 20.05.2026 - Redesign UI (Dark Sidebar + Light Content)
- Přepsán `resources/views/welcome.blade.php` — nový layout s tmavým postranním panelem (`bg-slate-950`) a světlým hlavním obsahem, kartičky pro dokumentaci a verze s hover efekty.
- Přepsán `resources/views/doc.blade.php` — čistý reader s top barem, back tlačítkem a `prose` typografií z `@tailwindcss/typography`.
- Aktualizován `resources/css/app.css` — přidán `@plugin "@tailwindcss/typography"`.
- Spuštěn `npm run build`, zkompilován Tailwind CSS + font Instrument Sans.

## 20.05.2026 - Přesun projektu do GitHub repozitáře
- Inicializován Git repozitář v `c:\laragon\www\mos`.
- Projekt nahrán do repozitáře [MDCZnet/dev-frame](https://github.com/MDCZnet/dev-frame) na GitHubu.
- Mergován LICENSE soubor z existujícího vzdáleného repozitáře.
- Celkem pushováno 67 souborů, větev `main` nastavena jako tracking branch.

## 20.05.2026 - Drobné úpravy
- Ze šablony `doc.blade.php` (zobrazení Markdown dokumentace) byl odstraněn odkaz "← Zpět na rozcestník", jelikož se odkazy na dokumentaci automaticky otevírají v nové záložce prohlížeče a odkaz byl zbytečný.

## 20.05.2026 - Přechod na Tailwind CSS (Modernizace designu) [ZRUŠENO]
- Na žádost zadavatele proveden redesign rozcestníku pomocí Tailwind CSS, ale následně **vrácen do původního stavu**, protože vzhled a implementace nevyhovovala. Tailwind byl ponechán ve struktuře, ale soubory Blade se opětovně vrátily k původnímu, jednoduchému CSS stylu.

## 20.05.2026 - Úpravy v Device Previeweru pro Desktop zobrazení
- Na žádost upraveno chování emulátoru (layout `resources/views/layouts/device-preview.blade.php`):
  - Na fyzickém tabletu a mobilu (šířka okna pod 1024px) se nyní emulator zcela schová a zobrazí se rovnou čistý iframe na celou obrazovku, aby obalová lišta nepřekážela.
  - Změněn text tlačítka z "Otevřít čisté" na "Čisté zobrazení".
  - Upraveno stylování `iframe`:
    - V Desktop zobrazení odstraněn padding okolo a box-shadow (šedý rámeček), nyní zabírá 100% plochy přímo od hrany.
    - V Tablet zobrazení přidán tlustý černý okraj se zaoblenými rohy (`border: 20px solid #111; border-radius: 15px;`) a odsazení od lišty/krajů okna, aby tablet skutečně připomínal fyzické zařízení v prostoru (stejně jako mobil).
  - Přidán JavaScript (`updateScale()`) a CSS parametry (`transform-origin: top center; flex-shrink: 0; align-items: flex-start;`), které automaticky detekují velikost aktuálního okna. Pokud se Tablet nebo Mobilní maketa se svými fixními rozměry do monitoru nevejdou, úměrně se přes CSS transform zmenší. Tak zůstanou vidět kompletní v plné výšce bez schování vrchního okraje pod navigaci.
  - Pomocí CSS (`::-webkit-scrollbar`, `-ms-overflow-style: none`, `scrollbar-width: none`) byly skryty vizuální scrollbary uvnitř iframe a uvnitř stránky `v1`, tak aby makety telefonu a tabletu nevykazovaly nevkusné posuvníky na pravé straně jako na desktopu, ale bylo stále možné scrollovat jako na dotykových zařízeních.

## 18.05.2026 - Obalovací Device Previewer pro verze aplikací
- Navržena a sepsána architektura řešení (v souboru `plans/device-preview-architecture.md`).
- Vytvořen layout **`resources/views/layouts/device-preview.blade.php`**:
  - Obsahuje přepínač mezi 3 režimy zařízení (Desktop - 100%, Tablet - 1024x768, Mobil - 375x812).
  - Volba rozlišení si pamatuje nastavení v lokálním úložišti (Local Storage).
  - Samotná verze aplikace je renderována uvnitř flexibilního `iframe`.
- Úprava **`routes/web.php`**: 
  - Catch-all routa nyní defaultně obaluje každou verzi do layoutu Previeweru.
  - V případě parametru `?preview=false` vypne obal a vrátí přímo zobrazení verze.
- Úprava ukázkové verze v **`resources/views/versions/v1/index.blade.php`** pro lepší otestování přepínání rozlišení (přidán box měnící barvu s media query).

## 18.05.2026 - Vytvořený rozcestník a systém verzí (Iniciální setup)
- Vytvořen **`HomeController.php`** pro:
  - Dynamické zjištění složek verzí ve složce `resources/views/versions`.
  - Parsování a zobrazení `README.md` a `DEVLOG.md` pomocí vestavěného `Str::markdown()`.
- Zaktualizována hlavní šablona **`welcome.blade.php`**, která dynamicky vypisuje tlačítka s odkazy na verze aplikace a základní Markdown soubory.
- Vytvořena nová šablona **`doc.blade.php`** pro stylizovaný výpis vygenerovaného obsahu z Markdown.
- Upraveny routy v **`routes/web.php`**:
  - Přidána dynamická "Catch-all" routa `/{version}`, která obstarává přesměrování a validaci přístupu na podsložky verzí (např. `/v1`).
- Připravena první ukázková složka a šablona verze v **`resources/views/versions/v1/index.blade.php`**.
