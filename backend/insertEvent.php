<?php
require('../../backend/database.php');

        // Dados do membro
        $nome_membro = $_POST["nome_membro"];
        $profissao_membro = $_POST["profissao_membro"];
        $email_membro = $_POST["email_membro"];
        $whatsapp_membro = $_POST["whatsapp_membro"];
        $instagram_membro = $_POST["instagram_membro"];
        $role = 'Comunidade';
        $status_membro = "Inativo";
        $senha_membro = $_POST["senha_membro"];
        $senha_memb_hash = password_hash($senha_membro, PASSWORD_DEFAULT);
        
        $primeira_letra_nome = substr($nome_membro, 0, 1);

        // Extraindo os dois primeiros números do CPF
        $dois_primeiros_cpf = substr($email_membro, 0, 2);
    
        // Extraindo os dois últimos dígitos do WhatsApp
        $dois_ultimos_whatsapp = substr($whatsapp_membro, -2);
    
        // Combinando as informações
        $codigo = $primeira_letra_nome . $dois_primeiros_cpf . $dois_ultimos_whatsapp;

        try {
            // Preparar e executar a consulta SQL para inserir dados do membro
            $stmt = $connect->prepare("INSERT INTO membro_tbl (nome_membro,  foto_membro,  profissao_membro, email_membro, whatsapp_membro, instagram_membro, roles, status_membro, senha_membro, codigo) 
                VALUES (:nome_membro,:foto_membro, :profissao_membro, :email_membro, :whatsapp_membro, :instagram_membro, :roles, :status_membro, :senha_membro, :codigo)");
        
            // Bind dos parâmetros
            $stmt->bindParam(':nome_membro', $nome_membro);
            $stmt->bindParam(':foto_membro', $imagem_nome);
            $stmt->bindParam(':profissao_membro', $profissao_membro);
            $stmt->bindParam(':email_membro', $email_membro);
            $stmt->bindParam(':whatsapp_membro', $whatsapp_membro);
            $stmt->bindParam(':instagram_membro', $instagram_membro);
            $stmt->bindParam(':roles', $role);
            $stmt->bindParam(':status_membro', $status_membro);
            $stmt->bindParam(':senha_membro', $senha_memb_hash);
            $stmt->bindParam(':codigo', $codigo);

            $stmt->execute();

            $result["success"]["message"] = "Cadastrado com sucesso!";
            header('Content-Type: application/json');
            echo json_encode($result);
        } catch (PDOException $e) {
            echo "Erro ao inserir dados do membro: " . $e->getMessage();
        }