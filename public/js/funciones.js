var efecto_carga = "bounce";
var ventana = function() {
	var propertiesDefault = {
        titulo: ''
        ,mensaje: ''
        ,tipo: 'info' // success, warning, error
        ,textoBoton: 'OK'
        ,textoBotonCancelar: 'Cancelar'
        ,textoBotonAceptar: 'Aceptar'
        ,cerrarConTeclaEscape: true
        ,cerrarAlConfirmar: true
		,botonCancelar: true
        ,placeholder: ""
		,timer: null // contador de tiempo
		,colorConfirm:"#DD6B55"
    };

	function alert(propiedades, callback) {
		var props = $.extend({}, propertiesDefault, propiedades);
		var opciones = {
			title: props.titulo
			,text: props.mensaje
			,type: props.tipo
			,confirmButtonText: props.textoBoton
			,html: false
			,allowEscapeKey: props.cerrarConTeclaEscape
		};

		if($.isFunction(callback)) {
			Swal.fire(opciones).then((result)=>{
				callback(true);
			});
		}
		else {
			Swal.fire(opciones);
		}
    }

    function confirm(propiedades, callback) {
		if(typeof propiedades.tipo == "undefined")
			propiedades.tipo = "warning";

        var props = $.extend({}, propertiesDefault, propiedades);

		var opciones = {
			title: props.titulo
			,text: props.mensaje
			,type: props.tipo
			,showCancelButton: true
			,confirmButtonColor: props.colorConfirm
			,confirmButtonText: props.textoBotonAceptar
			,cancelButtonText: props.textoBotonCancelar
			,html: false
			,allowEscapeKey: props.cerrarConTeclaEscape
			//,closeOnConfirm: props.cerrarAlConfirmar
			,timer:props.timer
		};

		if($.isFunction(callback)) {
			// Swal.fire(opciones, function(isConfirm) {
				// callback(isConfirm);
			// });
			Swal.fire(opciones).then((result)=>{
				callback(result);
			});
		}
		else {
			Swal.fire(opciones);
		}
    }

	function prompt(propiedades, callback) {
		propiedades.tipo = "input";
        var props = $.extend({}, propertiesDefault, propiedades);

		var opciones = {
			title: props.titulo
			,text: props.mensaje
			,icon: props.tipo
			,showCancelButton: props.botonCancelar
			,confirmButtonColor: "#DD6B55"
			,confirmButtonText: props.textoBotonAceptar
			,cancelButtonText: props.textoBotonCancelar
			,html: true
			,allowEscapeKey: props.cerrarConTeclaEscape
			,closeOnConfirm: false
			,inputPlaceholder: props.placeholder
			,animation: "slide-from-top"
		};

		if($.isFunction(callback)) {
			Swal.fire(opciones, function(isConfirm) {
				var bool = callback(isConfirm);
				if(bool === false) {
					return false;
				}
				swal.close();
			});
		}
		else {
			Swal.fire(opciones);
		}
    }

	return {alert: alert, confirm: confirm, prompt: prompt};
}();

// PARA LA GRILLA (DataTables)
var grilla = function() {
    /**
     * Obtener el ID real de la grilla
     */
    function _get_real_id(idobject) {
        if ($("table[realid='" + idobject + "']").length == 1) {
            var table = "dt-" + idobject;

            //if ($("table[realid='" + idobject + "']").hasClass("grilla_popup")) {
                //table += "_popup";
            //}

            var realId = "#" + table;

            if ($.fn.DataTable.isDataTable(realId)) {
                return table;
            } else {
                console.log(idobject + " podria no ser una instancia de DataTables");
            }
        } else {
            console.log("El objeto DOM no existe o existe mas de una instancia para " + idobject);
        }

        return false;
    }

    /**
     * Metodo para recargar la grilla
     * @param String idobject ID del objeto DOM de la tabla grilla.
     * @access public
     */
    function reload(idobject, bool) {
        var table = _get_real_id(idobject);
        if (typeof bool != 'boolean')
            bool = true;

        if (table !== false) {
            $("#" + table).DataTable().draw(bool);
            //$("#" + table).DataTable().ajax.reload(); //Tambien funciona
            //$("#"+table).DataTable().stateLoadCallback();
        }
    }

    /**
     * Metodo para indicar los filtros sobre la grilla
     */
    function set_filter(idobject, columna, simbolo, valor, add) {
        var table = _get_real_id(idobject);

        if (table === false)
            return;

        if (typeof add != "boolean")
            add = false;

        var aoServerParams, aoDataTemp = [],
            i, j, k, where = [],
            existCol = false,
            existWhere = false,
            existParam = false;

        aoServerParams = $("#" + table).DataTable().settings()[0].aoServerParams;

        // verificamos si existe algun ServerParams
        if (aoServerParams.length) {
            // recorremos la lista de serverParams
            for (i in aoServerParams) {
                aoDataTemp = [];
                aoServerParams[i].fn(aoDataTemp);

                if (aoDataTemp.length) {
                    for (j in aoDataTemp) {
                        if (aoDataTemp[j].name == "where") {
                            // obtenemos el where
                            where = JSON.parse(aoDataTemp[j].value);
                            if (add == false) {
                                if (where.length) {
                                    for (k in where) {
                                        // existe el filtro que deseamos?, solo modificamos datos
                                        if (where[k].column == columna) {
                                            where[k].simbol = simbolo;
                                            where[k].value = valor;

                                            existCol = true;
                                            break;
                                        }
                                    }
                                }
                            }

                            // si no existe el filtro, agregamos el filtro
                            if (!existCol) {
                                where.push({ column: columna, simbol: simbolo, value: valor });
                            }

                            // reemplazamos el nuevo where
                            aoDataTemp[j].value = JSON.stringify(where);

                            existWhere = true; // indicamos que hemos encontrado el where
                            break;
                        }
                    }
                }

                if (existWhere) {
                    aoServerParams[i].fn = function(arr) {
                        for (var i in aoDataTemp) {
                            arr.push(aoDataTemp[i]);
                        }
                    };

                    existParam = true;
                    break;
                }
            }
        }

        if (!existParam) {
            aoServerParams.push({
                "fn": function(arr) {
                    //arr.name    = "where";
                    arr.values   = JSON.stringify([{ column: columna, simbol: simbolo, value: valor }]);
                },
                "sName": "user"
            });
        }

        $("#" + table).DataTable().settings()[0].aoServerParams = aoServerParams;
    }

    /**
     * Metodo para eliminar los filtros de la grilla
     */
    function del_filter(idobject, columna, all) {
        if (typeof(all) != "boolean")
            all = false;
        var table = _get_real_id(idobject);

        var aoServerParams, aoDataTemp, i, j, k, where, exists = false;

        aoServerParams = $("#" + table).DataTable().settings()[0].aoServerParams;

        if (aoServerParams.length) {
            for (i in aoServerParams) {
                aoDataTemp = [];
                aoServerParams[i].fn(aoDataTemp);

                if (aoDataTemp.length) {
                    for (j in aoDataTemp) {
                        if (aoDataTemp[j].name == "where") {
                            // obtenemos el where
                            where = JSON.parse(aoDataTemp[j].value);
                            if (where.length) {
                                for (k = 0; k < where.length; k++) {
                                    if (where[k].column == columna) {
                                        where.splice(k, 1); // eliminamos el filtro
                                        exists = true;
                                        if (all == false)
                                            break;
                                        else
                                            k = Number(k) - 1;
                                    }
                                }
                            }

                            if (exists) {
                                // reemplazamos el nuevo where
                                aoDataTemp[j].value = JSON.stringify(where);

                                // reescribimos funcion
                                aoServerParams[i].fn = function(arr) {
                                    for (var i in aoDataTemp) {
                                        arr.push(aoDataTemp[i]);
                                    }
                                };
                                break;
                            }
                        }

                        if (exists) { break; }
                    }
                }

                if (exists) { break; }
            }
        }

        if (exists) {
            $("#" + table).DataTable().settings()[0].aoServerParams = aoServerParams;
        }
    }

    function get_data(idobject, iRow) {
        var table = _get_real_id(idobject);

        if (table !== false) {
            var api = $("#" + table).DataTable();

            if (iRow === undefined) {
                if (api.$("tr.selected").length) {
                    iRow = api.$("tr.selected");
                } else if (api.$("tr.DTTT_selected[role='row']").length) {
                    iRow = api.$("tr.DTTT_selected[role='row']");
                }
            }

            if (iRow !== undefined) {
                return api.row(iRow).id() || null;
            }
        }

        return null;
    }

    function get_rows(idobject, iRow) {
        var table = _get_real_id(idobject);
        if (table !== false) {
            var api = $("#" + table).DataTable();

            if (iRow === undefined) {
                if (api.$("tr.selected[role='row']").length) {
                    iRow = api.$("tr.selected[role='row']");
                } else if (api.$("tr.DTTT_selected[role='row']").length) {
                    iRow = api.$("tr.DTTT_selected[role='row']");
                }
            }

            if (iRow !== undefined) {
                return api.row(iRow).data() || null;
            }
        }


        return null;
    }

    function get_id(idobject, iRow) {
        var row = get_data(idobject, iRow);

        if (row != null) {
            //return row["DT_RowIndex"];
            return row;
        }
        return null;
    }

    function search(idobject, input, regex, smart) {
        var table = _get_real_id(idobject);
        if (table !== false) {
            $("#" + table).DataTable().search(input, regex, smart).draw();
        }
    }

    function columns_adjust(idobject) {
        var table = _get_real_id(idobject);
        if (table !== false) {
            $("#" + table).DataTable().columns.adjust();
        }
    }

    return {
        reload: reload,
        get_id: get_id,
        get_data: get_data,
        get_rows: get_rows,
        set_filter: set_filter,
        del_filter: del_filter,
        search: search,
        columns_adjust: columns_adjust
    };
}();


// PARA LAS VENTANAS DIALOG
var dialog = function() {
    var _default = {};
	var _pattern = /\[.*?\]/g;
	var _button_type = ["btn-default", "btn-primary", "btn-success",
		"btn-info", "btn-warning", "btn-danger", "btn-dark", "btn-white"];
	var _button_size = ["btn-lg", "btn-sm", "btn-xs"];
	var _label_primary = "guardar";

	function _init() {
		_default = {
			selector: false
			,title: false
			,width: false // modal-lg, modal-sm
			,position: false // top, bottom, center, sidebar [modal-*]
			,closeOnEscape: true
			,closeIcon: true
			,buttons: {}
			,submitOnEnter: true
			,tabs: false // selector or html template
			,style: false // primary, success, danger, warning, info [modal-*]
			,fillIn: false
			,submit: null
			,open: null
			,close: null
		};
	}

	function _clearfix(v, sub, to_array) {
		if(sub === true)
			v = v.toString().substring;
            //v = v.substring(1, v.length-1);
		v = $.trim(v);
		v = v.replace(/\s+/g, " ");
		if(to_array === true)
			return v.split(" ");
		return v;
	}

	function _header(modal, props) {
		if(props.title !== false) {
			var html = '<div class="modal-header '+((props.style !== false)?('bg-'+props.style):'')+'">';
			html += '<h4 class="modal-title">'+props.title+'</h4>';
			if(props.closeIcon === true)
				html += '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class="fa fa-times"></i></span></button>';
			html += '</div>';

			$(".modal-content", modal).prepend(html);
		}
	}

	function _footer(modal, props) {
		if($.isPlainObject(props.buttons) && ! $.isEmptyObject(props.buttons)) {
			$(".modal-content", modal).append('<div class="modal-footer"></div>');
			$.each(props.buttons, function(label, fn) {
				classes = ["btn"], type = false, size = false, arr = label.match(_pattern);
				if($.isArray(arr) && arr.length > 0) {
					for(var i in arr) {
						label = label.replace(arr[i], "");
						cls = _clearfix(arr[i], true, true);
						for(var j in cls) {
							if(_button_type.indexOf(cls[j]) != -1)
								type = cls[j];
							else if(_button_size.indexOf(cls[j]) != -1)
								size = cls[j];
							else if(classes.indexOf(cls[j]) != -1)
								classes.push(cls[j]);
						}
					}
				}
				label = _clearfix(label);
				if(size === false)
					size = 'btn-sm';
				if(label.toLowerCase() == _label_primary && type === false)
					type = "btn-primary";
				if(type === false)
					type = 'btn-default';

				// crear el boton
				btn = $('<button type="button" class="'+classes.join(" ")+" "+type+" "+size+'">'+label+'</button>');

				// agregar boton al modal
				$(".modal-footer", modal).append(btn);

				// evento click del boton
				btn.click(function(e) {
					e.preventDefault();
					if($.isFunction(fn)) {
						fn();
					}
				});
			});
		}
	}

	function _dialog(props) {
		// creamos estructura html del modal
		$(props.selector).wrap('<div class="modal-body" />');
		$(props.selector).closest(".modal-body").wrap('<div class="modal-content" />');
		$(props.selector).closest(".modal-content").wrap('<div class="modal-dialog" />');
		$(props.selector).closest(".modal-dialog").wrap('<div class="modal" />');

		// el modal
		var modal = $(props.selector).closest(".modal");

		// atributos del modal
		modal.addClass("fade");
		// modal.attr("tabindex", "-1");
		modal.attr("role", "dialog");
		modal.attr("aria-hidden", "true");
		modal.attr("data-backdrop", "static");

		if(props.closeOnEscape === false) {
			modal.attr("data-keyboard", "false");
		}
		// if(props.style !== false) {
			// modal.addClass("modal-"+props.style);
		// }
		if(props.fillIn === true) {
			modal.addClass("modal-fill-in");
		}
		if(props.position !== false) {
			$(".modal-dialog", modal).addClass("modal-"+props.position);
		}
		if(props.width !== false) {
			$(".modal-dialog", modal).addClass(props.width);
		}

		// el header
		_header(modal, props);

		// insertando el tab
		if(props.tabs !== false) {
			if($(props.tabs).length > 0 && $(props.tabs).hasClass("nav-tabs")) {
				$(props.tabs).insertAfter($(".modal-header", modal));
			}
		}

		// el footer
		_footer(modal, props);

		// mostramos el form
		if($(props.selector).is(":hidden")) {
			$(props.selector).css({"display":"", "visibility":"", "opacity":""});
		}

		// evento submit form
		if($(props.selector).prop("tagName") == "FORM") {
			if(props.submitOnEnter == false) {
				$(props.selector).submit(function(e) {
					e.preventDefault();
					return false;
				});
			}
			else if($.isFunction(props.submit)) {
				$(props.selector).submit(function(e) {
					props.submit(e);
				});
			}
		}

		// funcion evento close del modal
		if($.isFunction(props.close)) {
			modal.on('hidden.bs.modal', props.close);
		}

		// funcion evento open del modal
		var fnopen = $.noop;
		if($.isFunction(props.open)) {
			// var fn = $.proxy(props.open, $(modal).data("modal"));
			fnopen = function() {
				if($(props.selector+" :input:visible[tabindex='1']", modal).length)
					$($(props.selector+" :input:visible[tabindex='1']", modal)[0]).focus();
				else
					$($(props.selector+" :input:visible", modal)[0]).focus();
				props.open();
			}
		}
		else {
			fnopen = function() {
				if($(props.selector+" :input:visible[tabindex='1']", modal).length)
					$($(props.selector+" :input:visible[tabindex='1']", modal)[0]).focus();
				else
					$($(props.selector+" :input:visible", modal)[0]).focus();
			}
		}
		modal.on('shown.bs.modal', fnopen);
	}

	function create(param) {
		_init();

		var props = $.extend({}, _default, param);

		// no se ha indicado selector
		if(props.selector == false)
			return;
		// selector no existe
		if($(props.selector).length <= 0)
			return;
		// selector ya es un modal
		if($(props.selector).hasClass("modal"))
			return;

		_dialog(props);
	}

	function open(selector) {
		if($(selector).length) {
			$(selector).closest(".modal").modal("show");
		}
	}

	function close(selector) {
		if($(selector).length) {
			$(selector).closest(".modal").modal("hide");
		}
	}

    return {create: create, open: open, close: close};
}();

/**
 * Funciones registrar form
 */
 var form = function() {
	var aControllers = [];

	function clearfix(keyvar) {
		keyvar = $.trim(keyvar);
		keyvar = keyvar.replace(/\W+/g, "");
		keyvar = keyvar.replace(/\s+/g, "");
		return keyvar;
	}

	function register(key, val, replace) {
		key = clearfix(key);

		if(typeof replace != "boolean")
			replace = false;

		if(key != "") {
			if(replace === true) { // nueva asignacion
				aControllers[key] = val;
			}
			else if( ! (key in aControllers)) { // no existe el registro
				aControllers[key] = val;
			}
			else if( ! $.isPlainObject(val)) { // no es un objeto
				aControllers[key] = val;
			}
			else if($.isEmptyObject(val)) { // objeto esta vacio
				aControllers[key] = val;
			}
			else { // extendemos nomas
				var object = $.extend({}, get(key), val);
				aControllers[key] = object;
			}
		}
	}

	function get(key) {
		key = clearfix(key);
		if( ! (key in aControllers)) {
			console.log("key "+key+" not found");
			return {};
		}

		return aControllers[key];
	}

	return {register:register, get:get}
}();

var input = function() {
	// origin callback render item, menu
	// var _defaultRenderItem = $.ui.autocomplete.prototype._renderItem;
	var _defaultRenderMenu = $.ui.autocomplete.prototype._renderMenu;

	// default props
	var _props = {
		selector: "" // selector del input text
		// configuracion del ajax post
		,controller: ""
		,method: "autocomplete"
		,maxRows: 50
		,data: ""
		// configuracion autocomplete plugin
		,minLength: 2
		,label: "[descripcion]" // template para label, use "[" y "]", para indicar datos a obtener
		,value: "[descripcion]" // template para value, use "[" y "]", para indicar datos a obtener
		,appendTo: null
		// configuracion usuario
		,highlight: false
		,show_empty_msg: false
		,empty_msg: "<i>No se han encontrado resultados.</i>"
		,show_new_item: false
		,new_item_msg: "Agregar nuevo item"
		,onSelect: null
		,onNewItem: null
	};

	function defaults(obj) {
		if(obj) {
			// obj._renderItem = _defaultRenderItem;
			obj._renderItem = function( ul, item) {
				return $( "<li></li>" )
					.data( "ui-autocomplete-item", item )
					.append( $( "<a></a>" ).html( item.label ) )
					.appendTo( ul );
			};
			obj._renderMenu = _defaultRenderMenu;
		}
	}

	function renderItemHighlight(obj) {
		if(obj) {
			obj._renderItem = function( ul, item) {
				if (item.disabled) {
					item.value = "";
					return $( "<li class='ui-state-disabled'>" ).data( "ui-autocomplete-item", item ).append( item.label ).appendTo( ul );
				} else {
					// re = new RegExp("^" + this.term, "i") ;
					// re = new RegExp("("+this.term+")(?![^<]*>|[^<>]*<\/)", "gi");
					re = new RegExp("("+this.term+")(?![^<]*>)", "gi");

					// re = new RegExp(this.term, "gi");
					t = item.label.replace(re,"<span class='highlight'>" + "$&" + "</span>");

					// re = new RegExp(String(this.term).replace(/\s+/g, "|"), "i");
					// t = item.label.replace(re,"<span class='highlight'>" + "$&" + "</span>");

					return $( "<li>" ).data( "ui-autocomplete-item", item ).append( "<a>" + t + "</a>" ).appendTo( ul );
				}
			};
		}
	}

	function renderMenu(obj, props) {
		obj._renderMenu = function( ul, items ) {
			$.ui.autocomplete.prototype._renderMenu.apply( this, [ul, items] );
			item = {
				label: props.new_item_msg+": <strong>"+this.term+
					"</strong>&nbsp;&nbsp<label class='pull-right label label-primary'><i class='fa fa-file'></i> Agregar</label>"
				,value: this.term
			};
			$( "<li class='success-element new-menu-item'>" ).data( "ui-autocomplete-item", item )
			  .append( "<a>" + item.label + "</a>" )
			  .appendTo( ul );
		}
	}

	function _renderData(obj) {
		if(obj)
			return $.isPlainObject(obj) ? $.param(obj) : obj;
		return "";
	}

	function autocomplete(props) {
		var def = $.extend({}, _props, props);
		if(def.show_new_item) {
			def.show_empty_msg = true;
		}

		// armamos el label
		var pattern = /(\[\w+\])/g
		var colLabel = def.label.match(pattern);
		var colValue = def.value.match(pattern);

		var f = $.isFunction(def.data);

		// var autoSearch = $(def.selector).autocomplete({
		$(def.selector).autocomplete({
			source: function( request, response ) {
                $.ajax({
                    url: route(def.controller+'.'+def.method),
                    type: 'GET',
                    data: "maxRows="+def.maxRows+"&valor="+request.term+"&"+(f ? _renderData(def.data()) : _renderData(def.data)),
                    beforeSend: function() {
                        //loading( '', $(".sweet-alert.show-input.showSweetAlert.visible") );
                    },
                    success: function(res){
                        if(res.length <= 0) {
                            if(def.show_empty_msg) {
                                res.push({label:def.empty_msg,value:"",disabled:true});
                            }
                            response(res);
                        }
                        else {
                            response( $.map( res, function( item ) {
                                // armammos el label
                                slabel = def.label;
                                $.each(colLabel, function(index, value) {
                                    col = value.replace(/\W/g, "");
                                    slabel = slabel.replace(value, item[col]);
                                });

                                // armamos el value
                                svalue = def.value;
                                $.each(colValue, function(index, value) {
                                    col = value.replace(/\W/g, "");
                                    svalue = svalue.replace(value, item[col]);
                                });

                                item.label = slabel;
                                item.value = svalue;

                                return item;
                            }));
                        }
                    },
                    complete: function () {
                        //loading( 'complete', $(".sweet-alert.show-input.showSweetAlert.visible") );
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
			},
			html: true,
			minLength: def.minLength,
			appendTo: def.appendTo,
			select: function( event, ui ) {
				var li = $(this).data("ui-autocomplete").menu.active;
				if(li.hasClass("ui-state-disabled")) {
					return false;
				}
				if(li.hasClass("new-menu-item")) {
					if($.isFunction(def.onNewItem)) {
						def.onNewItem(ui.item.value);
					}
					return false;
				}
				if($.isFunction(def.onSelect)) {
					def.onSelect(ui.item);
				}
			}
		});
		// }).data( "ui-autocomplete" );

		// defaults(autoSearch);
		defaults($(def.selector).data( "ui-autocomplete" ));

		if(def.highlight) {
			// renderItemHighlight(autoSearch);
			renderItemHighlight($(def.selector).data( "ui-autocomplete" ));
		}

		if(def.show_new_item) {
			// renderMenu(autoSearch, def);
			renderMenu($(def.selector).data( "ui-autocomplete" ), def);
		}
	}

	return {autocomplete:autocomplete}
}();

$(function(){
    if($('.data-table').length){
        $.extend( $.fn.dataTable.defaults, {
            "dom": "<'row'<'col-sm-6'l><'col-sm-6'f>><'row'<'col-md-12 col-sm-12 text-center'r>><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",
            language:{
                "processing": "Procesando...",
                "lengthMenu": "Mostrar _MENU_ registros",
                "zeroRecords": "No se encontraron resultados",
                "emptyTable": "Ningún dato disponible en esta tabla",
                "infoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
                "infoFiltered": "(filtrado de un total de _MAX_ registros)",
                "search": "Buscar:",
                "infoThousands": ",",
                "loadingRecords": "Cargando...",
                "paginate": {
                    "first": "Primero",
                    "last": "Último",
                    "next": "Siguiente",
                    "previous": "Anterior"
                },
                "buttons": {
                },
                "autoFill": {

                },
                "decimal": ",",
                "searchBuilder": {
                },
                "searchPanes": {
                },
                "select": {
                },
                "thousands": ".",
                "datetime": {
                },
                "editor": {
                },
                "info": "Mostrando de _START_ a _END_ de _TOTAL_ entradas"
            }
        });

        $.fn.dataTable.ext.errMode = 'none';
        csrf_token($('meta[name="csrf-token"]').attr('content'));
    }
});

function csrf_token(csrf_token){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': csrf_token
        }
    });
}

function number_format(number, decimals, dec_point, thousands_sep) {
    number = (number + '')
      .replace(/[^0-9+\-Ee.]/g, '');
    var n = !isFinite(+number) ? 0 : +number,
      prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
      sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
      dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
      s = '',
      toFixedFix = function(n, prec) {
        var k = Math.pow(10, prec);
        return '' + (Math.round(n * k) / k)
          .toFixed(prec);
      };
    // Fix for IE parseFloat(0.55).toFixed(0) = 0;
    s = (prec ? toFixedFix(n, prec) : '' + Math.round(n))
      .split('.');
    if (s[0].length > 3) {
      s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
    }
    if ((s[1] || '')
      .length < prec) {
      s[1] = s[1] || '';
      s[1] += new Array(prec - s[1].length + 1)
        .join('0');
    }
    return s.join(dec);
}

/*
selector: $(".preloader")
proceso: ["send", "complete"]
*/
function loading_frame(proceso, selector){
    selector    = selector || $(".preloader");
    proceso     = proceso || "send";
    if(selector){
        if(proceso=="send"){
            selector.css('height', "100vh");
            setTimeout(function () {
                selector.children().show();
            }, 200);
        }else if(proceso=="complete"){
            selector.css('height', 0);
            setTimeout(function () {
                selector.children().hide();
            }, 200);
        }
    }
}

function loading(proceso, selector) {
    selector = selector || $(".modal-content");
    proceso = proceso || "send";

    if (selector) {
        if (proceso == "send") {
            if(selector.length){
                selector.waitMe({ effect: efecto_carga });
            }
        } else if (proceso == "complete") {
            if(selector.length){
                selector.waitMe('hide')
            }
        }
    }
}

function version_navegador(browser){
    browser             = browser || "Chrome";
    is_search_browser   = navigator.userAgent.toLowerCase().indexOf('chrome');
    if(is_search_browser>-1){
        console.log("Si es el navegador "+browser);
        var browserAgent = navigator.userAgent;
        var OffsetVersion = browserAgent.indexOf(browser);
        var browserVersion = browserAgent.substring(OffsetVersion + 7);
        var browserMajorVersion = parseInt('' + browserVersion, 10);
        return browserMajorVersion;
    }
    return 0;
}

function dataURItoBinArray(data) {
    var binary = atob(data);
    var array = [];
    for(var i = 0; i < binary.length; i++) {
        array.push(binary.charCodeAt(i));
    }
    return new Uint8Array(array);
}

$('[data-toggle="tooltip"]').tooltip();
