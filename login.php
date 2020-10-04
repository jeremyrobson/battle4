<?php
    session_start();

    if ($_SESSION["username"]) {
        header("Location: index.php");
    }

    require_once("User.php");

    $form = $_POST["form"];

    if (!empty($form)) {
        $user = User::getUser("username", $form["username"]);

        if (empty($user)) {
            echo "Username or password incorrect!";
        } else {
            print_r($form["password"]);
            print_r($user->password);
            if (password_verify($form["password"], $user->password)) {
                $_SESSION["username"] = $user->username;
                header("Location: index.php");
            } else {
                echo "Password incorrect!";
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