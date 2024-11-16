if (typeof _prefix_ubigeo_ === 'undefined')
    _prefix_ubigeo_ = "";
var ubigeo = {
	selector: "#modal-ubigeo"+_prefix_ubigeo_ // selector, ID div modal
	,idubigeo: false // id del ubigeo a seleccionar
	,onCancel: $.noop // callback when click button cancel
	,onSave: $.noop // callback button save

	,_clearfix: function(idubigeo) {
		idubigeo = String(idubigeo).replace(/\s+/g, "");
		if(/\d{6}/.test(idubigeo)) {
			return idubigeo;
		}
		return false;
	}

	,_get_id_if_set: function(rel) {
		if(this.idubigeo === false)
			return false;

		if(rel == "departamento") {
			return String(this.idubigeo).substr(0, 2) + "0000";
		}
		else if (rel == "provincia") {
			return String(this.idubigeo).substr(0, 4) + "00";
		}
		else if (rel == "distrito") {
			return this.idubigeo;
		}

		return false;
	}

	,select: function(rel) {
		var sel = this._get_id_if_set(rel);
		if(sel !== false)
			$("select.idubigeo_temp_modal[data-name='"+rel+"']", this.selector).val( sel );
	}

	,reload: function(self, callback) {
        str_data = 'cod_dpto='+$('select.idubigeo_temp_modal[data-name="departamento"] option:selected').attr("data-dpto")??null;
        str_data+= '&cod_prov='+$('select.idubigeo_temp_modal[data-name="provincia"] option:selected').attr("data-prov")??null;
        $.ajax({
            url: route('ubigeo.'+self.data("reload")),
            type: 'GET',
            data: str_data,
            beforeSend: function() {
                loading();
            },
            success: function(arr){
                var options = '';
                if(arr.length) {
                    for(var i in arr) {
                        options += '<option value="'+arr[i].id_ubigeo+'" data-dpto="'+arr[i].cod_dpto+'" data-prov="'+arr[i].cod_prov+'" >'+arr[i].nombre+'</option>';
                    }
                }
                $("select.idubigeo_temp_modal[data-name='"+self.data("reload")+"']", ubigeo.selector).html( options );
                if(ubigeo.idubigeo !== false) {
                    ubigeo.select(self.data("reload"));
                }
                if($.isFunction(callback)) {
                    callback();
                }
            },
            complete: function () {
                loading("complete");
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
	}

	,set: function(idubigeo) {
		this.idubigeo = this._clearfix(idubigeo);
		if(this.idubigeo !== false) {
			var self = $("select.idubigeo_temp_modal:first", this.selector);
			self.val( this._get_id_if_set(self.data("name")) );
			self.trigger("change");
		}
	}

	,show: function() {
        dialog.open(this.selector);
	}
	,close: function() {
        dialog.close(this.selector);
	}

	,cancel: function(callback) {
		this.onCancel = callback;
	}
	,ok: function(callback) {
		this.onSave = callback;
	}
};

dialog.create({
    selector: ubigeo.selector
    ,title: "Seleccione el Ubigeo "
    ,width: 'modal-sm'
    ,style: 'primary'
    ,closeOnEscape: true
    ,buttons: {
        Guardar: function(){
            if($("select.idubigeo_temp_modal", ubigeo.selector).required()) {
                if($.isFunction(ubigeo.onSave)) {
                    // preparamos data
                    var object = {};
                    var idubigeo = null;
                    if( $("select.idubigeo_temp_modal", ubigeo.selector).length ) {
                        $("select.idubigeo_temp_modal", ubigeo.selector).each(function() {
                            object[$(this).data("name")] = $("option:selected", this).text();
                            if($(this).val() != "") {
                                idubigeo = $(this).val();
                            }
                        });
                    }
                    object["idubigeo"] = idubigeo;

                    ubigeo.onSave(object);
                }

                ubigeo.close();
            }
        },
        Cancelar: function(){
            if($.isFunction(ubigeo.onCancel)) {
                ubigeo.onCancel();
            }
            ubigeo.close();
        }
    }
    ,close: function() {

    }
});

$("select.idubigeo_temp_modal", ubigeo.selector).on("change", function() {
	var rel = $.trim($(this).data("reload"));
	if(rel != "") {
        if(rel=="provincia"){
            $("select.idubigeo_temp_modal[data-name='provincia']", ubigeo.selector).html("<option value='' data-prov=''></option>");
            $("select.idubigeo_temp_modal[data-name='distrito' ]", ubigeo.selector).html("<option value=''></option>");
        }else if(rel=="distrito"){
            $("select.idubigeo_temp_modal[data-name='distrito']", ubigeo.selector).html("<option value=''></option>");
        }
		ubigeo.reload($(this), function() {
			$("select.idubigeo_temp_modal[data-name='"+rel+"']", ubigeo.selector).trigger("change");
		});
	}
});

if(default_ubigeo)
    $("select.idubigeo_temp_modal").val(default_ubigeo).trigger("change");
