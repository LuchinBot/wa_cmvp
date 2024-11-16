Dropzone.autoDiscover = false;
var myDropzone = new Dropzone("#dropzone"+_prefix_empresa, {
	addRemoveLinks: false,//Esto es true si o solo si se usara la opcion de borrar los ficheros subidos
	clickable: true,
	dictDefaultMessage:"Arrastre aquí su logo",
    url: route(_path_controller_empresa+'.upload_file'),
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
			$("#imagen_asamblea"+_prefix_empresa).val(obj.filename);
			$("#asamblea_prev"+_prefix_empresa).attr("src", obj.path_file_img);
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

$("#ruc"+_prefix_empresa).numero_entero();
$("#email"+_prefix_empresa).alfanumerico({"permitir": "@._-"});

$('#objetivo'+_prefix_empresa).summernote({
    placeholder: 'Escriba aqui el objetivo',
    tabsize: 2,
    height: 130,
    toolbar: [
        ['style', ['style']],
        ['font', ['bold', 'underline', 'clear']],
        ['color', ['color']],
        ['para', ['ul', 'ol', 'paragraph']],
        ['view', ['codeview']]
    ]
});

$('#historia'+_prefix_empresa).summernote({
    placeholder: 'Escriba aqui la historia',
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

$('#mision'+_prefix_empresa).summernote({
    placeholder: 'Escriba aqui la mision',
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

$('#vision'+_prefix_empresa).summernote({
    placeholder: 'Escriba aqui la visión',
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

$('#descripcion_consejo'+_prefix_empresa).summernote({
    placeholder: 'Escriba aqui la descripción del consejo departamental',
    tabsize: 2,
    height: 130,
    toolbar: [
        ['style', ['style']],
        ['font', ['bold', 'underline', 'clear']],
        ['color', ['color']],
        ['para', ['ul', 'ol', 'paragraph']],
        ['view', ['codeview']]
    ]
});

$(document).on("click", "#btn_ubigeo"+_prefix_empresa, function(e) {
    e.preventDefault();
	ubigeo.ok(function(data) {
        console.log(data);
		$("#ubigeo_descr"+_prefix_empresa).val(data.departamento+' - '+data.provincia+' - '+data.distrito);
		$("#id_ubigeo"+_prefix_empresa).val(data.idubigeo);
	});
	ubigeo.show();
});

dialog.create({
    selector: "#form-"+_path_controller_empresa
    ,title: "Registrar "+_name_module_empresa
    ,width: 'modal-lg'
    ,style: 'primary'
    ,closeOnEscape: true
    ,buttons: {
        Guardar: function(){
            form.get(_path_controller_empresa).guardar();
        },
        Cancelar: function(){
            form.get(_path_controller_empresa).cancelar();
        }
    }
    ,close: function() {
        form.get(_path_controller_empresa).reset();
    }
});

form.register(_path_controller_empresa, {
    _prefix: _prefix_empresa,
	_form: "#form-"+_path_controller_empresa,

    nuevo: function() {
        limpieza_empresa(this._form);
        dialog.open(this._form);
    },
    cancelar: function() {
		dialog.close(this._form);
	},
    editar: function(id) {
		var $self = this;
        $.ajax({
			url:route(_path_controller_empresa+'.edit',id),
			type:'GET',
            beforeSend: function() {
                loading();
                dialog.open($self._form);
            },
			success: function(response) {
                limpieza_empresa($self._form);

                $.each(response, function(k, v) {
					$("#"+k, $self._form).val(v);
				});

                $("#objetivo", $self._form).summernote('code', response.objetivo);
                $("#historia", $self._form).summernote('code', response.historia);
                $("#mision", $self._form).summernote('code', response.mision);
                $("#vision", $self._form).summernote('code', response.vision);
                $("#descripcion_consejo", $self._form).summernote('code', response.descripcion_consejo);

                $("#foto_prev"+_prefix_empresa).attr("src", response.url_logo);
                $("#objetivo_prev"+_prefix_empresa).attr("src", response.url_imagen_objetivo);
                $("#consejo_prev"+_prefix_empresa).attr("src", response.url_imagen_consejo);
                $("#asamblea_prev"+_prefix_empresa).attr("src", response.url_imagen_asamblea);
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
                    url:route(_path_controller_empresa+'.destroy',id),
                    type:'DELETE',
                    beforeSend: function() {
                        loading();
                    },
                    success: function(response) {
                        toastr.success('Datos grabados correctamente.','Notificación '+_name_module_empresa);
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
            var post_data = new FormData($($self._form)[0]);

            $.ajax({
                url: route(_path_controller_empresa+'.store'),
                type: 'POST',
                data: post_data,
                cache       : false,
                contentType : false,
                processData : false,
                beforeSend: function() {
                    loading();
                },
                success: function(response){
                    toastr.success('Datos grabados correctamente.','Notificación '+_name_module_empresa);
                    $self.callback(response);
                    dialog.close($self._form);
                },
                complete: function () {
                    loading("complete");
                },
                error: function(e){
                    if(e.status==422){ //Errores de Validacion
                        limpieza_empresa();

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
		grilla.reload(_name_tabla_empresa, false);
	},
    reset: function() {
        $(":input", this._form).val("");
        $('.nav-tabs a[href="#custom-tabs-01"]').tab('show');
        $("#foto_prev"+_prefix_empresa).attr("src", _default_logo_empresa);
        $("#objetivo_prev"+_prefix_empresa).attr("src", _default_obj_empresa);
        $("#consejo_prev"+_prefix_empresa).attr("src", _default_cmvsm_empresa);
        $("#asamblea_prev"+_prefix_empresa).attr("src", _default_asam_empresa);
        $("#objetivo", this._form).summernote('code', "<p><br></p>");
        $("#historia", this._form).summernote('code', "<p><br></p>");
        $("#mision", this._form).summernote('code', "<p><br></p>");
        $("#vision", this._form).summernote('code', "<p><br></p>");
        $("#descripcion_consejo", this._form).summernote('code', "<p><br></p>");

        limpieza_empresa();
    }
});

limpieza_empresa=(id)=>{
    id = id || "#form-"+_path_controller_empresa;

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
