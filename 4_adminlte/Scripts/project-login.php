<?php
session_start();
require_once "./connect.php";


if (!isset($_POST['email'], $_POST['pass'])) {
    // Could not get the data that should have been sent.
    $_SESSION['error'] = "Wypełnij wszystkie pola!";
    echo "<script>history.back();</script>";
    exit();
}

// Prepare our SQL, preparing the SQL statement will prevent SQL injection.
if ($stmt = $conn->prepare('SELECT id, firstName, password FROM users WHERE email = ?')) {
    // Bind parameters (s = string, i = int, b = blob, etc), in our case the username is a string so we use "s"
    $stmt->bind_param('s', $_POST['email']);
    $stmt->execute();
    // Store the result so we can check if the account exists in the database.
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $firstName, $password);
        $stmt->fetch();
        // Account exists, now we verify the password.
        // Note: remember to use password_hash in your registration file to store the hashed passwords.
        if (password_verify($_POST['pass'], $password)) {
            // Verification success! User has logged-in!
            // Create sessions, so we know the user is logged in, they basically act like cookies but remember the data on the server.
            session_regenerate_id();
            $_SESSION['loggedin'] = TRUE;
            $_SESSION['email'] = $_POST['email'];
            $_SESSION['name'] = $firstName;
            $_SESSION['id'] = $id;
            //echo 'Welcome ' . $_SESSION['name'] . '('. $_SESSION['id'] .')'. '!';
            header('Location: ../AdminLTE/Project-pages/Page-1.php');
        } else {
            // Incorrect password
            $_SESSION['error'] = "Błędnie podane hasło";
            echo "<script>history.back();</script>";
            exit();
            //echo 'Incorrect username and/or password!';
        }
    } else {
        // Incorrect username
        $_SESSION['error'] = "Błędnie podany adres e-mail";
        echo "<script>history.back();</script>";
        exit();
        //echo 'Incorrect username and/or password!';
    }

    $stmt->close();
}
?>
