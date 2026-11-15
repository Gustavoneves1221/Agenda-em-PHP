<?php

$config = require "/home/agenda-em-php/config/config.php";

try {

    $pdo = new PDO(
        "pgsql:host=" . $config["host"] .
        ";port=" . $config["port"] .
        ";dbname=" . $config["dbname"],
        $config["user"],
        $config["password"]
    );

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {

    die("Erro na conexão com o banco de dados.");

}