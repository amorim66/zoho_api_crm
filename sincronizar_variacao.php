<?php
require_once 'zoho_auth.php';
require_once 'functions.php';

$accessToken = getAccessToken();
if (!$accessToken) {
    die('Erro ao obter access token.');
}

// Configurações da API de produtos
$headers = [
    "Authorization: Zoho-oauthtoken $accessToken",
    "Content-Type: application/json"
];

// Chamada à API para buscar os produtos
$ch = curl_init('https://www.zohoapis.com/crm/v2/Products');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
$response = curl_exec($ch);
curl_close($ch);

$products = json_decode($response, true);

// Processa e insere os produtos na loja virtual
foreach ($products['data'] as $product) {
    // Adaptar os dados do produto para a estrutura da API da loja virtual
    $productData = [
        'referencia' => $product['Product_Code'],
        'insert_nome' => $product['Product_Name'],
        'insert_status' => '1',
        'insert_preco_venda' => $product['Unit_Price'],
        'insert_quantidade_estoque' => $product['Quantity_in_Stock'],
        // Adicionar outros campos conforme necessário...
    ];

    $ch = curl_init('https://dominioloja/api/cad_produto.php');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([$productData]));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'api-key: seu_api_key'
    ]);
    $result = curl_exec($ch);
    curl_close($ch);

    // Verifica se a inserção foi bem-sucedida
    if (!$result) {
        echo "Erro ao inserir o produto: " . $product['Product_Name'] . "\n";
    } else {
        echo "Produto inserido: " . $product['Product_Name'] . "\n";
    }
}
?>
