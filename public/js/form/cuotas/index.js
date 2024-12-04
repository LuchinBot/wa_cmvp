$(function () {
    $("#codcolegiado").select2({
        placeholder: $(this).data("placeholder"),
        width: "100%",
    });

    $(document).on("select2:open", () => {
        document.querySelector(".select2-search__field").focus();
    });
});

$(document).on("click", "#btn_buscar", function (e) {
    e.preventDefault();

    $.ajax({
        url: route(_path_controller + ".get_data"),
        type: "GET",
        data: $("#filtro-form").serialize(),
        beforeSend: function () {
            loading("", $("#btn_buscar"));
        },
        success: function (response) {
            //console.log(response.data.persona_natural.nombre_completo);
            let exists = false;
            $("#table_result tbody tr").each(function () {
                let existingCod = $(this).find("input[type='hidden']").val();
                if (existingCod === String(response.data.codcolegiado)) {
                    exists = true;
                    return false;
                }
            });

            if (exists) {
                let message =
                    "<tr><td colspan='5' class='text-center bg-danger'><i>Colegiado ya cuenta con cuota asignada.</i></td></tr>";
            } else if (!response.status) {
                let newRow = createRow(
                    response.data.codcolegiado,
                    response.data.persona_natural.nombre_completo,
                    response.data.persona_natural.numero_documento_identidad,
                    response.data.numero_colegiatura
                );
                $("#table_result tbody").append(newRow);
            }

            if (response.status) {
                let message =
                    "<tr><td colspan='5' class='text-center bg-danger'><i>Colegiado ya cuenta con cuota asignada.</i></td></tr>";
                $("#table_result tbody").append(message);
                setTimeout(function () {
                    $("#table_result tbody .bg-danger").fadeOut().remove();
                }, 3000);
            }
        },
        complete: function () {
            loading("complete", $("#btn_buscar"));
        },
        error: function (e) {
            if (e.status == 419) {
                toastr.error(
                    "La sesiÃ³n ya expirÃ³, por favor cierre sesiÃ³n y vuelva a ingresar"
                );
            } else if (e.status == 500) {
                toastr.error(
                    e.responseJSON.message ??
                        "Hubo problemas internos, por favor comunicate de inmediato con SOPORTE"
                );
            }
        },
    });

    function createRow(id, nombres, dni, colegiatura) {
        let row = "<tr index='" + id + "'>";
        /*
        row +=
            "<td class='d-none'><input type='hidden' value='" + id + "'></td>";*/
        row +=
            "<td><p class='font-weight-bold text-secondary'>" +
            nombres +
            "<br><span class='font-weight-light'><b>DNI:</b> " +
            dni +
            " | <b>CMVP:</b> " +
            colegiatura +
            "</span></p></td>";
        row +=
            "<td><input type='number' class='form-control text-center' value='' name='mes'></td>";
        row +=
            "<td><input type='number' class='form-control text-center' value='' name='anio'></td>";
        row +=
            "<td><input type='number' class='form-control text-center' value='' name='monto'></td>";

        row +=
            "<td class='text-center'><button type='button' class='btn btn-info btn-cuota'><i class='fa fa-save'></i></button></td>";
        row += "</tr>";
        return row;
    }

    $(document).on("click", ".btn-cuota", function () {
        var row = $(this).closest("tr");
        var id = row.attr("index");
        var mes = row.find("input[name='mes']").val();
        var anio = row.find("input[name='anio']").val();
        var monto = row.find("input[name='monto']").val();
        var btn = $(this);

        btn.prop("disabled", false);

        if (mes < 1 || mes > 12) {
            toastr.error("El mes debe estar entre 1 y 12");
            btn.prop("disabled", false);
            return;
        }

        if (anio < 1900) {
            toastr.error("El año debe ser 1900 o mayor");
            btn.prop("disabled", false);
            return;
        }

        if (mes && anio && monto) {
            var data = {
                id: id,
                mes: mes,
                anio: anio,
                monto: monto,
            };
            $.ajax({
                url: route(_path_controller + ".save_data"),
                type: "GET",
                data: data,
                beforeSend: function () {
                    loading("", $("#btn-cuota"));
                },
                success: function (response) {
                    if (response.status) {
                        btn.prop("disabled", true);
                        toastr.success(
                            "Datos grabados correctamente.",
                            "Notificación " + _name_module
                        );
                    }
                },
                complete: function () {
                    btn.prop("disabled", true);
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
        } else {
            toastr.warning("Por favor, complete todos los campos.");
        }
    });
});
