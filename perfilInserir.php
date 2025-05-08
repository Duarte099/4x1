<?php
    //Inclui a conexão à base de dados na página
    include('./db/conexao.php'); 

    //Caso a variavel op nao esteja declarado e o metodo não seja post volta para a página da dashboard.php
    if (isset($_GET['op']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
        //Obtem o operação 
        $op = $_GET['op'];

        
        //Se a operação for edit
        if ($op == 'edit') {
            // Caminho para a pasta de uploads
            $uploadDir = './uploads/imagens/';

            $foto = $_FILES['foto'];
            $caminhoFinal = "";
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            $maxFileSize = 5 * 1024 * 1024; // 5MB

            if (
                isset($_FILES['foto']) &&
                $_FILES['foto']['error'] === UPLOAD_ERR_OK &&
                is_uploaded_file($_FILES['foto']['tmp_name'])
            ) {
                $foto = $_FILES['foto'];
                
                // Verifica MIME
                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                $mimeType = finfo_file($finfo, $foto['tmp_name']);
                finfo_close($finfo);

                if (in_array($mimeType, $allowedTypes)) {
                    // Extensão
                    $ext = strtolower(pathinfo($foto['name'], PATHINFO_EXTENSION));
                    if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif'])) {
                        if ($foto['size'] <= $maxFileSize) {
                            $nomeFinal = uniqid('foto_', true) . '.' . $ext;
                            $caminhoFinal = $uploadDir . $nomeFinal;

                            if (move_uploaded_file($foto['tmp_name'], $caminhoFinal)) {
                                $_SESSION['img'] = $caminhoFinal;
                            } else {
                                echo'Erro ao guardar a imagem.';
                            }
                        } else {
                            echo 'A imagem é demasiado grande.';
                        }
                    } else {
                        echo 'Extensão da imagem inválida.';
                    }
                } else {
                    echo 'Tipo de imagem não permitido.';
                }
            }

            if (!empty($_POST['password']) && !empty($caminhoFinal)) {
                // Atualiza tudo: nome, email, password, imagem, estado
                $passwordHash = password_hash($_POST['password'], PASSWORD_DEFAULT);
                if ($_SESSION["tipo"] == "professor") {
                    $sql = "UPDATE administrador SET nome = ?, email = ?, contacto = ?, pass = ?, img = ? WHERE id = ?";
                }
                else{
                    $sql = "UPDATE administrador SET nome = ?, email = ?, pass = ?, img = ? WHERE id = ?";
                }
                $stmt = $con->prepare($sql);
                if ($stmt) {
                    if ($_SESSION["tipo"] == "professor") {
                        $stmt->bind_param("sssssi", $_POST['nome'], $_POST['email'], $_POST["contacto"], $passwordHash, $caminhoFinal, $_SESSION["id"]);
                    }
                    else{
                        $stmt->bind_param("ssssi", $_POST['nome'], $_POST['email'], $passwordHash, $caminhoFinal, $_SESSION["id"]);
                    }
                    if ($stmt->execute()) {
                        notificacao('success', 'Perfil atualizado com sucesso!');
                        if ($_SESSION["tipo"] == "professor") {
                            registrar_log("prof", "O professor [" . $_SESSION["id"] . "]" . $_SESSION["nome"] . " atualizou o seu perfil");
                        }
                        else{
                            registrar_log("admin", "O administrador [" . $_SESSION["id"] . "]" . $_SESSION["nome"] . " atualizou o seu perfil");
                        }
                    } else {
                        notificacao('danger', 'Erro ao atualizar perfil: ' . $stmt->error);
                    }
                    $stmt->close();
                } else {
                    notificacao('danger', 'Erro ao atualizar perfil: ' . $con->error);
                }
            }
            elseif (!empty($_POST['password'])) {
                // Apenas password foi alterada
                $passwordHash = password_hash($_POST['password'], PASSWORD_DEFAULT);
                if ($_SESSION["tipo"] == "professor") {
                    $sql = "UPDATE administrador SET nome = ?, email = ?, contacto = ?, pass = ? WHERE id = ?";
                }
                else{
                    $sql = "UPDATE administrador SET nome = ?, email = ?, pass = ? WHERE id = ?";
                }
                $stmt = $con->prepare($sql);
                if ($stmt) {
                    if ($_SESSION["tipo"] == "professor") {
                        $stmt->bind_param("ssssi", $_POST['nome'], $_POST['email'], $_POST["contacto"], $passwordHash, $_SESSION["id"]);
                    }
                    else{
                        $stmt->bind_param("sssi", $_POST['nome'], $_POST['email'], $passwordHash, $_SESSION["id"]);
                    }
                    if ($stmt->execute()) {
                        notificacao('success', 'Perfil atualizado com sucesso!');
                        if ($_SESSION["tipo"] == "professor") {
                            registrar_log("prof", "O professor [" . $_SESSION["id"] . "]" . $_SESSION["nome"] . " atualizou o seu perfil");
                        }
                        else{
                            registrar_log("admin", "O administrador [" . $_SESSION["id"] . "]" . $_SESSION["nome"] . " atualizou o seu perfil");
                        }
                    } else {
                        notificacao('danger', 'Erro ao atualizar perfil: ' . $stmt->error);
                    }
                    $stmt->close();
                } else {
                    notificacao('danger', 'Erro ao atualizar perfil: ' . $con->error);
                }
            }
            elseif (!empty($caminhoFinal)) {
                // Apenas imagem foi alterada
                if ($_SESSION["tipo"] == "professor") {
                    $sql = "UPDATE professores SET nome = ?, email = ?, contacto = ?, img = ? WHERE id = ?";
                }
                else{
                    $sql = "UPDATE administrador SET nome = ?, email = ?, img = ? WHERE id = ?";
                }
                $stmt = $con->prepare($sql);
                if ($stmt) {
                    if ($_SESSION["tipo"] == "professor") {
                        $stmt->bind_param("ssssi", $_POST['nome'], $_POST['email'], $_POST["contacto"], $caminhoFinal, $_SESSION["id"]);
                    }
                    else{
                        $stmt->bind_param("sssi", $_POST['nome'], $_POST['email'], $caminhoFinal, $_SESSION["id"]);
                    }
                    if ($stmt->execute()) {
                        notificacao('success', 'Perfil atualizado com sucesso!');
                        if ($_SESSION["tipo"] == "professor") {
                            registrar_log("prof", "O professor [" . $_SESSION["id"] . "]" . $_SESSION["nome"] . " atualizou o seu perfil");
                        }
                        else{
                            registrar_log("admin", "O administrador [" . $_SESSION["id"] . "]" . $_SESSION["nome"] . " atualizou o seu perfil");
                        }
                    } else {
                        notificacao('danger', 'Erro ao atualizar perfil: ' . $stmt->error);
                    }
                    $stmt->close();
                } else {
                    notificacao('danger', 'Erro ao atualizar perfil: ' . $con->error);
                }
            }
            else {
                // Nem password nem imagem foram alteradas
                if ($_SESSION["tipo"] == "professor") {
                    echo 1;
                    $sql = "UPDATE professores SET nome = ?, email = ?, contacto = ? WHERE id = ?";
                }
                else{
                    echo 2; 
                    $sql = "UPDATE administrador SET nome = ?, email = ? WHERE id = ?";
                }
                $stmt = $con->prepare($sql);
                if ($stmt) {
                    if ($_SESSION["tipo"] == "professor") {
                        echo 3;
                        $stmt->bind_param("sssi", $_POST['nome'], $_POST['email'], $_POST["contacto"], $_SESSION["id"]);
                    }
                    else{
                        echo 4;
                        $stmt->bind_param("ssi", $_POST['nome'], $_POST['email'], $_SESSION["id"]);
                    }
                    
                    if ($stmt->execute()) {
                        echo 5;
                        notificacao('success', 'Perfil atualizado com sucesso!');
                        if ($_SESSION["tipo"] == "professor") {
                            registrar_log("prof", "O professor [" . $_SESSION["id"] . "]" . $_SESSION["nome"] . " atualizou o seu perfil");
                        }
                        else{
                            registrar_log("admin", "O administrador [" . $_SESSION["id"] . "]" . $_SESSION["nome"] . " atualizou o seu perfil");
                        }
                    } else {
                        notificacao('danger', 'Erro ao atualizar perfil: ' . $stmt->error);
                    }
                    $stmt->close();
                } else {
                    notificacao('danger', 'Erro ao atualizar perfil: ' . $con->error);
                }
            }
            header('Location: perfil.php');
        }
        else {
            notificacao('warning', 'Operação inválida.');
            header('Location: dashboard.php');
            exit();
        }
    }
    else {
        header('Location: dashboard.php');
        exit();
    }
?>