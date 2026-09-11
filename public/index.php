<?php
$host = 'db';             
$dbname = 'mydatabase';   
$user = 'user';           
$password = 'password';   
$port = 3306;

try {
    $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4";
    
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Lanzar excepciones en errores
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Devolver arreglos asociativos
        PDO::ATTR_EMULATE_PREPARES   => false,                  // Usar preparaciones reales de MySQL
    ];

    $pdo = new PDO($dsn, $user, $password, $options);

    echo "Successful connection to the database (MariaDB)!";

} catch (PDOException $e) {
    echo "Database connection error: " . $e->getMessage();
}
?>