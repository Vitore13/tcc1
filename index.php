<?php
include_once '../codigo/BackEnd/config.php';
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Stockmate</title>
    <link rel="stylesheet" href="../codigo/css/dashboard.css">
</head>
<body>
    <div class="navbar">
        <div class="logo">
            <img src="img/logo_fundo_transparente.png" alt="Logo">
        </div>
        <div class="menu">
            <a class="active" href="../codigo/index.php">
                DashBoard
            </a>
            <a class="butao" href="../codigo/registro/registro.php">
                Registro
            </a>
            <a class="butao" href="../codigo/estoque/index.php">
                Estoque
            </a>
        </div>
        <a class="solicitacao-btn" href="../codigo/BackEnd/solicitacao.php">
            Solicitação
        </a>
    </div>

    <div class="container">
        <?php

        // Fetch data for Devoluções (Entries)
        $devolucoes_query = "SELECT COUNT(*) AS total_devolucoes FROM movimentacao WHERE tipo_movimentacao = 1";
        $devolucoes_result = $conn->query($devolucoes_query);
        $devolucoes = $devolucoes_result->fetch_assoc()['total_devolucoes'];

        // Fetch data for Saídas (Exits)
        $saidas_query = "SELECT COUNT(*) AS total_saidas FROM movimentacao WHERE tipo_movimentacao = 0";
        $saidas_result = $conn->query($saidas_query);
        $saidas = $saidas_result->fetch_assoc()['total_saidas'];

        // Fetch data for Nº de Materiais
        $materiais_query = "SELECT SUM(quantidade) AS total_materiais FROM item";
        $materiais_result = $conn->query($materiais_query);
        $materiais = $materiais_result->fetch_assoc()['total_materiais'];

        // Fetch data for Solicitações (New Entries)
        $solicitacoes_query = "SELECT COUNT(*) AS total_solicitacoes FROM movimentacao";
        $solicitacoes_result = $conn->query($solicitacoes_query);
        $solicitacoes = $solicitacoes_result->fetch_assoc()['total_solicitacoes'];
        ?>

        <div class="info-card">
            <span>Entradas</span>
            <span class="number"><?php echo $devolucoes; ?></span>
        </div>
        <div class="info-card">
            <span>Saídas</span>
            <span class="number"><?php echo $saidas; ?></span>
        </div>
        <div class="info-card">
            <span>Nº de Materiais</span>
            <span class="number"><?php echo $materiais; ?></span>
        </div>
        <div class="info-card">
            <span>Solicitações</span>
            <span class="number"><?php echo $solicitacoes; ?></span>
        </div>
    </div>

    <div class="atraso-table">
        <h2>Solicitações</h2>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Responsável</th>
                    <th>Data</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Fetch the most recent movements, ordered by date descending
                $solicitacoes_detalhes_query = "SELECT id_movimentacao, responsavel, data FROM movimentacao ORDER BY data DESC";
                $solicitacoes_detalhes_result = $conn->query($solicitacoes_detalhes_query);

                while ($row = $solicitacoes_detalhes_result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['id_movimentacao'] . "</td>";
                    echo "<td>" . $row['responsavel'] . "</td>";
                    echo "<td>" . date('d/m/Y', strtotime($row['data'])) . "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <footer>
        <!-- Footer content -->
    </footer>
</body>
</html>
