<?php
require 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = trim($_POST['primer_nombre']);
    $last_name = trim($_POST['apellido']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $phone = trim($_POST['numero_telefonico']);
    
    // Determinar el rol basándose en el dominio del correo electrónico
    $role = 'User';
    if (strpos($email, '@admin') !== false) {
        $role = 'Admin';
    } elseif (strpos($email, '@gmail.com') !== false) {
        $role = 'User';
    }
    
    // Encriptar la contraseña
    $encriptar_password = password_hash($password , PASSWORD_DEFAULT);
    
    // Insertar los datos en la base de datos
    $sql = "INSERT INTO users (first_name, last_name, email, password, phone, rol) VALUES (?, ?, ?, ?, ?, ?)";
    
    if ($stmt = mysqli_prepare($conexion, $sql)) {
        mysqli_stmt_bind_param($stmt, "ssssss", $first_name, $last_name, $email, $encriptar_password, $phone, $role);
        
        if (mysqli_stmt_execute($stmt)) {
            echo "Registro exitoso.";
            // Redirigir al perfil o a la página de inicio
            header("location: login.php");
        } else {
            $error = "ESTE CORREO YA ESTÁ REGISTRADO.";
        }

        mysqli_stmt_close($stmt);
    } else {
        echo "Error: No se pudo preparar la consulta: " . mysqli_error($conexion);
    }
    mysqli_close($conexion);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Registro</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="w-screen h-screen">
<div class="bg-[url('./image/bg.jpg')] bg-cover bg-no-repeat w-full h-full flex items-center justify-center">
    <form action=" register.php" method="post" class=" w-[400px] p-2 m-6 bg-gray-500/[0.5] text-center flex flex-col items-center justify-center">
    <h2 class="p-[2.0rem] font-semibold text-3xl">Crear una cuenta</h2>
        <p class="text-red-600 mb-4 font-semibold"><?php if (isset($error)) echo $error?> </p>
            <div class="w-[200px] flex place-content-around m-auto">
                <div class="h-[20px] w-[20px] flex items-center justify-center p-[1.5rem] border-solid border-white border-[1px] rounded-[50%] text-2xl ease-in duration-300 pointer">
                    <i class='bx bxl-instagram' style="color: #ffff;"></i>
                </div>
                <div class="h-[20px] w-[20px] flex items-center justify-center p-[1.5rem] border-solid border-white border-[1px] rounded-[50%] text-2xl ease-in duration-300 pointer">
                    <i class='bx bxl-linkedin' style="color: #ffff;" ></i>
                </div>
                <div class="h-[20px] w-[20px] flex items-center justify-center p-[1.5rem] border-solid border-white border-[1px] rounded-[50%] text-2xl ease-in duration-300 pointer">
                    <i class='bx bxl-facebook-circle' style="color: #ffff;" ></i>
                </div>
            </div>
        <input placeholder="Primer nombre" type="text" name="primer_nombre" required  class="w-[70%] block mt-2 m-auto mb-4  bg-transparent border-b border-b-white border-b text-center outline-none p-2 text-lg text-white">
            
        <input placeholder="Apellido" type="text" name="apellido" required  class="w-[70%] block m-auto mb-4  bg-transparent border-b border-b-white border-b text-center outline-none p-2 text-lg text-white">
     
        <input placeholder="Email" type="email" name="email" required  class="w-[70%] block m-auto mb-4  bg-transparent border-b border-b-white border-b text-center outline-none p-2 text-lg text-white">
     
        <input placeholder="Contrasena" type="password" name="password" required  class="w-[70%] block m-auto mb-4  bg-transparent border-b border-b-white border-b text-center outline-none p-2 text-lg text-white">
     
        <input placeholder="Numero telefonico" type="number" name="numero_telefonico" required  class="w-[70%] block m-auto mb-4  bg-transparent border-b border-b-white border-b text-center outline-none p-2 text-lg text-white">

        <input placeholder="Fecha de nacimiento" type="date" name="fecha_de_nacimiento" required  class="w-[70%] block m-auto mb-4  bg-transparent border-b border-b-white border-b text-center outline-none p-2 text-lg text-white ">
     
        <button type="submit" value="Registrar" class="w-[60%] m-auto border-solid p-[.7rem] rounded-[2rem] bg-white font-bold mt-[1.5rem] text-[.8rem] pointer text-black"> Registrar </button>

        <p class="text-white mt-4">Ya tienes una cuenta? <a href="login.php" class="text-white text-[1.2rem] font-semibold hover:underline cursor-pointer hover:text-green-500 hover: ease-in duration-300">Inicia sesión</a></p>
    </form>
</body>
</html>
