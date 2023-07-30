<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InteractModel extends Model
{
    use HasFactory;
    protected $table = 'interaction';
    protected $guarded = [];

    public function saveStoryInteract($data){
        return $this->create($data);
    }
}
