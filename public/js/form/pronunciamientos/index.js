if (typeof _path_controller_pronunciamiento === 'undefined')
    _path_controller_pronunciamiento = "pronunciamientos";

/**
 * Botones
*/
$("#btn-new[data-controller='"+_path_controller_pronunciamiento+"']").on("click", function(e) {
    e.preventDefault();
    form.get(_path_controller_pronunciamiento).nuevo();
});

$("#btn-edit[data-controller='"+_path_controller_pronunciamiento+"']").on("click", function(e) {
    e.preventDefault();
    var id = grilla.get_id(_name_tabla_pronunciamiento);

    if(id != null) {
        form.get(_path_controller_pronunciamiento).editar(id);
    }else{
        ventana.alert({titulo: "Ups..!", mensaje: "Seleccione un registro", tipo:"warning"});
    }
});

$("#btn-delete[data-controller='"+_path_controller_pronunciamiento+"']").on("click", function(e) {
    e.preventDefault();
    var id = grilla.get_id(_name_tabla_pronunciamiento);
    if(id != null) {
        form.get(_path_controller_pronunciamiento).eliminar(id);
    }else{
        ventana.alert({titulo: "Ups..!", mensaje: "Seleccione un registro", tipo:"warning"});
    }
});

