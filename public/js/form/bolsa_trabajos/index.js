if (typeof _path_controller_bolsa_trabajo === 'undefined')
    _path_controller_bolsa_trabajo = "bolsa_trabajos";

/**
 * Botones
*/
$("#btn-new[data-controller='"+_path_controller_bolsa_trabajo+"']").on("click", function(e) {
    e.preventDefault();
    form.get(_path_controller_bolsa_trabajo).nuevo();
});

$("#btn-edit[data-controller='"+_path_controller_bolsa_trabajo+"']").on("click", function(e) {
    e.preventDefault();
    var id = grilla.get_id(_name_tabla_bolsa_trabajo);

    if(id != null) {
        form.get(_path_controller_bolsa_trabajo).editar(id);
    }else{
        ventana.alert({titulo: "Ups..!", mensaje: "Seleccione un registro", tipo:"warning"});
    }
});

$("#btn-delete[data-controller='"+_path_controller_bolsa_trabajo+"']").on("click", function(e) {
    e.preventDefault();
    var id = grilla.get_id(_name_tabla_bolsa_trabajo);
    if(id != null) {
        form.get(_path_controller_bolsa_trabajo).eliminar(id);
    }else{
        ventana.alert({titulo: "Ups..!", mensaje: "Seleccione un registro", tipo:"warning"});
    }
});

$(document).on("keydown", function(e) {
    if(e.keyCode==112){
        //F1
        e.preventDefault();
	    e.stopPropagation();
        $("#btn-new[data-controller='"+_path_controller_bolsa_trabajo+"']").trigger("click");
    }
});

