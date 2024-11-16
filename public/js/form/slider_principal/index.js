if (typeof _path_controller_slider_principal === 'undefined')
    _path_controller_slider_principal = "slider_principal";

/**
 * Botones
*/
$("#btn-new[data-controller='"+_path_controller_slider_principal+"']").on("click", function(e) {
    e.preventDefault();
    form.get(_path_controller_slider_principal).nuevo();
});

$("#btn-edit[data-controller='"+_path_controller_slider_principal+"']").on("click", function(e) {
    e.preventDefault();
    var id = grilla.get_id(_name_tabla_slider_principal);

    if(id != null) {
        form.get(_path_controller_slider_principal).editar(id);
    }else{
        ventana.alert({titulo: "Ups..!", mensaje: "Seleccione un registro", tipo:"warning"});
    }
});

$("#btn-delete[data-controller='"+_path_controller_slider_principal+"']").on("click", function(e) {
    e.preventDefault();
    var id = grilla.get_id(_name_tabla_slider_principal);
    if(id != null) {
        form.get(_path_controller_slider_principal).eliminar(id);
    }else{
        ventana.alert({titulo: "Ups..!", mensaje: "Seleccione un registro", tipo:"warning"});
    }
});

