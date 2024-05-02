$("#cart").click(function (e) {

    if ($("#user").html() == "") {
        e.preventDefault();
        //Hay que logearse
        $("#modal-login").modal("show");
    }
}

);

$(".quantity").change((e) => {
    let quantity = e.currentTarget.value;
    let idcartdetail = (e.currentTarget.parentElement.parentElement.id).replace("idcartdetail", "");
    let url = "update_cartdetail.php?idcartdetail=" + idcartdetail+"&quantity="+quantity;
    fetch(url)
        .then(response => response.json())
        .then(data => {
            // Aquí recibes los datos en formato JSON
            // Puedes manipular los datos aquí
            let cart = data.cart;
            var totalesPorProducto = cart.map(function (cart) {
                return cart.price * cart.quantity;
            });

            // Usamos reduce() para sumar los totales de todos los productos
            var totalCart = totalesPorProducto.reduce(function (total, subtotal) {
                return total + subtotal;
            }, 0);

            // Convertimos el total a euros
            var totalEuros = totalCart / 100; // Dividimos por 100 si el precio está en centavos

            // Formateamos el total con dos dígitos después del punto decimal
            var totalFormateado = totalEuros.toFixed(2);

            $("#euros_total").text(totalFormateado + " €")
            
        })
        .catch(error => {
            console.error('Error:', error);
        });

});

$(".delete").click((e) => {
    let fila = e.currentTarget.parentElement.parentElement;
    let idcartdetail = (e.currentTarget.parentElement.parentElement.id).replace("idcartdetail", "");
    let url = "delete_cartdetail.php?idcartdetail=" + idcartdetail;
    fetch(url)
        .then(response => response.json())
        .then(data => {
            // Aquí recibes los datos en formato JSON
            // Puedes manipular los datos aquí
            fila.remove();
            let cart = data.cart;
            var totalesPorProducto = cart.map(function (cart) {
                return cart.price * cart.quantity;
            });

            // Usamos reduce() para sumar los totales de todos los productos
            var totalCart = totalesPorProducto.reduce(function (total, subtotal) {
                return total + subtotal;
            }, 0);

            // Convertimos el total a euros
            var totalEuros = totalCart / 100; // Dividimos por 100 si el precio está en centavos

            // Formateamos el total con dos dígitos después del punto decimal
            var totalFormateado = totalEuros.toFixed(2);

            $("#euros_total").text(totalFormateado + " €")
            $("#products_count").html(cart.length);
        })
        .catch(error => {
            console.error('Error:', error);
        });
})

$("#btnConfirm").click(() =>{
    console.log("oe");
    $(".datos_envio").toggle();
});