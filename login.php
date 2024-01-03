<?php 

$isFail = 0;

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['login']) && !empty($_POST["email"])) {
    $email = $_POST['email'];
    $pass = $_POST["pass"];

    require_once('connect.php');
    
    $stmt = $mysqli->prepare("SELECT * FROM user WHERE email = ?;");
    $stmt->bind_param("s", $email);

    if (!$stmt->execute()) {
        echo "select failed";
        exit();
    } 

    $result = $stmt->get_result();

    if ($result->num_rows <= 0) {
        echo "Login Failed: incorrect  email";
        exit();
    }
    
    $user = $result->fetch_assoc();
    if(password_verify($pass, $user["user_password"])) {
        session_start();
        $_SESSION["uid"] = (int) $user["uid"];
        $_SESSION["user_name"] = $user["name"];
        $_SESSION["email"] = $user["email"];
        $_SESSION["role_id"] = (int) $user["role_id"];
        header("Location: index.php");
        exit(0);
    } else {
        $isFail = 1;
    }
}

?>

<!DOCTYPE html>
<html lang="en-US">
<head>
    <title>Spytifor - Music Streaming Platform</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/login.css">
</head>

<body class="login-page-body">
    <header class="login-header">
        <svg class="logo-spytifor-home">
            <use href="img/logo.svg#logo-spytifor-white-text" />
        </svg>
    </header>
    
    <p style="display: <?php echo ($isFail ? "block" : "none") ?>;" class='banner'>Login Failed: Email or password is not correct</p>
    <main id="login-main">
        <section id="logo-section">
            <svg class="logo-spytifor">
                <use href="img/logo.svg#logo-spytifor-blue" />
            </svg>
            <h1 class="logo-spytifor-text">Spytifor</h1>
        </section>
        <section id="login-section">
            <h1 class="form-title">Log in to Spytifor</h1>
            <form action="<?php $_SERVER["PHP_SELF"] ?>" method="post">
                <label class="form-label">Email</label>
                <input class="form-input" type="email" name="email" placeholder="Email" required>
                <label class="form-label">Password</label>
                <input class="form-input" type="password" name="pass" placeholder="Password" required>
                <input class="form-submit" type="submit" name="login" value="Login">
            </form>
            <hr>
            <p>Don't have an account? <a class="form-link" href="register.html">Sign up for Spytifor</a></p>
        </section>
    </main>
    <footer>
        <p>For educational purpose only | Project of CSS326 Database programming</p>
    </footer>
</body>

</html>