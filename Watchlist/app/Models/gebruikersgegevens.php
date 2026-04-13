<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $user_id
 * @property string $voornaam
 * @property string $achternaam
 * @property string $email
 * @property string|null $telefoonnummer
 * @property string $wachtwoord
 */
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

