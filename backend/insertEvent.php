<?php
require('connect.php');

try {
    // Dados do membro
    $title = $_POST["title"] ?? '';
    $date = $_POST["date"] ?? '';

    // Verificar se os dados são válidos
    if (!empty($title) && !empty($date)) {
        // Preparar e executar a consulta SQL para inserir dados do membro
        $stmt = $connect->prepare("INSERT INTO events (title, date) VALUES (:title, :date)");

        // Bind dos parâmetros
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':date', $date);

        $stmt->execute();

        $result = [
            "success" => true,
            "data" => [
                "title" => $title
            ]
        ];
    } else {
        $result = [
            "success" => false,
            "message" => "Dados inválidos."
        ];
    }
    
    header('Content-Type: application/json');
    echo json_encode($result);
} catch (PDOException $e) {
    $result = [
        "success" => false,
        "message" => "Erro ao inserir dados do membro: " . $e->getMessage()
    ];
    header('Content-Type: application/json');
    echo json_encode($result);
}
