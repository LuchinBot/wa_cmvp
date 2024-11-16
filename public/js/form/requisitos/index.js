if (typeof _path_controller_requisito === 'undefined')
    _path_controller_requisito = "requisitos";

/**
 * Botones
*/
$("#btn-new[data-controller='"+_path_controller_requisito+"']").on("click", function(e) {
    e.preventDefault();
    form.get(_path_controller_requisito).nuevo();
});

$("#btn-edit[data-controller='"+_path_controller_requisito+"']").on("click", function(e) {
    e.preventDefault();
    var id = grilla.get_id(_name_tabla_requisito);

    if(id != null) {
        form.get(_path_controller_requisito).editar(id);
    }else{
        ventana.alert({titulo: "Ups..!", mensaje: "Seleccione un registro", tipo:"warning"});
    }
});

$("#btn-delete[data-controller='"+_path_controller_requisito+"']").on("click", function(e) {
    e.preventDefault();
    var id = grilla.get_id(_name_tabla_requisito);
    if(id != null) {
        form.get(_path_controller_requisito).eliminar(id);
    }else{
        ventana.alert({titulo: "Ups..!", mensaje: "Seleccione un registro", tipo:"warning"});
    }
});

$(document).on("keydown", function(e) {
    if(e.keyCode==112){
        //F1
        e.preventDefault();
	    e.stopPropagation();
        $("#btn-new[data-controller='"+_path_controller_requisito+"']").trigger("click");
    }
});

