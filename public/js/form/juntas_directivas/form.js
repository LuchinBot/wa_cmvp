let default_tr = "<tr><td colspan='4'><center><i>Sin integrantes</i></center></td></tr>";

$(function(){
    $("table#table_integrantes tbody").html(default_tr);
    $("#nro_doc_identidad_integrante"+_prefix_junta_directiva).numero_entero();
});

$(document).on("keydown", "#nro_doc_identidad_integrante"+_prefix_junta_directiva, function(e) {
    if(e.keyCode==13){
        //F1
        e.preventDefault();
	    e.stopPropagation();
        $("#btn-search-integrante"+_prefix_junta_directiva).trigger("click");
    }
});

input.autocomplete({
	selector: "#nombres_integrante"+_prefix_junta_directiva
	,controller: "colegiados"
	,method: "autocomplete"
	,label: "<strong>[label]</strong>"
	,value: "[label]"
	,highlight: true
    ,show_empty_msg: true
    ,show_new_item:false
	,onSelect: function(item) {
        $("#codcolegiado"+_prefix_junta_directiva).val(item.id);
        console.log(item);
        setTimeout(()=>{
            $("#nombres_integrante"+_prefix_junta_directiva).val(item.apellido_paterno+" "+item.apellido_materno+", "+item.nombres);
            $("#nro_doc_identidad_integrante"+_prefix_junta_directiva).val(item.numero_documento_identidad);
        }, 200);
	}
});

$(document).on("click", "#btn-add-integrante"+_prefix_junta_directiva, function(e){
    e.preventDefault();

    let id_integrante = $("#codcolegiado"+_prefix_junta_directiva).val();
    if($("#codcolegiado"+_prefix_junta_directiva).required()){
        if($("#table_integrantes tbody tr[index='"+id_integrante+"']").length>0){
            toastr.warning("Ya existe el integrante en la lista");
            return;
        }

        let arrReq = [];
        dato = {
            codintegrante_junta_directiva:""
            , codcolegiado: id_integrante
            , integrante: $("#nombres_integrante"+_prefix_junta_directiva).val()
            , dni_integrante: $("#nro_doc_identidad_integrante"+_prefix_junta_directiva).val()
            , codcargo: ""
        };
        arrReq.push(dato);
        setJuntaDirectiva(arrReq);

        $("#codcolegiado"+_prefix_junta_directiva).val("");
        $("#nro_doc_identidad_integrante"+_prefix_junta_directiva).val("");
        $("#nombres_integrante"+_prefix_junta_directiva).val("").focus();
        return;
    }

    toastr.warning("Debe ingresar un integrante");
});

$(document).on("click",".btn-delete",function(e){
    e.preventDefault();

    var tr  = $(this).closest("tr");
    ventana.confirm({
        titulo: "Advertencia"
        ,mensaje: "Desea borrar al integrante"
        ,textoBotonAceptar: "Si"
        ,textoBotonCancelar: "Cancelar"
    }, function(ok) {
        if(ok.value) {
            tr.remove();
            order_detalle_item();

            if($("table#table_integrantes tbody tr[index]").length<1)
                $("table#table_integrantes tbody").html(default_tr);
        }
    });
});

dialog.create({
    selector: "#form-"+_path_controller_junta_directiva
    ,title: "Registrar "+_name_module_junta_directiva
    ,width: 'modal-md'
    ,style: 'primary'
    ,closeOnEscape: true
    ,buttons: {
        Guardar: function(){
            form.get(_path_controller_junta_directiva).guardar();
        },
        Cancelar: function(){
            form.get(_path_controller_junta_directiva).cancelar();
        }
    }
    ,close: function() {
        form.get(_path_controller_junta_directiva).reset();
    }
});

form.register(_path_controller_junta_directiva, {
    _prefix: _prefix_junta_directiva,
	_form: "#form-"+_path_controller_junta_directiva,

    nuevo: function() {
        limpieza_junta_directiva(this._form);

        dialog.open(this._form);
    },
    cancelar: function() {
		dialog.close(this._form);
	},
    editar: function(id) {
		var $self = this;
        $.ajax({
			url:route(_path_controller_junta_directiva+'.edit',id),
			type:'GET',
            beforeSend: function() {
                loading();
                dialog.open($self._form);
            },
			success: function(response) {
                limpieza_junta_directiva($self._form);

                $.each(response, function(k, v) {
					$("#"+k, $self._form).val(v);
				});

                setJuntaDirectiva(response.integrantes_junta);
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
                    url:route(_path_controller_junta_directiva+'.destroy',id),
                    type:'DELETE',
                    beforeSend: function() {
                        loading();
                    },
                    success: function(response) {
                        toastr.success('Datos grabados correctamente.','Notificación '+_name_module_junta_directiva);
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
            $("table#table_integrantes tbody tr").each(function(k, v){
                if($(this).attr("index")){
                    post_data+="&integrantes["+k+"][codintegrante_junta_directiva]="+$(this).attr("data-detalle");
                    post_data+="&integrantes["+k+"][codcolegiado]="+$(this).attr("index");
                    post_data+="&integrantes["+k+"][codcargo]="+$(this).find(".codcargo").val();
                }
            });
            $.ajax({
                url: route(_path_controller_junta_directiva+'.store'),
                type: 'POST',
                data: post_data,
                beforeSend: function() {
                    loading();
                },
                success: function(response){
                    toastr.success('Datos grabados correctamente.','Notificación '+_name_module_junta_directiva);
                    $self.callback(response);
                    dialog.close($self._form);
                },
                complete: function () {
                    loading("complete");
                },
                error: function(e){
                    if(e.status==422){ //Errores de Validacion
                        limpieza_junta_directiva();

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
		grilla.reload(_name_tabla_junta_directiva);
	},
    reset: function() {
        $(":input", this._form).val("");

        $("table#table_integrantes tbody").html(default_tr);
        limpieza_junta_directiva();
    }
});

limpieza_junta_directiva=(id)=>{
    id = id || "#form-"+_path_controller_junta_directiva;

    $(id+" span.error_text_o_o").addClass("d-none");
    $(id+" input").removeClass("is-invalid");
}

function setJuntaDirectiva(row){
    $(row).each(function(k,v){
        junta_directiva(k, v);
    });
}

function junta_directiva(i, val){
    if($("table#table_integrantes tbody tr[index]").length<1){
        $("table#table_integrantes tbody tr").remove();
    }

    item = $("table#table_integrantes tbody tr[index]").length;

    _table = "<tr index='"+val['codcolegiado']+"' data-detalle='"+val["codintegrante_junta_directiva"]+"' >";
    _table+= "  <td class='xitem'>"+(item+1)+"</td>";
    _table+="   <td>"+val["dni_integrante"]+" - "+val["integrante"]+"</td>";
    _table+="   <td>";
    _table+="       <select class='form-control input-xs codcargo'>";
    $(_select_cargos_junta_directiva).each(function(k, v){
        _selected = "";
        if(val["codcargo"]==v["codcargo"])
            _selected = "selected";
        _table+="       <option value="+v["codcargo"]+" "+_selected+" >"+v["descripcion"]+"</option>";
    });
    _table+="       </select>";
    _table+="    </td>";
    _table+="   <td>";
    _table+="       <a href='javascript:void(0);' title='Eliminar Integrante' class='btn btn-delete btn-outline-danger btn-xs btn-icon hover-effect-dot waves-effect waves-themed'><i class='fa fa-trash'></i></a>";
    _table+="    </td>";
    _table+="</tr>";

    $("table#table_integrantes tbody").append(_table);
}

function order_detalle_item(){
	xx = 1;
	$(".xitem").each(function(x,y){
		$(this).html(xx);
		xx++;
	});
}
