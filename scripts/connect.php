<?php

try{
    $conn = new mysqli("localhost", "root", "", "mono_bank");
}catch(mysqli_sql_exception $e){
    echo $e->getMessage();
}