<!-- Kreira tabelu -->
<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "testPHP";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // sql za kreiranje tabele
    $sql = "CREATE TABLE Users (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
    name VARCHAR(30) NOT NULL,
    email VARCHAR(50) NOT NULL,
    uername VARCHAR(50) NOT NULL,
    password CHAR(255) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )";

    
    $conn->exec($sql);
    echo "Tabela uspešno kreirana";
    }
catch(PDOException $e)
    {
    echo $sql . "<br>" . $e->getMessage();
    }

$conn = null;
?> 