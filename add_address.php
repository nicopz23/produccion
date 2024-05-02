<?php
session_start();
if (isset($_SESSION['username'])) {
    //comprobar si hay carrito en la bd
    $usernamec = $_SESSION['username'];
}else{
    header ('Location: ./');
}

if (isset($_POST['street'])) {
    $street = $_POST['street'];
    $city = $_POST['city'];
    $zipcode = $_POST['zipcode'];
    $country = $_POST['country'];
    $iduser = $_SESSION["iduser"];
    require_once 'conexion.php';
    $sql = "insert into address (street,city,zipcode,country,iduser) values (?,?,?,?,?)";
    $stm = $conn->prepare($sql);
    $stm->bindParam(1,$street);
    $stm->bindParam(2,$city);
    $stm->bindParam(3,$zipcode);
    $stm->bindParam(4,$country);
    $stm->bindParam(5,$iduser);
    $stm->execute();
    if ($stm->rowCount()>0) {
        header("Location: cart");
        exit();
    }else{
        $error = "Error al crear la dirección";
    }
}
?>
<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="assets/css/index.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Add Address</title>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">Mi Tienda</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Productos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Contacto</a>
                    </li>

                </ul>
                <span id="user"><?php if (isset($usernamec)) echo "Bienvenido " . $usernamec; ?></span>
            </div>
        </div>
    </nav>

    <div class="container contenedor-productos row">
        
        <h3>New Address</h3>

        <form action="" method="post" class="add_address">
            <input class="form-control" type="text" name="street" placeholder="Street">
            <input class="form-control" type="text" name="city" placeholder="City">
            <input class="form-control" type="text" name="zipcode" placeholder="Zipcode">
            <input class="form-control" type="text" name="country" placeholder="Country">
            <input class="btn btn-success" type="submit" value="New Address">
            <?php
            if (isset($error)){echo "<p>".$error."</p>";}
            ?>
        </form>


    </div>
    
    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

    <!-- Option 2: Separate Popper and Bootstrap JS -->
    <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
    -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="assets/js/product.js"></script>
</body>

</html>