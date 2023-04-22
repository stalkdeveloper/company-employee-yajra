<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $table = 'companies';

    protected $primarykey = 'id';

    protected $foreignkey = 'user_id';

    protected $fillable = ['company_name', 'email', 'image','website','user_id'];

    public function users(){
        return $this->belongsTo(User::class);
    }

    public function employees()
    {
        return $this->hasmany(Employee::class);
    }
}
