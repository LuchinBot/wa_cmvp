<?php

namespace App\Http\Middleware;

use App\Models\Empresa;
use Closure;

class ApiTokenMiddleware
{
    public function handle($request, Closure $next)
    {
        if($request->header('Authorization')){
            //$token=$request->bearerToken();
            //$apiToken=Empresa::where('token',$token)->first();
            //if(is_null($apiToken)){
            //    return sendError("Token $token Invalido", 403);
            //}
            //$request->merge(['api_token'=>$apiToken]);

            return $next($request);
        }

        return sendError("No autorizado, no se envió token de autenticación para manejar datos de la empresa", 403);
    }
}
