dialog.create({
    selector: "#form-"+_path_controller_actividad_institucional
    ,title: "Registrar "+_name_module_actividad_institucional
    ,width: 'modal-md'
    ,style: 'primary'
    ,closeOnEscape: true
    ,buttons: {
        Guardar: function(){
            form.get(_path_controller_actividad_institucional).guardar();
        },
        Cancelar: function(){
            form.get(_path_controller_actividad_institucional).cancelar();
        }
    }
    ,close: function() {
        form.get(_path_controller_actividad_institucional).reset();
    }
});

form.register(_path_controller_actividad_institucional, {
    _prefix: _prefix_actividad_institucional,
	_form: "#form-"+_path_controller_actividad_institucional,

    nuevo: function() {
        limpieza_actividad_institucional(this._form);

        dialog.open(this._form);
    },
    cancelar: function() {
		dialog.close(this._form);
	},
    editar: function(id) {
		var $self = this;
        $.ajax({
			url:route(_path_controller_actividad_institucional+'.edit',id),
			type:'GET',
            beforeSend: function() {
                loading();
                dialog.open($self._form);
            },
			success: function(response) {
                limpieza_actividad_institucional($self._form);

                $.each(response, function(k, v) {
					$("#"+k, $self._form).val(v);
				});

                setGaleria(response.galeria);
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
                    url:route(_path_controller_actividad_institucional+'.destroy',id),
                    type:'DELETE',
                    beforeSend: function() {
                        loading();
                    },
                    success: function(response) {
                        toastr.success('Datos grabados correctamente.','Notificación '+_name_module_actividad_institucional);
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
            $("ul#lista_galeria li").each(function(k, v){
				post_data+= "&galeria_bien["+k+"][codgaleria_actividad_institucional]="+$(this).attr("key-galeria");
				post_data+= "&galeria_bien["+k+"][imagen]="+$(this).attr("key-imagen");
			});
            $.ajax({
                url: route(_path_controller_actividad_institucional+'.store'),
                type: 'POST',
                data: post_data,
                beforeSend: function() {
                    loading();
                },
                success: function(response){
                    toastr.success('Datos grabados correctamente.','Notificación '+_name_module_actividad_institucional);
                    $self.callback(response);
                    dialog.close($self._form);
                },
                complete: function () {
                    loading("complete");
                },
                error: function(e){
                    if(e.status==422){ //Errores de Validacion
                        limpieza_actividad_institucional();

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
		grilla.reload(_name_tabla_actividad_institucional);
	},
    reset: function() {
        $(":input", this._form).val("");

        $("label[for='file']").html("Cargar Imagen");
        $("ul#lista_galeria li").remove();
        limpieza_actividad_institucional();
    }
});

limpieza_actividad_institucional=(id)=>{
    id = id || "#form-"+_path_controller_actividad_institucional;

    $(id+" span.error_text_o_o").addClass("d-none");
    $(id+" input").removeClass("is-invalid");
}

function cargar_imagen(f){
    id      = $(f).attr("id");

    let imagenAR = document.getElementById("file");
    if (imagenAR.files.length != 0 && imagenAR.files[0].type.match(/image.*/)) {
        let lecimg = new FileReader();
        let div_custom = $(f).closest("div.custom-file");
            div_custom.find("label[for='file']").html(imagenAR.files[0].name);
        lecimg.onload = function(e) {
            console.log("Upload imagen");
            upload_imagen();
        };
        lecimg.onerror = function(e) {
            console.log("Error al leer la imagen", e);
        };
        lecimg.readAsDataURL(imagenAR.files[0]);
    } else {
        console.log("Debe seleccionar una imagen");
    }
}

function upload_imagen(){
    var formUpload = new FormData($('#form-'+_path_controller_actividad_institucional)[0]);
    formUpload.append('file', $('#file')[0].files[0]);

    $.ajax({
        url: route(_path_controller_actividad_institucional+'.upload_file'),
        type: 'POST',
        cache       : false,
        contentType : false,
        processData : false,
        data: formUpload,
        beforeSend: function() {
            loading();
        },
        success: function(response){
            if(response.filename){
                arrGaleria = [];
                data = {codgaleria_actividad_institucional: ""
                        , imagen: response.filename
                        , url_imagen: response.url_imagen
                        };
                arrGaleria.push(data);
                setGaleria(arrGaleria);
            }
            $("label[for='file']").html("Cargar Imagen");
        },
        complete: function () {
            loading("complete");
        },
        error: function(e){
            if(e.status==422){ //Errores de Validacion
                limpieza_actividad_institucional();

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

function setGaleria(row){
	$(row).each(function(k,v){
		galeria_fotos(v);
	});
}

function galeria_fotos(val){
	_ul = "<li class='list-group-item' key-galeria='"+val['codgaleria_actividad_institucional']+"' key-imagen='"+val['imagen']+"' >";
	_ul+= "	<center>";
	_ul+= "		<img class='card-img-top' style='width:50%;' src='"+val['url_imagen']+"'>";
	_ul+= "	</center>";
	_ul+= "	<button class='btn btn-block btn-xs btn-danger mr-2 mt-1 btn-del' type='button'><i class='fa fa-trash'></i></button>";
	_ul+= "</li>";

	$("ul#lista_galeria").append(_ul);
}
