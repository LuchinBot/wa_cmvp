<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;

use App\Models\Sexo;
use App\Models\EstadoCivil;
use App\Models\TipoDocumentoIdentidad;
use App\Models\TipoPropietario;
use App\Models\Ubigeo;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer(['ubigeo.form'],function($view){
            $view->with('departamento', (new Ubigeo())->selectApi()->departamentos()
            ->get());
        });

        View::composer(['persona_natural.form', 'seguridad.usuarios.form', 'colegiados.form'],function($view){
            $view->with('tipo_doc_identidad', (new TipoDocumentoIdentidad())->get());
        });

        View::composer(['persona_natural.form'],function($view){
            $view->with('sexo', (new Sexo())->get());
            $view->with('estado_civil', (new EstadoCivil())->get());
        });

        //Para evitar error 1071 en migrations
        Schema::defaultStringLength(191);
    }
}
