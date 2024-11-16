let default_tr = "<tr><td colspan='4'><center><i>Sin requisitos</i></center></td></tr>";

$(function(){
    $("table#table_requisitos tbody").html(default_tr);
});

$(document).on("click", "a.select_icon", function() {
    var icon = $(this).data("icono");
    setIcon(icon);
    $("#icono"+_prefix_tramite).val(icon);
});

$(document).on("blur", "#icono"+_prefix_tramite, function() {
    setIcon($(this).val());
});

$(document).on("click", "#btn-registrar-requisito"+_prefix_tramite, function(e){
    e.preventDefault();

    form.get("requisitos").nuevo();
});

$(document).on("click", "#btn-agregar-requisito"+_prefix_tramite, function(e){
    e.preventDefault();

    let id_requisito = $("#codrequisito_add"+_prefix_tramite).val();
    if($("#codrequisito_add"+_prefix_tramite).required()){
        if($("#table_requisitos tbody tr[index='"+id_requisito+"']").length>0){
            toastr.warning("Ya existe el requisito en la lista");
            return;
        }

        let arrReq = [];
        dato = {
            codrequisito_tramite:""
            , codrequisito: id_requisito
            , requisito: $("#codrequisito_add"+_prefix_tramite+" option:selected").text()
            , nota:""
            , archivo: ""
        };
        arrReq.push(dato);
        setRequisitos(arrReq);
    }
});

$(document).on("click", ".btn-add-observacion", function(e){
    e.preventDefault();
    let tr_nota = $(this).closest("tr");
    let observ  = tr_nota.find(".nota_requisito").html();

    $("#table_requisitos tbody tr").removeClass("tr_select");
    tr_nota.addClass("tr_select");

    $("#observacion"+_prefix_tramite).val(observ);
    $("#requisito_seleccionado"+_prefix_tramite).html(tr_nota.find(".input_requisito").html());

    dialog.open("#form-nota");
});

$(document).on("click",".btn-delete-file",function(e){
    e.preventDefault();

    var tr  = $(this).closest("tr");
    if(!tr.attr("data-archivo")){
        toastr.warning("Aún no sube su archivo");
        return;
    }
    ventana.confirm({
        titulo: "Advertencia"
        ,mensaje: "Desea borrar el archivo del requisito "+tr.find(".input_requisito").val()+"?"
        ,textoBotonAceptar: "Si"
        ,textoBotonCancelar: "Cancelar"
    }, function(ok) {
        if(ok.value) {
            tr.attr("data-archivo", "");
            tr.find("label[for='file']").html("");
        }
    });
});

$(document).on("click",".btn-delete-requisito",function(e){
    e.preventDefault();

    var tr  = $(this).closest("tr");
    ventana.confirm({
        titulo: "Advertencia"
        ,mensaje: "Desea borrar el requisito "+tr.find(".input_requisito").val()+"?"
        ,textoBotonAceptar: "Si"
        ,textoBotonCancelar: "Cancelar"
    }, function(ok) {
        if(ok.value) {
            tr.remove();
            order_detalle_item();

            if($("table#table_requisitos tbody tr[index]").length<1)
                $("table#table_requisitos tbody").html(default_tr);
        }
    });
});

dialog.create({
    selector: "#form-"+_path_controller_tramite
    ,title: "Registrar "+_name_module_tramite
    ,width: 'modal-md'
    ,style: 'primary'
    ,closeOnEscape: true
    ,buttons: {
        Guardar: function(){
            form.get(_path_controller_tramite).guardar();
        },
        Cancelar: function(){
            form.get(_path_controller_tramite).cancelar();
        }
    }
    ,close: function() {
        form.get(_path_controller_tramite).reset();
    }
});

dialog.create({
    selector: "#form-nota"
    ,title: "Añadir Nota "
    ,width: 'modal-sm'
    ,style: 'primary'
    ,closeOnEscape: true
    ,buttons: {
        Guardar: function(){
            let tr_select = $("table#table_requisitos tbody tr.tr_select");
            tr_select.find(".nota_requisito").html($("#observacion"+_prefix_tramite).val());

            dialog.close("#form-nota");
        },
        Cancelar: function(){
            dialog.close("#form-nota");
        }
    }
    ,close: function() {
        $(":input", "#form-nota").val("");
        $("#requisito_seleccionado"+_prefix_tramite).html("");
    }
});

form.register(_path_controller_tramite, {
    _prefix: _prefix_tramite,
	_form: "#form-"+_path_controller_tramite,

    nuevo: function() {
        limpieza_tramite(this._form);
        dialog.open(this._form);
    },
    cancelar: function() {
		dialog.close(this._form);
	},
    editar: function(id) {
		var $self = this;
        $.ajax({
			url:route(_path_controller_tramite+'.edit',id),
			type:'GET',
            beforeSend: function() {
                loading();
                dialog.open($self._form);
            },
			success: function(response) {
                limpieza_tramite($self._form);
                $.each(response, function(k, v) {
					$("#"+k, $self._form).val(v);
				});

                setIcon(response.icono);
                setRequisitos(response.requisitos);
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
                    url:route(_path_controller_tramite+'.destroy',id),
                    type:'DELETE',
                    beforeSend: function() {
                        loading();
                    },
                    success: function(response) {
                        toastr.success('Datos grabados correctamente.','Notificación '+_name_module_tramite);
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
			var post_data = $($self._form).serialize();
            $("table#table_requisitos tbody tr").each(function(k, v){
                if($(this).attr("index")){
                    post_data+="&requisitos["+k+"][codrequisito_tramite]="+$(this).attr("data-detalle");
                    post_data+="&requisitos["+k+"][codrequisito]="+$(this).attr("index");
                    post_data+="&requisitos["+k+"][archivo]="+$(this).attr("data-archivo");
                    post_data+="&requisitos["+k+"][nota]="+$.trim($(this).find(".nota_requisito").html());
                }
            });

            $.ajax({
                url: route(_path_controller_tramite+'.store'),
                type: 'POST',
                data: post_data,
                beforeSend: function() {
                    loading();
                },
                success: function(response){
                    toastr.success('Datos grabados correctamente.','Notificación '+_name_module_tramite);
                    $self.callback(response);
                    dialog.close($self._form);
                },
                complete: function () {
                    loading("complete");
                },
                error: function(e){
                    if(e.status==422){ //Errores de Validacion
                        limpieza_tramite();

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
    callback: function() {
		grilla.reload(_name_tabla_tramite);
	},
    reset: function() {
        $(":input", this._form).val("");
        $('.nav-tabs a[href="#custom-tabs-01"]').tab('show');
        $("table#table_requisitos tbody").html(default_tr);

        setIcon();
    }
});

setIcon = (icon)=>{
	$("#icono_preview").html('<i style="font-size:8px;" class="fa '+icon+'"></i>');
}

function setRequisitos(row){
    $(row).each(function(k,v){
        detalle_requisitos(k, v);
    });
}

function detalle_requisitos(i, val){
    if($("table#table_requisitos tbody tr[index]").length<1){
        $("table#table_requisitos tbody tr").remove();
    }

    item = $("table#table_requisitos tbody tr[index]").length;

    _table = "<tr index='"+val['codrequisito']+"' data-detalle='"+val["codrequisito_tramite"]+"' data-archivo='"+(val['archivo']??"")+"' >";
    _table+= "  <td class='xitem'>"+(item+1)+"</td>";
    _table+="   <td>";
    _table+="       <div class='input-group input-group-xs'>";
    _table+="           <textarea class='form-control input-xs input_requisito' rows='1' readonly placeholder='Ficha de inscripción'>"+(val["requisito"]??"")+"</textarea>";
    _table+="           <div class='input-group-prepend'>";
    _table+="               <button class='btn btn-xs btn-primary btn-add-observacion' title='Añadir observación' type='button'>";
    _table+="                   <i class='fa fa-file'></i>";
    _table+="               </button>";
    _table+="           </div>";
    _table+="       </div>";
    _table+="       <i class='text-success nota_requisito' style='font-size:11px;'>"+(val["nota"]??"")+"</i>";
    _table+="    </td>";
    _table+="   <td>";
    _table+="       <div class='input-group input-group-xs'>";
    _table+="           <div class='custom-file' style='height:22px;'>";
    _table+="               <input type='file' class='custom-file-input' accept='.pdf' id='file_"+item+"'  onchange='cargar_archivo(this)' >";
    _table+="               <label class='custom-file-label custom-file-label-xs' for='file'>"+(val["archivo"]??"")+"</label>";
    _table+="           </div>";
    _table+="           <div class='input-group-prepend'>";
    _table+="               <button class='btn btn-xs btn-default btn-delete-file' title='Eliminar archivo subido' type='button'>";
    _table+="                   <i class='fa fa-times-circle'></i>";
    _table+="               </button>";
    _table+="           </div>";
    _table+="       </div>";
    _table+="    </td>";
    _table+="   <td>";
    _table+="       <a href='javascript:void(0);' title='Eliminar requisito' class='btn btn-delete-requisito btn-outline-danger btn-xs btn-icon hover-effect-dot waves-effect waves-themed'><i class='fa fa-trash'></i></a>";
    _table+="    </td>";
    _table+="</tr>";

    $("table#table_requisitos tbody").append(_table);
}

function cargar_archivo(f){
    id      = $(f).attr("id");
    tr      = $(f).closest("tr");
    let fileBin = document.getElementById(id);
    if (fileBin.files.length != 0) {

        let lecimg = new FileReader();
        lecimg.onload = function(e) {
            let post_data = new FormData();
            post_data.append("file", fileBin.files[0]);
            $.ajax({
                url: route(_path_controller_tramite+'.subir_archivo'),
                type: 'POST',
                cache       : false,
                contentType : false,
                processData : false,
                data: post_data,
                beforeSend: function() {
                    loading("", $("table#table_requisitos tbody"));
                },
                success: function(response){
                    let div_custom = $(f).closest("div.custom-file");
                    div_custom.find("label[for='file']").html(response.archivo);
                    tr.attr("data-archivo", response.archivo);
                },
                complete: function () {
                    loading( 'complete', $("table#table_requisitos tbody") );
                },
                error: function(e){
                    if(e.status==422){ //Errores de Validacion
                        $.each(e.responseJSON.errors, function(i, item) {
                            toastr.warning(item);
                        });
                    }else if(e.status==419){
                        toastr.error("La sesión ya expiró, por favor cierre sesión y vuelva a ingresar");
                    }else if(e.status==500){
                        toastr.error((e.responseJSON.message)??'Hubo problemas internos, por favor comunicate de inmediato con SOPORTE');
                    }
                }
            });
        };
        lecimg.onerror = function(e) {
            console.log("Error al leer el archivo", e);
        };
        console.log(fileBin.files[0]);
        lecimg.readAsDataURL(fileBin.files[0]);
    } else {
        console.log("Debe seleccionar una imagen");
    }
}

limpieza_tramite=(id)=>{
    id = id || "#form-"+_path_controller_tramite;

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

/**
 * Extend persona
*/
form.register("requisitos", {
    callback: function(response) {
        $("#codrequisito_add"+_prefix_tramite).append("<option value='"+response.codrequisito+"'>"+response.descripcion+"</option>");
        $("#codrequisito_add"+_prefix_tramite).val(response.codrequisito);
    }
});
