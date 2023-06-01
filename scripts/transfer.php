<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once "./connect.php";

    if (!isset($_POST['r-account'], $_POST['r-name'], $_POST['amount'], $_POST['check'])) {
        // Could not get the data that should have been sent.
        $_SESSION['error'] = "Fill in all fields!";
        echo "<script>history.back();</script>";
        exit();
    }
/*
    echo "<p>" . $_POST['r-account'] . "</p>";
    echo "<p>" . $_POST['r-name'] . "</p>";
    echo "<p>" . $_POST['amount'] . "</p>";
    echo "<p>" . $_POST['check'] . "</p>";
    echo "<hr />";
    echo "<p>" . $_SESSION['id'] . "</p>";
    echo "<p>".$_SESSION['account']."</p>";
    echo "<hr />";
*/

    $senderbalance = $conn->query("SELECT balance FROM balance WHERE account_id = $_SESSION[id] ");
    while($row = $senderbalance->fetch_assoc()){
        $sbalance = $row['balance'];
    }
    if($sbalance < $_POST['amount']) {
        $_SESSION['error'] = "Your account does not have enough funds";
        echo "<script>history.back();</script>";
        exit();
    }

    if ($stmt = $conn->prepare('select id, balance from user u left join balance on id = account_id where account = ?')) {
        // Bind parameters (s = string, i = int, b = blob, etc), in our case the username is a string so we use "s"
        $stmt->bind_param('s', $_POST['r-account']);
        $stmt->execute();
        // Store the result so we can check if the account exists in the database.
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($id, $balance);
            $stmt->fetch();

            $newRbalance = $balance + $_POST['amount'];

            $newSbalance = $sbalance - $_POST['amount'];
            /*
            echo $newRbalance;
            echo "<hr />";
            echo $newSbalance;*/


            $sql = "UPDATE balance SET balance = $newRbalance where account_id = $id";
            $conn->query($sql);
            /*
            if ($conn->affected_rows == 1){
                $_SESSION["success"] = "Transfer is succesfull!";
            }else{
                $_SESSION["error"] = "Transfer failed!";
            }*/
            $sql = "UPDATE balance SET balance = $newSbalance where account_id = $_SESSION[id]";
            $conn->query($sql);
            if ($conn->affected_rows == 1){
                $_SESSION["success"] = "Transfer is succesfull!";
            }else{
                $_SESSION["error"] = "Transfer failed!";
            }

            $history = $conn->prepare("INSERT INTO history (`sender_id`, `recipient_id`, `amount`) VALUES (?, ?, ?);");

            $history->bind_param('iii', $_SESSION["account"], $_POST["r-account"], $_POST["amount"]);

            $history->execute();

            if ($history->affected_rows) {
                //$_SESSION["success"] = "History of transfer updated succesfully!" . $_POST["firstName"] . " " . $_POST["lastName"];
                header("location: ../pages/project/operations.php");
                exit();
            } else {
                $_SESSION["error"] = "History update failed!";
                header("location: ../pages/project/operations.php");
                exit();
            }

        } else {
            // Incorrect username
            $_SESSION['error'] = "Incorrect account ID";
            echo "<script>history.back();</script>";
            exit();
            //echo 'Incorrect username and/or password!';
        }

        $stmt->close();
    }

}else{
    $_SESSION["error"] = "Fill in all fields!";
    header("location: ../pages/project/operations.php");
    exit();
}
?>