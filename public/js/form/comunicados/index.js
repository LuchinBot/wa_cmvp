if (typeof _path_controller_comunicado === 'undefined')
    _path_controller_comunicado = "comunicados";

/**
 * Botones
*/
$("#btn-new[data-controller='"+_path_controller_comunicado+"']").on("click", function(e) {
    e.preventDefault();
    form.get(_path_controller_comunicado).nuevo();
});

$("#btn-edit[data-controller='"+_path_controller_comunicado+"']").on("click", function(e) {
    e.preventDefault();
    var id = grilla.get_id(_name_tabla_comunicado);

    if(id != null) {
        form.get(_path_controller_comunicado).editar(id);
    }else{
        ventana.alert({titulo: "Ups..!", mensaje: "Seleccione un registro", tipo:"warning"});
    }
});

$("#btn-delete[data-controller='"+_path_controller_comunicado+"']").on("click", function(e) {
    e.preventDefault();
    var id = grilla.get_id(_name_tabla_comunicado);
    if(id != null) {
        form.get(_path_controller_comunicado).eliminar(id);
    }else{
        ventana.alert({titulo: "Ups..!", mensaje: "Seleccione un registro", tipo:"warning"});
    }
});

