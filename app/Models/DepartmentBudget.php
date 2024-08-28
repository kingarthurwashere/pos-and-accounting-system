<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DepartmentBudget extends Model
{
    use HasFactory;

    public function budget()
    {
        return $this->belongsTo(Department::class);
    }
}
