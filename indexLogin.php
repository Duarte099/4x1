<?php
    session_start(); // Adicione isso no início para garantir que a sessão seja iniciada
    $auxLogin = true; // Define a variável ANTES de incluir conexao
    include("./db/conexao");

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Prepare our SQL, preparing the SQL statement will prevent SQL injection.
        if ($stmt = $con->prepare('SELECT id, pass, name as nomeX, email, img, active FROM administrator WHERE user = ? OR email = ?')) {
            // Bind parameters (s = string, i = int, b = blob, etc), in our case the username is a string so we use "s"
            $stmt->bind_param('ss', $_POST['username'], $_POST['username']);
            $stmt->execute();
            // Store the result so we can check if the account exists in the database.
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                $stmt->bind_result($id, $password, $nomeX, $email, $img, $active);
                $stmt->fetch();
                if ($active == 1) {
                    // Account exists, now we verify the password.
                    // Note: remember to use password_hash in your registration file to store the hashed passwords.
                    if (password_verify($_POST['password'], $password)) {
                        // Verification success! User has logged-in!
                        // Create sessions, so we know the user is logged in, they basically act like cookies but remember the data on the server.
                        session_regenerate_id();
                        $_SESSION['loggedin'] = TRUE;
                        $_SESSION['id'] = $id;
                        $_SESSION['name'] = $nomeX;
                        $_SESSION['email'] = $email;
                        $_SESSION['img'] = $img;
                        $_SESSION['password'] = $password;
                        $_SESSION['passX'] = password_hash($_POST['password'], PASSWORD_DEFAULT);

                        registrar_log("Administrador " . $nomeX . "(" . $id . ") entrou");

                        header('Location: dashboard');
                        exit();
                    } else {
                        header('Location: index?erro=Password ou user Incorreto!');
                        exit();
                    }
                } else {
                    header('Location: index?erro=Password ou user Incorreto!');
                    exit();
                }
            } else {
                header('Location: index?erro=Password ou user Incorreto!');
                exit();
            }
            $stmt->close();
        }
    }
    else {
        header('Location: index');
        exit();
    }
?>
        