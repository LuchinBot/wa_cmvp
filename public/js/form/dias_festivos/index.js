if (typeof _path_controller_dias_festivos === 'undefined')
    _path_controller_dias_festivos = "dias_festivos";

/**
 * Botones
*/
$("#btn-new[data-controller='"+_path_controller_dias_festivos+"']").on("click", function(e) {
    e.preventDefault();
    form.get(_path_controller_dias_festivos).nuevo();
});

$("#btn-edit[data-controller='"+_path_controller_dias_festivos+"']").on("click", function(e) {
    e.preventDefault();
    var id = grilla.get_id(_name_tabla_dias_festivos);

    if(id != null) {
        form.get(_path_controller_dias_festivos).editar(id);
    }else{
        ventana.alert({titulo: "Ups..!", mensaje: "Seleccione un registro", tipo:"warning"});
    }
});

$("#btn-delete[data-controller='"+_path_controller_dias_festivos+"']").on("click", function(e) {
    e.preventDefault();
    var id = grilla.get_id(_name_tabla_dias_festivos);
    if(id != null) {
        form.get(_path_controller_dias_festivos).eliminar(id);
    }else{
        ventana.alert({titulo: "Ups..!", mensaje: "Seleccione un registro", tipo:"warning"});
    }
});

