if (typeof _path_controller_tipo_curso === 'undefined')
    _path_controller_tipo_curso = "tipo_cursos";

/**
 * Botones
*/
$("#btn-new[data-controller='"+_path_controller_tipo_curso+"']").on("click", function(e) {
    e.preventDefault();
    form.get(_path_controller_tipo_curso).nuevo();
});

$("#btn-edit[data-controller='"+_path_controller_tipo_curso+"']").on("click", function(e) {
    e.preventDefault();
    var id = grilla.get_id(_name_tabla_tipo_curso);

    if(id != null) {
        form.get(_path_controller_tipo_curso).editar(id);
    }else{
        ventana.alert({titulo: "Ups..!", mensaje: "Seleccione un registro", tipo:"warning"});
    }
});

$("#btn-delete[data-controller='"+_path_controller_tipo_curso+"']").on("click", function(e) {
    e.preventDefault();
    var id = grilla.get_id(_name_tabla_tipo_curso);
    if(id != null) {
        form.get(_path_controller_tipo_curso).eliminar(id);
    }else{
        ventana.alert({titulo: "Ups..!", mensaje: "Seleccione un registro", tipo:"warning"});
    }
});

$(document).on("keydown", function(e) {
    if(e.keyCode==112){
        //F1
        e.preventDefault();
	    e.stopPropagation();
        $("#btn-new[data-controller='"+_path_controller_tipo_curso+"']").trigger("click");
    }
});

