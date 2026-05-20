# Multi-verze Rozcestník (Laravel)

Tento projekt slouží jako centrální rozcestník pro správu a spouštění různých verzí aplikace.

## Co bylo vytvořeno
- **Tailwind CSS**: Aplikace byla designově modernizována. Pro čtení Markdown obsahu byl přidán plugin `Typography`.
- **HomeController (`app/Http/Controllers/HomeController.php`)**: Zajišťuje dynamické čtení adresářů s verzemi (`resources/views/versions`) a parsování Markdown souborů.
- **Routy (`routes/web.php`)**: Dynamická catch-all routa (`/{version}`) a routy pro čtení dokumentace (`/doc/{file}`).
- **Obalovací layout (Device Previewer)**: Speciální layout (`resources/views/layouts/device-preview.blade.php`), do kterého jsou verze zabaleny. Umožňuje přepínání zařízení (Desktop, Tablet, Mobil) pro testování responzivity přímo na lokálu přes iframe.
- **Šablony**:
  - `welcome.blade.php`: Zcela přepracovaná úvodní obrazovka s odkazy na verze a dokumentaci.
  - `doc.blade.php`: Šablona pro zobrazení přeloženého Markdown obsahu.
  - `versions/v1/index.blade.php`: Ukázková první verze.

## Zobrazení verzí (Device Preview)
Po prokliku na verzi z rozcestníku se aplikace standardně otevře uvnitř **Device Previeweru**. Můžeš přepínat mezi zařízeními pomocí horní lišty. 
Pokud potřebuješ čistou verzi bez tohoto obalu (například pro debugování nebo plný náhled), stačí kliknout na tlačítko vpravo nahoře "Otevřít čisté" nebo za URL přidat parametr `?preview=false`.

## Přidání nové verze
Stačí vytvořit novou podsložku ve složce `resources/views/versions/` (např. `v2`) a do ní umístit soubor `index.blade.php`. Rozcestník ji na hlavní stránce okamžitě nabídne.
