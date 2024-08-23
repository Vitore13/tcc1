<?php
include_once '../BackEnd/config.php'; // Inclua o arquivo de configuração do banco de dados

// Ajuste a consulta SQL com os nomes corretos das colunas
$query = "
    SELECT 
        movimentacao.id_movimentacao AS codigo, 
        CASE 
            WHEN movimentacao.tipo_movimentacao = 1 THEN 'ENTRADA'
            WHEN movimentacao.tipo_movimentacao = 0 THEN 'SAÍDA'
        END AS movimentacao,
        movimentacao.responsavel, 
        movimentacao.data, 
        item.nomedoitem AS Item, 
        movimentacao.quantidade_movimentacao AS quantidade, 
        movimentacao.id_item
    FROM movimentacao
    INNER JOIN item ON movimentacao.id_item = item.id_item
";

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - Stockmate</title>
    <link rel="stylesheet" href="../css/Registro.css">
    <link rel="stylesheet" href="css/Registro.css">
</head>
<body>
    <div class="navbar">
        <div class="logo">
            <img src="../img/logo_fundo_transparente.png" alt="Logo">
        </div>
        <div class="menu">
            <a class="butao" href="../index.php">DashBoard</a>
            <a class="active" href="../registro/registro.php">Registro</a>
            <a class="butao" href="../estoque/index.php">Estoque</a>
        </div>
        <a class="solicitacao-btn" href="../BackEnd/solicitacao.php">Solicitação</a>
    </div>

    <div class="search-bar">
        <input type="text" id="filterInput" placeholder="Pesquisar...">
    </div>

    <div class="table-container">
        <table id="movimentacaoTable">
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Movimentação</th>
                    <th>Responsável</th>
                    <th>Data</th>
                    <th>Item</th>
                    <th>Quantidade</th>
                    <th>ID Do Item</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    // Loop através dos resultados e exibição dos dados
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['codigo']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['movimentacao']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['responsavel']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['data']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['Item']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['quantidade']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['id_item']) . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='7'>Nenhuma movimentação encontrada</td></tr>";
                }

                // Fecha a conexão
                $conn->close();
                ?>
            </tbody>
        </table>
    </div>

    <script>
        // Função para filtrar a tabela com base na entrada do usuário
        document.getElementById('filterInput').addEventListener('keyup', function() {
            var input = document.getElementById('filterInput');
            var filter = input.value.toUpperCase();
            var table = document.getElementById('movimentacaoTable');
            var tr = table.getElementsByTagName('tr');

            for (var i = 1; i < tr.length; i++) { // Começa em 1 para pular o cabeçalho
                var td = tr[i].getElementsByTagName('td')[1]; // A segunda coluna é a de movimentação
                if (td) {
                    var txtValue = td.textContent || td.innerText;
                    if (txtValue.toUpperCase().indexOf(filter) > -1) {
                        tr[i].style.display = "";
                    } else {
                        tr[i].style.display = "none";
                    }
                }
            }
        });
    </script>
</body>
</html>
