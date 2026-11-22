<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AcademicLink extends Model
{
    protected $fillable = ['name', 'url', 'sort_order'];
}
