<?php

namespace GrupoRuilo;

use Illuminate\Database\Eloquent\Model;

class task extends Model
{
    protected $fillable = [
        'userId', 'title', 'observation', 'description', 'level', 'status', 'createBy'
    ];

}
