if (typeof _path_controller_junta_directiva === 'undefined')
    _path_controller_junta_directiva = "juntas_directivas";

/**
 * Botones
*/
$("#btn-new[data-controller='"+_path_controller_junta_directiva+"']").on("click", function(e) {
    e.preventDefault();
    form.get(_path_controller_junta_directiva).nuevo();
});

$("#btn-edit[data-controller='"+_path_controller_junta_directiva+"']").on("click", function(e) {
    e.preventDefault();
    var id = grilla.get_id(_name_tabla_junta_directiva);

    if(id != null) {
        form.get(_path_controller_junta_directiva).editar(id);
    }else{
        ventana.alert({titulo: "Ups..!", mensaje: "Seleccione un registro", tipo:"warning"});
    }
});

$("#btn-delete[data-controller='"+_path_controller_junta_directiva+"']").on("click", function(e) {
    e.preventDefault();
    var id = grilla.get_id(_name_tabla_junta_directiva);
    if(id != null) {
        form.get(_path_controller_junta_directiva).eliminar(id);
    }else{
        ventana.alert({titulo: "Ups..!", mensaje: "Seleccione un registro", tipo:"warning"});
    }
});

$(document).on("keydown", function(e) {
    if(e.keyCode==112){
        //F1
        e.preventDefault();
	    e.stopPropagation();
        $("#btn-new[data-controller='"+_path_controller_junta_directiva+"']").trigger("click");
    }
});

