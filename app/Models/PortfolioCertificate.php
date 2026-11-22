<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PortfolioCertificate extends Model
{
    protected $fillable = ['title', 'issuer', 'image_path', 'verification_url', 'sort_order'];
}
