if (typeof _path_controller_colegiado === 'undefined')
    _path_controller_colegiado = "colegiados";

/**
 * Botones
*/
$("#btn-new[data-controller='"+_path_controller_colegiado+"']").on("click", function(e) {
    e.preventDefault();
    form.get(_path_controller_colegiado).nuevo();
});

$("#btn-edit[data-controller='"+_path_controller_colegiado+"']").on("click", function(e) {
    e.preventDefault();
    var id = grilla.get_id(_name_tabla_colegiado);

    if(id != null) {
        form.get(_path_controller_colegiado).editar(id);
    }else{
        ventana.alert({titulo: "Ups..!", mensaje: "Seleccione un registro", tipo:"warning"});
    }
});

$("#btn-delete[data-controller='"+_path_controller_colegiado+"']").on("click", function(e) {
    e.preventDefault();
    var id = grilla.get_id(_name_tabla_colegiado);
    if(id != null) {
        form.get(_path_controller_colegiado).eliminar(id);
    }else{
        ventana.alert({titulo: "Ups..!", mensaje: "Seleccione un registro", tipo:"warning"});
    }
});

$(document).on("keydown", function(e) {
    if(e.keyCode==112){
        //F1
        e.preventDefault();
	    e.stopPropagation();
        $("#btn-new[data-controller='"+_path_controller_colegiado+"']").trigger("click");
    }
});

