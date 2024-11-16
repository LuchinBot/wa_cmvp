dialog.create({
    selector: "#form-"+_path_controller_bolsa_trabajo
    ,title: "Registrar "+_name_module_bolsa_trabajo
    ,width: 'modal-md'
    ,style: 'primary'
    ,closeOnEscape: true
    ,buttons: {
        Guardar: function(){
            form.get(_path_controller_bolsa_trabajo).guardar();
        },
        Cancelar: function(){
            form.get(_path_controller_bolsa_trabajo).cancelar();
        }
    }
    ,close: function() {
        form.get(_path_controller_bolsa_trabajo).reset();
    }
});

form.register(_path_controller_bolsa_trabajo, {
    _prefix: _prefix_bolsa_trabajo,
	_form: "#form-"+_path_controller_bolsa_trabajo,

    nuevo: function() {
        limpieza_bolsa_trabajo(this._form);

        dialog.open(this._form);
    },
    cancelar: function() {
		dialog.close(this._form);
	},
    editar: function(id) {
		var $self = this;
        $.ajax({
			url:route(_path_controller_bolsa_trabajo+'.edit',id),
			type:'GET',
            beforeSend: function() {
                loading();
                dialog.open($self._form);
            },
			success: function(response) {
                limpieza_bolsa_trabajo($self._form);

                $.each(response, function(k, v) {
					$("#"+k, $self._form).val(v);
				});
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
                    url:route(_path_controller_bolsa_trabajo+'.destroy',id),
                    type:'DELETE',
                    beforeSend: function() {
                        loading();
                    },
                    success: function(response) {
                        toastr.success('Datos grabados correctamente.','Notificación '+_name_module_bolsa_trabajo);
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

            $.ajax({
                url: route(_path_controller_bolsa_trabajo+'.store'),
                type: 'POST',
                data: post_data,
                beforeSend: function() {
                    loading();
                },
                success: function(response){
                    toastr.success('Datos grabados correctamente.','Notificación '+_name_module_bolsa_trabajo);
                    $self.callback(response);
                    dialog.close($self._form);
                },
                complete: function () {
                    loading("complete");
                },
                error: function(e){
                    if(e.status==422){ //Errores de Validacion
                        limpieza_bolsa_trabajo();

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
		grilla.reload(_name_tabla_bolsa_trabajo);
	},
    reset: function() {
        $(":input", this._form).val("");
        limpieza_bolsa_trabajo();
    }
});

limpieza_bolsa_trabajo=(id)=>{
    id = id || "#form-"+_path_controller_bolsa_trabajo;

    $(id+" span.error_text_o_o").addClass("d-none");
    $(id+" input").removeClass("is-invalid");
}
