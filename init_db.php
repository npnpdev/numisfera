<?php
$host = '10.40.71.115';
$user = 'root';
$pass = 'student';
$db   = 'BE_194103';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // WYŁĄCZENIE STRICT MODE - naprawia błąd Incorrect datetime value '0000-00-00'
    $pdo->exec("SET SESSION sql_mode = '';");

    // Sprawdzamy czy w bazie istnieje kluczowa tabela PrestaShop
    $res = $pdo->query("SHOW TABLES LIKE 'ps_configuration'");
    
    if ($res->rowCount() === 0) {
        echo "AUTOMAT: Baza jest pusta. Rozpoczynam importowanie pliku SQL linia po linii...\n";

        $sqlFile = '/tmp/init.sql';
        $query = '';
        $handle = fopen($sqlFile, 'r');

        if ($handle) {
            while (($line = fgets($handle)) !== false) {
                // Pomiń komentarze i puste linie
                $trimmedLine = trim($line);
                if ($trimmedLine == '' || strpos($trimmedLine, '--') === 0 || strpos($trimmedLine, '/*') === 0) {
                    continue;
                }

                $query .= $line;
                
                // Jeśli linia kończy się średnikiem, wykonaj zapytanie i wyczyść bufor
                if (substr($trimmedLine, -1) == ';') {
                    try {
                        $pdo->exec($query);
                    } catch (Exception $e) {
                        // Logujemy błąd konkretnej linii, ale idziemy dalej
                        echo "UWAGA: Pominięto zapytanie z powodu błędu: " . $e->getMessage() . "\n";
                    }
                    $query = '';
                }
            }
            fclose($handle);
            echo "AUTOMAT: Import zakończony sukcesem.\n";
        } else {
            echo "BŁĄD: Nie można otworzyć pliku /tmp/init.sql\n";
        }
    } else {
        echo "AUTOMAT: Baza już posiada dane. Pomijam inicjalizację.\n";
    }
} catch (Exception $e) {
    echo "BŁĄD KRYTYCZNY: " . $e->getMessage() . "\n";
    exit(1);
}
