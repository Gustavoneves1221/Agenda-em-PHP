<?php

require_once "conexao.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nome = $_POST["nome"];
    $telefone = $_POST["telefone"];

    $logradouro = $_POST["logradouro"];
    $bairro = $_POST["bairro"];
    $cidade = $_POST["cidade"];
    $cep = $_POST["cep"];

    try {

    $pdo->beginTransaction();

    $sql = "INSERT INTO contato (nome, telefone)
            VALUES (:nome, :telefone)
            RETURNING id_contato";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ":nome" => $nome,
        ":telefone" => $telefone
    ]);

    $id_contato = $stmt->fetchColumn();

    $sql = "INSERT INTO endereco
            (logradouro, bairro, cidade, cep, id_contato)
            VALUES (:logradouro, :bairro, :cidade, :cep, :id_contato)";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ":logradouro" => $logradouro,
        ":bairro" => $bairro,
        ":cidade" => $cidade,
        ":cep" => $cep,
        ":id_contato" => $id_contato
    ]);

    $pdo->commit();

    header("Location: index.php");
    exit;

} catch (PDOException $e) {

    $pdo->rollBack();

    echo "Erro ao cadastrar: " . $e->getMessage();

}
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Cadastrar Contato</title>
</head>

<body>

    <h1>Cadastrar novo contato</h1>

    <form action="cadastrar.php" method="POST">

        <h2>Dados pessoais</h2>

        <label>Nome:</label>
        <input type="text" name="nome" required>

        <br><br>

        <label>Telefone:</label>
        <input type="text" name="telefone" required>

        <h2>Endereço</h2>

        <label>Logradouro:</label>
        <input type="text" name="logradouro" required>

        <br><br>

        <label>Bairro:</label>
        <input type="text" name="bairro" required>

        <br><br>

        <label>Cidade:</label>
        <input type="text" name="cidade" required>

        <br><br>

        <label>CEP:</label>
        <input type="text" name="cep" required>

        <br><br>

        <button type="submit">Cadastrar</button>

    </form>

    <br>

    <a href="index.php">Voltar para a agenda</a>

</body>
</html>