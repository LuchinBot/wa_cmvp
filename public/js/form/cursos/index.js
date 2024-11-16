if (typeof _path_controller_curso === 'undefined')
    _path_controller_curso = "cursos";

/**
 * Botones
*/
$("#btn-new[data-controller='"+_path_controller_curso+"']").on("click", function(e) {
    e.preventDefault();
    form.get(_path_controller_curso).nuevo();
});

$("#btn-edit[data-controller='"+_path_controller_curso+"']").on("click", function(e) {
    e.preventDefault();
    var id = grilla.get_id(_name_tabla_curso);

    if(id != null) {
        form.get(_path_controller_curso).editar(id);
    }else{
        ventana.alert({titulo: "Ups..!", mensaje: "Seleccione un registro", tipo:"warning"});
    }
});

$("#btn-delete[data-controller='"+_path_controller_curso+"']").on("click", function(e) {
    e.preventDefault();
    var id = grilla.get_id(_name_tabla_curso);
    if(id != null) {
        form.get(_path_controller_curso).eliminar(id);
    }else{
        ventana.alert({titulo: "Ups..!", mensaje: "Seleccione un registro", tipo:"warning"});
    }
});

