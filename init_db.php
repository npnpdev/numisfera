<?php
$host = '10.40.71.115';
$user = 'root';
$pass = 'student';
$db   = 'BE_194103';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $res = $pdo->query("SHOW TABLES LIKE 'ps_configuration'");
    if ($res->rowCount() === 0) {
        echo "AUTOMAT: Importowanie pliku SQL linia po linii...\n";
        
        $sqlFile = '/tmp/init.sql';
        $query = '';
        $handle = fopen($sqlFile, 'r');
        
        if ($handle) {
            while (($line = fgets($handle)) !== false) {
                // Pomiń komentarze i puste linie
                if (trim($line) == '' || strpos($line, '--') === 0 || strpos($line, '/*') === 0) continue;
                
                $query .= $line;
                // Jeśli linia kończy się średnikiem, wykonaj zapytanie
                if (substr(trim($line), -1) == ';') {
                    $pdo->exec($query);
                    $query = '';
                }
            }
            fclose($handle);
            echo "AUTOMAT: Import zakończony sukcesem.\n";
        }
    } else {
        echo "AUTOMAT: Baza już posiada dane. Pomijam.\n";
    }
} catch (Exception $e) {
    echo "BŁĄD: " . $e->getMessage() . "\n";
    exit(1);
}
