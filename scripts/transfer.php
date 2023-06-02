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

    $senderbalance = $conn->query("SELECT balance FROM balance WHERE account_id = $_SESSION[id] ");
    while($row = $senderbalance->fetch_assoc()){
        $sbalance = $row['balance'];
    }

    if($sbalance < $_POST['amount']) {
        $_SESSION['error'] = "Your account does not have enough funds";
        echo "<script>history.back();</script>";
        exit();
    }

    if ($stmt = $conn->prepare('select u.id, account_id, balance from user u left join balance on u.id = account_id where account = ?')) {
        // Bind parameters (s = string, i = int, b = blob, etc), in our case the username is a string so we use "s"
        $stmt->bind_param('s', $_POST['r-account']);
        $stmt->execute();
        // Store the result so we can check if the account exists in the database.
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($id, $acc_id, $balance);
            $stmt->fetch();

            if($balance == null){

                $addB = $conn->prepare("INSERT INTO balance (`account_id`, `balance`) VALUES (?, ?);");

                $startB = 0;

                $addB->bind_param('ii', $id,$startB);

                $addB->execute();

                if ($stmt->affected_rows) {
                    $_SESSION["error"] = "Account for reciever " . $_POST['r-name'] . " was added!";
                }
            }


            $newRbalance = $balance + $_POST['amount'];

            $newSbalance = $sbalance - $_POST['amount'];
            /*
            echo $newRbalance;
            echo "<hr />";
            echo $newSbalance;*/


            $sql = "UPDATE balance SET balance = $newRbalance where account_id = $id";
            $conn->query($sql);
            if ($conn->affected_rows == 1){
                $_SESSION["success"] = "Transfer is succesfull!";
            }else{

                $_SESSION["error"] = "Transfer failed!";
                header("location: ../pages/project/operations.php");
            }

            $sql = "UPDATE balance SET balance = $newSbalance where account_id = $_SESSION[id]";
            $conn->query($sql);
            if ($conn->affected_rows == 1){
                $_SESSION["success"] = "Transfer is succesfull!";
            }else{
                $_SESSION["error"] = "Transfer failed!";
                header("location: ../pages/project/operations.php");
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