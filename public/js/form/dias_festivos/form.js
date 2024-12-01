/*
$('#descripcion'+_prefix_dias_festivos).summernote({
    placeholder: 'Escriba aqui la descripcion o detalles del dias_festivos',
    tabsize: 2,
    height: 100,
    toolbar: [
        ['style', ['style']],
        ['font', ['bold', 'underline', 'clear']],
        ['color', ['color']],
        ['para', ['ul', 'ol', 'paragraph']],
        ['view', ['codeview']]
    ]
});*/



dialog.create({
    selector: "#form-"+_path_controller_dias_festivos
    ,title: "Registrar "+_name_module_dias_festivos
    ,width: 'modal-lg'
    ,style: 'primary'
    ,closeOnEscape: true
    ,buttons: {
        Guardar: function(){
            form.get(_path_controller_dias_festivos).guardar();
        },
        Cancelar: function(){
            form.get(_path_controller_dias_festivos).cancelar();
        }
    }
    ,close: function() {
        form.get(_path_controller_dias_festivos).reset();
    }
});

form.register(_path_controller_dias_festivos, {
    _prefix: _prefix_dias_festivos,
	_form: "#form-"+_path_controller_dias_festivos,

    nuevo: function() {
        limpieza_dias_festivos(this._form);
        dialog.open(this._form);
    },
    cancelar: function() {
		dialog.close(this._form);
	},
    editar: function(id) {
		var $self = this;
        $.ajax({
			url:route(_path_controller_dias_festivos+'.edit',id),
			type:'GET',
            beforeSend: function() {
                loading();
                dialog.open($self._form);
            },
			success: function(response) {
                limpieza_dias_festivos($self._form);
                $.each(response, function(k, v) {
                    $("#"+k, $self._form).val(v);
						
				});
                $("#descripcion", $self._form).summernote('destroy');
                $("#descripcion", $self._form).val(response.descripcion);
                $("#flayer_prev", $self._form).attr("src", response.url_flayer);

    
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
			if(ok.value) {
                $.ajax({
                    url:route(_path_controller_dias_festivos+'.destroy',id),
                    type:'DELETE',
                    beforeSend: function() {
                        loading();
                    },
                    success: function(response) {
                        toastr.success('Datos grabados correctamente.','Notificación '+_name_module_dias_festivos);
                        $self.callback(response);
                    },
                    complete: function () {
                        loading("complete");
                    },
                    error: function(e) {
                        //console.log("Error..", e);
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
            var post_data = new FormData($($self._form)[0]);

            $.ajax({
                url: route(_path_controller_dias_festivos+'.store'),
                type: 'POST',
                data: post_data,
                cache       : false,
                contentType : false,
                processData : false,
                beforeSend: function() {
                    loading();
                },
                success: function(response){
                    console.log(response)
                    //toastr.success('Datos grabados correctamente.','Notificación '+_name_module_dias_festivos);
                    $self.callback(response);
                    dialog.close($self._form);
                },
                complete: function () {
                    loading("complete");
                },
                error: function(e){
                    if(e.status==422){ //Errores de Validacion
                        limpieza_dias_festivos();

                        $.each(e.responseJSON.errors, function(i, item) {
                            $('#'+i).addClass('is-invalid');
                            $('.'+i).removeClass('d-none');
                            if($('.'+i).length)
                                $('.'+i).html(item);
                            else{
                                toastr.warning(item);
                                console.log(item);
                            }

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
    callback: function() {
		grilla.reload(_name_tabla_dias_festivos);
	},
    reset: function() {
        $(":input", this._form).val("");
        $('.nav-tabs a[href="#custom-tabs-01"]').tab('show');
        $("#flayer_prev", this._form).attr("src", _default_flayer_dias_festivos);
        $("#plantilla_prev", this._form).attr("src", _default_plantilla_dias_festivos);
    }
});

limpieza_dias_festivos=(id)=>{
    id = id || "#form-"+_path_controller_dias_festivos;

    $(id+" span.error_text_o_o").addClass("d-none");
    $(id+" input").removeClass("is-invalid");
}


function cargar_flayer(f, tag_img){
    id      = $(f).attr("id");
    tag_img = tag_img || id;

    let imagenAR = document.getElementById(id);
    if (imagenAR.files.length != 0 && imagenAR.files[0].type.match(/image.*/)) {
        let lecimg = new FileReader();
        let div_custom = $(f).closest("div.custom-file");
            div_custom.find("label[for='file']").html(imagenAR.files[0].name);
        lecimg.onload = function(e) {
            var img = document.getElementById(tag_img);

            img.src = e.target.result;
        };
        lecimg.onerror = function(e) {
            console.log("Error al leer la imagen", e);
        };
        lecimg.readAsDataURL(imagenAR.files[0]);
    } else {
        console.log("Debe seleccionar una imagen");
    }
}