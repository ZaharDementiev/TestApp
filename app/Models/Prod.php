<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prod extends Model
{
    use HasFactory;

    public function showCharacteristics()
    {
        $characteristics = '';
        foreach (json_decode($this->characteristics) as $key => $value) {
            $characteristics .= $key . ': ' . $value . "\n";
        }
        return $characteristics;
    }
}
