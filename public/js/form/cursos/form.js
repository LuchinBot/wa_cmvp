let arr_check = ["con_certificado"];
let default_tr = "<tr><td colspan='4'><center><i>Sin requisitos</i></center></td></tr>";
$(function(){
   $("#con_certificado"+_prefix_curso).trigger("change");
   $("#nro_doc_identidad_participante"+_prefix_curso).numero_entero();
});

$('#descripcion'+_prefix_curso).summernote({
    placeholder: 'Escriba aqui la descripcion o detalles del curso',
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

$(document).on("change", "#con_certificado"+_prefix_curso, function(e){
    //e.preventDefault();

    if($(this).is(":checked")){
        $(".custom-plantilla").removeClass("readonly");
        $("#nombres_vicedecano"+_prefix_curso).prop("readonly", false);
        $("#nombres_director"+_prefix_curso).prop("readonly", false);
        $("#nro_doc_identidad_participante"+_prefix_curso).prop("readonly", false);
    }else{
        $(".custom-plantilla").addClass("readonly");
        $("#nombres_vicedecano"+_prefix_curso).prop("readonly", true);
        $("#nombres_director"+_prefix_curso).prop("readonly", true);
        $("#nro_doc_identidad_participante"+_prefix_curso).prop("readonly", true);
    }
});

$(document).on("keydown", "#nro_doc_identidad_participante"+_prefix_curso, function(e) {
    if(e.keyCode==13){
        //F1
        e.preventDefault();
	    e.stopPropagation();
        $("#btn-search-participante"+_prefix_curso).trigger("click");
    }
});

$(document).on("click",".btn-delete-participante",function(e){
    e.preventDefault();

    var tr  = $(this).closest("tr");
    ventana.confirm({
        titulo: "Advertencia"
        ,mensaje: "Desea borrar al participante "+tr.find("td:eq(2)").html()+"?"
        ,textoBotonAceptar: "Si"
        ,textoBotonCancelar: "Cancelar"
    }, function(ok) {
        if(ok.value) {
            tr.remove();
            order_detalle_item();

            if($("table#table_participantes tbody tr[index]").length<1)
                $("table#table_participantes tbody").html(default_tr);
        }
    });
});

input.autocomplete({
	selector: "#nombres_vicedecano"+_prefix_curso
	,controller: "colegiados"
	,method: "autocomplete"
	,label: "<strong>[label]</strong>"
	,value: "[label]"
	,highlight: true
    ,show_empty_msg: true
	,onSelect: function(item) {
		$("#codcolegiado_vicedecano").val(item.id);

        console.log(item);
        setTimeout(()=>{
            $("#nombres_vicedecano"+_prefix_curso).val(item.numero_documento_identidad+" - "+item.apellido_paterno+" "+item.apellido_materno+", "+item.nombres);
        }, 200);
	}
});

input.autocomplete({
	selector: "#nombres_director"+_prefix_curso
	,controller: "colegiados"
	,method: "autocomplete"
	,label: "<strong>[label]</strong>"
	,value: "[label]"
	,highlight: true
    ,show_empty_msg: true
	,onSelect: function(item) {
		$("#codcolegiado_director").val(item.id);

        console.log(item);
        setTimeout(()=>{
            $("#nombres_director"+_prefix_curso).val(item.numero_documento_identidad+" - "+item.apellido_paterno+" "+item.apellido_materno+", "+item.nombres);
        }, 200);
	}
});

$(document).on("click", "#btn-search-participante"+_prefix_curso, function(e){
    e.preventDefault();

    if($("#nro_doc_identidad_participante"+_prefix_curso).required()){
        $.ajax({
            url:route('persona_natural.search_all'),
            type:'POST',
            data:"codtipo_documento_identidad=1&numero_documento_identidad="+$("#nro_doc_identidad_participante"+_prefix_curso).val()+"&save=true",
            beforeSend: function() {
                loading();
            },
            success: function(response) {
                limpieza_curso();

                if(response && response.codpersona_natural){
                    $("#codparticipante"+_prefix_curso).val(response.codpersona_natural);
                    $("#nombres_participante"+_prefix_curso).val(response.nombre_completo);
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

$(document).on("click", "#btn-add-participante"+_prefix_curso, function(e){
    e.preventDefault();

    let id_participante = $("#codparticipante"+_prefix_curso).val();
    if($("#codparticipante"+_prefix_curso).required()){
        if($("#table_participantes tbody tr[index='"+id_participante+"']").length>0){
            toastr.warning("Ya existe en participante en la lista");
            return;
        }

        let arrReq = [];
        dato = {
            codparticipante_curso:""
            , codparticipante: id_participante
            , nombres_participante: $("#nombres_participante"+_prefix_curso).val()
            , dni_participante:$("#nro_doc_identidad_participante"+_prefix_curso).val()
        };
        arrReq.push(dato);
        setParticipantes(arrReq);
    }
});

dialog.create({
    selector: "#form-"+_path_controller_curso
    ,title: "Registrar "+_name_module_curso
    ,width: 'modal-lg'
    ,style: 'primary'
    ,closeOnEscape: true
    ,buttons: {
        Guardar: function(){
            form.get(_path_controller_curso).guardar();
        },
        Cancelar: function(){
            form.get(_path_controller_curso).cancelar();
        }
    }
    ,close: function() {
        form.get(_path_controller_curso).reset();
    }
});

form.register(_path_controller_curso, {
    _prefix: _prefix_curso,
	_form: "#form-"+_path_controller_curso,

    nuevo: function() {
        limpieza_curso(this._form);
        dialog.open(this._form);
    },
    cancelar: function() {
		dialog.close(this._form);
	},
    editar: function(id) {
		var $self = this;
        $.ajax({
			url:route(_path_controller_curso+'.edit',id),
			type:'GET',
            beforeSend: function() {
                loading();
                dialog.open($self._form);
            },
			success: function(response) {
                limpieza_curso($self._form);
                $.each(response, function(k, v) {
                    if(arr_check.indexOf(k)>=0)
						$("#"+k, $self._form).prop("checked", ((v=='S')?true:false));
					else
						$("#"+k, $self._form).val(v);
				});

                setParticipantes(response.participantes);

                $("#descripcion", $self._form).summernote('code', response.descripcion);
                $("#flayer_prev", $self._form).attr("src", response.url_flayer);
                $("#plantilla_prev", $self._form).attr("src", response.url_plantilla_certificado);

                if(response.vicedecano){
                    $("#nombres_vicedecano"+_prefix_curso).val(response.vicedecano.persona_natural.numero_documento_identidad+" - "+response.vicedecano.persona_natural.nombre_completo);
                }

                if(response.director){
                    $("#nombres_director"+_prefix_curso).val(response.director.persona_natural.numero_documento_identidad+" - "+response.director.persona_natural.nombre_completo);
                }

                $("#con_certificado"+_prefix_curso).trigger("change");
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
                    url:route(_path_controller_curso+'.destroy',id),
                    type:'DELETE',
                    beforeSend: function() {
                        loading();
                    },
                    success: function(response) {
                        toastr.success('Datos grabados correctamente.','Notificación '+_name_module_curso);
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
            $(arr_check).each(function(k, v){
                post_data.append(v, ($("#"+v).is(":checked")?"S":"N"));
			});

            $("table#table_participantes tbody tr").each(function(k, v){
                if($(this).attr("index")){
                    post_data.append("participantes["+k+"][codparticipante_curso]", $(this).attr("data-detalle"));
                    post_data.append("participantes["+k+"][codparticipante]", $(this).attr("index"));
                }
            });

            $.ajax({
                url: route(_path_controller_curso+'.store'),
                type: 'POST',
                data: post_data,
                cache       : false,
                contentType : false,
                processData : false,
                beforeSend: function() {
                    loading();
                },
                success: function(response){
                    toastr.success('Datos grabados correctamente.','Notificación '+_name_module_curso);
                    $self.callback(response);
                    dialog.close($self._form);
                },
                complete: function () {
                    loading("complete");
                },
                error: function(e){
                    if(e.status==422){ //Errores de Validacion
                        limpieza_curso();

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
		grilla.reload(_name_tabla_curso);
	},
    reset: function() {
        $(":input", this._form).val("");
        $('.nav-tabs a[href="#custom-tabs-01"]').tab('show');
        $("#flayer_prev", this._form).attr("src", _default_flayer_curso);
        $("#plantilla_prev", this._form).attr("src", _default_plantilla_curso);
        $("#descripcion", this._form).summernote('code', "<p><br></p>");
        $("table#table_participantes tbody").html(default_tr);
        $(arr_check).each(function(k, v){
			$("#"+v, this._form).prop("checked", false);
		});
        $("#con_certificado"+_prefix_curso).trigger("change");
    }
});

limpieza_curso=(id)=>{
    id = id || "#form-"+_path_controller_curso;

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

function cargar_plantilla(f, tag_img){
    if(!$("#con_certificado"+_prefix_curso).is(":checked")){
        toastr.warning("Este curso no está habilitado para cargar plantilla de certificado");
        return;
    }
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

function setParticipantes(row){
    $(row).each(function(k,v){
        detalle_participantes(k, v);
    });
}

function detalle_participantes(i, val){
    if($("table#table_participantes tbody tr[index]").length<1){
        $("table#table_participantes tbody tr").remove();
    }

    item = $("table#table_participantes tbody tr[index]").length;

    _table = "<tr index='"+val['codparticipante']+"' data-detalle='"+val["codparticipante_curso"]+"' >";
    _table+= "  <td class='xitem'>"+(item+1)+"</td>";
    _table+="   <td>"+val["dni_participante"]+"</td>";
    _table+="   <td>"+val["nombres_participante"]+"</td>";
    _table+="   <td>";
    _table+="       <a href='javascript:void(0);' title='Eliminar Participante' class='btn btn-delete-participante btn-outline-danger btn-xs btn-icon hover-effect-dot waves-effect waves-themed'><i class='fa fa-trash'></i></a>";
    _table+="    </td>";
    _table+="</tr>";

    $("table#table_participantes tbody").append(_table);
}
