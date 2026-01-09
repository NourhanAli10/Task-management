<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Workspace extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'type',
        'description',
        'website',
        'logo',
        'visibility',
        'owner_id',
    ];


    protected $attributes = [
        'visibility' => 'private',
    ];


    public function user() {
        return $this->belongsTo(User::class, 'owner_id');

    }


}
