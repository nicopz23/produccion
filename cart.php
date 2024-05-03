<?php
include_once("./models/product.php");
session_start();
if (isset($_SESSION["username"])) {
    if (isset($_SESSION["cart"])) {
        //si existe session de carrito y usuario puede entrar al carrito
        $cart = $_SESSION["cart"];
        $iduser = $_SESSION["iduser"];
        $usernamec = $_SESSION["username"];
        //consultamos las direcciones
        require_once 'conexion.php';
        $sql = "select * from address where iduser=" . $iduser;
        $stm = $conn->prepare($sql);
        $stm->execute();
        //guardamos las direcciones en la variable para luego utilizarlas en el form
        $address = $stm->fetchAll(PDO::FETCH_ASSOC);
        foreach ($cart as $product) {
            $sql = "select * from product where idproduct = ?";
            $stm = $conn->prepare($sql);
            $stm->bindParam(1, $product->idproduct);
            $stm->execute();
            if ($stm->rowCount() > 0) {
                $result = $stm->fetch(PDO::FETCH_ASSOC);
                $product->name = $result["name"];
                $product->description = $result["description"];
                $product->price = $result["price"];
                $product->image = $result["image"];
            }
        }
        if (isset($_SESSION["idcart"])) {
            $idcart = $_SESSION["idcart"];
        } else {
            $sql_cart = "insert into cart (iduser) value (?)";
            $stm_cart = $conn->prepare($sql_cart);
            $stm_cart->bindParam(1, $iduser);
            $stm_cart->execute();
            $idcart = $conn->lastInsertId();
            $_SESSION["idcart"] = $idcart;
        }
        $sql_delete = "delete from cart_detail where idcart=" . $idcart;
        $conn->exec($sql_delete);
        foreach ($cart as $key => $product) {
            $sql = "insert into cart_detail (idcart, idproduct, quantity, price) values (?,?,?,?)";
            $stm = $conn->prepare($sql);
            $stm->bindParam(1, $idcart);
            $stm->bindParam(2, $product->idproduct);
            $stm->bindParam(3, $product->quantity);
            $stm->bindParam(4, $product->price);
            $stm->execute();
            $idcartdetail = $conn->lastInsertId();
            $product->idcartdetail = $idcartdetail;
        }
    } else {
        header("Location: ./");
        exit();
    }
} else {
    //si no tiene session redirije a index
    header("Location: ./");
    exit();
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
    <title>Mi Carrito</title>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="./">Mi Tienda</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="./">Inicio</a>
                    </li>
                </ul>
                <span id="user"><?php if (isset($usernamec)) echo "Bienvenido " . $usernamec; ?></span>
            </div>
        </div>
    </nav>

    <div class="container contenedor-productos row">
        <div class="shop-cart" id="cart">
            <a class="nav-link" href="cart"><span><i class="fas fa-shopping-cart"></i><span id="products_count"><?php echo isset($cart) ? count($cart) : ''; ?></span> </span></a>
        </div>
        <h3>Carrito</h3>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col"></th>
                        <th scope="col"></th>
                        <th scope="col">Product</th>
                        <th scope="col">Quantity</th>
                        <th scope="col">Price</th>
                        <th scope="col">Total</th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $total = 0;
                    foreach ($cart as $key => $product) {
                        $total += floatval($product->price) * floatval($product->quantity);
                        echo '<tr id="idcartdetail' . $product->idcartdetail . '">
                        <th scope="row">' . $key . '</th>
                        <td><img class="img-cart" src="assets/product/' . $product->image . '" alt=""></td>
                        <td>
                            <h6>' . $product->name . '</h6>
                            <p>' . $product->description . '</p>
                        </td>
                        <td><input class="quantity" type="number" value="' . $product->quantity . '"></td>
                        <td>' . $product->price . ' €/k</td>
                        <td>' . floatval($product->price) * floatval($product->quantity) . ' €/k</td>
                        <td><span class="delete" ><i class="fa-solid fa-circle-xmark" style="color: red;"></i></span> </td>
                    </tr>';
                    }
                    echo "<tr><td class='importe_total'  colspan='5'>Total:</td><td class='euros_total' id='euros_total' colspan='2'>" . $total . " €</td></tr>"
                    ?>
                </tbody>
            </table>
        </div>
        <button class="btn btn-success" id="btnConfirm" type="button">Order Confirm</button>
        <div class="datos_envio">

            <form action="add_order" method="post">
                <span>Delivery date: </span><input type="date" name="date" required>
                <hr>
                <span>Delivery Address </span>
                <div class="address row">
                    <?php
                    foreach ($address as $key => $dir) {
                        echo ' <div class="col-md-3 col-sm-12">
                    <input type="radio" name="idaddress" value="' . $dir["idaddress"] . '" required>
                    <h5>' . $dir["street"] . '</h5>
                    <p><span>' . $dir["zipcode"] . '</span>-<span>' . $dir["city"] . '</span></p>
                    <p>' . $dir["country"] . '</p>
                </div>';
                    }
                    ?>
                </div>
                <div>
                    <a href="add_address"><i class="fa-solid fa-map-location-dot"></i>
                        <i class="fa-solid fa-plus"></i></a>
                </div>
                <input type="submit" value="Create Order" class="btn btn-success">
            </form>
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
    <script src="./assets/js/product.js"></script>
</body>

</html>