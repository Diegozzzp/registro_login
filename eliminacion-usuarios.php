<?php

    session_start();
    require 'config.php';

    $id = $_GET['id'];
    $sql = "DELETE FROM users WHERE id = ?";
    $stmt = mysqli_prepare($conexion, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    mysqli_close($conexion);
    header("location: admin-usuarios.php");

?>