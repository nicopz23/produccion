<?php
$idcartdetail = $_GET["idcartdetail"];
include 'conexion.php';
$sql = "delete from cart_detail where idcartdetail=?";
$stm = $conn->prepare($sql);
$stm->bindParam(1,$idcartdetail);
$stm->execute();

$response = array(
    'param1' => $idcartdetail,
    'mensaje' => 'solicitud recibida'
);

header('Content-Type: apliccation/json');

echo json_encode($response);