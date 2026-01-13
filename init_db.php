<?php
$host = '10.40.71.115';
$user = 'root';
$pass = 'student';
$db   = 'BE_194103';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Sprawdzamy czy tabela ps_configuration istnieje
    $res = $pdo->query("SHOW TABLES LIKE 'ps_configuration'");
    if ($res->rowCount() === 0) {
        echo "AUTOMAT: Baza pusta. Rozpoczynam import SQL...\n";
        $sql = file_get_contents('/tmp/init.sql');
        $pdo->exec($sql);
        echo "AUTOMAT: Import zakończony.\n";
    } else {
        echo "AUTOMAT: Baza już posiada dane. Pomijam.\n";
    }
} catch (Exception $e) {
    echo "BŁĄD: " . $e->getMessage() . "\n";
    exit(1); // Wyjdź z błędem jeśli nie ma połączenia
}
