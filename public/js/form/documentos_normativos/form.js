dialog.create({
    selector: "#form-"+_path_controller_documento_normativo
    ,title: "Registrar "+_name_module_documento_normativo
    ,width: 'modal-md'
    ,style: 'primary'
    ,closeOnEscape: true
    ,buttons: {
        Guardar: function(){
            form.get(_path_controller_documento_normativo).guardar();
        },
        Cancelar: function(){
            form.get(_path_controller_documento_normativo).cancelar();
        }
    }
    ,close: function() {
        form.get(_path_controller_documento_normativo).reset();
    }
});

form.register(_path_controller_documento_normativo, {
    _prefix: _prefix_documento_normativo,
	_form: "#form-"+_path_controller_documento_normativo,

    nuevo: function() {
        limpieza_documento_normativo(this._form);

        dialog.open(this._form);
    },
    cancelar: function() {
		dialog.close(this._form);
	},
    editar: function(id) {
		var $self = this;
        $.ajax({
			url:route(_path_controller_documento_normativo+'.edit',id),
			type:'GET',
            beforeSend: function() {
                loading();
                dialog.open($self._form);
            },
			success: function(response) {
                limpieza_documento_normativo($self._form);

                $.each(response, function(k, v) {
					$("#"+k, $self._form).val(v);
				});

                $(".file_upload").html("<a href='"+response.url_archivo+"' target='_blank'>"+response.archivo+"</a>");
			},
            complete: function () {
                loading("complete");
            },
			error: function(e) {
                if(e.status==419){
                    toastr.error("La sesión ya expiró, por favor cierre sesión y vuelva a ingresar");
                }else if(e.status==500){
                    toastr.error((e.responseJSON.message)??'Hubo problemas internos, por favor comunicate de inmediato con SOPORTE');
                }
			}
		});
    },
    eliminar: function(id) {
        var $self = this;
        ventana.confirm({
			titulo:"Confirmar"
			,mensaje:"¿Desea eliminar el registro seleccionado?"
			,textoBotonAceptar: "Eliminar"
		}, function(ok){
			if(ok) {
                $.ajax({
                    url:route(_path_controller_documento_normativo+'.destroy',id),
                    type:'DELETE',
                    beforeSend: function() {
                        loading();
                    },
                    success: function(response) {
                        toastr.success('Datos grabados correctamente.','Notificación '+_name_module_documento_normativo);
                        $self.callback(response);
                    },
                    complete: function () {
                        loading("complete");
                    },
                    error: function(e) {
                        if(e.status==419){
                            toastr.error("La sesión ya expiró, por favor cierre sesión y vuelva a ingresar");
                        }else if(e.status==500){
                            toastr.error((e.responseJSON.message)??'Hubo problemas internos, por favor comunicate de inmediato con SOPORTE');
                        }
                    }
                });
            }
        });
    },
    guardar: function(){
		var $self = this;
        var s = true;
		if(s){
			let post_data = new FormData($($self._form)[0]);

            $.ajax({
                url: route(_path_controller_documento_normativo+'.store'),
                type: 'POST',
                data: post_data,
                cache       : false,
                contentType : false,
                processData : false,
                beforeSend: function() {
                    loading();
                },
                success: function(response){
                    toastr.success('Datos grabados correctamente.','Notificación '+_name_module_documento_normativo);
                    $self.callback(response);
                    dialog.close($self._form);
                },
                complete: function () {
                    loading("complete");
                },
                error: function(e){
                    if(e.status==422){ //Errores de Validacion
                        limpieza_documento_normativo();

                        $.each(e.responseJSON.errors, function(i, item) {
                            $('#'+i).addClass('is-invalid');
                            $('.'+i).removeClass('d-none');
                            if($('.'+i).length)
                                $('.'+i).html(item);
                            else
                                toastr.warning(item);
                        });
                    }else if(e.status==419){
                        toastr.error("La sesión ya expiró, por favor cierre sesión y vuelva a ingresar");
                    }else if(e.status==500){
                        toastr.error((e.responseJSON.message)??'Hubo problemas internos, por favor comunicate de inmediato con SOPORTE');
                    }
                }
            });
        }
    },
    callback: function(data) {
		grilla.reload(_name_tabla_documento_normativo);
	},
    reset: function() {
        $(":input", this._form).val("");

        $(".file_upload").html("");
        limpieza_documento_normativo();
    }
});

limpieza_documento_normativo=(id)=>{
    id = id || "#form-"+_path_controller_documento_normativo;

    $(id+" span.error_text_o_o").addClass("d-none");
    $(id+" input").removeClass("is-invalid");
}

function cargar_archivo(f){
    id      = $(f).attr("id");

    let imagenAR = document.getElementById(id);
    if ( imagenAR.files.length != 0 ) {
        let lecimg = new FileReader();
        let div_custom = $(f).closest("div.custom-file");
            div_custom.find("label[for='file']").html(imagenAR.files[0].name);
        lecimg.onload = function(e) {

        };
        lecimg.onerror = function(e) {
            console.log("Error al leer la imagen", e);
        };
        lecimg.readAsDataURL(imagenAR.files[0]);
    } else {
        console.log("Debe seleccionar una imagen");
    }
}
