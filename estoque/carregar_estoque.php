<?php
include_once '../BackEnd/config.php'; // Certifique-se de que o caminho para o arquivo config está correto

$search = isset($_GET['search']) ? $_GET['search'] : ''; // Captura o termo de busca, se existir

// Consulta SQL para buscar itens
$query = "SELECT id_item, nomedoitem, quantidade FROM item";
$params = []; // Inicializa o array de parâmetros
$types = ''; // Inicializa a string de tipos

if (!empty($search)) {
    $query .= " WHERE nomedoitem LIKE ?";
    $params[] = '%' . $search . '%'; // Adiciona o parâmetro ao array
    $types .= 's'; // Define o tipo do parâmetro como string
}

$query .= " ORDER BY CASE WHEN nomedoitem LIKE ? THEN 1 ELSE 2 END, nomedoitem ASC";
$params[] = $search . '%'; // Adiciona o segundo parâmetro para ordenação
$types .= 's'; // Define o tipo do segundo parâmetro como string

$itensStmt = $conn->prepare($query);

// Se houver parâmetros, fazemos o bind_param
if (!empty($params)) {
    $itensStmt->bind_param($types, ...$params);
}

$itensStmt->execute();
$itensResult = $itensStmt->get_result();

if ($itensResult->num_rows > 0) {
    while ($item = $itensResult->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($item['id_item']) . "</td>";
        echo "<td>" . htmlspecialchars($item['nomedoitem']) . "</td>";
        echo "<td>" . htmlspecialchars($item['quantidade']) . "</td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='3'>Nenhum item encontrado</td></tr>";
}

$itensStmt->close();
$conn->close();
?>
