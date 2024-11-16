if (typeof _path_controller_empresa === 'undefined')
    _path_controller_empresa = "empresas";

/**
 * Botones
*/
$("#btn-new[data-controller='"+_path_controller_empresa+"']").on("click", function(e) {
    e.preventDefault();
    form.get(_path_controller_empresa).nuevo();
});

$("#btn-edit[data-controller='"+_path_controller_empresa+"']").on("click", function(e) {
    e.preventDefault();
    var id = grilla.get_id(_name_tabla_empresa);

    if(id != null) {
        form.get(_path_controller_empresa).editar(id);
    }else{
        ventana.alert({titulo: "Ups..!", mensaje: "Seleccione un registro", tipo:"warning"});
    }
});

$("#btn-delete[data-controller='"+_path_controller_empresa+"']").on("click", function(e) {
    e.preventDefault();
    var id = grilla.get_id(_name_tabla_empresa);
    if(id != null) {
        form.get(_path_controller_empresa).eliminar(id);
    }else{
        ventana.alert({titulo: "Ups..!", mensaje: "Seleccione un registro", tipo:"warning"});
    }
});

$(document).on("keydown", function(e) {
    if(e.keyCode==112){
        //F1
        e.preventDefault();
	    e.stopPropagation();
        $("#btn-new[data-controller='"+_path_controller_empresa+"']").trigger("click");
    }
});

