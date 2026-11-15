<?php

require_once "conexao.php";

$sql = "SELECT
            c.id_contato,
            c.nome,
            c.telefone,
            e.logradouro,
            e.bairro,
            e.cidade,
            e.cep
        FROM contato c
        LEFT JOIN endereco e
        ON c.id_contato = e.id_contato
        ORDER BY c.nome";

$resultado = $pdo->query($sql);

$contatos = $resultado->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Minha Agenda</title>
</head>

<body>

    <h1>Minha Agenda de Contatos</h1>

    <a href="cadastrar.php">Cadastrar novo contato</a>

    <hr>

    <h2>Contatos cadastrados</h2>

    <?php if (count($contatos) > 0): ?>

        <?php foreach ($contatos as $contato): ?>

            <div>
                <h3>
                    <?= htmlspecialchars($contato['nome']) ?>
                </h3>

                <p>Telefone:
                    <?= htmlspecialchars($contato['telefone']) ?>
                </p>

                <p>Endereço:
                    <?= htmlspecialchars($contato['logradouro'] ?? '') ?>
                </p>

                <p>Bairro:
                    <?= htmlspecialchars($contato['bairro'] ?? '') ?>
                </p>

                <p>Cidade:
                    <?= htmlspecialchars($contato['cidade'] ?? '') ?>
                </p>

                <p>CEP:
                    <?= htmlspecialchars($contato['cep'] ?? '') ?>
                </p>

                <p>
                    <a href="editar.php?id=<?= $contato['id_contato'] ?>">
                        Editar
                    </a>
                </p>

                <form action="excluir.php" method="POST">

                    <input
                        type="hidden"
                        name="id_contato"
                        value="<?= $contato['id_contato'] ?>"
    >

                    <button
                        type="submit"   
                        onclick="return confirm('Tem certeza que deseja excluir este contato?')"
    >
                        Excluir contato
                    </button>

</form>
            </div>

            <hr>

        <?php endforeach; ?>

    <?php else: ?>

        <p>Nenhum contato cadastrado.</p>

    <?php endif; ?>

</body>
</html>