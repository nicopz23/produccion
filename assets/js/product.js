$("#cart").click((e)=>{
    if($("#user").html()==""){
        e.preventDefault();
        $("#modal-login").modal("show");
    } else{
        
    }
});

$(".quantity").change((e)=>{
    let quantity = e.currentTarget.value;

});

$(".delete").click((e)=>{
    let idcartdetail= (e.currentTarget.id).replace("idcartdetail","");
    let url = "delete_cartdetail.php?idcartdetail="+idcartdetail;
    fetch(url)
    .then(response => response.json())
    .then(data =>{
        console.log(data);
    })
    .catch(error=>{
        console.error('Error:',error);
    });
})