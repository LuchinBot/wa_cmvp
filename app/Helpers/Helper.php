<?php

if ( ! function_exists('formatBytes')) {
	function formatBytes($size, $precision = 2){
		$base = log($size, 1024);
		$suffixes = array('', 'Kb', 'Mb', 'Gb', 'Tb');

		return round(pow(1024, $base - floor($base)), $precision) .' '. $suffixes[floor($base)];
	}
}

if( ! function_exists("starts_with")) {
	function starts_with($string, $pattern) {
		return substr($string, 0, strlen($pattern)) == $pattern;
	}
}

if( ! function_exists("ends_with")) {
	function ends_with($string, $pattern) {
		return substr($string, (-1)*strlen($pattern)) == $pattern;
	}
}

if( ! function_exists("current_perfil")) {
	function current_perfil() {
		return auth()->user()->sucursal()->codperfil;
	}
}

if( ! function_exists("current_sucursal")) {
	function current_sucursal($default=0) {
        return auth()->user()->sucursal()->codsucursal??$default;
	}
}

if( ! function_exists("current_ubigeo")) {
	function current_ubigeo() {
		return auth()->user()->sucursal()->id_ubigeo;
	}
}

if( ! function_exists("current_empresa")) {
	function current_empresa() {
		return auth()->user()->sucursal()->razon_social;
	}
}

if( ! function_exists("current_ruc")) {
	function current_ruc() {
		return auth()->user()->sucursal()->ruc;
	}
}

if( ! function_exists("current_data")) {
	function current_data($param) {
		return auth()->user()->sucursal()->$param??null;
	}
}

if(! function_exists("imgMapaBits")){
    function imgMapaBits($filePath, $bit=24){
        $img_info = getimagesize($filePath);
        switch($img_info["mime"]){
            case "image/jpeg":{$image = imagecreatefromjpeg($filePath);break;}
            case "image/gif":{$image = imagecreatefromgif($filePath);break;}
            case "image/png":{$image = imagecreatefrompng($filePath);break;}
            default: {dd("Unsupported image time");}
        }
        $bits = \pow(2, $bit);
        //imagetruecolortopalette($image, true, $bits);
        $namePath = public_path('imagen.bmp');
        if(!file_exists($namePath)){
            imagebmp($image, $namePath);
            imagedestroy($image);
        }

        return $namePath;
    }
}

if( ! function_exists('public_path_shared')){
    function public_path_shared($path=""){
        $dir_add = path_hosting();

        if(in_hosting()){
            return dirname(dirname(public_path()))."/".((!empty($dir_add)?($dir_add."/"):'')).$path;
        }else{
            return public_path($path);
        }
    }
}

if( ! function_exists('public_path_host')){
    function public_path_host($path=""){
        $dir_add = path_hosting();

        if(in_hosting()){
            return dirname(dirname(public_path()))."/".((!empty($dir_add)?($dir_add."/"):'')).$path;
        }else{
            return public_path($path);
        }
    }
}

if( ! function_exists('url_shared')){
    function url_shared($path=""){
        $dir_add = "";

        if(in_hosting()){
            return config('app.url_online', "http://127.0.0.1/").((!empty($dir_add)?($dir_add."/"):'')).$path;
        }else{
            return url($path);
        }
    }
}

if( ! function_exists('in_hosting')){
    function in_hosting(){
       return config('app.in_hosting', false);
    }
}

if( ! function_exists('path_shared')){
    function path_shared($path=""){
        $dir_add = path_hosting();
        if(in_hosting())
            return (!empty($dir_add)?($dir_add."/"):'').$path;
        //return $path;
        return public_path($path);
    }
}

if( ! function_exists('path_hosting')){
    function path_hosting(){
        return config('app.path_online', "");
    }
}

if ( ! function_exists('sendError')) {
    function sendError($message, $code=404, $data=[]){
        $result = ["success"=>FALSE, "message"=>$message];

        return response()->json(array_merge($result, $data), $code);
    }
}

if ( ! function_exists('sendSuccess')) {
    function sendSuccess($data=[], $message="" , $success=TRUE){
        $result = ["success"=>$success, "message"=>$message];

        return response()->json(array_merge($result, $data));
    }
}

if ( ! function_exists('fecha_en')) {
	function fecha_en($str, $full = FALSE) {
		return fecha_es($str, $full, "/", "-");
	}
}

if ( ! function_exists('fecha_es')) {
	function fecha_es($str, $full = FALSE, $split = "-", $join = "/") {
		if( ! empty($str)) {
			$ext = "";
			if(strlen($str) > 10) {
				$ext = substr($str, 10);
				$str = substr($str, 0, 10);
			}

			if($full)
				return implode($join, array_reverse(explode($split, $str))) . $ext;

			return implode($join, array_reverse(explode($split, $str)));
		}

		return "";
	}
}

if ( ! function_exists('url_consulta_comprobante')) {
	function url_consulta_comprobante($obj, $default="") {
        $url_consulta_elect = $default;
        if(!empty($obj->empresa->pagina_web)){
            $ultimo_digito  = substr($obj->empresa->pagina_web, -1);
            $separador      = ($ultimo_digito=="/")?"":$ultimo_digito;

            $url_consulta_elect = $obj->empresa->pagina_web.$separador."consulta_comprobante";
        }

        return $url_consulta_elect;
	}
}
