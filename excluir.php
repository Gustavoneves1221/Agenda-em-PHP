
<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once "conexao.php";

// Verifica se o formulário enviou o ID
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $id_contato = filter_input(INPUT_POST, "id_contato", FILTER_VALIDATE_INT);

    if (!$id_contato || $id_contato <= 0) {
        exit("ID de contato inválido.");
    }

    try {

        // Inicia uma transação
        $pdo->beginTransaction();

        // Primeiro, exclui o endereço
        $sql = "DELETE FROM endereco
                WHERE id_contato = :id_contato";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            ":id_contato" => $id_contato
        ]);

        // Depois, exclui o contato
        $sql = "DELETE FROM contato
                WHERE id_contato = :id_contato";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            ":id_contato" => $id_contato
        ]);

        // Confirma as exclusões
        $pdo->commit();

        // Retorna para a página principal
        header("Location: index.php");
        exit;

    } catch (PDOException $e) {

        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }

        echo "Erro ao excluir contato: "
             . htmlspecialchars($e->getMessage());

    }

} else {

    echo "Requisição inválida.";

}
