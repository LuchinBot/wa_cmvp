if (typeof _path_controller_venta === "undefined")
    _path_controller_venta = "ventas";
console.log(_path_controller_venta);

/**
 * Botones
 */
$("#btn-new[data-controller='" + _path_controller_venta + "']").on(
    "click",
    function (e) {
        e.preventDefault();
        form.get(_path_controller_venta).nuevo();
        console.log(form.get(_path_controller_venta));
    }
);

$("#btn-edit[data-controller='" + _path_controller_venta + "']").on(
    "click",
    function (e) {
        e.preventDefault();
        var id = grilla.get_id(_name_tabla_venta);

        if (id != null) {
            form.get(_path_controller_venta).editar(id);
        } else {
            ventana.alert({
                titulo: "Ups..!",
                mensaje: "Seleccione un registro",
                tipo: "warning",
            });
        }
    }
);

$("#btn-delete[data-controller='" + _path_controller_venta + "']").on(
    "click",
    function (e) {
        e.preventDefault();
        var id = grilla.get_id(_name_tabla_venta);
        if (id != null) {
            form.get(_path_controller_venta).eliminar(id);
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
        $("#btn-new[data-controller='" + _path_controller_venta + "']").trigger(
            "click"
        );
    }
});
