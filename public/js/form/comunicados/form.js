Dropzone.autoDiscover = false;
var myDropzone = new Dropzone("#dropzone"+_prefix_comunicado, {
	addRemoveLinks: false,//Esto es true si o solo si se usara la opcion de borrar los ficheros subidos
	clickable: true,
	dictDefaultMessage:"Arrastre aquí su logo",
    url: route(_path_controller_comunicado+'.upload_file'),
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
    placeholder: 'Escriba aqui la reseña del comunicado',
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
    selector: "#form-"+_path_controller_comunicado
    ,title: "Registrar "+_name_module_comunicado
    ,width: 'modal-lg'
    ,style: 'primary'
    ,closeOnEscape: true
    ,buttons: {
        Guardar: function(){
            form.get(_path_controller_comunicado).guardar();
        },
        Cancelar: function(){
            form.get(_path_controller_comunicado).cancelar();
        }
    }
    ,close: function() {
        form.get(_path_controller_comunicado).reset();
    }
});

form.register(_path_controller_comunicado, {
    _prefix: _prefix_comunicado,
	_form: "#form-"+_path_controller_comunicado,

    nuevo: function() {
        limpieza_comunicado();
        dialog.open(this._form);
    },
    cancelar: function() {
		dialog.close(this._form);
	},
    editar: function(id) {
		var $self = this;
        $.ajax({
			url:route(_path_controller_comunicado+'.edit',id),
			type:'GET',
            beforeSend: function() {
                loading();
                dialog.open($self._form);
            },
			success: function(response) {
                limpieza_comunicado();

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
                    url:route(_path_controller_comunicado+'.destroy',id),
                    type:'DELETE',
                    beforeSend: function() {
                        loading();
                    },
                    success: function(response) {
                        toastr.success('Datos grabados correctamente.','Notificación '+_name_module_comunicado);
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
                url: route(_path_controller_comunicado+'.store'),
                type: 'POST',
                data: post_data,
                cache       : false,
                contentType : false,
                processData : false,
                beforeSend: function() {
                    loading();
                },
                success: function(response){
                    toastr.success('Datos grabados correctamente.','Notificación '+_name_module_comunicado);
                    $self.callback(response);
                    dialog.close($self._form);
                },
                complete: function () {
                    loading("complete");
                },
                error: function(e){
                    if(e.status==422){ //Errores de Validacion
                        limpieza_comunicado();

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
		grilla.reload(_name_tabla_comunicado);
	},
    reset: function() {
        $(":input", this._form).val("");
        $('.nav-tabs a[href="#custom-tabs-01"]').tab('show');
        $("#foto_prev").attr("src", _default_imagen_comunicado);
        $("#prev_imagen").attr("src", _default_flayer_comunicado);
        $("#descripcion", this._form).summernote('code', "<p><br></p>");

        limpieza_comunicado();
    }
});

limpieza_comunicado=(id)=>{
    id = id || "#form-"+_path_controller_comunicado;

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
