<?php
if (isset($_POST["email"])) {
    try {
        require_once 'conexion.php';
        $email = $_POST["email"];
        $password = $_POST["password"];
        $sql = "select * from user where email = ? and password = ?";
        $stm = $conn->prepare($sql);
        $stm->bindparam(1, $email);
        $stm->bindparam(2, $password);
        $stm->execute();
        if ($stm->rowCount() > 0) {
            $result = $stm->fetch(PDO::FETCH_ASSOC);
            session_start();
            $_SESSION["username"] = $result["username"];
            header("Location: ./");
            exit();
        } else {
            $error = "Usuario o contraseña no encontrados";
        }
    } catch (PDOException $e) {
        $error = "error interno ".$e->getMessage();
    }
}
?>
<?php
if (isset($error)) {
    echo $error;
}
?>
