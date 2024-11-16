let default_tr =
    "<tr><td colspan='3'><center><i>Sin producto</i></center></td></tr>";

dialog.create({
    selector: "#form-" + _path_controller_venta,
    title: "Registrar " + _name_module_venta,
    width: "modal-md",
    style: "primary",
    closeOnEscape: true,
    buttons: {
        Guardar: function () {
            form.get(_path_controller_venta).guardar();
        },
        Cancelar: function () {
            form.get(_path_controller_venta).cancelar();
        },
    },
    close: function () {
        form.get(_path_controller_venta).reset();
    },
});

$(document).on("click", "#btn-agregar-producto" + _prefix_venta, function (e) {
    e.preventDefault();

    let id_producto = $("#idproducto_add" + _prefix_venta).val();
    if ($("#idproducto_add" + _prefix_venta).required()) {
        if (
            $("#table_producto tbody tr[index='" + id_producto + "']").length >
            0
        ) {
            toastr.warning("Ya existe la producto en la lista");
            return;
        }

        let arrReq = [];
        dato = {
            codventa_producto: "",
            idproducto: id_producto,
            producto_venta: $(
                "#idproducto_add" + _prefix_venta + " option:selected"
            ).text(),
        };
        arrReq.push(dato);
        setDetalleVenta(arrReq);
    }
});

$(document).on("click", ".btn-delete-producto", function (e) {
    e.preventDefault();

    var tr = $(this).closest("tr");
    ventana.confirm(
        {
            titulo: "Advertencia",
            mensaje: "Desea borrar la producto?",
            textoBotonAceptar: "Si",
            textoBotonCancelar: "Cancelar",
        },
        function (ok) {
            if (ok.value) {
                tr.remove();
                order_detalle_item();

                if ($("table#table_producto tbody tr[index]").length < 1)
                    $("table#table_producto tbody").html(default_tr);
            }
        }
    );
});

form.register(_path_controller_venta, {
    _prefix: "",
    _form: "#form-" + _path_controller_venta,

    nuevo: function () {
        limpieza_venta(this._form);

        dialog.open(this._form);
    },
    cancelar: function () {
        dialog.close(this._form);
    },
    editar: function (id) {
        var $self = this;
        $.ajax({
            url: route(_path_controller_venta + ".edit", id),
            type: "GET",
            beforeSend: function () {
                loading();
                dialog.open($self._form);
            },
            success: function (response) {
                limpieza_venta($self._form);

                $.each(response, function (k, v) {
                    $("#" + k, $self._form).val(v);
                });

                setDetalleVenta(response.producto_venta);
            },
            complete: function () {
                loading("complete");
            },
            error: function (e) {
                if (e.status == 419) {
                    toastr.error(
                        "La sesión ya expiró, por favor cierre sesión y vuelva a ingresar"
                    );
                } else if (e.status == 500) {
                    toastr.error(
                        e.responseJSON.message ??
                            "Hubo problemas internos, por favor comunicate de inmediato con SOPORTE"
                    );
                }
            },
        });
    },
    eliminar: function (id) {
        var $self = this;
        ventana.confirm(
            {
                titulo: "Confirmar",
                mensaje: "¿Desea eliminar el registro seleccionado?",
                textoBotonAceptar: "Eliminar",
            },
            function (ok) {
                if (ok) {
                    $.ajax({
                        url: route(_path_controller_venta + ".destroy", id),
                        type: "DELETE",
                        beforeSend: function () {
                            loading();
                        },
                        success: function (response) {
                            toastr.success(
                                "Datos grabados correctamente.",
                                "Notificación " + _name_module_venta
                            );
                            $self.callback(response);
                        },
                        complete: function () {
                            loading("complete");
                        },
                        error: function (e) {
                            if (e.status == 419) {
                                toastr.error(
                                    "La sesión ya expiró, por favor cierre sesión y vuelva a ingresar"
                                );
                            } else if (e.status == 500) {
                                toastr.error(
                                    e.responseJSON.message ??
                                        "Hubo problemas internos, por favor comunicate de inmediato con SOPORTE"
                                );
                            }
                        },
                    });
                }
            }
        );
    },
    guardar: function () {
        var $self = this;
        var s = true;
        if (s) {
            let post_data = new FormData($($self._form)[0]);
            $("table#table_producto tbody tr").each(function (k, v) {
                if ($(this).attr("index")) {
                    post_data.append(
                        "detalle_venta[" + k + "][codventa_producto]",
                        $(this).attr("data-detalle")
                    );
                    post_data.append(
                        "detalle_venta[" + k + "][idproducto]",
                        $(this).attr("index")
                    );
                }
            });
            console.log(post_data);
            $.ajax({
                url: route(_path_controller_venta + ".store"),
                type: "POST",
                data: post_data,
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function () {
                    loading();
                },
                success: function (response) {
                    toastr.success(
                        "Datos grabados correctamente.",
                        "Notificación " + _name_module_venta
                    );
                    $self.callback(response);
                    dialog.close($self._form);
                },
                complete: function () {
                    loading("complete");
                },
                error: function (e) {
                    if (e.status == 422) {
                        //Errores de Validacion
                        limpieza_venta();

                        $.each(e.responseJSON.errors, function (i, item) {
                            $("#" + i).addClass("is-invalid");
                            $("." + i).removeClass("d-none");
                            if ($("." + i).length) $("." + i).html(item);
                            else toastr.warning(item);
                        });
                    } else if (e.status == 419) {
                        toastr.error(
                            "La sesión ya expiró, por favor cierre sesión y vuelva a ingresar"
                        );
                    } else if (e.status == 500) {
                        toastr.error(
                            e.responseJSON.message ??
                                "Hubo problemas internos, por favor comunicate de inmediato con SOPORTE"
                        );
                    } else if (e.status == 400) {
                        toastr.error(e.responseJSON.message);
                    }
                },
            });
        }
    },
    callback: function (data) {
        grilla.reload(_name_tabla_venta);
    },
    reset: function () {
        $(":input", this._form).val("");
        $("#codtipo_doc_ident", this._form).prop("selectedIndex", 0);
        $('.nav-tabs a[href="#custom-tabs-01"]').tab("show");
        $("table#table_producto tbody").html(default_tr);
        $(".file_upload").html("");
        limpieza_venta();
    },
});

function setDetalleVenta(row) {
    $(row).each(function (k, v) {
        detalle_producto(k, v);
    });
}

function detalle_producto(i, val) {
    if ($("table#table_producto tbody tr[index]").length < 1) {
        $("table#table_producto tbody tr").remove();
    }

    item = $("table#table_producto tbody tr[index]").length;

    _table =
        "<tr index='" +
        val["idproducto"] +
        "' data-detalle='" +
        val["codventa_producto"] +
        "'  >";
    _table += "  <td class='xitem'>" + (item + 1) + "</td>";
    _table += "   <td>" + val["producto_venta"] + "</td>";
    _table += "   <td>";
    _table +=
        "       <a href='javascript:void(0);' title='Eliminar producto' class='btn btn-delete-producto btn-outline-danger btn-xs btn-icon hover-effect-dot waves-effect waves-themed'><i class='fa fa-trash'></i></a>";
    _table += "    </td>";
    _table += "</tr>";

    $("table#table_producto tbody").append(_table);
}

limpieza_venta = (id) => {
    id = id || "#form-" + _path_controller_venta;

    $(id + " span.error_text_o_o").addClass("d-none");
    $(id + " input").removeClass("is-invalid");
};

function order_detalle_item() {
    xx = 1;
    $(".xitem").each(function (x, y) {
        $(this).html(xx);
        xx++;
    });
}
