<?php
session_start();
require 'config.php';

// Verificar si el usuario está autenticado y es administrador
if (!isset($_SESSION['id'])) {
    header("location: login.php");
    exit;
}

if (isset($_SESSION['id']) && is_numeric($_SESSION['id'])) {
    $id = $_SESSION['id'];

    // Obtener los detalles del usuario a editar
    $sql = "SELECT first_name, last_name, email, phone FROM users WHERE id = ?";
    
    if ($stmt = mysqli_prepare($conexion, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $id);
        
        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_bind_result($stmt, $first_name, $last_name, $email, $phone);
            mysqli_stmt_fetch($stmt);
        } else {
            echo "Error al ejecutar la consulta: " . mysqli_error($conexion);
            exit;
        }
        
        mysqli_stmt_close($stmt);
    } else {
        echo "Error al preparar la consulta: " . mysqli_error($conexion);
        exit;
    }
} else {
    echo "ID de usuario no válido.";
    exit;
}

// Procesar la actualización del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    
    $sql = "UPDATE users SET first_name = ?, last_name = ?, email = ?, phone = ? WHERE id = ?";
    
    if ($stmt = mysqli_prepare($conexion, $sql)) {
        mysqli_stmt_bind_param($stmt, "ssssi", $first_name, $last_name, $email, $phone, $id);
        
        if (mysqli_stmt_execute($stmt)) {
            // Redirigir a la tabla de usuarios después de la actualización
            header("location: admin-usuarios.php");
            exit;
        } else {
            echo "Error al ejecutar la actualización: " . mysqli_error($conexion);
        }
        
        mysqli_stmt_close($stmt);
    } else {
        echo "Error al preparar la consulta: " . mysqli_error($conexion);
    }
}

mysqli_close($conexion);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Editar Usuario</title>
</head>
<body class="w-screen h-screen justify-center items-center flex bg-[url('./image/bg-edits.jpg')] bg-cover bg-no-repeat"> 
    <form class="w-[400px] p-2 m-8 bg-gray-800/[0.5] text-center  flex flex-col items-center justify-center" action="usuarios-edit.php" method="post">
        <h1 class="text-white font-bold text-3xl mb-4">Editar usuario</h1>
    <p class="text-red-500 mb-4 font-semibold "><?php if (isset($login_err)) echo $login_err?></p>
        <div>
            <input value="<?php echo htmlspecialchars($first_name); ?>" type="text" name="first_name" required class="w-[100%] block m-auto mb-4  bg-transparent border-b border-b-white border-b text-center outline-none p-2 text-lg text-white">
        </div>
        <div>
            <input value="<?php echo htmlspecialchars($last_name); ?>" type="text" name="last_name" required class="w-[100%] block m-auto mb-4  bg-transparent border-b border-b-white border-b text-center outline-none p-2 text-lg text-white">
        </div>
        <div>
            <input value="<?php echo htmlspecialchars($email); ?>" type="email" name="email" required class="w-[100%] block m-auto mb-4  bg-transparent border-b border-b-white border-b text-center outline-none p-2 text-lg text-white">
        </div>
        <div>
            <input value="<?php echo htmlspecialchars($phone); ?>" type="text" name="phone" required class="w-[100%] block m-auto mb-4  bg-transparent border-b border-b-white border-b text-center outline-none p-2 text-lg text-white">
        </div>
        <div>
            <button type="submit" class="border-solid p-[.6rem] rounded-[2rem] bg-white font-bold mt-[1rem] text-[1rem] text-black pointer hover: ease-in duration-300 hover:bg-green-400">Realizar actualización</button>
        </div>
    </form>
</body>
</html>
