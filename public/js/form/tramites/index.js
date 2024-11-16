if (typeof _path_controller_tramite === 'undefined')
    _path_controller_tramite = "tramites";

/**
 * Botones
*/
$("#btn-new[data-controller='"+_path_controller_tramite+"']").on("click", function(e) {
    e.preventDefault();
    form.get(_path_controller_tramite).nuevo();
});

$("#btn-edit[data-controller='"+_path_controller_tramite+"']").on("click", function(e) {
    e.preventDefault();
    var id = grilla.get_id(_name_tabla_tramite);

    if(id != null) {
        form.get(_path_controller_tramite).editar(id);
    }else{
        ventana.alert({titulo: "Ups..!", mensaje: "Seleccione un registro", tipo:"warning"});
    }
});

$("#btn-delete[data-controller='"+_path_controller_tramite+"']").on("click", function(e) {
    e.preventDefault();
    var id = grilla.get_id(_name_tabla_tramite);
    if(id != null) {
        form.get(_path_controller_tramite).eliminar(id);
    }else{
        ventana.alert({titulo: "Ups..!", mensaje: "Seleccione un registro", tipo:"warning"});
    }
});

