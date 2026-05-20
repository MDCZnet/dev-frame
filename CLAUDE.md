# Instrukce pro AI (AI Assistant Guidelines)

Tento soubor obsahuje globální instrukce pro chování AI agenta v tomto projektu. AI agent je povinen se těmito pravidly vždy řídit.

## 1. Povinnost logování změn do DEVLOG.md
Při každém zásahu do projektu, ať už se jedná o implementaci nové funkce, refaktoring nebo opravu chyby, je AI agent **povinen** zapsat provedené změny do souboru `DEVLOG.md` (v kořenovém adresáři). 
- Zápis musí obsahovat aktuální datum realizace, které **musí být vždy na začátku nadpisu** (např. `## 18.05.2026 - Název úkolu`).
- Zápis musí být jasný, stručný a srozumitelný.
- Musí obsahovat seznam modifikovaných, vytvořených či smazaných souborů s jejich krátkým popisem.

## 2. Povinnost aktualizace dokumentace v README.md
Pokud AI agent vytvoří nebo významně upraví **klíčovou funkci** aplikace (například novou logiku, nové routy podstatné pro běh systému, změny v architektuře), musí tuto funkci popsat v souboru `README.md`.
- Popis by měl být srozumitelný pro ostatní vývojáře nebo uživatele.
- Musí vysvětlovat účel a základní fungování dané komponenty nebo funkce.
- V případě návodů k použití (např. "Jak přidat novou verzi") je potřeba tyto návody do `README.md` rovnou přidat nebo aktualizovat.
