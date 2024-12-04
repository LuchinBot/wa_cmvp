<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

Route::post('logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');
Route::group(["middleware"=>"auth"], function(){
	Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    //Route::get('cuota', [App\Http\Controllers\CuotasController::class, 'index'])->name('cuota');
    //Route::get('cuota/{id}/{estado}/{total}', [App\Http\Controllers\CuotasController::class, 'colegiado'])->name('cuota');
    //Route::get('habilitado/{id}', [App\Http\Controllers\CuotasController::class, 'habilitado'])->name('habilitado');

    Route::get('ubigeo/provincia', [App\Http\Controllers\UbigeoController::class, 'provincia'])->name('ubigeo.provincia');
    Route::get('ubigeo/distrito', [App\Http\Controllers\UbigeoController::class, 'distrito'])->name('ubigeo.distrito');

    Route::get('perfiles', [App\Http\Controllers\PerfilController::class, 'index'])->name('perfiles');
    Route::get('perfiles/grilla', [App\Http\Controllers\PerfilController::class, 'grilla'])->name('perfiles.grilla');
    Route::post('perfiles/store', [App\Http\Controllers\PerfilController::class, 'store'])->name('perfiles.store');
    Route::get('perfiles/{id}/edit',[App\Http\Controllers\PerfilController::class, 'edit'])->name('perfiles.edit');
    Route::delete('perfiles/{id}/destroy',[App\Http\Controllers\PerfilController::class, 'destroy'])->name('perfiles.destroy');
/*
    Route::get('estado_civil', [App\Http\Controllers\EstadoCivilController::class, 'index'])->name('estado_civil');
    Route::get('estado_civil/grilla', [App\Http\Controllers\EstadoCivilController::class, 'grilla'])->name('estado_civil.grilla');
    Route::post('estado_civil/store', [App\Http\Controllers\EstadoCivilController::class, 'store'])->name('estado_civil.store');
    Route::get('estado_civil/{id}/edit',[App\Http\Controllers\EstadoCivilController::class, 'edit'])->name('estado_civil.edit');
    Route::delete('estado_civil/{id}/destroy',[App\Http\Controllers\EstadoCivilController::class, 'destroy'])->name('estado_civil.destroy');

    Route::get('sexos', [App\Http\Controllers\SexoController::class, 'index'])->name('sexos');
    Route::get('sexos/grilla', [App\Http\Controllers\SexoController::class, 'grilla'])->name('sexos.grilla');
    Route::post('sexos/store', [App\Http\Controllers\SexoController::class, 'store'])->name('sexos.store');
    Route::get('sexos/{id}/edit',[App\Http\Controllers\SexoController::class, 'edit'])->name('sexos.edit');
    Route::delete('sexos/{id}/destroy',[App\Http\Controllers\SexoController::class, 'destroy'])->name('sexos.destroy');

    Route::get('tipo_documentos_identidad', [App\Http\Controllers\TipoDocumentoIdentidadController::class, 'index'])->name('tipo_documentos_identidad');
    Route::get('tipo_documentos_identidad/grilla', [App\Http\Controllers\TipoDocumentoIdentidadController::class, 'grilla'])->name('tipo_documentos_identidad.grilla');
    Route::post('tipo_documentos_identidad/store', [App\Http\Controllers\TipoDocumentoIdentidadController::class, 'store'])->name('tipo_documentos_identidad.store');
    Route::get('tipo_documentos_identidad/{id}/edit',[App\Http\Controllers\TipoDocumentoIdentidadController::class, 'edit'])->name('tipo_documentos_identidad.edit');
    Route::delete('tipo_documentos_identidad/{id}/destroy',[App\Http\Controllers\TipoDocumentoIdentidadController::class, 'destroy'])->name('tipo_documentos_identidad.destroy');
*/
    Route::get('tipo_cursos', [App\Http\Controllers\TipoCursoController::class, 'index'])->name('tipo_cursos');
    Route::get('tipo_cursos/grilla', [App\Http\Controllers\TipoCursoController::class, 'grilla'])->name('tipo_cursos.grilla');
    Route::post('tipo_cursos/store', [App\Http\Controllers\TipoCursoController::class, 'store'])->name('tipo_cursos.store');
    Route::get('tipo_cursos/{id}/edit',[App\Http\Controllers\TipoCursoController::class, 'edit'])->name('tipo_cursos.edit');
    Route::delete('tipo_cursos/{id}/destroy',[App\Http\Controllers\TipoCursoController::class, 'destroy'])->name('tipo_cursos.destroy');

    Route::get('requisitos', [App\Http\Controllers\RequisitoController::class, 'index'])->name('requisitos');
    Route::get('requisitos/grilla', [App\Http\Controllers\RequisitoController::class, 'grilla'])->name('requisitos.grilla');
    Route::post('requisitos/store', [App\Http\Controllers\RequisitoController::class, 'store'])->name('requisitos.store');
    Route::get('requisitos/{id}/edit',[App\Http\Controllers\RequisitoController::class, 'edit'])->name('requisitos.edit');
    Route::delete('requisitos/{id}/destroy',[App\Http\Controllers\RequisitoController::class, 'destroy'])->name('requisitos.destroy');

    Route::get('persona_natural', [App\Http\Controllers\PersonaNaturalController::class, 'index'])->name('persona_natural');
    Route::get('persona_natural/grilla',[App\Http\Controllers\PersonaNaturalController::class, 'grilla'])->name('persona_natural.grilla');
    Route::post('persona_natural/store',[App\Http\Controllers\PersonaNaturalController::class, 'store'])->name('persona_natural.store');
    Route::get('persona_natural/{id}/edit',[App\Http\Controllers\PersonaNaturalController::class, 'edit'])->name('persona_natural.edit');
    Route::get('persona_natural/autocomplete', [App\Http\Controllers\PersonaNaturalController::class, 'autocomplete'])->name('persona_natural.autocomplete');
    Route::post('persona_natural/search_external', [App\Http\Controllers\PersonaNaturalController::class, 'searchExternal'])->name('persona_natural.search_external');
    Route::post('persona_natural/search_internal', [App\Http\Controllers\PersonaNaturalController::class, 'searchInternal'])->name('persona_natural.search_internal');
    Route::post('persona_natural/search_all', [App\Http\Controllers\PersonaNaturalController::class, 'searchAll'])->name('persona_natural.search_all');

    Route::get('empresas', [App\Http\Controllers\EmpresaController::class, 'index'])->name('empresas');
    Route::get('empresas/grilla', [App\Http\Controllers\EmpresaController::class, 'grilla'])->name('empresas.grilla');
    Route::post('empresas/store', [App\Http\Controllers\EmpresaController::class, 'store'])->name('empresas.store');
    Route::get('empresas/{id}/edit',[App\Http\Controllers\EmpresaController::class, 'edit'])->name('empresas.edit');
    Route::delete('empresas/{id}/destroy',[App\Http\Controllers\EmpresaController::class, 'destroy'])->name('empresas.destroy');
    Route::post('empresas/upload_file', [App\Http\Controllers\EmpresaController::class, 'uploadFile'])->name('empresas.upload_file');

    Route::get('slider_principal', [App\Http\Controllers\SliderPrincipalController::class, 'index'])->name('slider_principal');
    Route::get('slider_principal/grilla', [App\Http\Controllers\SliderPrincipalController::class, 'grilla'])->name('slider_principal.grilla');
    Route::post('slider_principal/store', [App\Http\Controllers\SliderPrincipalController::class, 'store'])->name('slider_principal.store');
    Route::get('slider_principal/{id}/edit',[App\Http\Controllers\SliderPrincipalController::class, 'edit'])->name('slider_principal.edit');
    Route::post('slider_principal/upload_file', [App\Http\Controllers\SliderPrincipalController::class, 'uploadFile'])->name('slider_principal.upload_file');
    Route::delete('slider_principal/{id}/destroy',[App\Http\Controllers\SliderPrincipalController::class, 'destroy'])->name('slider_principal.destroy');

    Route::get('tramites', [App\Http\Controllers\TramiteController::class, 'index'])->name('tramites');
    Route::get('tramites/grilla', [App\Http\Controllers\TramiteController::class, 'grilla'])->name('tramites.grilla');
    Route::post('tramites/store', [App\Http\Controllers\TramiteController::class, 'store'])->name('tramites.store');
    Route::get('tramites/{id}/edit',[App\Http\Controllers\TramiteController::class, 'edit'])->name('tramites.edit');
    Route::post('tramites/subir_archivo', [App\Http\Controllers\TramiteController::class, 'uploadFile'])->name('tramites.subir_archivo');
    Route::delete('tramites/{id}/destroy',[App\Http\Controllers\TramiteController::class, 'destroy'])->name('tramites.destroy');

    Route::get('comunicados', [App\Http\Controllers\ComunicadoController::class, 'index'])->name('comunicados');
    Route::get('comunicados/grilla', [App\Http\Controllers\ComunicadoController::class, 'grilla'])->name('comunicados.grilla');
    Route::post('comunicados/store', [App\Http\Controllers\ComunicadoController::class, 'store'])->name('comunicados.store');
    Route::get('comunicados/{id}/edit',[App\Http\Controllers\ComunicadoController::class, 'edit'])->name('comunicados.edit');
    Route::post('comunicados/upload_file', [App\Http\Controllers\ComunicadoController::class, 'uploadFile'])->name('comunicados.upload_file');
    Route::delete('comunicados/{id}/destroy',[App\Http\Controllers\ComunicadoController::class, 'destroy'])->name('comunicados.destroy');

    Route::get('pronunciamientos', [App\Http\Controllers\PronunciamientoController::class, 'index'])->name('pronunciamientos');
    Route::get('pronunciamientos/grilla', [App\Http\Controllers\PronunciamientoController::class, 'grilla'])->name('pronunciamientos.grilla');
    Route::post('pronunciamientos/store', [App\Http\Controllers\PronunciamientoController::class, 'store'])->name('pronunciamientos.store');
    Route::get('pronunciamientos/{id}/edit',[App\Http\Controllers\PronunciamientoController::class, 'edit'])->name('pronunciamientos.edit');
    Route::post('pronunciamientos/upload_file', [App\Http\Controllers\PronunciamientoController::class, 'uploadFile'])->name('pronunciamientos.upload_file');
    Route::delete('pronunciamientos/{id}/destroy',[App\Http\Controllers\PronunciamientoController::class, 'destroy'])->name('pronunciamientos.destroy');

    Route::get('cursos', [App\Http\Controllers\CursoController::class, 'index'])->name('cursos');
    Route::get('cursos/grilla', [App\Http\Controllers\CursoController::class, 'grilla'])->name('cursos.grilla');
    Route::post('cursos/store', [App\Http\Controllers\CursoController::class, 'store'])->name('cursos.store');
    Route::get('cursos/{id}/edit',[App\Http\Controllers\CursoController::class, 'edit'])->name('cursos.edit');
    Route::post('cursos/upload_file', [App\Http\Controllers\CursoController::class, 'uploadFile'])->name('cursos.upload_file');
    Route::delete('cursos/{id}/destroy',[App\Http\Controllers\CursoController::class, 'destroy'])->name('cursos.destroy');

    Route::get('noticias', [App\Http\Controllers\NoticiaController::class, 'index'])->name('noticias');
    Route::get('noticias/grilla', [App\Http\Controllers\NoticiaController::class, 'grilla'])->name('noticias.grilla');
    Route::post('noticias/store', [App\Http\Controllers\NoticiaController::class, 'store'])->name('noticias.store');
    Route::get('noticias/{id}/edit',[App\Http\Controllers\NoticiaController::class, 'edit'])->name('noticias.edit');
    Route::post('noticias/upload_file', [App\Http\Controllers\NoticiaController::class, 'uploadFile'])->name('noticias.upload_file');
    Route::delete('noticias/{id}/destroy',[App\Http\Controllers\NoticiaController::class, 'destroy'])->name('noticias.destroy');

    Route::get('eventos', [App\Http\Controllers\EventoController::class, 'index'])->name('eventos');
    Route::get('eventos/grilla', [App\Http\Controllers\EventoController::class, 'grilla'])->name('eventos.grilla');
    Route::post('eventos/store', [App\Http\Controllers\EventoController::class, 'store'])->name('eventos.store');
    Route::get('eventos/{id}/edit',[App\Http\Controllers\EventoController::class, 'edit'])->name('eventos.edit');
    Route::post('eventos/upload_file', [App\Http\Controllers\EventoController::class, 'uploadFile'])->name('eventos.upload_file');
    Route::delete('eventos/{id}/destroy',[App\Http\Controllers\EventoController::class, 'destroy'])->name('eventos.destroy');

    Route::get('juntas_directivas', [App\Http\Controllers\JuntaDirectivaController::class, 'index'])->name('juntas_directivas');
    Route::get('juntas_directivas/grilla', [App\Http\Controllers\JuntaDirectivaController::class, 'grilla'])->name('juntas_directivas.grilla');
    Route::post('juntas_directivas/store', [App\Http\Controllers\JuntaDirectivaController::class, 'store'])->name('juntas_directivas.store');
    Route::get('juntas_directivas/{id}/edit',[App\Http\Controllers\JuntaDirectivaController::class, 'edit'])->name('juntas_directivas.edit');
    Route::delete('juntas_directivas/{id}/destroy',[App\Http\Controllers\JuntaDirectivaController::class, 'destroy'])->name('juntas_directivas.destroy');

    Route::get('documentos_normativos', [App\Http\Controllers\DocumentoNormativoController::class, 'index'])->name('documentos_normativos');
    Route::get('documentos_normativos/grilla', [App\Http\Controllers\DocumentoNormativoController::class, 'grilla'])->name('documentos_normativos.grilla');
    Route::post('documentos_normativos/store', [App\Http\Controllers\DocumentoNormativoController::class, 'store'])->name('documentos_normativos.store');
    Route::get('documentos_normativos/{id}/edit',[App\Http\Controllers\DocumentoNormativoController::class, 'edit'])->name('documentos_normativos.edit');
    Route::delete('documentos_normativos/{id}/destroy',[App\Http\Controllers\DocumentoNormativoController::class, 'destroy'])->name('documentos_normativos.destroy');

    Route::get('bolsa_trabajos', [App\Http\Controllers\BolsaTrabajoController::class, 'index'])->name('bolsa_trabajos');
    Route::get('bolsa_trabajos/grilla', [App\Http\Controllers\BolsaTrabajoController::class, 'grilla'])->name('bolsa_trabajos.grilla');
    Route::post('bolsa_trabajos/store', [App\Http\Controllers\BolsaTrabajoController::class, 'store'])->name('bolsa_trabajos.store');
    Route::get('bolsa_trabajos/{id}/edit',[App\Http\Controllers\BolsaTrabajoController::class, 'edit'])->name('bolsa_trabajos.edit');
    Route::delete('bolsa_trabajos/{id}/destroy',[App\Http\Controllers\BolsaTrabajoController::class, 'destroy'])->name('bolsa_trabajos.destroy');

    Route::get('actividad_institucional', [App\Http\Controllers\ActividadInstitucionalController::class, 'index'])->name('actividad_institucional');
    Route::get('actividad_institucional/grilla', [App\Http\Controllers\ActividadInstitucionalController::class, 'grilla'])->name('actividad_institucional.grilla');
    Route::post('actividad_institucional/store', [App\Http\Controllers\ActividadInstitucionalController::class, 'store'])->name('actividad_institucional.store');
    Route::get('actividad_institucional/{id}/edit',[App\Http\Controllers\ActividadInstitucionalController::class, 'edit'])->name('actividad_institucional.edit');
    Route::delete('actividad_institucional/{id}/destroy',[App\Http\Controllers\ActividadInstitucionalController::class, 'destroy'])->name('actividad_institucional.destroy');
    Route::post('actividad_institucional/upload_file', [App\Http\Controllers\ActividadInstitucionalController::class, 'uploadFile'])->name('actividad_institucional.upload_file');


    Route::get('colegiados', [App\Http\Controllers\ColegiadoController::class, 'index'])->name('colegiados');
    Route::get('colegiados/grilla', [App\Http\Controllers\ColegiadoController::class, 'grilla'])->name('colegiados.grilla');
    Route::post('colegiados/store', [App\Http\Controllers\ColegiadoController::class, 'store'])->name('colegiados.store');
    Route::get('colegiados/{id}/edit',[App\Http\Controllers\ColegiadoController::class, 'edit'])->name('colegiados.edit');
    Route::delete('colegiados/{id}/destroy',[App\Http\Controllers\ColegiadoController::class, 'destroy'])->name('colegiados.destroy');
    Route::get('colegiados/autocomplete', [App\Http\Controllers\ColegiadoController::class, 'autocomplete'])->name('colegiados.autocomplete');

    Route::get('cajas', [App\Http\Controllers\CajaController::class, 'index'])->name('cajas');
    Route::get('cajas/grilla', [App\Http\Controllers\CajaController::class, 'grilla'])->name('cajas.grilla');
    Route::post('cajas/store', [App\Http\Controllers\CajaController::class, 'store'])->name('cajas.store');
    Route::get('cajas/{id}/edit',[App\Http\Controllers\CajaController::class, 'edit'])->name('cajas.edit');
    Route::delete('cajas/{id}/destroy',[App\Http\Controllers\CajaController::class, 'destroy'])->name('cajas.destroy');

    Route::get('cajas_principal', [App\Http\Controllers\CajaPrincipalController::class, 'index'])->name('cajas_principal');
    Route::get('cajas_principal/grilla', [App\Http\Controllers\CajaPrincipalController::class, 'grilla'])->name('cajas_principal.grilla');
    Route::post('cajas_principal/store', [App\Http\Controllers\CajaPrincipalController::class, 'store'])->name('cajas_principal.store');
    Route::get('cajas_principal/{id}/edit',[App\Http\Controllers\CajaPrincipalController::class, 'edit'])->name('cajas_principal.edit');
    Route::delete('cajas_principal/{id}/destroy',[App\Http\Controllers\CajaPrincipalController::class, 'destroy'])->name('cajas_principal.destroy');
    
    Route::get('ventas', [App\Http\Controllers\VentaController::class, 'index'])->name('ventas');
    Route::get('ventas/grilla', [App\Http\Controllers\VentaController::class, 'grilla'])->name('ventas.grilla');
    Route::post('ventas/store', [App\Http\Controllers\VentaController::class, 'store'])->name('ventas.store');
    Route::get('ventas/{id}/edit',[App\Http\Controllers\VentaController::class, 'edit'])->name('ventas.edit');
    Route::delete('ventas/{id}/destroy',[App\Http\Controllers\VentaController::class, 'destroy'])->name('ventas.destroy');

    Route::get('productos', [App\Http\Controllers\ProductoController::class, 'index'])->name('productos');
    Route::get('productos/grilla', [App\Http\Controllers\ProductoController::class, 'grilla'])->name('productos.grilla');
    Route::post('productos/store', [App\Http\Controllers\ProductoController::class, 'store'])->name('productos.store');
    Route::get('productos/{id}/edit',[App\Http\Controllers\ProductoController::class, 'edit'])->name('productos.edit');
    Route::delete('productos/{id}/destroy',[App\Http\Controllers\ProductoController::class, 'destroy'])->name('productos.destroy');

    Route::get('dias_festivos', [App\Http\Controllers\DiasFestivosController::class, 'index'])->name('dias_festivos');
    Route::get('dias_festivos/grilla', [App\Http\Controllers\DiasFestivosController::class, 'grilla'])->name('dias_festivos.grilla');
    Route::post('dias_festivos/store', [App\Http\Controllers\DiasFestivosController::class, 'store'])->name('dias_festivos.store');
    Route::post('dias_festivos/upload_file', [App\Http\Controllers\DiasFestivosController::class, 'uploadFile'])->name('dias_festivos.upload_file');
    Route::get('dias_festivos/{id}/edit',[App\Http\Controllers\DiasFestivosController::class, 'edit'])->name('dias_festivos.edit');
    Route::delete('dias_festivos/{id}/destroy',[App\Http\Controllers\DiasFestivosController::class, 'destroy'])->name('dias_festivos.destroy');
    Route::get('dias_festivos/verificar',[App\Http\Controllers\DiasFestivosController::class, 'verificarDia'])->name('dias_festivos.verificarDia');

    
    Route::get('apertura_cuotas', [App\Http\Controllers\CuotasController::class, 'index'])->name('cuotas');
    Route::get('apertura_cuotas/get_data', [App\Http\Controllers\CuotasController::class, 'getData'])->name('cuotas.get_data');
    Route::get('apertura_cuotas/save_data', [App\Http\Controllers\CuotasController::class, 'saveData'])->name('cuotas.save_data');
    Route::get('apertura_cuotas/grilla', [App\Http\Controllers\CuotasController::class, 'grilla'])->name('cuotas.grilla');
    Route::post('apertura_cuotas/store', [App\Http\Controllers\CuotasController::class, 'store'])->name('cuotas.store');
    Route::get('apertura_cuotas/{id}/edit',[App\Http\Controllers\CuotasController::class, 'edit'])->name('cuotas.edit');
    Route::delete('apertura_cuotas/{id}/destroy',[App\Http\Controllers\CuotasController::class, 'destroy'])->name('cuotas.destroy');

});

Route::get('logs', [\Rap2hpoutre\LaravelLogViewer\LogViewerController::class, 'index']);
