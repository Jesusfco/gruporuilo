<?php

namespace GrupoRuilo;

use Illuminate\Database\Eloquent\Model;

class credential extends Model
{
    protected $fillable = [
        'userId', 'systemId'
    ];

}
