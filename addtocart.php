<?php
include_once "./models/product.php";
session_start();

if (isset($_GET["idproduct"])) {
    $idproduct = $_GET["idproduct"];
    $quantity = isset($_GET["quantity"]) && $_GET["quantity"] !== '' ? max(1, intval($_GET["quantity"])) : 1;
    //Comprobamos si el usuario se ha logeado
    if (isset($_SESSION["username"])) {
        include 'conexion.php';
        $iduser = $_SESSION["iduser"];
        $price = $_GET["price"];
        //Guardamos en bbdd
        if (isset($_GET["idcart"])) {
            $idcart = $_GET["idcart"];
        } else {
            $sql_cart = "insert into cart (iduser) value (?)";
            $stm_cart = $conn->prepare($sql_cart);
            $stm_cart->bindParam(1, $iduser);
            $stm_cart->execute();
            $idcart = $conn->lastInsertId();
            $_SESSION["idcart"] = $idcart;
        }
        $sql = "insert into cart_detail (idcart, idproduct, quantity, price) values (?, ?, ?, ?)";
        $stm = $conn->prepare($sql);
        $stm->bindParam(1, $idcart);
        $stm->bindParam(2, $idproduct);
        $stm->bindParam(3, $quantity);
        $stm->bindParam(4, $price);
        $stm->execute();
    } else {
        //Guardamos el carrito en session
        $product = new Product($idproduct, $quantity);
        if (isset($_SESSION["cart"])) {
            $cart = $_SESSION["cart"];
        } else {
            $cart = array();
        }
        array_push($cart, $product);
        $_SESSION["cart"] = $cart;
    }
}

header("Location: ./");
exit();
