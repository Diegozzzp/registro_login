<?php
session_start();
require 'config.php';

if (!isset($_SESSION['id'])) {
    header("location: login.php");
    exit;
}

$user_id = $_SESSION['id'];
$user_role = $_SESSION['role'];

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];

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
    
    // Verificar permisos: el usuario puede editar su perfil o el admin puede editar cualquier perfil
    if ($user_role === 'Admin' || $user_id == $id) {
        $sql = "UPDATE users SET first_name = ?, last_name = ?, email = ?, phone = ? WHERE id = ?";
        
        if ($stmt = mysqli_prepare($conexion, $sql)) {
            mysqli_stmt_bind_param($stmt, "ssssi", $first_name, $last_name, $email, $phone, $id);
            
            if (mysqli_stmt_execute($stmt)) {
                // Redirigir según el rol del usuario
                if ($user_role === 'Admin') {
                    header("location: users.php");
                } else {
                    header("location: profile.php");
                }
                exit;
            } else {
                echo "Error al ejecutar la actualización: " . mysqli_error($conexion);
            }
            
            mysqli_stmt_close($stmt);
        } else {
            echo "Error al preparar la consulta: " . mysqli_error($conexion);
        }
    } else {
        echo "No tienes permiso para editar este perfil.";
        exit;
    }
}

mysqli_close($conexion);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Editar Usuario</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="w-screen h-screen justify-center items-center flex bg-[url('./image/edits-admin.jpg')] bg-cover bg-no-repeat">
    <form action="usuarios-edit.php?id=<?php echo $id; ?>" method="post" class=" w-[400px] p-2 m-6 bg-gray-700/[0.5] text-center flex flex-col items-center justify-center">
    <h2 class="p-[2.0rem] font-semibold text-3xl">Editar Usuario</h2>    
    
        <input type="text" name="first_name" value="<?php echo htmlspecialchars($first_name); ?>" required class="w-[70%] block mt-2 m-auto mb-4  bg-transparent border-b border-b-white border-b text-center outline-none p-2 text-lg text-white">
       
        <input type="text" name="last_name" value="<?php echo htmlspecialchars($last_name); ?>" required class="w-[70%] block m-auto mb-4  bg-transparent border-b border-b-white border-b text-center outline-none p-2 text-lg text-white">
       
        <input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required class="w-[70%] block m-auto mb-4  bg-transparent border-b border-b-white border-b text-center outline-none p-2 text-lg text-white">
        
        <input type="text" name="phone" value="<?php echo htmlspecialchars($phone); ?>" class="w-[70%] block m-auto mb-4  bg-transparent border-b border-b-white border-b text-center outline-none p-2 text-lg text-white">
    
        <button type="submit" value="Actualizar" class="w-[60%] m-auto border-solid p-[.7rem] rounded-[2rem] bg-white font-bold mt-[1.5rem] text-[.8rem] pointer text-black hover: ease-in duration-300 hover:bg-green-400"> Actualizar </button>
        
    </form>
</body>
</html>