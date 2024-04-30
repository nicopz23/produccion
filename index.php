<?php
include_once "./models/product.php";
require_once 'conexion.php';
session_start();
$sql = 'select * from product';
$consulta = $conn->prepare($sql);
$consulta->execute();
$resultados = $consulta->fetchAll(PDO::FETCH_ASSOC);

//Compruebo si hay carrito
if (isset($_SESSION['cart'])) {
    $cart = $_SESSION["cart"];
}

if (isset($_SESSION['username'])) {
    //comprobar si hay carrito en la bd
    $usernamec = $_SESSION['username'];
    $iduser = $_SESSION["iduser"];
}
if (!isset($cart)) {
    $sql = "select * from cart_detail where idcart=(select idcart from cart where iduser=? order by date desc limit 1)";
    $consulta = $conn->prepare($sql);
    $consulta->bindParam(1, $iduser);
    $consulta->execute();
    $resultados = $consulta->fetchAll(PDO::FETCH_ASSOC);
    $cart = array();
    foreach ($resultados as $key => $p) {
        $product = new Product($p["idproduct"], $p["quantity"]);
        array_push($cart, $product);
    }
    $_SESSION["cart"] = $cart;
    $_SESSION["idcart"] = $resultados["idcart"];
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
    <title>App Pedidos</title>
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
        <div class="shop-cart" id="cart">
            <a class="nav-link" href="cart"><span><i class="fas fa-shopping-cart"></i><?php echo isset($cart) ? count($cart) : ''; ?> </span></a>
        </div>
        <h3>Productos</h3>

        <?php
        foreach ($resultados as $product) {
            echo '<div class="card productcard col-sm-12 col-md-3" style="width: 18rem;">
        <img class="card-img-top" src="assets/product/' . $product["image"] . '" alt="Card image cap">
        <div class="card-body">
            <div class="producto-detalle">
                <div>
                    <h5 class="card-title">' . $product["name"] . '</h5>
                    <p class="card-text">' . $product["description"] . '</p>
                </div>
                <div>
                    <h5 class="card-title">' . $product["price"] . ' €/kg</h5>
                </div>
            </div>
            <form action="addtocart.php" method="get">
                <div class="add-to-cart">
                    <input type="hidden" name="idcart" value = "' . $idcart . '">
                    <input type="hidden" name="price" value="' . $product["price"] . '">
                    <input type="hidden" name="idproduct" value = "' . $product['idproduct'] . '">
                    <input class="form-control" type ="number" name="quantity" min = 1 step=1 >
                    <button class="btn btn-primary"><i class="fa-solid fa-cart-plus"></i></button>
                </div>
            </form>
            </div>
        </div>';
        }
        ?>
    </div>
    <div id="modal-login" class="modal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Login</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="login.php" method="post">
                    <div class="modal-body">
                        <h5>Para seguir a tu compra tienes que iniciar sesión</h5>
                        <input class="form-control email" type="email" name="email" id="email" placeholder="Email" required>
                        <input class="form-control" type="password" name="password" id="password" placeholder="Password" required>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Login</button>
                        </div>
                </form>
            </div>
        </div>
    </div>
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