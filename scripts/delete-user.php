<?php
session_start();

//echo "<p>$_SESSION[userDeleteId]</p>";

if($_SESSION['role'] != 3) {
    $_SESSION["error"] = "How you even got where?";
    header("location: ../pages/project/admin-users-list.php");
}

require_once "connect.php";

if ($stmt = $conn->prepare('select firstName, lastName, account from user where id = ?')) {
    // Bind parameters (s = string, i = int, b = blob, etc), in our case the username is a string so we use "s"
    $stmt->bind_param('i', $_SESSION['userDeleteId']);
    $stmt->execute();
    // Store the result so we can check if the account exists in the database.
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($firstName, $lastName, $account);
        $stmt->fetch();
    }
}

$sql = "DELETE FROM history where sender_id = $account";
$conn->query($sql);

$sql = "DELETE FROM history where recipient_id = $account";
$conn->query($sql);

$sql = "DELETE FROM balance where account_id = $_SESSION[userDeleteId]";
$conn->query($sql);

$sql = "DELETE FROM user WHERE `user`.`id` = $_SESSION[userDeleteId]";
$conn->query($sql);

if ($conn->affected_rows == 0){
    $_SESSION["error"] = "User wasn't deleted!";
    header("location: ../pages/project/admin-users-list.php");

}else{
    $_SESSION["success"] = "User ".$firstName ." ". $lastName." deleted successfully!";
    header("location: ../pages/project/admin-users-list.php");
}
?>