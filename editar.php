
<?php

require_once "conexao.php";

// Verifica se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Recebe os dados enviados pelo formulário
    $id_contato = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

    $nome = $_POST["nome"];
    $telefone = $_POST["telefone"];
    $logradouro = $_POST["logradouro"];
    $bairro = $_POST["bairro"];
    $cidade = $_POST["cidade"];
    $cep = $_POST["cep"];

    try {

        // Inicia uma transação
        $pdo->beginTransaction();

        // Atualiza os dados do contato
        $sql = "UPDATE contato
                SET nome = :nome,
                    telefone = :telefone
                WHERE id_contato = :id_contato";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            ":nome" => $nome,
            ":telefone" => $telefone,
            ":id_contato" => $id_contato
        ]);

        // Atualiza o endereço
        $sql = "UPDATE endereco
                SET logradouro = :logradouro,
                    bairro = :bairro,
                    cidade = :cidade,
                    cep = :cep
                WHERE id_contato = :id_contato";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            ":logradouro" => $logradouro,
            ":bairro" => $bairro,
            ":cidade" => $cidade,
            ":cep" => $cep,
            ":id_contato" => $id_contato
        ]);

        // Confirma as alterações
        $pdo->commit();

        // Volta para a página principal
        header("Location: index.php");
        exit;

    } catch (PDOException $e) {

        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }

        echo "Erro ao atualizar contato: "
             . htmlspecialchars($e->getMessage());
    }
}

// Recebe o ID pela URL
$id_contato = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

if (!$id_contato || $id_contato <= 0) {
    exit("ID inválido.");
}

// Busca o contato e seu endereço
$sql = "SELECT
            c.nome,
            c.telefone,
            e.logradouro,
            e.bairro,
            e.cidade,
            e.cep
        FROM contato c
        LEFT JOIN endereco e
        ON c.id_contato = e.id_contato
        WHERE c.id_contato = :id_contato";

$stmt = $pdo->prepare($sql);
$stmt->execute([":id_contato" => $id_contato]);

$contato = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$contato) {
    exit("Contato não encontrado.");
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Editar contato</title>
</head>

<body>

    <h1>Editar contato</h1>

    <form action="editar.php?id=<?= $id_contato ?>" method="POST">

        <label>Nome:</label>
        <input type="text" name="nome"
            value="<?= htmlspecialchars($contato['nome']) ?>" required>

        <br><br>

        <label>Telefone:</label>
        <input type="text" name="telefone"
            value="<?= htmlspecialchars($contato['telefone']) ?>" required>

        <br><br>

        <label>Logradouro:</label>
        <input type="text" name="logradouro"
            value="<?= htmlspecialchars($contato['logradouro'] ?? '') ?>">

        <br><br>

        <label>Bairro:</label>
        <input type="text" name="bairro"
            value="<?= htmlspecialchars($contato['bairro'] ?? '') ?>">

        <br><br>

        <label>Cidade:</label>
        <input type="text" name="cidade"
            value="<?= htmlspecialchars($contato['cidade'] ?? '') ?>">

        <br><br>

        <label>CEP:</label>
        <input type="text" name="cep"
            value="<?= htmlspecialchars($contato['cep'] ?? '') ?>">

        <br><br>

        <button type="submit">Salvar alterações</button>

    </form>

    <br>

    <a href="index.php">Voltar</a>

</body>
</html>
