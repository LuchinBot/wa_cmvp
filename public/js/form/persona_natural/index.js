if (typeof _path_controller_persona_natural === 'undefined')
    _path_controller_persona_natural = "persona_natural";

/**
 * Botones
*/
$("#btn-new[data-controller='"+_path_controller_persona_natural+"']").on("click", function(e) {
    e.preventDefault();
    form.get(_path_controller_persona_natural).nuevo();
});

$("#btn-edit[data-controller='"+_path_controller_persona_natural+"']").on("click", function(e) {
    e.preventDefault();
    var id = grilla.get_id(_name_tabla_persona_natural);

    if(id != null) {
        form.get(_path_controller_persona_natural).editar(id);
    }else{
        ventana.alert({titulo: "Ups..!", mensaje: "Seleccione un registro", tipo:"warning"});
    }
});

$("#btn-delete[data-controller='"+_path_controller_persona_natural+"']").on("click", function(e) {
    e.preventDefault();
    var id = grilla.get_id(_name_tabla_persona_natural);
    if(id != null) {
        form.get(_path_controller_persona_natural).eliminar(id);
    }else{
        ventana.alert({titulo: "Ups..!", mensaje: "Seleccione un registro", tipo:"warning"});
    }
});

$("#btn-sync[data-controller='"+_path_controller_persona_natural+"']").on("click", function(e) {
    e.preventDefault();
    var id = grilla.get_id(_name_tabla_persona_natural);
    if(id != null) {
        form.get(_path_controller_persona_natural).sincronizar(id);
    }else{
        ventana.alert({titulo: "Ups..!", mensaje: "Seleccione un registro", tipo:"warning"});
    }
});
