<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class File extends Model
{
    //
    use SoftDeletes;
    protected $fillable = ['id','type', 'path', 'mime_type', 'md5', 'title', 'folder', 'object_id', 'size', 'width', 'height', 'downloads', 'public', 'editor', 'status', 'created_op'];

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];
}
