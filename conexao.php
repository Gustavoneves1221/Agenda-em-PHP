<?php

$host = "localhost";
$port = "5432";
$dbname = "cadastro";
$user = "postgres";
$password = "@Wyden1221";

try {
    $pdo = new PDO(
        "pgsql:host=$host;port=$port;dbname=$dbname",
        $user,
        $password
    );

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "Conexão realizada com sucesso!";

} catch (PDOException $e) {
    die("Erro na conexão: " . $e->getMessage());
}

?>