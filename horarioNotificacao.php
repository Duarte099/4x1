<?php
    include('./db/conexao.php');

    $sql1 = "SELECT * FROM alunos WHERE ativo = 1 AND notHorario = 1";
    $result1 = $con->query($sql1);
    if ($result1->num_rows > 0) {
        while ($row1 = $result1->fetch_assoc()) {
            $sql2 = "SELECT * FROM horario_alunos WHERE idAluno = {$row1['id']}";
            $result2 = $con->query($sql2);
            if ($result2->num_rows > 0) {
                while ($row2 = $result2->fetch_assoc()) {
                    $sql3 = "SELECT * FROM horario WHERE id = {$row2['idHorario']}";
                    $result3 = $con->query($sql3);
                    if ($result3->num_rows > 0) {
                        while ($row3 = $result3->fetch_assoc()) {
                            
                        }
                    }
                }
            }
        }
    }
    
    $url = 'http://teu-servidor.com:3000/send';

    // Caminho para o ficheiro PDF
    $filePath = 'caminho/para/teu/fichiero.pdf';
    $fileData = new CURLFile($filePath);

    // Dados a enviar
    $data = [
        'number' => '351911234567', // Número de destino
        'message' => 'Aqui está o PDF que combinámos!',
        'apiKey' => 'a-tua-chave-secreta',
        'file' => $fileData
    ];

    // Iniciar cURL
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Executar a requisição
    $response = curl_exec($ch);

    // Verificar se houve erro na requisição
    if ($response === false) {
        // Se ocorreu erro na cURL
        echo 'Erro cURL: ' . curl_error($ch);
    } else {
        // Se a requisição foi bem-sucedida, verificar código de status HTTP
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        if ($httpCode == 200) {
            // Se a resposta for OK (200)
            echo 'Mensagem enviada com sucesso!';
        } else {
            // Se não for OK, exibe o erro
            echo 'Erro ao enviar mensagem. Código HTTP: ' . $httpCode;
            echo ' Resposta: ' . $response; // Mensagem de erro do servidor
        }
    }

    // Fechar a cURL
    curl_close($ch);
?>