dialog.create({
    selector: "#form-" + _path_controller_producto,
    title: "Registrar " + _name_module_producto,
    width: "modal-md",
    style: "primary",
    closeOnEscape: true,
    buttons: {
        Guardar: function () {
            form.get(_path_controller_producto).guardar();
        },
        Cancelar: function () {
            form.get(_path_controller_producto).cancelar();
        },
    },
    close: function () {
        form.get(_path_controller_producto).reset();
    },
});

form.register(_path_controller_producto, {
    _prefix: "",
    _form: "#form-" + _path_controller_producto,

    nuevo: function () {
        limpieza_caja(this._form);
        dialog.open(this._form);
    },
    cancelar: function () {
        dialog.close(this._form);
    },
    editar: function (id) {
        var $self = this;
        $.ajax({
            url: route(_path_controller_producto + ".edit", id),
            type: "GET",
            beforeSend: function () {
                loading();
                dialog.open($self._form);
            },
            success: function (response) {
                limpieza_caja($self._form);

                $.each(response, function (k, v) {
                    $("#" + k, $self._form).val(v);
                });

                $("#codtipo_doc_ident" + _prefix_caja).val(
                    response.persona_natural.codtipo_documento_identidad
                );
                $("#nro_doc_caja" + _prefix_caja).val(
                    response.persona_natural.numero_documento_identidad
                );
                $("#nombres_caja" + _prefix_caja).val(
                    response.persona_natural.nombre_completo
                );

                $(".file_upload").html(
                    "<a href='" +
                        response.url_cv +
                        "' target='_blank'>" +
                        response.curriculum_vitae +
                        "</a>"
                );

                setEspecialides(response.especialidad_caja);
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
                mensaje: "¿Desea cerrar la caja seleccionada?",
                textoBotonAceptar: "Eliminar",
            },
            function (ok) {
                if (ok) {
                    $.ajax({
                        url: route(_path_controller_producto + ".destroy", id),
                        type: "DELETE",
                        beforeSend: function () {
                            loading();
                        },
                        success: function (response) {
                            toastr.success(
                                "Caja cerra exitosamente.",
                                "Notificación " + _name_module_producto
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
                            } else if (e.status == 400) {
                                toastr.error(
                                    e.responseJSON.message ??
                                        "No hay ventas en la caja."
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
            console.log(post_data);
            $.ajax({
                url: route(_path_controller_producto + ".store"),
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
                        "Notificación " + _name_module_producto
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
                        limpieza_caja();

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
                        toastr.error(
                            e.responseJSON.message ??
                                "Hay una caja abierta, debes cerrarla para poder abrir una nueva."
                        );
                    }
                },
            });
        }
    },
    callback: function (data) {
        grilla.reload(_name_tabla_caja);
    },
    reset: function () {
        $(":input", this._form).val("");
        $("#idtipo_caja", this._form).prop("selectedIndex", 0);
        limpieza_caja();
    },
});

limpieza_caja = (id) => {
    id = id || "#form-" + _path_controller_producto;

    $(id + " span.error_text_o_o").addClass("d-none");
    $(id + " input").removeClass("is-invalid");
};
