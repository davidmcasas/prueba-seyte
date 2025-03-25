<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Client extends Model
{
    use HasFactory, SoftDeletes;

    // Definir los campos que se pueden rellenar masivamente
    protected $fillable = [
        //'code',
        'company_name',
        'cif',
        'address',
        'municipality',
        'province',
        'contract_start_date',
        'contract_end_date',
        'examinations_included',
    ];

    protected static function booted()
    {
        static::creating(function ($client) {
            $client->code = Str::slug($client->company_name) . '-' . uniqid();
        });
    }

    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }
}
