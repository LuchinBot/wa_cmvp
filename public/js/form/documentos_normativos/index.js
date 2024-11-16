if (typeof _path_controller_documento_normativo === 'undefined')
    _path_controller_documento_normativo = "documentos_normativos";

/**
 * Botones
*/
$("#btn-new[data-controller='"+_path_controller_documento_normativo+"']").on("click", function(e) {
    e.preventDefault();
    form.get(_path_controller_documento_normativo).nuevo();
});

$("#btn-edit[data-controller='"+_path_controller_documento_normativo+"']").on("click", function(e) {
    e.preventDefault();
    var id = grilla.get_id(_name_tabla_documento_normativo);

    if(id != null) {
        form.get(_path_controller_documento_normativo).editar(id);
    }else{
        ventana.alert({titulo: "Ups..!", mensaje: "Seleccione un registro", tipo:"warning"});
    }
});

$("#btn-delete[data-controller='"+_path_controller_documento_normativo+"']").on("click", function(e) {
    e.preventDefault();
    var id = grilla.get_id(_name_tabla_documento_normativo);
    if(id != null) {
        form.get(_path_controller_documento_normativo).eliminar(id);
    }else{
        ventana.alert({titulo: "Ups..!", mensaje: "Seleccione un registro", tipo:"warning"});
    }
});

$(document).on("keydown", function(e) {
    if(e.keyCode==112){
        //F1
        e.preventDefault();
	    e.stopPropagation();
        $("#btn-new[data-controller='"+_path_controller_documento_normativo+"']").trigger("click");
    }
});

