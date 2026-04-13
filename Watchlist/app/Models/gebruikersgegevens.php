<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class gebruikersgegevens extends Model
{
    protected $fillable = [
        'user_id',
        'voornaam',
        'achternaam',
        'email',
        'telefoonnummer',
        'wachtwoord',
    ];
}

