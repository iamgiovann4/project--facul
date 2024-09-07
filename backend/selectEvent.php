<?php
    require ('connect.php'); 
    //Require() : a função require() importa arquivos, porém, caso o mesmo não seja encontrado, será levantado uma exceção e a execução é finalizada. Essa é uma maneira de interrompermos a execução dos scripts caso alguma anomalia ocorra.

    try {
        $stmt = $connect->prepare("SELECT title, date FROM events");
        $stmt->execute();

        $eventos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $result["success"]["message"] = "Events com sucesso"; //criamos o array para devolver o resultado do insert numa mensagem de sucesso.

        $result["data"] = $eventos; //criamos o array para devolver o resultado do insert com os dados inseridos.

        header('Content-Type: Text/json'); //para ser enviado no formato json.
        echo json_encode($result); //exibir o resultado.
    } catch (PDOException $erro) {
        echo "falha ao cadastrar" . $erro->getMessage();
    }
?>