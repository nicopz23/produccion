<?php
require_once 'conexion.php';

$sql = 'select * from product';
$consulta = $conn->prepare($sql);
$consulta->execute();
$resultados = $consulta->fetchAll(PDO::FETCH_ASSOC);
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
    <div class="container contenedor-productos row">

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
                    <input type="hidden" name="idproduct" value = "'. $product['idproduct'].'">
                    <input class="form-control" type ="number" min = 1 step=1 >
                    <button class="btn btn-primary"><i class="fa-solid fa-cart-plus"></i></button>
                </div>
            </form>
            </div>
        </div>';
        }
        ?>
    </div>
    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

    <!-- Option 2: Separate Popper and Bootstrap JS -->
    <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
    -->
</body>

</html>