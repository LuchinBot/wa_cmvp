<?php

namespace App\Actions;

class DataTableInternal
{
    private $modelAdapter;
    private $aColumns;
    public $sIndexColumn;
    private $urlFile            = "";
    private $popup              = false;
    private $bFilter            = true;
    private $placeholder        = false;
    private $dataParams         = [];

    private $aColumnsSelected   = [];


    public function __construct($path="")
    {
        $this->urlFile = $path;
    }

    /**
     * Asignamos el modelo de la tabla sobre el cual se generara la grilla
     */
    public function setModel($model) {
        $this->modelAdapter = $model;
    }


    /**
     * Establece el array de columnas a mostrar en la grilla
     * @param array $columns
     */
    public function setColumns(array $columns) {
        $this->aColumnsSelected = $columns;
    }

    public function setParams(array $columns){
        $this->dataParams   = $columns;
    }

    public function setFilter($bool) {
		$this->bFilter = $bool;
	}

    public function setPlaceHolder($placeholder) {
        $this->placeholder = $placeholder;
    }

    /**
     * Metodo para indicar si la grilla sera para una ventana popup
     * @param boolean $popup
     */
    public function setPopup($popup) {
        $this->popup = $popup;
    }

    /**
     * Metodo para indicar el campo PK de la tabla actual.
     * @param string $indexColumn
     */
    public function setIndexColumn($indexColumn) {
        $this->sIndexColumn = $indexColumn;
    }

    /**
     * Metodo para crear la tabla de la grilla que contendra los registros;
     * el parametro $columnsName es un array 2D que contiene el
     * nombre de la columna y su tamanio
     * e.g.
     *		$columnasName = array(
     *			array('columna1', '20%'),
     *			array('columna2', '25%'),
     *			array('columna3', '15%'),
     *			array('columna4', '10%')
     *		);
     *
     * @param array $columnsName nombre de las columnas que apareceran en la grilla
     * @param string $idTable id del tag TABLE en el DOM
     * @param boolean $popup indica si la grilla es una ventana popup
     * @return string html para la tabla de la grilla
     */
    public function createTable($cascada="div.container-fluid>div.row>div.col-12>div.card.box_shano_o_o>div.card-body.linea_top_o_o", $idTable = "", $class = "table table-bordered table-striped data-table") {
        if(empty($idTable))
			$idTable = $this->modelAdapter->getTable();
        $cls    = "";
        if($this->popup){
            $idTable .= "_popup";
            $cls = "grilla_popup";
        }
        $tableRealId = $idTable;

        $thead = "";

        foreach($this->aColumnsSelected as $k=>$ft) {
			if( is_array($ft) ) {
				$thead .= '<th width="'.(($ft['ancho'])??'5%').'">'.($ft['descripcion']??('Columna '+($k+1))).'</th>';
			}
			else {
				$thead .= '<th>'.$ft.'</th>';
			}
		}

        $tag_html = "";

        $html  = '<table id="dt-'.$idTable.'" realid="'.$tableRealId.'" class="table '.$class.' '.$cls.'">';
        $html .= '  <thead>';
        $html .= '      <tr>'.$thead.'</tr>';
        $html .= '  </thead>';
        $html .= '  <tbody>';
        $html .='       <tr><td colspan="'.(count($this->aColumnsSelected)).'" class="dataTables_empty text-center">Loading data from server</td></tr>';
        $html .='   </tbody>';
        $html .= '</table>';

        if(!empty($cascada)){
            $tag            = explode(">", $cascada);
            $firstElement   = array_shift($tag);
            $elementFirst   = explode(".", $firstElement);
            $etiqueta       = array_shift($elementFirst);

            $tag_html.="<".($etiqueta)." class='".implode(" ", $elementFirst)."' >";
            $tag_html.= $this->createCascade(implode(">", $tag), $html);
            $tag_html.="</".($etiqueta).">";
        }else{
            $tag_html.=$html;
        }

        return $tag_html;
    }

    public function createCascade($cascada, $html=""){
        $tag_htmlC = "";
        if(empty($cascada))
            return $tag_htmlC.=$html;

        $tag            = explode(">", $cascada);
        $firstElement   = array_shift($tag);
        $elementFirst   = explode(".", $firstElement);
        $etiqueta       = array_shift($elementFirst);

        $tag_htmlC.="<".($etiqueta)." class='".implode(" ", $elementFirst)."' >";
        $tag_htmlC.= $this->createCascade(implode(">", $tag), $html);
        $tag_htmlC.="</".($etiqueta).">";

        return $tag_htmlC;
    }

    public function createScript($idTable = ""){
        if(empty($idTable))
			$idTable = $this->modelAdapter->getTable();
        if($this->popup)
            $idTable .= "_popup";
        $html = "var oTable_dt{$idTable};\n";
        $html.= "$(function(){\n";
        $html.= "   oTable_dt{$idTable} = $('#dt-{$idTable}').dataTable({\n";
        $html.= "       'pageLength': 10\n";
        $html.= "       ,'processing': true\n";
        $html.= "       ,'serverSide': true\n";
        $html.= "       ,'language': {\n";
        if($this->placeholder !== FALSE){
            $html.="        searchPlaceholder: '{$this->placeholder}'";
        }
        $html.= "       }\n";
        if($this->popup){
            $html.= "   ,'lengthChange': false\n";
        }
        if($this->bFilter==false)
            $html .= " ,'bFilter':false\n";
        $html.= "      ,'destroy': true\n";
        $html.= "      ,'responsive': true\n";
        $html.= "      ,'autoWidth': false\n";
        $html.= "      ,'ordering': true\n";
        $html.= "      ,'rowId': '".$this->modelAdapter->getKeyName()."'\n";
        $html.= "      ,'ajax': {\n";
        $html.= "           'url':route('".($this->urlFile)."')\n";
        $html.= "           ,'type':'get'\n";
        if(!empty($this->dataParams)){
            $html.= ",'data': function(d){";
            foreach($this->dataParams as $kk=>$vv){
                $html.="d.{$kk}= {$vv};";
            }
            $html.= "}\n";
        }
        $html.= "           ,'beforeSend': function(){\n";
        //$html.= "               $('#dt" . $idTable . "').waitMe({effect : 'bounce' })\n";
        $html.= "           }\n";
        $html.= "           ,'complete': function(){\n";
        //$html.= "               $('#dt" . $idTable . "').waitMe('hide')\n";
        $html.= "           }\n";
        $html.= "       }\n";

        $cols = [];

        foreach($this->aColumnsSelected as $c) {
            if(!isset($c['jsColumn']))
                return false;
			$cols[] = [
                "data"=>$c['jsColumn']['data']
                , "name"=>(($c['jsColumn']['name'])??$c['jsColumn']['data'])
                , "orderable"=>(($c['jsColumn']['orderable'])??true)
                , "searchable"=>(($c['jsColumn']['searchable'])??true)
                , "className"=>(($c['jsColumn']['className'])??"")
            ];
		}

		$html.= "      ,'columns': " . json_encode($cols)."\n";
        $html.= "   });\n";

        $html.= "   $('#dt-{$idTable}').on('click', 'tr', function(){\n";
        $html.= "       if($(this).hasClass('selected') ) {\n";
        $html.= "           $(this).removeClass('selected');\n";
        $html.= "       }else{\n";
        $html.= "           oTable_dt{$idTable}.$('tr.selected').removeClass('selected');\n";
        $html.= "           $(this).addClass('selected');\n";
        $html.= "       }\n";
        $html.= "   });\n";

        $html.= "});\n";

        return $html;
    }
}
