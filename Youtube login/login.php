<?php
session_start();
if (isset($_SESSION["user"])) {
    header("Location: ../Youtube home/home.html");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <script>
        function redirectToRegistration() {
            window.location.href = "registration.php";
        }
    </script>
</head>
<body>
    <div class="container">
        <?php
        if (isset($_POST["login"])) {
            $email = $_POST["email"];
            $password = $_POST["password"];
            require_once "database.php";
            $sql = "SELECT * FROM users WHERE email = ?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "s", $email);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $user = mysqli_fetch_array($result, MYSQLI_ASSOC);
            mysqli_stmt_close($stmt);
            
            if ($user) {
                if (password_verify($password, $user["password"])) {
                    $_SESSION["user"] = $user["email"];
                    header("Location: ../Youtube home/home.html");
                    exit();
                } else {
                    echo "<div class='alert alert-danger'>Password does not match</div>";
                }
            } else {
                echo "<div class='alert alert-danger'>Email does not match</div>";
            }
        }
        ?>
        <div class="box">
            <div class="title-box">
                <img class="yt-logo" src="youtube logo.png" alt="YouTube">
                <p style="color: white; font-size: 30px;">Sign in to YouTube!</p>
                <p style="color: white; font-size: 20px; font-weight: bold;">Join the largest worldwide video community!</p>
                <p style="color: white; font-size: 15px;">Get full access to YouTube with your account</p>
                <ul style="padding-left: 15px;">
                    <li style="color: white; font-size: 12px; list-style-type: none; margin-bottom: 10px;">Upload and share your own videos with the world</li>
                    <li style="color: white; font-size: 12px; list-style-type: none; margin-bottom: 10px;">Comment on, rate, and make video responses to your favorite videos</li>
                    <li style="color: white; font-size: 12px; list-style-type: none; margin-bottom: 10px;">Build playlists of favorites to watch later</li>
                </ul>
            </div>
            <div class="form-box active" id="login-form">
                <div class="column">
                    <form action="login.php" method="post">
                        <p style="color: white; font-size: 30px;">Sign in</p>
                        <input type="email" name="email" placeholder="Email" required>
                        <input type="password" name="password" placeholder="Password" required>
                        <div class="form-row">
                            <button type="submit" name="login" style="color: white;">Sign in</button>
                        </div>
                        <a href="javascript:void(0);" onclick="redirectToRegistration();" id="create-account-btn">Create new account</a>
                    </form>     
                </div>
            </div>
        </div>
    </div>
</body>
</html>
