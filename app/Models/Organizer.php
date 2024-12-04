<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Organizer extends Model
{
    //
    protected $guarded=[];

    public function event(){
        return $this->hasMany(Event::class);
    }
}
