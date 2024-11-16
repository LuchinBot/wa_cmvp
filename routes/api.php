<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(["middleware"=>"api.token"], function(){

});

Route::get('ws_principal/property_company', [App\Http\Controllers\Api\PrincipalWS::class, 'propertyCompany'])->name('ws_main.property_company');
Route::get('ws_principal/main_web', [App\Http\Controllers\Api\PrincipalWS::class, 'mainWeb'])->name('ws_principal.main_web');
Route::get('ws_principal/process', [App\Http\Controllers\Api\PrincipalWS::class, 'process'])->name('ws_principal.process');
Route::get('ws_about/about', [App\Http\Controllers\Api\PrincipalWS::class, 'About'])->name('ws_process.about');
Route::get('ws_main/provinces', [App\Http\Controllers\Api\PrincipalWS::class, 'ListProvinces'])->name('ws_main.provinces');
Route::get('ws_main/galery', [App\Http\Controllers\Api\PrincipalWS::class, 'Galery'])->name('ws_main.galery');
Route::post('ws_main/contact_mail', [App\Http\Controllers\Api\PrincipalWS::class, 'ContactMail'])->name('ws_main.contact_mail');

Route::get('ws_docs_normativos/docs_normatives', [App\Http\Controllers\Api\DocumentosNormativosWS::class, 'listDocuments'])->name('ws_docs_normativos.docs_normatives');

Route::get('ws_cors/download', [App\Http\Controllers\Api\DescargaWS::class, 'download'])->name('ws_cors.download');

Route::get('ws_collegiate/list_sexes', [App\Http\Controllers\Api\ColegiadoWS::class, 'ListSexes'])->name('ws_collegiate.list_sexes');
Route::get('ws_collegiate/list_onomastic', [App\Http\Controllers\Api\ColegiadoWS::class, 'ListOnomastic'])->name('ws_collegiate.list_onomastic');
Route::get('ws_collegiate/statistics', [App\Http\Controllers\Api\ColegiadoWS::class, 'Statistics'])->name('ws_collegiate.statistics');
Route::get('ws_collegiate/search_collegiate', [App\Http\Controllers\Api\ColegiadoWS::class, 'searchCollegiate'])->name('ws_collegiate.search_collegiate');

Route::get('ws_course/list_courses', [App\Http\Controllers\Api\CursoWS::class, 'ListCourses'])->name('ws_course.list_courses');
Route::get('ws_event/list_events', [App\Http\Controllers\Api\EventoWS::class, 'ListEvents'])->name('ws_event.list_events');

Route::get('ws_news/list_news', [App\Http\Controllers\Api\NoticiaWS::class, 'ListNews'])->name('ws_news.list_news');
Route::get('ws_news/get_news', [App\Http\Controllers\Api\NoticiaWS::class, 'getNews'])->name('ws_news.get_news');
Route::get('ws_news/search_news', [App\Http\Controllers\Api\NoticiaWS::class, 'searchNews'])->name('ws_news.search_news');

Route::get('ws_release/list_releases', [App\Http\Controllers\Api\ComunicadoWS::class, 'ListReleases'])->name('ws_release.list_releases');
Route::get('ws_release/get_release', [App\Http\Controllers\Api\ComunicadoWS::class, 'getRelease'])->name('ws_release.get_release');
Route::get('ws_release/search_releases', [App\Http\Controllers\Api\ComunicadoWS::class, 'searchReleases'])->name('ws_release.search_releases');

Route::get('ws_pronouncement/list_pronouncements', [App\Http\Controllers\Api\PronunciamientoWS::class, 'ListPronouncements'])->name('ws_pronouncement.list_pronouncements');
Route::get('ws_pronouncement/get_pronouncement', [App\Http\Controllers\Api\PronunciamientoWS::class, 'getPronouncement'])->name('ws_pronouncement.get_pronouncement');
Route::get('ws_pronouncement/search_pronouncements', [App\Http\Controllers\Api\PronunciamientoWS::class, 'searchPronouncement'])->name('ws_pronouncement.search_pronouncements');

Route::get('ws_process/list_process', [App\Http\Controllers\Api\TramiteWS::class, 'ListProcess'])->name('ws_process.list_process');
Route::get('ws_process/get_process', [App\Http\Controllers\Api\TramiteWS::class, 'getProcess'])->name('ws_process.get_process');


Route::get('ws_jobs/job_call', [App\Http\Controllers\Api\ConvocatoriaWS::class, 'ListJobsCall'])->name('ws_jobs.job_call');
