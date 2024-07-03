<?php
session_start();
if (!isset($_SESSION['user_id'])  || $_SESSION['user_role'] != '3') {
    header('Location: ../login');
}

?>