<?php
session_start();

if (!isset($_SESSION['id'])) {
    header("location: login.php");
    exit;
}

require 'config.php';

$id = $_SESSION['id'];
$sql = "SELECT first_name, last_name, email, phone, created_at FROM users WHERE id = ?";
if ($stmt = mysqli_prepare($conexion, $sql)) {
    mysqli_stmt_bind_param($stmt, "i", $id);
    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_bind_result($stmt, $first_name, $last_name, $email, $phone, $created_at);
        mysqli_stmt_fetch($stmt);
    }
    mysqli_stmt_close($stmt);
}

mysqli_close($conexion);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Perfil</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body class="h-screen w-screen ">
<div class="w-full h-full flex items-center justify-center">
    <div class=" flex flex-col items-center bg-gray-100 max-w-[400px] h-[400px]">
        <i class="fa-solid fa-circle-user text-8xl mt-3" style="color: #000000;"></i>
            <h1 class="text-2xl font-bold p-2 ">Bienvenid@, <?php echo htmlspecialchars ($_SESSION['primer_nombre']); ?></h1>
            <p class="p-2 text-lg">Nombre: <?php echo htmlspecialchars($first_name); ?></p>
            <p class="p-2 text-lg">Apellido: <?php echo htmlspecialchars($last_name); ?></p>
            <p class="p-2 text-lg">Email: <?php echo htmlspecialchars($email); ?></p>
            <p class="p-2 text-lg">Teléfono: <?php echo htmlspecialchars($phone); ?></p>
            <p class="p-2 text-lg">Fecha de Registro: <?php echo htmlspecialchars($created_at); ?></p>
            <a href="usuarios-edit.php?id=?" class="p-2 bg-green-500 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded m-2">Editar</a>
            <a href="cerrar-sesion.php" class="p-2 bg-red-500 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded m-2">Cerrar sesión</a>
    </div>
</div>
</body>
</html>
