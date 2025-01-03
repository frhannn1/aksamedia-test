<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'image', 'name', 'phone', 'division_id', 'position'];

    public $incrementing = false; // Jika menggunakan UUID

    protected $keyType = 'string';

    public function division()
    {
        return $this->belongsTo(Division::class);
    }
}
