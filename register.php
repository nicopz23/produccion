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
                $error = "No se ha podido crear el usuario" . $e->getMessage();
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>
    <form action="" method="post" enctype="multipart/form-data">
        <section class="vh-100" style="background-color: #2779e2;">
            <div class="container h-100">
                <div class="row d-flex justify-content-center align-items-center h-100">
                    <div class="col-xl-9">

                        <h1 class="text-white mb-4">Register</h1>

                        <div class="card" style="border-radius: 15px;">
                            <div class="card-body">

                                <div class="row align-items-center pt-4 pb-3">
                                    <div class="col-md-3 ps-5">

                                        <h6 class="mb-0">Username</h6>

                                    </div>
                                    <div class="col-md-9 pe-5">

                                        <input type="text" name="username" class="form-control form-control-lg" placeholder="Username" />

                                    </div>
                                </div>

                                <hr class="mx-n3">

                                <div class="row align-items-center py-3">
                                    <div class="col-md-3 ps-5">

                                        <h6 class="mb-0">Email address</h6>

                                    </div>
                                    <div class="col-md-9 pe-5">

                                        <input type="email" name="email" class="form-control form-control-lg" placeholder="example@example.com" />

                                    </div>
                                </div>

                                <hr class="mx-n3">

                                <div class="row align-items-center py-3">
                                    <div class="col-md-3 ps-5">

                                        <h6 class="mb-0">Password</h6>

                                    </div>
                                    <div class="col-md-9 pe-5">

                                        <input type="password" name="password" class="form-control" rows="3" placeholder="Password">

                                    </div>
                                </div>

                                <hr class="mx-n3">

                                <div class="row align-items-center py-3">
                                    <div class="col-md-3 ps-5">

                                        <h6 class="mb-0">Upload photo</h6>

                                    </div>
                                    <div class="col-md-9 pe-5">

                                        <input class="form-control form-control-lg" name="image" id="formFileLg" type="file" />
                                        <div class="small text-muted mt-2">Upload ur photo</div>

                                    </div>
                                </div>

                                <hr class="mx-n3">
                                <?php

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

                                if (isset($error)) {
                                    echo "<p>" . $error . "</p>";
                                }
                                ?>
                                <div class="px-5 py-4">
                                    <button type="submit" data-mdb-button-init data-mdb-ripple-init class="btn btn-primary btn-lg">Register</button>
                                </div>

                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </section>
    </form>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

    <!-- Option 2: Separate Popper and Bootstrap JS -->
    <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
    -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
</body>

</html>