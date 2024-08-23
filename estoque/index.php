<?php
include_once '../BackEnd/config.php';
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estoque - Stockmate</title>
    <link rel="stylesheet" href="../css/estoque.css">
    <link rel="stylesheet" href="../css/gerenciar.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>
    <div class="navbar">
        <div class="logo">
            <img src="../img/logo_fundo_transparente.png" alt="Logo">
        </div>
        <div class="menu">
            <a class="butao" href="../index.php">DashBoard</a>
            <a class="butao" href="../registro/registro.php">Registro</a>
            <a class="active" href="../estoque/index.php">Estoque</a>
        </div>
        <a class="solicitacao-btn" href="../BackEnd/solicitacao.php">Solicitação</a>
    </div>
    
    <div class="search-bar">
        <input type="text" id="search-query" placeholder="Pesquisar...">
        <button id="search-button" class="btn">Pesquisar</button>
        <button id="gerencia-button" class="btng">Gerencia</button> <!-- Botão Gerencia permanece inalterado -->
    </div>
    
    <div class="tab-container">
        <div class="tab">
            <button class="close">&times;</button>
            <div class="content">
                <h2>Dados de identificação:</h2>    
                <form id="estoque-form" method="post" target="_self">
                    <div id="tirarNome" style="display: block;">
                        <div class="form-group">
                            <label for="nome">Nome:</label>
                            <input type="text" id="nome" name="nome">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="tipo">Tipo:</label>
                        <input type="text" id="tipo" name="tipo">
                    </div>
                    <div class="form-group">
                        <label>Tipo de Movimentação:</label>
                        <div class="radio-group">
                            <label for="entrada" class="entrada">Adicionar</label>
                            <input type="radio" id="entrada" name="movimentacao" value="1">
                            <label for="saida" class="saida">Excluir</label>
                            <input type="radio" id="saida" name="movimentacao" value="0">
                            <input type="submit" value="Enviar" class="Enviar">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>ID DO ITEM</th>
                    <th>ITEM</th>
                    <th>QUANTIDADE</th>
                </tr>
            </thead>
            <tbody id="tabela-estoque">
                <!-- Dados do banco de dados serão inseridos aqui -->
            </tbody>
        </table>
    </div>
    
    <script>
        $(document).ready(function(){
            // Função para carregar os dados do estoque
            function carregarEstoque(query = '') {
                $.ajax({
                    url: '../estoque/carregar_estoque.php', // Certifique-se de que o caminho está correto
                    method: 'GET',
                    data: { search: query },
                    success: function(data) {
                        $('#tabela-estoque').html(data);
                    }
                });
            }
            
            // Carrega os dados quando a página é carregada
            carregarEstoque();
            
            // Função para pesquisar os dados ao clicar no botão Pesquisar
            $('#search-button').click(function(){
                var query = $('#search-query').val();
                carregarEstoque(query);
            });

            // Permite pesquisa ao pressionar Enter na barra de pesquisa
            $('#search-query').keypress(function(e) {
                if (e.which == 13) { // 13 é o código da tecla Enter
                    $('#search-button').click();
                }
            });

            // Função para enviar o formulário de estoque
            $('#estoque-form').submit(function(event){
                event.preventDefault();
                $.ajax({
                    url: '../estoque/estoque.php',
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        console.log('Resposta recebida:', response);
                        if (response.status === 'success') {
                            alert(response.message);
                            carregarEstoque();  // Recarrega os dados após inserção/remoção
                        } else {
                            alert('Erro: ' + response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Erro:', xhr.responseText);  // Imprime a resposta para depuração
                        alert('Erro ao realizar a operação: ' + error);
                    }
                });
            });
        });
    </script>
    <script src="../estoque/js/script.js"></script>
</body>
</html>



 