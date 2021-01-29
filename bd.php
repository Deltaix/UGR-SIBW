<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

function conectarBD() {
    $bdhost = "localhost";
    $bdusuario = "jpgs";
    $bdpassword = "passbdjpgs98";
    $bd = "basesibw";
    
    $conexion = new mysqli($bdhost, $bdusuario, $bdpassword, $bd) or die("Conexion fallida: %s\n". $conexion -> error);
    $conexion->set_charset("utf8");

    return $conexion;
}

function obtenerEventos($rango) {
    $mysql = conectarBD();    

    if ($rango == "gestor" or $rango == "superusuario")
        $sql = "SELECT * FROM eventos";
    else
        $sql = "SELECT * FROM eventos WHERE publicado = '1'";

    $peticion = $mysql->query($sql);
    if ($peticion->num_rows > 0) {
        return $peticion;
    }
    
    mysql_close($mysql);
}

function obtenerEvento($idEvento) {
    $mysql = conectarBD();

    $sql = "SELECT * FROM eventos WHERE id=$idEvento";
    $peticion = $mysql->query($sql);

    if ($peticion->num_rows > 0) {
        $row = mysqli_fetch_array($peticion);
        return $row;
    }

    mysql_close($mysql);
}

function borrarEvento($idEvento) {
    $mysql = conectarBD();

    $sql = "DELETE FROM `imagenes` WHERE `imagenes`.`idEvento` = '$idEvento'";
    $peticion = $mysql->query($sql);

    if (!$peticion)
        return false;
    
    $sql = "DELETE FROM `eventos` WHERE `eventos`.`id` = '$idEvento'";
    $peticion = $mysql->query($sql);

    if ($peticion)
        return true;
    
    return false;
}

function editarEvento($nombre, $fecha, $lugar, $texto, $foto, $imagen, $facebook, $twitter, $tag, $idEvento) {
    $mysql = conectarBD();

    $path = "images/";
    
    if ($nombre) {
        $sql = "UPDATE `eventos` SET `nombre` = '$nombre' WHERE `eventos`.`id` = '$idEvento'";
        $peticion = $mysql->query($sql);
        if (!$peticion)
            return false;
    }
    if ($fecha) {
        $sql = "UPDATE `eventos` SET `fecha` = '$fecha' WHERE `eventos`.`id` = '$idEvento'";
        $peticion = $mysql->query($sql);
        if (!$peticion)
            return false;
    }
    if ($lugar) {
        $sql = "UPDATE `eventos` SET `lugar` = '$lugar' WHERE `eventos`.`id` = '$idEvento'";
        $peticion = $mysql->query($sql);
        if (!$peticion)
            return false;
    }
    if ($texto) {
        $sql = "UPDATE `eventos` SET `texto` = '$texto' WHERE `eventos`.`id` = '$idEvento'";
        $peticion = $mysql->query($sql);
        if (!$peticion)
            return false;
    }
    if ($foto) {
        move_uploaded_file($foto['tmp_name'], $path.basename($foto['name']));
        $path_img = $path . $foto['name'];

        $sql = "UPDATE `eventos` SET `imagen` = '$path_img' WHERE `eventos`.`id` = '$idEvento'";
        $peticion = $mysql->query($sql);
        if (!$peticion)
            return false;
    }
    if ($imagen) {
        if ($imagen['name']) {
            move_uploaded_file($imagen['tmp_name'], $path.basename($imagen['name']));
            $path_img = $path . $imagen['name'];

            $sql = "SELECT MAX(id) AS total FROM imagenes";
            $filas = $mysql->query($sql);
            $numero = mysqli_fetch_assoc($filas);
            $id = $numero['total'] + 1;

            $sql = "INSERT INTO `imagenes` (`id`, `idEvento`, `ruta`) VALUES ('$id', '$idEvento', '$path_img')";
            $peticion = $mysql->query($sql);
            if (!$peticion)
                return false;
        }
    }
    if ($facebook) {
        $sql = "UPDATE `eventos` SET `facebook` = '$facebook' WHERE `eventos`.`id` = '$idEvento'";
        $peticion = $mysql->query($sql);
        if (!$peticion)
            return false;
    }
    if ($twitter) {
        $sql = "UPDATE `eventos` SET `twitter` = '$twitter' WHERE `eventos`.`id` = '$idEvento'";
        $peticion = $mysql->query($sql);
        if (!$peticion)
            return false;
    }
    if ($tag) {
        $sql = "UPDATE `eventos` SET `etiquetas` = '$tag' WHERE `eventos`.`id` = '$idEvento'";
        $peticion = $mysql->query($sql);
        if (!$peticion)
            return false;
    }
    return true;
}

function aniadirEvento($nombre, $fecha, $lugar, $texto, $foto, $imagen, $facebook, $twitter, $tag) {
    $mysql = conectarBD();

    $sql = "SELECT id FROM eventos ORDER BY id DESC LIMIT 1";
    $peticion = $mysql->query($sql);
    $fila = $peticion->fetch_array();
    $idEvento = $fila['id'] + 1;

    $path = "images/";
    move_uploaded_file($foto['tmp_name'], $path.basename($foto['name']));
    $path_img = $path . $foto['name'];

    $sql = "INSERT INTO `eventos` (`id`, `nombre`, `fecha`, `lugar`, `texto`, `twitter`, `facebook`, `imagen`) VALUES ('$idEvento', '$nombre', '$fecha', '$lugar', '$texto', '$twitter', '$facebook', '$path_img')";
    $peticion = $mysql->query($sql);

    if (!$peticion)
        return false;
    
    if ($imagen) {
        if ($imagen['name']) {
            move_uploaded_file($imagen['tmp_name'], $path.basename($imagen['name']));
            $path_img = $path . $imagen['name'];

            $sql = "SELECT MAX(id) AS total FROM imagenes";
            $filas = $mysql->query($sql);
            $numero = mysqli_fetch_assoc($filas);
            $id = $numero['total'] + 1;

            $sql = "INSERT INTO `imagenes` (`id`, `idEvento`, `ruta`) VALUES ('$id', '$idEvento', '$path_img')";
            $peticion = $mysql->query($sql);
            if (!peticion)
                return false;
        }
    }

    return true;
}

function obtenerAllComentarios() {
    $mysql = conectarBD();

    $sql = "SELECT * FROM comentarios";
    $peticion = $mysql->query($sql);

    if ($peticion)
        return $peticion;
    else
        return false;
}

function obtenerComentarios($idEvento) {
    $mysql = conectarBD();

    $sql = "SELECT * FROM comentarios WHERE idEvento=$idEvento";
    $peticion = $mysql->query($sql);

    if ($peticion->num_rows > 0) {
        return $peticion;
    }
    else return false;

    mysql_close($mysql);
}

function obtenerComentario($idComentario) {
    $mysql = conectarBD();

    $sql = "SELECT * FROM comentarios WHERE id=$idComentario";
    $peticion = $mysql->query($sql);

    if ($peticion) {
        return $peticion->fetch_assoc();
    }
    else
        return false;
}

function nuevoComentario($nombre, $texto, $idEvento) {
    $mysql = conectarBD();

    $sql = "SELECT MAX(id) AS total FROM comentarios";
    $filas = $mysql->query($sql);
    $numero = mysqli_fetch_assoc($filas);
    $id = $numero['total'] + 1;
    $date = date('Y-m-d H:i:s');

    $banned = obtenerBanned();
    $fila = mysqli_fetch_assoc($banned);
    while ($fila) {
        $palabra = $fila['palabra'];
        str_replace($palabra, str_repeat("*", strlen($palabra)), $texto);
        $fila = mysqli_fetch_assoc($banned);
    }

    $orden = "INSERT INTO `comentarios` (`id`, `idEvento`, `nombre`, `texto`, `fecha`) VALUES ('$id', '$idEvento', '$nombre', '$texto', '$date')";
    $peticion = $mysql->query($orden);

    // mysql_close($mysql);
}

function editarComentario($idComentario, $nuevotexto) {
    $mysql = conectarBD();
    
    $sql = "UPDATE `comentarios` SET `texto` = '$nuevotexto' WHERE `comentarios`.`id` = '$idComentario'";
    $peticion = $mysql->query($sql);

    if (!$peticion)
        return false;
    
    $sql = "UPDATE `comentarios` SET `editado` = '1' WHERE `comentarios`.`id` = '$idComentario'";
    $peticion = $mysql->query($sql);

    if ($peticion)
        return true;
    
    return false;
}

function obtenerBanned() {
    $mysql = conectarBD();

    $sql = "SELECT * FROM palabrasbaneadas";
    $peticion = $mysql->query($sql);

    if ($peticion->num_rows > 0) {
        return $peticion;
    }
    else {
        return false;
    }

    mysql_close($mysql);
}

function obtenerImagenes($idEvento) {
    $mysql = conectarBD();

    $sql = "SELECT * FROM imagenes WHERE idEvento=$idEvento";
    $peticion = $mysql->query($sql);

    if ($peticion) {
        return $peticion;
    }

    mysql_close($mysql);
}

function obtenerNombre($username) {
    $mysql = conectarBD();

    $sql = "SELECT * FROM usuarios WHERE username=$username";
    $peticion = $mysql->query($sql);

    if ($peticion->num_rows > 0) {
        return $username;
    }

    mysql_close($mysql);
}

function registrarUsuario($nick, $pass) {
    $mysql = conectarBD();

    $sql = "SELECT * FROM usuarios WHERE username=$nick";
    $peticion = $mysql->query($sql);

    if ($peticion) {
        return false;
    }

    $hash = password_hash($pass, PASSWORD_DEFAULT);

    $sql = "INSERT INTO `usuarios` (`username`, `password`) VALUES ('$nick', '$hash')";
    $peticion = $mysql->query($sql);

    // mysql_close($mysql);
}

function checkLogin($nick, $pass) {
    $mysql = conectarBD();

    $sql = "SELECT * FROM usuarios WHERE username='$nick'";
    $peticion = $mysql->query($sql);
    $usuario = $peticion->fetch_assoc();

    if ($peticion) {
        if (password_verify($pass, $usuario['password'])) {
            return true;
        }
    }

    return false;
    
    $mysql->close();
}

function obtenerRango($nick) {
    $mysql = conectarBD();

    $sql = "SELECT * FROM usuarios WHERE username='$nick'";
    $peticion = $mysql->query($sql);

    if ($peticion) {
        $var = $peticion->fetch_assoc();

        if ($var["usernormal"]) {
            return "usernormal";
        }
        elseif ($var["moderador"]) {
            return "moderador";
        }
        elseif ($var["gestor"]) {
            return "gestor";
        }
        else {
            return "superusuario";
        }
    }

    return false;
}

function actualizarPass($newpass, $oldpass, $nick) {
    $mysql = conectarBD();

    $sql = "SELECT * FROM usuarios WHERE username='$nick'";
    $peticion = $mysql->query($sql);
    $usuario = $peticion->fetch_assoc();

    if ($peticion) {
        if (password_verify($oldpass, $usuario['password'])) {
            $hash = password_hash($newpass, PASSWORD_DEFAULT);

            $sql = "UPDATE `usuarios` SET `password` = '$hash' WHERE `usuarios`.`username` = '$nick'";
            $peticion = $mysql->query($sql);
            if ($peticion) {
                return true;
            }
        }
    }

    return false;
}

function borrarComentario($id) {
    $mysql = conectarBD();

    $sql = "DELETE FROM `comentarios` WHERE `comentarios`.`id` = 2";
    $peticion = $mysql->query($sql);
}

function obtenerUsuarios() {
    $mysql = conectarBD();

    $sql = "SELECT * FROM usuarios";
    $peticion = $mysql->query($sql);

    if ($peticion)
        return $peticion;
    
    return false;
}

function modificarPermiso($username, $permiso) {
    $mysql = conectarBD();

    if ($permiso == "registrado") {
        $sql = "UPDATE `usuarios` SET `usernormal` = '1', `moderador` = '0', `gestor` = '0', `superusuario` = '0' WHERE `usuarios`.`username` = '$username'";
        $peticion = $mysql->query($sql);
    }
    elseif ($permiso == "moderador") {
        $sql = "UPDATE `usuarios` SET `usernormal` = '0', `moderador` = '1', `gestor` = '0', `superusuario` = '0' WHERE `usuarios`.`username` = '$username'";
        $peticion = $mysql->query($sql);
    }
    elseif ($permiso == "gestor") {
        $sql = "UPDATE `usuarios` SET `usernormal` = '0', `moderador` = '0', `gestor` = '1', `superusuario` = '0' WHERE `usuarios`.`username` = '$username'";
        $peticion = $mysql->query($sql);
    }
    elseif ($permiso == "superusuario") {
        $sql = "UPDATE `usuarios` SET `usernormal` = '0', `moderador` = '0', `gestor` = '0', `superusuario` = '1' WHERE `usuarios`.`username` = '$username'";
        $peticion = $mysql->query($sql);
    }

    if ($peticion)
        return true;
    
    return false;
}

function obtenerBusqueda($buscar, $rango) {
    $mysql = conectarBD();

    if ($rango == "gestor" or $rango == "superusuario")
        $sql = "SELECT * FROM eventos WHERE nombre LIKE '%$buscar%'";
    else
        $sql = "SELECT * FROM eventos WHERE nombre LIKE '%$buscar%' AND `publicado` = '1'";    
    
    $peticion = $mysql->query($sql);

    $eventos = [];
    if ($peticion) {
        $fila = mysqli_fetch_assoc($peticion);
        while ($fila) {
            array_push($eventos, $fila);
            $fila = mysqli_fetch_assoc($peticion);
        }
        return $eventos;
    }

    return false;
}
?>