<?php

require_once("check_session.php");

if ((isset($_POST['change'])) && ($_POST['newpass'] === $_POST['connewpass']) && ($_POST["newpass"] !== "") && ($_POST["oldpass"] !== "")){

    require_once("connect.php");

    $q_oldpass ='select user_password from user where uid = ' . $_SESSION['uid'].';';
    if (!$r_oldpass = $mysqli->query($q_oldpass)) {
        echo "failed " . $myslqi->error;
    }

    $oldpass = $r_oldpass->fetch_assoc();
    
    if (!password_verify($_POST["oldpass"], $oldpass["user_password"])) {
        exit("Password is not correct");
    }

    $hashedNewPass = password_hash($_POST["newpass"], PASSWORD_ARGON2ID);
    
    $change = 'update user set user_password = ? where uid = ?; ';
    $stmt = $mysqli->prepare($change);
    $stmt->bind_param("si",$hashedNewPass, $_SESSION['uid']);

    if (!$stmt->execute()) {
        exit("statement execution failed");
    }
    
    session_destroy();
    header('location: login.php');
    exit(0);
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
    <main id="register-main">
        <section id="login-section">
            <h1 class="form-title">Change password</h1>
            <form action="<?php $_SERVER["SCRIPT_NAME"] ?>" method="post">
                <label for="oldpass" class="form-label">Old Password</label>
                <input id="oldpass" class="form-input" type="password" name="oldpass" placeholder="Old Password" onkeyup="compare_pass()" required>
                <label for="newpass" class="form-label">New Password</label>
                <input id="newpass" class="form-input" type="password" name="newpass" placeholder="New Password" onkeyup="compare_pass()" required>
                <label for="connewpass" class="form-label">Confirm New Password</label>
                <input id="connewpass" class="form-input" type="password" name="connewpass"placeholder="Confirm New Password" onkeyup="compare_pass()" required>
                <p id="p-pass-compare">Passwords do not match</p>
                <input id="change" class="form-submit" type="submit" name="change" value="Change">
            </form>
        </section>
    </main>
    <footer>
        <p>For educational purpose only | Project of CSS325 Database programming</p>
    </footer>

    <script>
        let change_bt = document.getElementById("change");
        let compare_pass = () => {
            if (document.getElementById("newpass").value == document.getElementById("connewpass").value) {
                document.getElementById("p-pass-compare").style.display = "none";
                change_bt.removeAttribute("disabled");
            } else {
                document.getElementById("p-pass-compare").style.display = "block";
                change_bt.setAttribute("disabled", "");
            }
        }
    </script>
</body>

</html>