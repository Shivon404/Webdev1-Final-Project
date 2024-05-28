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
    <title>Registration Form</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <script>
        function redirectToLogin() {
            window.location.href = "login.php";
        }
    </script>
</head>
<body>
    <div class="container">
        <?php
        if (isset($_POST["submit"])) {
            $fname = $_POST["fname"];
            $lname = $_POST["lname"];
            $username = $_POST["username"];
            $email = $_POST["email"];
            $password = $_POST["password"];
            $passwordRepeat = $_POST["repeat_password"];
            
            $fullName = $fname . ' ' . $lname;
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);

            $errors = array();
            
            if (empty($fname) || empty($lname) || empty($username) || empty($email) || empty($password) || empty($passwordRepeat)) {
                array_push($errors, "All fields are required");
            }
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                array_push($errors, "Email is not valid");
            }
            if (strlen($password) < 8) {
                array_push($errors, "Password must be at least 8 characters long");
            }
            if ($password !== $passwordRepeat) {
                array_push($errors, "Passwords do not match");
            }
            require_once "database.php";
            $sql = "SELECT * FROM users WHERE email = ?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "s", $email);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $rowCount = mysqli_num_rows($result);
            if ($rowCount > 0) {
                array_push($errors, "Email already exists!");
            }
            mysqli_stmt_close($stmt);

            if (count($errors) > 0) {
                foreach ($errors as $error) {
                    echo "<div class='alert alert-danger'>$error</div>";
                }
            } else {
                $sql = "INSERT INTO users (fname, lname, username, email, password) VALUES (?, ?, ?, ?, ?)";
                $stmt = mysqli_stmt_init($conn);
                if (mysqli_stmt_prepare($stmt, $sql)) {
                    mysqli_stmt_bind_param($stmt, "sssss", $fname, $lname, $username, $email, $passwordHash);
                    mysqli_stmt_execute($stmt);
                    mysqli_stmt_close($stmt);

                    // Set the user session and redirect to home
                    $_SESSION["user"] = $email; // Store user email in session
                    header("Location: ../Youtube home/home.html");
                    exit();
                } else {
                    die("Something went wrong");
                }
            }
        }
        ?>
        <div class="box">
            <div class="title-box">
                <img class="yt-logo" src="youtube logo.png" alt="YouTube">
                <p style="color: white; font-size: 30px;">Sign up for YouTube!</p>
                <p style="color: white; font-size: 20px; font-weight: bold;">Join the largest worldwide video community!</p>
                <p style="color: white; font-size: 15px;">Get full access to YouTube with your account</p>
                <ul style="padding-left: 15px;">
                    <li style="color: white; font-size: 12px; list-style-type: none; margin-bottom: 10px;">Upload and share your own videos with the world</li>
                    <li style="color: white; font-size: 12px; list-style-type: none; margin-bottom: 10px;">Comment on, rate, and make video responses to your favorite videos</li>
                    <li style="color: white; font-size: 12px; list-style-type: none; margin-bottom: 10px;">Build playlists of favorites to watch later</li>
                </ul>
            </div>
            <div class="form-box" id="register-form">
                <div class="column">
                    <form action="registration.php" method="post">
                        <p style="color: white; font-size: 30px;">Register</p>
                        <div class="form-row">
                            <input style="margin-right: 10px; width: 160px;" class="idk" type="text" name="fname" placeholder="First name" required>
                            <input class="idk" type="text" name="lname" placeholder="Last name" required>
                        </div>
                        <input type="text" name="username" placeholder="Username" required>
                        <input type="email" name="email" placeholder="Email" required>
                        <input type="password" name="password" placeholder="Password" required>
                        <input type="password" name="repeat_password" placeholder="Repeat Password" required>
                        <button type="submit" name="submit">Register</button>
                        <a href="javascript:void(0);" onclick="redirectToLogin();" id="sign-in-btn">Already have an account?</a>
                    </form>     
                </div>
            </div>
        </div>
    </div>
</body>
</html>
