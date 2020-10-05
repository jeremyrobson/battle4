<?php
    require_once("User.php");

    $form = $_POST["form"];

    if (!empty($form)) {
        $user = User::getUser("username", $form["username"]);

        if (empty($user)) {
            User::insertUser(new User($form));
        } else {
            echo "That user already exists!";
        }
    }
?>

<form action="register.php" method="post">
    <div>
        <label for="username">Username</label>
        <input type="text" id="username" name="form[username]" required />
    </div>
    <div>
        <label for="password">Password</label>
        <input type="password" id="password" name="form[password]" required />
    </div>
    <div>
        <button type="submit" id="register">Register</button>
    </div>
</form>
