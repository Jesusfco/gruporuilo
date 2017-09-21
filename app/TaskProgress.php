<?php

namespace GrupoRuilo;

use Illuminate\Database\Eloquent\Model;

class TaskProgress extends Model
{
    protected $table = 'task_progresses';
    protected $fillable = [
        'taskId', 'message', 'observation', 'read', 'progress', 'readTime', 'modifyBy'
    ];

}
