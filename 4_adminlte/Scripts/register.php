<?php
session_start();
//	echo "<pre>";
//		print_r($_POST);
//	echo "</pre>";
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $errors = [];
/*
    foreach ($_POST as $key => $value) {
        //echo "$key: $value<br>";
        if (empty($value)) {
            //echo "$key<br>";
            $_SESSION["error"] = "Wypełnij wszystkie pola!";
            echo "<script>history.back();</script>";
            exit();
        }
    }*/

    foreach ($_POST as $key => $value) {
        if (empty($value)) {
            $errors[] = "Pole $key jest wymagane!";
        }
    }

    if($_POST["email"] != $_POST["confirm_email"]){
        $errors[] = "Adresy email są różne!";
    }

    if($_POST["pass"] != $_POST["confirm_pass"]){
        $errors[] = "Hasła są różne!";
    }

    if(!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)){
        $errors[] = "Niepoprawny format adresu!";
    }

    if(!empty($error)){
        $_SESSION["error"] = implode("<br>",$errors);
        echo "<script>history.back();</script>";
        exit();
    }


    //mysqli_report(MYSQLI_REPORT_ERROR);
    //mysqli_report(MYSQLI_REPORT_STRICT);



//https://www.php.net/manual/en/mysqli-stmt.bind-param.php
    require_once "./connect.php";

    try{
        $stmt = $conn->prepare("INSERT INTO `users` (`city_id`, `email`, `firstName`, `lastName`, `birthday`, `password`) VALUES (?, ?, ?, ?, ?, ?);");

        $pass = password_hash($_POST["pass"], algo:PASSWORD_ARGON2ID);

        $stmt->bind_param("isssss", $_POST["city_id"], $_POST["email"], $_POST["firstName"], $_POST["lastName"], $_POST["birthday"], $pass);

        $stmt->execute();
//echo $stmt->affected_rows;


        if ($stmt->affected_rows) {
            $_SESSION["success"] = "Prawidłowo dodano użytkownika " . " " . $_POST["firstName"] . " " . $_POST["lastName"];
            header("location: ../AdminLTE/pages/index.php");;
        } else {
            $_SESSION["error"] = "Nie dodano dodano użytkownika!";
            echo "<script>history.back();</script>";
        }
    }catch(mysqli_sql_exception $e){
        $_SESSION["error"] = "Adres email jest zarezerwowany!";
        echo "<script>history.back();</script>";
    }


}else{
    $_SESSION["error"] = "Wypełni wszystkie dane";
    echo "<script>history.back();</script>";
}




