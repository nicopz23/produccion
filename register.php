<?php
if (isset($_POST["username"])) {
    include "conexion.php";

    $usuario = $_POST["username"];
    $email = $_POST["email"];
    $passwordl = $_POST["password"];

    $image = $_FILES["image"]["name"];
    $target_dir = "assets/img/";
    $target_file = $target_dir . basename($_FILES["image"]["name"]);

    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Verificar si la imagen es real o falsa
    if (isset($_POST["submit"])) {
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if ($check !== false) {
            echo "File is an image - " . $check["mime"] . ".";
            $uploadOk = 1;
        } else {
            echo "File is not an image.";
            $uploadOk = 0;
        }
    }

    // Verificar si el archivo ya existe
    if (file_exists($target_file)) {
        echo "Sorry, file already exists.";
        $uploadOk = 0;
    }

    // Verificar el tamaño de la imagen
    if ($_FILES["image"]["size"] > 500000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Permitir ciertos formatos de archivo
    if (
        $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif"
    ) {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    // Verificar si $uploadOk está configurado en 0 por un error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
        // Si todo está bien, intenta subir el archivo
    } else {
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            try {
                $sql = "insert into user (username, email ,password, image) values(?, ?, ?, ?)";

                $stmt = $conn->prepare($sql);
                $stmt->bindParam(1, $usuario);
                $stmt->bindParam(2, $email);
                $stmt->bindParam(3, $passwordl);
                $stmt->bindParam(4, $image);

                $stmt->execute();

                $rowCount = $stmt->rowCount(); // Obtiene el número de filas afectadas por la última operación

                if ($rowCount > 0) {
                    // La inserción fue exitosa, muestra el mensaje
                    header("Location: ./");
                    exit();
                } else {
                    // La inserción falló, muestra un mensaje de error si es necesario
                    $error = "No se ha podido crear el usuario";
                }
            } catch (PDOException $e) {
                $error = "No se ha podido crear el usuario";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
</head>

<body>
    <form action="" method="post" enctype="multipart/form-data">
        <label>Username</label>
        <input type="text" name="username" placeholder="Username">
        <label>Email</label>
        <input type="email" name="email" placeholder="Email">
        <label>Password</label>
        <input type="password" name="password" placeholder="Password">
        <input type="file" name="image">
        <button type="submit">New</button>
        <?php
        if (isset($error)) {
            echo "<p>" . $error . "</p>";
        }
        ?>
    </form>
</body>

</html>