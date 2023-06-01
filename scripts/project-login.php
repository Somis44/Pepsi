<?php
session_start();
require_once "./connect.php";

if (!isset($_POST['email'], $_POST['pass'])) {
    // Could not get the data that should have been sent.
    $_SESSION['error'] = "Fill in all fields!";
    echo "<script>history.back();</script>";
    exit();
}

// Prepare our SQL, preparing the SQL statement will prevent SQL injection.
if ($stmt = $conn->prepare('SELECT id, role_id, firstName, lastName, password FROM user WHERE email = ?')) {
    // Bind parameters (s = string, i = int, b = blob, etc), in our case the username is a string so we use "s"
    $stmt->bind_param('s', $_POST['email']);
    $stmt->execute();
    // Store the result so we can check if the account exists in the database.
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $role_id,$firstName, $lastName, $password);
        $stmt->fetch();
        // Account exists, now we verify the password.
        // Note: remember to use password_hash in your registration file to store the hashed passwords.
        if (password_verify($_POST['pass'], $password)) {
            // Verification success! User has logged-in!
            // Create sessions, so we know the user is logged in, they basically act like cookies but remember the data on the server.
            session_regenerate_id();
            $_SESSION['loggedin'] = TRUE;
            $_SESSION['email'] = $_POST['email'];
            $_SESSION['id'] = $id;
            $_SESSION['role'] = $role_id;
            $_SESSION['firstName'] = $firstName;
            $_SESSION['lastName'] = $lastName;
            $_SESSION['session_id']=session_id();
            //echo 'Welcome ' . $_SESSION['name'] . '('. $_SESSION['id'] .')'. '!';
            if($_SESSION['role'] == 2){
                header('Location: ../pages/project/moder-home-page.php');
            }elseif($_SESSION['role'] == 3){
                header('Location: ../pages/project/admin-home-page.php');
            }else{
                header('Location: ../pages/project/user-home-page.php');
            }
        } else {
            // Incorrect password
            $_SESSION['error'] = "Incorrect e-mail address or password";
            echo "<script>history.back();</script>";
            exit();
            //echo 'Incorrect username and/or password!';
        }
    } else {
        // Incorrect username
        $_SESSION['error'] = "Incorrect e-mail address or password";
        echo "<script>history.back();</script>";
        exit();
        //echo 'Incorrect username and/or password!';
    }

    $stmt->close();
}
?>
