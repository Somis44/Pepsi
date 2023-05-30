<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $errors = [];

    foreach ($_POST as $key => $value) {
        if (empty($value)) {
            $errors[] = "Field $key is required!";
        }
    }

    if ($_POST["email"] != $_POST["confirm_email"]) {
        $errors[] = "Email addresses are different!";
    }

    if ($_POST["pass"] != $_POST["confirm_pass"]) {
        $errors[] = "The passwords are different!";
    }

    if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid address format!";
    }

    if (!empty($errors)) {
        $_SESSION["error"] = implode("<br>", $errors);
        header("location: ../pages/project/login.php");
        exit();
    }

    require_once "./connect.php";

    try {
        $stmt = $conn->prepare("INSERT INTO user (`city_id`, `account`, `email`, `firstName`, `lastName`, `birthday`, `password`) VALUES (?, ?, ?, ?, ?, ?, ?);");

        $pass = password_hash($_POST["pass"], PASSWORD_ARGON2ID);

        $account = rand(1000000, 9999999);

        $stmt->bind_param('iisssss', $_POST["city_id"], $account, $_POST["email"], $_POST["firstName"], $_POST["lastName"], $_POST["birthday"], $pass);

        $stmt->execute();

        if ($stmt->affected_rows) {
            $_SESSION["success"] = "User added successfully " . $_POST["firstName"] . " " . $_POST["lastName"];
            header("location: ../pages/project/login.php");
            exit();
        } else {
            $_SESSION["error"] = "User not added!";
            header("location: ../pages/project/login.php");
            exit();
        }
    } catch (Exception $e) {
        $_SESSION["error"] = "Email address is reserved!";
        header("location: ../pages/project/login.php");
        exit();
    }
} else {
    $_SESSION["error"] = "Fill in all fields!";
    header("location: ../pages/project/login.php");
    exit();
}



