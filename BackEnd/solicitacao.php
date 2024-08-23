<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StockMate</title>
    <link rel="stylesheet" href="../css/socilitacao.css">
</head>
<body>
    <div class="navbar">
        <div class="logo">
            <img src="../img/logo_fundo_transparente.png" alt="Logo">
        </div>
        <div class="menu">
            <a class="butao" href="../index.php">DashBoard</a>
            <a class="butao" href="../registro/registro.php">Registro</a>
            <a class="butao" href="../estoque/index.php">Estoque</a>
        </div>
        <a class="solicitacao-btn" href="solicitacao.php">Solicitação</a>
    </div>

    <div class="content">
        <h2>Dados de identificação:</h2>
        <form action="solicitacao.php" method="POST">
            <div class="form-group">
                <label for="nome">Nome do Item:</label>
                <input type="text" id="nome" name="nome" required>
            </div>  
            <div class="form-group">
                <label for="tipo">Categoria:</label>
                <input type="text" id="tipo" name="tipo" required>
            </div>
            <div class="form-group">
                <label for="quantidade">Quantidade:</label>
                <input type="number" id="quantidade" name="quantidade" required>
            </div>
            <div class="form-group">
                <label for="responsavel">Responsável:</label>
                <input type="text" id="responsavel" name="responsavel" required>
            </div>
            <div class="form-group">
                <label for="data">Data:</label>
                <input type="date" id="data" name="data" required>
            </div>
            <div class="form-group">
                <label>Tipo de Movimentação:</label>
                <div class="radio-group">
                    <label for="entrada" class="entrada">Adicionar</label>
                    <input type="radio" id="entrada" name="movimentacao" value="1" required>
                    <label for="saida" class="saida">Remover</label>
                    <input type="radio" id="saida" name="movimentacao" value="0" required>
                </div>
            </div>
            <button type="submit" class="Enviar">Enviar</button>
        </form>

        <?php
        include_once 'config.php';

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $nome = $_POST['nome'];
            $categoria = $_POST['tipo'];
            $movimentacao = $_POST['movimentacao'];
            $quantidade = $_POST['quantidade'];
            $responsavel = $_POST['responsavel'];
            $data = $_POST['data'];

            $response = ['status' => '', 'message' => ''];

            // Verifica se a categoria já existe
            $categoriaStmt = $conn->prepare("SELECT id_categoria FROM categoria WHERE nomedacategoria = ?");
            $categoriaStmt->bind_param("s", $categoria);
            $categoriaStmt->execute();
            $categoriaStmt->store_result();

            if ($categoriaStmt->num_rows == 0) {
                // Insere nova categoria se não existir
                $inserirCategoriaStmt = $conn->prepare("INSERT INTO categoria (nomedacategoria) VALUES (?)");
                $inserirCategoriaStmt->bind_param("s", $categoria);
                $inserirCategoriaStmt->execute();
                $idCategoria = $conn->insert_id;
                $inserirCategoriaStmt->close();
            } else {
                // Recupera o ID da categoria existente
                $categoriaStmt->bind_result($idCategoria);
                $categoriaStmt->fetch();
            }
            $categoriaStmt->close();

            // Verifica se o item já existe
            $itemStmt = $conn->prepare("SELECT id_item, quantidade FROM item WHERE nomedoitem = ?");
            $itemStmt->bind_param("s", $nome);
            $itemStmt->execute();
            $itemStmt->store_result();

            if ($movimentacao == 1) { // Adicionar item
                if ($itemStmt->num_rows > 0) {
                    // Se o item já existir, atualiza a quantidade
                    $itemStmt->bind_result($idItem, $quantidadeAtual);
                    $itemStmt->fetch();
                    $novaQuantidade = $quantidadeAtual + $quantidade;

                    $updateItemStmt = $conn->prepare("UPDATE item SET quantidade = ? WHERE id_item = ?");
                    $updateItemStmt->bind_param("ii", $novaQuantidade, $idItem);
                    $updateItemStmt->execute();

                    $response['status'] = 'success';
                    $response['message'] = 'Quantidade do item atualizada com sucesso!';
                    $updateItemStmt->close();
                } else {
                    // Se o item não existir, insere um novo
                    $insertItemStmt = $conn->prepare("INSERT INTO item (nomedoitem, quantidade, id_categoria) VALUES (?, ?, ?)");
                    $insertItemStmt->bind_param("sii", $nome, $quantidade, $idCategoria);
                    $insertItemStmt->execute();
                    $idItem = $conn->insert_id; // Captura o ID do novo item
                    $response['status'] = 'success';
                    $response['message'] = 'Item inserido com sucesso!';
                    $insertItemStmt->close();
                }
            } else { // Remover item
                if ($itemStmt->num_rows > 0) {
                    // Se o item existir, atualiza a quantidade
                    $itemStmt->bind_result($idItem, $quantidadeAtual);
                    $itemStmt->fetch();

                    if ($quantidadeAtual >= $quantidade) {
                        $novaQuantidade = $quantidadeAtual - $quantidade;

                        if ($novaQuantidade > 0) {
                            // Atualiza a quantidade do item
                            $updateItemStmt = $conn->prepare("UPDATE item SET quantidade = ? WHERE id_item = ?");
                            $updateItemStmt->bind_param("ii", $novaQuantidade, $idItem);
                            $updateItemStmt->execute();
                            $updateItemStmt->close();
                        } else {
                            // Remove o item se a quantidade for 0
                            $deleteItemStmt = $conn->prepare("DELETE FROM item WHERE id_item = ?");
                            $deleteItemStmt->bind_param("i", $idItem);
                            $deleteItemStmt->execute();
                            $deleteItemStmt->close();
                        }

                        $response['status'] = 'success';
                        $response['message'] = 'Quantidade do item atualizada com sucesso!';
                    } else {
                        $response['status'] = 'error';
                        $response['message'] = 'Quantidade insuficiente para a remoção!';
                    }
                } else {
                    $response['status'] = 'error';
                    $response['message'] = 'Item não encontrado!';
                }
            }

            // Registra a movimentação
            if ($itemStmt->num_rows > 0) {
                $movimentacaoStmt = $conn->prepare("INSERT INTO movimentacao (id_item, tipo_movimentacao, quantidade_movimentacao, responsavel, data, id_categoria) VALUES (?, ?, ?, ?, ?, ?)");
                $movimentacaoStmt->bind_param("iiissi", $idItem, $movimentacao, $quantidade, $responsavel, $data, $idCategoria);
                $movimentacaoStmt->execute();
                $movimentacaoStmt->close();
            }

            $itemStmt->close();

            // Exibir resposta
            echo '<div class="response-message">';
            echo '<p>Status: ' . htmlspecialchars($response['status']) . '</p>';
            echo '<p>Mensagem: ' . htmlspecialchars($response['message']) . '</p>';
            echo '</div>';
        }
        ?>
    </div>
</body>
</html>
