if (typeof _path_controller_actividad_institucional === 'undefined')
    _path_controller_actividad_institucional = "actividad_institucional";

/**
 * Botones
*/
$("#btn-new[data-controller='"+_path_controller_actividad_institucional+"']").on("click", function(e) {
    e.preventDefault();
    form.get(_path_controller_actividad_institucional).nuevo();
});

$("#btn-edit[data-controller='"+_path_controller_actividad_institucional+"']").on("click", function(e) {
    e.preventDefault();
    var id = grilla.get_id(_name_tabla_actividad_institucional);

    if(id != null) {
        form.get(_path_controller_actividad_institucional).editar(id);
    }else{
        ventana.alert({titulo: "Ups..!", mensaje: "Seleccione un registro", tipo:"warning"});
    }
});

$("#btn-delete[data-controller='"+_path_controller_actividad_institucional+"']").on("click", function(e) {
    e.preventDefault();
    var id = grilla.get_id(_name_tabla_actividad_institucional);
    if(id != null) {
        form.get(_path_controller_actividad_institucional).eliminar(id);
    }else{
        ventana.alert({titulo: "Ups..!", mensaje: "Seleccione un registro", tipo:"warning"});
    }
});

$(document).on("keydown", function(e) {
    if(e.keyCode==112){
        //F1
        e.preventDefault();
	    e.stopPropagation();
        $("#btn-new[data-controller='"+_path_controller_actividad_institucional+"']").trigger("click");
    }
});

