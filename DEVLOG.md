# Vývojářský deník (DEVLOG)

Zde jsou zaznamenány veškeré implementační kroky a architektura vytvořeného multi-verze rozcestníku.

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
