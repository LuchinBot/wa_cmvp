if (typeof _path_controller_evento === 'undefined')
    _path_controller_evento = "eventos";

/**
 * Botones
*/
$("#btn-new[data-controller='"+_path_controller_evento+"']").on("click", function(e) {
    e.preventDefault();
    form.get(_path_controller_evento).nuevo();
});

$("#btn-edit[data-controller='"+_path_controller_evento+"']").on("click", function(e) {
    e.preventDefault();
    var id = grilla.get_id(_name_tabla_evento);

    if(id != null) {
        form.get(_path_controller_evento).editar(id);
    }else{
        ventana.alert({titulo: "Ups..!", mensaje: "Seleccione un registro", tipo:"warning"});
    }
});

$("#btn-delete[data-controller='"+_path_controller_evento+"']").on("click", function(e) {
    e.preventDefault();
    var id = grilla.get_id(_name_tabla_evento);
    if(id != null) {
        form.get(_path_controller_evento).eliminar(id);
    }else{
        ventana.alert({titulo: "Ups..!", mensaje: "Seleccione un registro", tipo:"warning"});
    }
});

$(document).on("keydown", function(e) {
    if(e.keyCode==112){
        //F1
        e.preventDefault();
	    e.stopPropagation();
        $("#btn-new[data-controller='"+_path_controller_evento+"']").trigger("click");
    }
});

