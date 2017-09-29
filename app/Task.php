<?php

namespace GrupoRuilo;

use Illuminate\Database\Eloquent\Model;
use GrupoRuilo\User;

class task extends Model
{
    protected $fillable = [
        'userId', 'title', 'observation', 'description', 'level', 'status', 'createBy'
    ];


    public function findUserName(){
        return $this->hasOne('GrupoRuilo\User', 'userId');
    }

    public function findCreatorName(){
        return ($this->hasOne('GrupoRuilo\User', 'createBy'))->name;
    }

}
