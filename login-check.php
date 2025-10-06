<?php

require_once("config/database.php");
$username = mysqli_real_escape_string($mysqli,stripslashes(strip_tags(htmlspecialchars(trim($_POST["username"])))));
$password = md5(mysqli_real_escape_string($mysqli,stripslashes(strip_tags(htmlspecialchars(trim($_POST["password"]))))));

if(!ctype_alnum($username) or !ctype_alnum($password)){
header("location: index.php?alert=1?username=");
}else{
    $query = mysqli_query($mysqli, "SELECT * FROM usuarios WHERE username = '$username' AND PASSWORD = '$password' AND STATUS = 'activo';")
    or die("error".mysqli_error($mysqli));
    $row = mysqli_num_rows($query);
    if($row > 0){
        $data = mysqli_fetch_assoc($query);

        session_start();
        $_SESSION["id_user"] = $data["id_user"];
        $_SESSION["username"] = $data["username"];
        $_SESSION["password"] = $data["password"];
        $_SESSION["name_user"] = $data["name_user"];
        $_SESSION["permisos_acceso"] = $data["permisos_acceso"];

        //echo "<script>alert('Existe el usuario sea bienvenido !');</script>";
        header("Location: main.php?module=start");
    }else{
        header("location: index.php?alert=1");
    }
}

?>