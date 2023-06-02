<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $errors = [];

    if(!isset($_POST["check"])){
        $errors[] = "You aren't clicked on check!";
    }

    foreach ($_POST as $key => $value) {
        if (empty($value)) {
            $errors[] = "Field $key is required!";
        }
    }

    if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid address format!";
    }

    if (!empty($errors)) {
        $_SESSION["error"] = implode("<br>", $errors);
        header("location: ../pages/project/admin-users-list.php?userUpdateId=$_SESSION[userUpdateId]");
        exit();
    }

    require_once "./connect.php";



    $sql = "UPDATE `user` SET `city_id` = '$_POST[city_id]', `role_id` = '$_POST[role_id]', `email` = '$_POST[email]', `firstName` = '$_POST[firstName]', `lastName` = '$_POST[lastName]', `birthday` = '$_POST[birthday]' WHERE `user`.`id` = $_SESSION[userUpdateId];";
    $conn->query($sql);

    if ($conn->affected_rows == 1){
        $_SESSION["success"] = "User $_POST[firstName] $_POST[lastName] was successfully updated!";
        header("location: ../pages/project/admin-users-list.php");
    }else{
        $_SESSION["error"] = "User wasn't updated!";
        header("location: ../pages/project/admin-users-list.php?userUpdateId=$_SESSION[userUpdateId]");
    }

}else {
    $_SESSION["error"] = "Fill in all fields!";
    header("location: ../pages/project/admin-users-list.php?userUpdateId=$_SESSION[userUpdateId]");
    exit();
}