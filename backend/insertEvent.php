<?php
require('connect.php');

        // Dados do membro
        $title = $_POST["title"];
        $date = $_POST["date"];

        try {
            // Preparar e executar a consulta SQL para inserir dados do membro
            $stmt = $connect->prepare("INSERT INTO events (title, date) 
                VALUES (:title, :date)");
        
            // Bind dos parâmetros
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':date', $date);

            $stmt->execute();

            $result["success"]["message"] = "Cadastrado com sucesso!";
            $result["data"]["title"] = $title;
            header('Content-Type: application/json');
            echo json_encode($result);
        } catch (PDOException $e) {
            echo "Erro ao inserir dados do membro: " . $e->getMessage();
        }