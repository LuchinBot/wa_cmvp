if (typeof _path_controller_perfil === 'undefined')
    _path_controller_perfil = "perfiles";

/**
 * Botones
*/
$("#btn-new[data-controller='"+_path_controller_perfil+"']").on("click", function(e) {
    e.preventDefault();
    form.get(_path_controller_perfil).nuevo();
});

$("#btn-edit[data-controller='"+_path_controller_perfil+"']").on("click", function(e) {
    e.preventDefault();
    var id = grilla.get_id(_name_tabla_perfil);

    if(id != null) {
        form.get(_path_controller_perfil).editar(id);
    }else{
        ventana.alert({titulo: "Ups..!", mensaje: "Seleccione un registro", tipo:"warning"});
    }
});

$("#btn-delete[data-controller='"+_path_controller_perfil+"']").on("click", function(e) {
    e.preventDefault();
    var id = grilla.get_id(_name_tabla_perfil);
    if(id != null) {
        form.get(_path_controller_perfil).eliminar(id);
    }else{
        ventana.alert({titulo: "Ups..!", mensaje: "Seleccione un registro", tipo:"warning"});
    }
});

$(document).on("keydown", function(e) {
    if(e.keyCode==112){
        //F1
        e.preventDefault();
	    e.stopPropagation();
        $("#btn-new[data-controller='"+_path_controller_perfil+"']").trigger("click");
    }
});

