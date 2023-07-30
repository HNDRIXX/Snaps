<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MainModel extends Model
{
    use HasFactory;
    protected $table = 'main_models';
    protected $guarded = [];

    public function saveTask($data) {
        return $this->create($data);
    }

    public function getTaskList() {
        return $this->all();
    }

    public function deleteTask($id) {
        $id = $this->find($id);
        $id->delete();
    }

    public function getTaskById($id){
        return $this->find($id);
    }

    public function updateTask($data, $id) {
        $task = $this->find($id);
        $task->update($data);
    }
}
