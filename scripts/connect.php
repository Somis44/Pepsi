<?php

try{
    $conn = new mysqli("localhost", "root", "", "tech_internetowe_register");
}catch(mysqli_sql_exception $e){
    echo $e->getMessage();
}