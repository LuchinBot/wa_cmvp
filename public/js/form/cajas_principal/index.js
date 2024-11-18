if (typeof _path_controller_caja_principal === "undefined")
    _path_controller_caja_principal = "cajas_principal";
console.log(_path_controller_caja_principal);

/**
 * Botones
 */
$("#btn-new[data-controller='" + _path_controller_caja_principal + "']").on(
    "click",
    function (e) {
        e.preventDefault();
        form.get(_path_controller_caja_principal).nuevo();
        console.log(form.get(_path_controller_caja_principal));
    }
    
);

$("#btn-edit[data-controller='" + _path_controller_caja_principal + "']").on(
    "click",
    function (e) {
        e.preventDefault();
        var id = grilla.get_id(_name_tabla_caja);

        if (id != null) {
            form.get(_path_controller_caja_principal).editar(id);
        } else {
            ventana.alert({
                titulo: "Ups..!",
                mensaje: "Seleccione un registro",
                tipo: "warning",
            });
        }
    }
);

$("#btn-delete[data-controller='" + _path_controller_caja_principal + "']").on(
    "click",
    function (e) {
        e.preventDefault();
        var id = grilla.get_id(_name_tabla_caja);
        if (id != null) {
            form.get(_path_controller_caja_principal).eliminar(id);
        } else {
            ventana.alert({
                titulo: "Ups..!",
                mensaje: "Seleccione un registro",
                tipo: "warning",
            });
        }
    }
);

$(document).on("keydown", function (e) {
    if (e.keyCode == 112) {
        //F1
        e.preventDefault();
        e.stopPropagation();
        $("#btn-new[data-controller='" + _path_controller_caja_principal + "']").trigger(
            "click"
        );
    }
});
