<?php
    session_start();
    session_destroy();
    header("location: ../pages/project/login.php?logout=1");
