<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aplikace V1</title>
    <style>
        /* Skrytí scrollbaru uvnitř samotné stránky, když běží uvnitř emulátoru */
        html::-webkit-scrollbar {
            display: none;
        }
        html {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        body {
            font-family: sans-serif;
            text-align: center;
            padding: 50px 20px;
            background-color: #e8f4f8;
            margin: 0;
            min-height: 150vh; /* Vynucení scrollování pro test */
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            display: inline-block;
            max-width: 800px;
            width: 90%;
            margin: 0 auto;
            text-align: left;
        }
        h1 { color: #2980b9; text-align: center; }
        h2 { color: #34495e; margin-top: 40px; border-bottom: 2px solid #eee; padding-bottom: 10px; }
        p { line-height: 1.8; color: #555; }
        /* Přidáno pro lepší demonstraci responzivity */
        .responsive-box {
            background: #3498db;
            color: white;
            padding: 20px;
            margin-top: 20px;
            border-radius: 5px;
            text-align: center;
            font-weight: bold;
        }
        
        .dummy-content {
            margin-top: 30px;
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
        }
        
        .dummy-card {
            background: #f9f9f9;
            border: 1px solid #eee;
            padding: 20px;
            border-radius: 8px;
        }

        @media (max-width: 768px) {
            .responsive-box {
                background: #e67e22;
                font-size: 14px;
            }
            .responsive-box::after {
                content: ' (Zobrazení přepnuto na Tablet/Mobil)';
            }
            body { padding: 20px 10px; }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Vítejte ve verzi 1</h1>
        <p>Toto je zkušební verze V1 pro demonstraci dynamického načítání verzí. Stránka je nyní záměrně prodloužena, aby bylo možné otestovat, jak se chová scrollování uvnitř Device Previeweru napříč různými zařízeními.</p>
        
        <div class="responsive-box">
            Tento box mění barvu z modré na oranžovou na menších zařízeních!
        </div>

        <h2>O testovací aplikaci</h2>
        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>

        <h2>Testovací grid</h2>
        <div class="dummy-content">
            <div class="dummy-card">
                <h3>Karta 1</h3>
                <p>Krátký popis karty s ukázkovým textem pro otestování zalamování.</p>
            </div>
            <div class="dummy-card">
                <h3>Karta 2</h3>
                <p>Krátký popis karty s ukázkovým textem pro otestování zalamování.</p>
            </div>
            <div class="dummy-card">
                <h3>Karta 3</h3>
                <p>Krátký popis karty s ukázkovým textem pro otestování zalamování.</p>
            </div>
            <div class="dummy-card">
                <h3>Karta 4</h3>
                <p>Krátký popis karty s ukázkovým textem pro otestování zalamování.</p>
            </div>
            <div class="dummy-card">
                <h3>Karta 5</h3>
                <p>Krátký popis karty s ukázkovým textem pro otestování zalamování.</p>
            </div>
            <div class="dummy-card">
                <h3>Karta 6</h3>
                <p>Krátký popis karty s ukázkovým textem pro otestování zalamování.</p>
            </div>
        </div>

        <h2>Závěr obsahu</h2>
        <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt.</p>
        
        <p style="text-align: center; margin-top: 50px; color: #999;">Konec stránky</p>
    </div>
</body>
</html>
