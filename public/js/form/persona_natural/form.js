//eval('var _name_tabla_'+_version_extend_+'='+'"persona_natural";')

$("#nombres"+_prefix_persona_natural+", #apellido_paterno"+_prefix_persona_natural+", #apellido_materno"+_prefix_persona_natural).letras({permitir: " "});
$("#telefono"+_prefix_persona_natural).numero_entero();
$("#numero_documento_identidad"+_prefix_persona_natural).numero_entero();



var guardado_automatico_persona_natural = false;
$(document).on("change", "#codtipo_documento_identidad"+_prefix_persona_natural, function(e){
    e.preventDefault();

    if($(this).val()!=""){
        longitud = $("#codtipo_documento_identidad"+_prefix_persona_natural+" option:selected").data("length");
        $("#numero_documento_identidad"+_prefix_persona_natural).attr("maxlength", longitud);
    }
});

$(document).on("keydown", "#numero_documento_identidad"+_prefix_persona_natural, function(e) {
    if(e.keyCode==13){
        //F1
        e.preventDefault();
	    e.stopPropagation();
        $("#buscar_persona"+_prefix_persona_natural).trigger("click");
    }
});

$(document).on("click", "#buscar_persona"+_prefix_persona_natural, function(e){
    e.preventDefault();

    if($("#numero_documento_identidad"+_prefix_persona_natural).required()){
        $.ajax({
            url:route('persona_natural.search_external'),
            type:'POST',
            data:{
                numero_documento_identidad:$("#numero_documento_identidad"+_prefix_persona_natural).val()
                ,codtipo_documento_identidad:$("#codtipo_documento_identidad"+_prefix_persona_natural).val()
            },
            beforeSend: function() {
                loading();
            },
            success: function(response) {
                if(!response.status){
                    loading_frame("complete");
                    toastr.warning(response.message??'Problemas al obtener informacion', '', {
                        closeButton: true
                    });
                    return;
                }

                $.each(response.data, function(k, v) {
                    $("#"+k+_prefix_persona_natural, "#form-"+_path_controller_persona_natural).val(v);
                });

                if(response.data.foto){
                    $("#foto_prev"+_prefix_persona_natural).attr("src", response.data.fotoPath);
                    $("label#custom-foto"+_prefix_persona_natural+"[for='file']").html(response.data.foto);
                }else{
                    $("label#custom-foto"+_prefix_persona_natural+"[for='file']").html("Cargar Foto");
                    $("#foto_prev"+_prefix_persona_natural).attr("src", _default_photo);
                }

                if(guardado_automatico_persona_natural){
                    let _modal_persona = $("#form-"+_path_controller_persona_natural).closest(".modal-content");
                    $(_modal_persona).find(".modal-footer button.btn-primary").trigger("click");
                }
            },
            complete: function () {
                loading("complete");
            },
            error: function(e) {
                if(e.status==422){ //Errores de Validacion
                    $.each(e.responseJSON.errors, function(k, v){
                        toastr.error(v);
                    });
                }else if(e.status==419){
                    toastr.error("La sesión ya expiró, por favor cierre sesión y vuelva a ingresar");
                }else if(e.status==500){
                    toastr.error((e.responseJSON.message)??'Hubo problemas internos, por favor comunicate de inmediato con SOPORTE');
                }
            }
        });
    }
});

$(document).on("click", "#btn_ubigeo"+_prefix_persona_natural, function(e) {
    e.preventDefault();
	ubigeo.ok(function(data) {
        console.log(data);
		$("#ubigeo_descr"+_prefix_persona_natural).val(data.departamento+' - '+data.provincia+' - '+data.distrito);
		$("#id_ubigeo"+_prefix_persona_natural).val(data.idubigeo);
	});
	ubigeo.show();
});

$("#codtipo_documento_identidad"+_prefix_persona_natural).trigger("change");

dialog.create({
    selector: "#form-"+_path_controller_persona_natural
    ,title: "Registrar "+_name_module_persona_natural
    ,width: 'modal-lg'
    ,style: 'primary'
    ,closeOnEscape: true
    ,buttons: {
        Guardar: function(){
            form.get(_path_controller_persona_natural).guardar();
        },
        Cancelar: function(){
            form.get(_path_controller_persona_natural).cancelar();
        }
    }
    ,close: function() {
        form.get(_path_controller_persona_natural).reset();
    }
});

form.register(_path_controller_persona_natural, {
    _prefix: _prefix_persona_natural,
	_form: "#form-"+_path_controller_persona_natural,

    nuevo: function() {
        limpieza_personas(this._form);
        dialog.open(this._form);
    },
    cancelar: function() {
		dialog.close(this._form);
	},
    editar: function(id) {
		var $self = this;
        $.ajax({
			url:route(_path_controller_persona_natural+'.edit',id),
			type:'GET',
            beforeSend: function() {
                loading();
                dialog.open($self._form);
            },
			success: function(response) {
                limpieza_personas($self._form);

                $.each(response, function(k, v) {
					$("#"+k+_prefix_persona_natural, $self._form).val(v);
				});

                $("label[for='file']").html(response.foto);
                $("#foto_prev"+_prefix_persona_natural).attr("src", response.url_foto);
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
                    url:route(_path_controller_persona_natural+'.destroy',id),
                    type:'DELETE',
                    beforeSend: function() {
                        loading();
                    },
                    success: function(response) {
                        toastr.success('Datos grabados correctamente.','Notificación '+_name_module_persona_natural, {
                            closeButton: true
                        });
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
    sincronizar: function(id){
        var $self = this;

        $.ajax({
            url:route(_path_controller_persona_natural+'.sync',id),
            type:'GET',
            beforeSend: function() {
                loading();
            },
            success: function(response) {
                toastr.success('Dato sincronizado correctamente');
                $self.callback(response);
            },
            complete: function () {
                loading("complete");
            },
            error: function(e) {
                if(e.status==422){ //Errores de Validacion
                    $.each(e.responseJSON.errors, function(k, v){
                        toastr.error(v);
                    });
                }else if(e.status==419){
                    toastr.error("La sesion ya expiró, por favor presione F5 para ingresar nuevamente.");
                }
            }
        });
    },
    guardar: function(){
		var $self = this;
        var s = true;
		if(s){
            let post_data = new FormData($($self._form)[0]);
            $.ajax({
                url: route(_path_controller_persona_natural+'.store'),
                type: 'POST',
                cache       : false,
                contentType : false,
                processData : false,
                data: post_data,
                beforeSend: function() {
                    loading();
                },
                success: function(response){
                    toastr.success('Datos grabados correctamente.','Notificación '+_name_module_persona_natural, {
                        closeButton: true
                    });
                    console.log(response);
                    $self.callback(response);
                    dialog.close($self._form);
                },
                complete: function () {
                    loading("complete");
                },
                error: function(e){
                    if(e.status==422){ //Errores de Validacion
                        limpieza_personas();

                        $.each(e.responseJSON.errors, function(i, item) {
                            $('#'+i).addClass('is-invalid');
                            $('.'+i).removeClass('d-none');
                            $('.'+i).html(item);
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
    extendBusqueda(){
        guardado_automatico_persona_natural = true;
        dialog.open(this._form);
        $("#buscar_persona"+_prefix_persona_natural).trigger("click");
        $("#btn_ubigeo"+_prefix_persona_natural).hide();
    },
    callback: function(data) {
        console.log(data);
		grilla.reload(_name_tabla_persona_natural, false);
	},
    reset: function() {
        $(":input", this._form).val("");
        $("#codtipo_documento_identidad"+_prefix_persona_natural, this._form).prop("selectedIndex", 0);
        //Falta limpiar la imagen por defecto
        limpieza_personas();
        guardado_automatico_persona_natural = false;
        $("#btn_ubigeo"+_prefix_persona_natural).show();
        $("label#custom-foto"+_prefix_persona_natural+"[for='file']").html("Cargar Foto");
        $("#foto_prev"+this._prefix, this._form).attr("src", _default_photo);
    }
});

//------------------------------------------ Limpieza
limpieza_personas=(id)=>{
    id = id || "#form-"+_path_controller_persona_natural;

    $(id+" span.error_text_o_o").addClass("d-none");
    $(id+" :input").removeClass("is-invalid");
}

function cargar_foto_persona(f, tag_img) {
    id      = $(f).attr("id");
    tag_img = tag_img || id;
    //console.log(tag_img, _prefix_persona);
    let imagenAR = document.getElementById("file"+_prefix_persona_natural);
    if (imagenAR.files.length != 0 && imagenAR.files[0].type.match(/image.*/)) {
		let lecimg = new FileReader();
        let div_custom = $(f).closest("div.custom-file");
            div_custom.find("label#custom-foto"+_prefix_persona_natural+"[for='file']").html(imagenAR.files[0].name);
		lecimg.onload = function(e) {
			var img = document.getElementById(tag_img+_prefix_persona_natural);

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
