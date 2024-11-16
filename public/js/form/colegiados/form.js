let default_tr = "<tr><td colspan='3'><center><i>Sin especialidad</i></center></td></tr>";

$(function(){
    $("table#table_requisitos tbody").html(default_tr);
    $("#table_especialidad"+_prefix_colegiado+",#nro_doc_colegiado"+_prefix_colegiado).numero_entero();
});

dialog.create({
    selector: "#form-"+_path_controller_colegiado
    ,title: "Registrar "+_name_module_colegiado
    ,width: 'modal-md'
    ,style: 'primary'
    ,closeOnEscape: true
    ,buttons: {
        Guardar: function(){
            form.get(_path_controller_colegiado).guardar();
        },
        Cancelar: function(){
            form.get(_path_controller_colegiado).cancelar();
        }
    }
    ,close: function() {
        form.get(_path_controller_colegiado).reset();
    }
});

$(document).on("keydown", "#nro_doc_colegiado"+_prefix_colegiado, function(e) {
    if(e.keyCode==13){
        //F1
        e.preventDefault();
	    e.stopPropagation();
        $("#btn-search-persona_natural"+_prefix_colegiado).trigger("click");
    }
});

$(document).on("click", "#btn-search-persona_natural"+_prefix_colegiado, function(e){
    e.preventDefault();

    if($("#nro_doc_colegiado"+_prefix_colegiado).required()){
        $.ajax({
            url:route('persona_natural.search_internal'),
            type:'POST',
            data:"codtipo_documento_identidad="+$("#codtipo_doc_ident"+_prefix_colegiado).val()+"&numero_documento_identidad="+$("#nro_doc_colegiado"+_prefix_colegiado).val(),
            beforeSend: function() {
                loading();

            },
            success: function(response) {
                limpieza_colegiado();

                if(response && response.codpersona_natural){
                    $("#codpersona_natural"+_prefix_colegiado).val(response.codpersona_natural);
                    $("#nombres_colegiado"+_prefix_colegiado).val(response.nombre_completo);
                }else{
                    $(".numero_documento_identidad").removeClass("d-none").html("No se encontró registros");
                }
            },
            complete: function () {
                loading("complete");
            },
            error: function(e) {
                if(e.status==422){
                    $.each(e.responseJSON.errors, function(i, item) {
                        $('#'+i).addClass('is-invalid');
                        $('.'+i).removeClass('d-none');
                        if($('.'+i).length)
                            $('.'+i).html(item);
                        else
                            toastr.warning(item);
                    });
                }
                else if(e.status==419){
                    toastr.error("La sesión ya expiró, por favor cierre sesión y vuelva a ingresar");
                }else if(e.status==500){
                    toastr.error((e.responseJSON.message)??'Hubo problemas internos, por favor comunicate de inmediato con SOPORTE');
                }
            }
        });
    }
});

$(document).on("click", "#btn-edit-persona_natural"+_prefix_colegiado, function(e){
    e.preventDefault();

    let id_persona = $("#codpersona_natural"+_prefix_colegiado).val();
    if(id_persona==""){
        toastr.warning("Busque y seleccione una persona natural para poder editar", '', {
            closeButton: true
        });
        $("#persona_natural_user"+_prefix_colegiado).focus();
        return;
    }
    form.get("persona_natural").editar(id_persona);
});

$(document).on("click", "#btn-register-persona_natural"+_prefix_colegiado, function(e){
    e.preventDefault();

    form.get("persona_natural").nuevo();
});

$(document).on("click", "#btn-agregar-especialidad"+_prefix_colegiado, function(e){
    e.preventDefault();

    let id_especialidad = $("#codespecialidad_add"+_prefix_colegiado).val();
    if($("#codespecialidad_add"+_prefix_colegiado).required()){
        if($("#table_especialidad tbody tr[index='"+id_especialidad+"']").length>0){
            toastr.warning("Ya existe la especialidad en la lista");
            return;
        }

        let arrReq = [];
        dato = {
            codcolegiado_especialidad:""
            , codespecialidad: id_especialidad
            , especialidad_colegiado: $("#codespecialidad_add"+_prefix_colegiado+" option:selected").text()
        };
        arrReq.push(dato);
        setEspecialides(arrReq);
    }
});

$(document).on("click",".btn-delete-especialidad",function(e){
    e.preventDefault();

    var tr  = $(this).closest("tr");
    ventana.confirm({
        titulo: "Advertencia"
        ,mensaje: "Desea borrar la especialidad?"
        ,textoBotonAceptar: "Si"
        ,textoBotonCancelar: "Cancelar"
    }, function(ok) {
        if(ok.value) {
            tr.remove();
            order_detalle_item();

            if($("table#table_especialidad tbody tr[index]").length<1)
                $("table#table_especialidad tbody").html(default_tr);
        }
    });
});

input.autocomplete({
	selector: "#nombres_vicedecano"+_prefix_colegiado
	,controller: "colegiados"
	,method: "autocomplete"
	,label: "[numero_documento_identidad] <strong>[label]</strong>"
	,value: "[label]"
	,highlight: true
    ,show_empty_msg: true
    ,show_new_item:false
	,onSelect: function(item) {
        $("#codcolegiado_vicedecano"+_prefix_colegiado).val(item.id);
        console.log(item);
        setTimeout(()=>{
            $("#nombres_vicedecano"+_prefix_colegiado).val(item.numero_documento_identidad+" - "+item.label);
        }, 200);
	}
});

form.register(_path_controller_colegiado, {
    _prefix: "",
	_form: "#form-"+_path_controller_colegiado,

    nuevo: function() {
        limpieza_colegiado(this._form);

        dialog.open(this._form);

        setTimeout(()=>{
            $("#nro_doc_colegiado"+_prefix_colegiado).focus();
        }, 200);
    },
    cancelar: function() {
		dialog.close(this._form);
	},
    editar: function(id) {
		var $self = this;
        $.ajax({
			url:route(_path_controller_colegiado+'.edit',id),
			type:'GET',
            beforeSend: function() {
                loading();
                dialog.open($self._form);
            },
			success: function(response) {
                limpieza_colegiado($self._form);

                $.each(response, function(k, v) {
					$("#"+k, $self._form).val(v);
				});

                $("#codtipo_doc_ident"+_prefix_colegiado).val(response.persona_natural.codtipo_documento_identidad);
                $("#nro_doc_colegiado"+_prefix_colegiado).val(response.persona_natural.numero_documento_identidad);
                $("#nombres_colegiado"+_prefix_colegiado).val(response.persona_natural.nombre_completo);

                $(".file_upload").html("<a href='"+response.url_cv+"' target='_blank'>"+response.curriculum_vitae+"</a>");

                setEspecialides(response.especialidad_colegiado);
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
                    url:route(_path_controller_colegiado+'.destroy',id),
                    type:'DELETE',
                    beforeSend: function() {
                        loading();
                    },
                    success: function(response) {
                        toastr.success('Datos grabados correctamente.','Notificación '+_name_module_colegiado);
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
            $("table#table_especialidad tbody tr").each(function(k, v){
                if($(this).attr("index")){
                    post_data.append("especialides["+k+"][codcolegiado_especialidad]", $(this).attr("data-detalle"))
                    post_data.append("especialides["+k+"][codespecialidad]", $(this).attr("index"))
                }
            });

            $.ajax({
                url: route(_path_controller_colegiado+'.store'),
                type: 'POST',
                data: post_data,
                cache       : false,
                contentType : false,
                processData : false,
                beforeSend: function() {
                    loading();
                },
                success: function(response){
                    toastr.success('Datos grabados correctamente.','Notificación '+_name_module_colegiado);
                    $self.callback(response);
                    dialog.close($self._form);
                },
                complete: function () {
                    loading("complete");
                },
                error: function(e){
                    if(e.status==422){ //Errores de Validacion
                        limpieza_colegiado();

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
		grilla.reload(_name_tabla_colegiado);
	},
    reset: function() {
        $(":input", this._form).val("");
        $("#codtipo_doc_ident", this._form).prop("selectedIndex", 0);
        $('.nav-tabs a[href="#custom-tabs-01"]').tab('show');
        $("table#table_especialidad tbody").html(default_tr);
        $(".file_upload").html("");
        limpieza_colegiado();
    }
});

function setEspecialides(row){
    $(row).each(function(k,v){
        detalle_especialidad(k, v);
    });
}

function detalle_especialidad(i, val){
    if($("table#table_especialidad tbody tr[index]").length<1){
        $("table#table_especialidad tbody tr").remove();
    }

    item = $("table#table_especialidad tbody tr[index]").length;

    _table = "<tr index='"+val['codespecialidad']+"' data-detalle='"+val["codcolegiado_especialidad"]+"'  >";
    _table+= "  <td class='xitem'>"+(item+1)+"</td>";
    _table+="   <td>"+val["especialidad_colegiado"]+"</td>";
    _table+="   <td>";
    _table+="       <a href='javascript:void(0);' title='Eliminar Especialidad' class='btn btn-delete-especialidad btn-outline-danger btn-xs btn-icon hover-effect-dot waves-effect waves-themed'><i class='fa fa-trash'></i></a>";
    _table+="    </td>";
    _table+="</tr>";

    $("table#table_especialidad tbody").append(_table);
}

limpieza_colegiado=(id)=>{
    id = id || "#form-"+_path_controller_colegiado;

    $(id+" span.error_text_o_o").addClass("d-none");
    $(id+" input").removeClass("is-invalid");
}

function order_detalle_item(){
	xx = 1;
	$(".xitem").each(function(x,y){
		$(this).html(xx);
		xx++;
	});
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

form.register("persona_natural", {
    callback: function(response) {
        $("#codpersona_natural"+_prefix_colegiado).val(response.codpersona_natural);
        $("#nombres_colegiado"+_prefix_colegiado).val(response.nombre_completo);
        $("#nro_doc_colegiado"+_prefix_colegiado).val(response.numero_documento_identidad);
    }
});
