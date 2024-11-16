<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use SoftDeletes, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table        = "usuario";
    protected $primaryKey   = "codusuario";

    protected $fillable     = ['codpersona_natural', 'codperfil', 'usuario', 'password', 'avatar', 'baja', 'es_superusuario' ,'google2fa_secret'];

    protected $appends      = ['url_avatar'];

    protected $hidden       = ['password', 'remember_token','created_at', 'updated_at', "deleted_at"];

    const PATH_FILE         = 'app/img/usuario/';
    const DEFAULT_AVATAR    = 'anonimo.png';

    public function getDefaultAvatar()
    {
        return static::DEFAULT_AVATAR;
    }

    public function getPath()
    {
        return static::PATH_FILE;
    }

    public function getAvatar(){
        return (($this->avatar)??$this->getDefaultAvatar());
    }

    public function getPathAvatar()
    {
        return ($this->getPath());
    }

    public function getUrlAvatar(){
        return url($this->getPathAvatar().$this->getAvatar());
    }

    public function getUrlAvatarAttribute()
    {
        return $this->getUrlAvatar();
    }

    public function adminlte_image(){
        return $this->getUrlAvatar();
    }

    public function adminlte_profile_url(){
        return 'usuario/perfil';
    }

    public function perfil()
    {
        return $this->belongsTo(Perfil::class,'codperfil');
    }

    public function persona_natural()
    {
        return $this->belongsTo(PersonaNatural::class,'codpersona_natural');
    }

    public function adminlte_desc(){
        $perfil = $this->perfil->descripcion??'Sin Perfil';
        return $perfil."\ ".$this->persona_natural->nombres." ".$this->persona_natural->apellido_paterno;
    }
}
