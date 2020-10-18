<?php
    session_start();

    if (isset($_SESSION["username"])) {
        header("Location: index.php");
    }

    require_once("User.php");

    if (isset($_POST["form"])) {
        $form = $_POST["form"];

        if (!empty($form)) {
            $user = User::getUser("username", $form["username"]);

            if (empty($user)) {
                echo "Username or password incorrect!";
            } else {
                if (password_verify($form["password"], $user->password)) {
                    $_SESSION["username"] = $user->username;
                    $_SESSION["user_id"] = $user->user_id;
                    header("Location: index.php");
                } else {
                    echo "Password incorrect!";
                }
            }
        }
    }
?>

<form action="login.php" method="post">
    <div>
        <label for="username">Username</label>
        <input type="text" id="username" name="form[username]" value="jeremy" required />
    </div>
    <div>
        <label for="password">Password</label>
        <input type="password" id="password" name="form[password]" value="test" required />
    </div>
    <div>
        <button type="submit" id="login">Login</button>
    </div>
    <div>
        <a href="register.php">Register</a>
    </div>
</form>