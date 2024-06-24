<?php
session_start();
require 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    
    // Consultar el usuario en la base de datos
    $sql = "SELECT id, first_name, last_name, email, password, rol FROM users WHERE email = ?";
    
    if ($stmt = mysqli_prepare($conexion, $sql)) {
        mysqli_stmt_bind_param($stmt, "s", $email);
        
        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_store_result($stmt);
            
            if (mysqli_stmt_num_rows($stmt) == 1) {
                mysqli_stmt_bind_result($stmt, $id, $first_name, $last_name, $email, $encriptar_password, $role);
                
                if (mysqli_stmt_fetch($stmt)) {
                    if (password_verify($password, $encriptar_password)) {
                        // Inicio de sesión exitoso
                        session_start();
                        $_SESSION['id'] = $id;
                        $_SESSION['primer_nombre'] = $first_name;
                        $_SESSION['apellido'] = $last_name;
                        $_SESSION['email'] = $email;
                        $_SESSION['role'] = $role;
                        
                        // Redirigir al perfil o a la página de usuarios si es admin
                        if ($role === 'Admin') {
                            header("location: admin-usuarios.php");
                        } else {
                            header("location: profile.php");
                        }
                    } else {
                        // Contraseña incorrecta
                        $login_err = "La contraseña es incorrecta.";
                    }
                }
            } else {
                // Usuario no encontrado
                $login_err = "No se encontró una cuenta con ese correo electrónico.";
            }
        } else {
            echo "Oops! Algo salió mal. Por favor, inténtalo de nuevo más tarde.";
        }

        mysqli_stmt_close($stmt);
    }
    mysqli_close($conexion);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="h-screen w-screen justify-center items-center flex">
<div class="bg-[url('./image/bg-login.jpg')] bg-cover bg-no-repeat w-full h-full flex items-center justify-center">
<form class="w-[400px] p-2 m-8 bg-gray-700/[0.5] text-center  flex flex-col items-center justify-center" method="post">
    <h2 class="p-[2rem] font-semibold text-3xl">Iniciar Sesión</h2>
    <p class="text-red-500 mb-4 font-semibold "><?php if (isset($login_err)) echo $login_err?></p>
        <div>
            <input placeholder="Email" type="email" name="email" required class="w-[100%] block m-auto mb-4  bg-transparent border-b border-b-white border-b text-center outline-none p-2 text-lg text-white">
        </div>
        <div>
            <input placeholder="Contraseña" type="password" name="password" required class="w-[100%] block m-auto mb-4  bg-transparent border-b border-b-white border-b text-center outline-none p-2 text-lg text-white">
        </div>
        <div>
            <button type="submit" class="border-solid p-[.8rem] rounded-[2rem] bg-white font-bold mt-[2rem] text-[1rem] text-black pointer hover: ease-in duration-300 hover:bg-green-400">Iniciar sesión</button>
        </div>

        <p class="text-white mt-4">No tienes una cuenta? <a href="register.php" class=" pointer  text-[1.2rem] font-semibold hover:underline hover:text-green-500 hover: ease-in duration-300">Registrate</a></p>
    </form>
</div>
</body>
</html>
