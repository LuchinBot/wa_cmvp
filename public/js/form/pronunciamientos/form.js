Dropzone.autoDiscover = false;
var myDropzone = new Dropzone("#dropzone"+_prefix_pronunciamiento, {
	addRemoveLinks: false,//Esto es true si o solo si se usara la opcion de borrar los ficheros subidos
	clickable: true,
	dictDefaultMessage:"Arrastre aquí su logo",
    url: route(_path_controller_pronunciamiento+'.upload_file'),
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
			$("#imagen_flayer").val(obj.filename);
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

$('#descripcion').summernote({
    placeholder: 'Escriba aqui la reseña del pronunciamiento',
    tabsize: 2,
    height: 100,
    toolbar: [
        ['style', ['style']],
        ['font', ['bold', 'underline', 'clear']],
        ['color', ['color']],
        ['para', ['ul', 'ol', 'paragraph']],
        ['view', ['codeview']]
    ]
});

dialog.create({
    selector: "#form-"+_path_controller_pronunciamiento
    ,title: "Registrar "+_name_module_pronunciamiento
    ,width: 'modal-lg'
    ,style: 'primary'
    ,closeOnEscape: true
    ,buttons: {
        Guardar: function(){
            form.get(_path_controller_pronunciamiento).guardar();
        },
        Cancelar: function(){
            form.get(_path_controller_pronunciamiento).cancelar();
        }
    }
    ,close: function() {
        form.get(_path_controller_pronunciamiento).reset();
    }
});

form.register(_path_controller_pronunciamiento, {
    _prefix: _prefix_pronunciamiento,
	_form: "#form-"+_path_controller_pronunciamiento,

    nuevo: function() {
        limpieza_pronunciamiento();
        dialog.open(this._form);
    },
    cancelar: function() {
		dialog.close(this._form);
	},
    editar: function(id) {
		var $self = this;
        $.ajax({
			url:route(_path_controller_pronunciamiento+'.edit',id),
			type:'GET',
            beforeSend: function() {
                loading();
                dialog.open($self._form);
            },
			success: function(response) {
                limpieza_pronunciamiento();

                $.each(response, function(k, v) {
					$("#"+k, $self._form).val(v);
				});

                $("#descripcion", $self._form).summernote('code', response.descripcion);
                $("#foto_prev").attr("src", response.url_imagen);
                $("#prev_imagen").attr("src", response.url_imagen_flayer);
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
                    url:route(_path_controller_pronunciamiento+'.destroy',id),
                    type:'DELETE',
                    beforeSend: function() {
                        loading();
                    },
                    success: function(response) {
                        toastr.success('Datos grabados correctamente.','Notificación '+_name_module_pronunciamiento);
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
                url: route(_path_controller_pronunciamiento+'.store'),
                type: 'POST',
                data: post_data,
                cache       : false,
                contentType : false,
                processData : false,
                beforeSend: function() {
                    loading();
                },
                success: function(response){
                    toastr.success('Datos grabados correctamente.','Notificación '+_name_module_pronunciamiento);
                    $self.callback(response);
                    dialog.close($self._form);
                },
                complete: function () {
                    loading("complete");
                },
                error: function(e){
                    if(e.status==422){ //Errores de Validacion
                        limpieza_pronunciamiento();

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
		grilla.reload(_name_tabla_pronunciamiento);
	},
    reset: function() {
        $(":input", this._form).val("");
        $('.nav-tabs a[href="#custom-tabs-01"]').tab('show');
        $("#foto_prev").attr("src", _default_imagen_pronunciamiento);
        $("#prev_imagen").attr("src", _default_flayer_pronunciamiento);
        $("#descripcion", this._form).summernote('code', "<p><br></p>");

        limpieza_pronunciamiento();
    }
});

limpieza_pronunciamiento=(id)=>{
    id = id || "#form-"+_path_controller_pronunciamiento;

    $(id+" span.error_text_o_o").addClass("d-none");
    $(id+" input").removeClass("is-invalid");
}

function cargar_imagen(f, tag_img){
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
