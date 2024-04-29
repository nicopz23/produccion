$("#cart").click((e)=>{
    if($("#user").html()==""){
        e.preventDefault();
        $("#modal-login").modal("show");
    } else{
        
    }
});