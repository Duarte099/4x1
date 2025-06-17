<?php 
    $login = true;
    include('./head.php'); 
?>
    <!-- Font Icon -->
    <link rel="stylesheet" href="./assets/fonts/material-icon/css/material-design-iconic-font.min.css">

    <!-- Main css -->
    <link rel="stylesheet" href="./assets/css/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="assets/css/plugins.min.css" />
    <link rel="stylesheet" href="assets/css/kaiadmin.min.css" />

    <title>4x1 | Login</title>
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
                        <form action="indexLogin" method="POST" class="register-form" id="register-form">
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
        $(document).ready(function () {
            <?php if (isset($_GET['erro']) && $_GET['erro'] == "true"): ?>
                $.notify({
                    message: 'Password ou user Incorreto!',
                    title: 'Notificação',
                    icon: 'fa fa-info-circle',
                }, {
                    type: 'danger',
                    placement: {
                        from: 'top',
                        align: 'right'
                    },
                    delay: 2000
                });
                const cleanUrl = window.location.origin + window.location.pathname;
                window.history.replaceState({}, document.title, cleanUrl);
            <?php endif; ?>
        });
    </script>
</body>
</html>