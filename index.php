<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login | 4x1</title>
    <link rel="icon" href="./images/Icon4x1.png" type="image/x-icon"/>

    <!-- Font Icon -->
    <link rel="stylesheet" href="./assets/fonts/material-icon/css/material-design-iconic-font.min.css">

    <!-- Main css -->
    <link rel="stylesheet" href="./assets/css/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="assets/css/plugins.min.css" />
    <link rel="stylesheet" href="assets/css/kaiadmin.min.css" />
    <style>
        .logo-img {
            width: 500px; /* ou o tamanho que quiser */
            display: block;
        }

        .field-icon {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #999;
        }
    </style>
</head>
<body>
    <div class="main">

        <!-- Sign up form -->
        <section class="signup">
            <div class="container">
                <div class="signup-content">
                    <div class="signup-form">
                        <h2 class="form-title">Login</h2>
                        <form action="indexLogin.php" method="POST" class="register-form" id="register-form">
                            <div class="form-group">
                                <label for="email"><i class="zmdi zmdi-email"></i></label>
                                <input type="email" name="email" id="email" placeholder="Email" required/>
                            </div>
                            <div class="form-group">
                                <label for="password"><i class="zmdi zmdi-lock"></i></label>
                                <input type="password" name="password" id="pass" placeholder="Password" required/>
                                <span toggle="#pass" class="zmdi zmdi-eye field-icon toggle-password"></span>
                            </div>
                            <div class="form-group form-button">
                                <input type="submit" name="signup" id="signup" class="form-submit" value="Login"/>
                            </div>
                        </form>
                    </div>
                    <div class="signup-image">
                        <figure><img src="./images/LogoPreto4x1.png" class="logo-img"></figure>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(".toggle-password").click(function () {
            var input = $($(this).attr("toggle"));
            if (input.attr("type") === "password") {
                input.attr("type", "text");
                $(this).removeClass("zmdi-eye").addClass("zmdi-eye-off");
            } else {
            input.attr("type", "password");
                $(this).removeClass("zmdi-eye-off").addClass("zmdi-eye");
            }
        });
    </script>
    <?php 
        include('./endPage.php'); 
    ?>
</body>
</html>