Dropzone.autoDiscover = false;
var myDropzone = new Dropzone("#dropzone"+_prefix_noticia, {
	addRemoveLinks: false,//Esto es true si o solo si se usara la opcion de borrar los ficheros subidos
	clickable: true,
	dictDefaultMessage:"Arrastre aquí su logo",
    url: route(_path_controller_noticia+'.upload_file'),
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
	sending: function(file, xhr, formData) {

	},
	dictRemoveFile: 'Borrar',//Esta opcion depende del parametro {addRemoveLinks}
	acceptedFiles: 'image/jpeg, image/jpg, image/png',
	accept: function(file, done) {
		done();
	},
	success: function( file, response ){
		var obj = response;
		if(obj.status){
			$("#imagen").val(obj.filename);
			$("#prev_imagen").attr("src", obj.path_file_img);
		}else{
			ventana.alert({titulo: "Ups..!", mensaje: obj.sms, tipo:"error"}, function() {

			});
		}
	},
	complete: function(file) {
		setTimeout(() => {
			this.removeAllFiles();//Para mostrar el texto, esto no borra el thumbnail
			file.previewElement.remove(); //Para quitar el thumbnail subido
		}, 1);
    },
	removedfile: function(file) {//Esta opcion depende del parametro {addRemoveLinks}

	},
});

$('#descripcion'+_prefix_noticia).summernote({
    placeholder: 'Escriba aqui la descripcion',
    tabsize: 2,
    height: 100,
    toolbar: [
        ['style', ['style']],
        ['font', ['bold', 'underline', 'clear']],
        ['color', ['color']],
        ['para', ['ul', 'ol', 'paragraph']],
    ]
});

dialog.create({
    selector: "#form-"+_path_controller_noticia
    ,title: "Registrar "+_name_module_noticia
    ,width: 'modal-md'
    ,style: 'primary'
    ,closeOnEscape: true
    ,buttons: {
        Guardar: function(){
            form.get(_path_controller_noticia).guardar();
        },
        Cancelar: function(){
            form.get(_path_controller_noticia).cancelar();
        }
    }
    ,close: function() {
        form.get(_path_controller_noticia).reset();
    }
});

form.register(_path_controller_noticia, {
    _prefix: _prefix_noticia,
	_form: "#form-"+_path_controller_noticia,

    nuevo: function() {
        limpieza_noticia(this._form);

        dialog.open(this._form);
    },
    cancelar: function() {
		dialog.close(this._form);
	},
    editar: function(id) {
		var $self = this;
        $.ajax({
			url:route(_path_controller_noticia+'.edit',id),
			type:'GET',
            beforeSend: function() {
                loading();
                dialog.open($self._form);
            },
			success: function(response) {
                limpieza_noticia($self._form);

                $.each(response, function(k, v) {
					$("#"+k, $self._form).val(v);
				});

                $("#prev_imagen").attr("src", response.url_imagen);
                $("#descripcion", $self._form).summernote('code', response.descripcion);
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
                    url:route(_path_controller_noticia+'.destroy',id),
                    type:'DELETE',
                    beforeSend: function() {
                        loading();
                    },
                    success: function(response) {
                        toastr.success('Datos grabados correctamente.','Notificación '+_name_module_noticia);
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
			var post_data = $($self._form).serialize();

            $.ajax({
                url: route(_path_controller_noticia+'.store'),
                type: 'POST',
                data: post_data,
                beforeSend: function() {
                    loading();
                },
                success: function(response){
                    toastr.success('Datos grabados correctamente.','Notificación '+_name_module_noticia);
                    $self.callback(response);
                    dialog.close($self._form);
                },
                complete: function () {
                    loading("complete");
                },
                error: function(e){
                    if(e.status==422){ //Errores de Validacion
                        limpieza_noticia();
                        $('.nav-tabs a[href="#custom-tabs-01"]').tab('show');
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
		grilla.reload(_name_tabla_noticia);
	},
    reset: function() {
        $(":input", this._form).val("");
        $('.nav-tabs a[href="#custom-tabs-01"]').tab('show');
        $("#descripcion", this._form).summernote('code', "<p><br></p>");
        $("#prev_imagen").attr("src", _default_imagen_noticia);

        limpieza_noticia();
    }
});

limpieza_noticia=(id)=>{
    id = id || "#form-"+_path_controller_noticia;

    $(id+" span.error_text_o_o").addClass("d-none");
    $(id+" input").removeClass("is-invalid");
}
