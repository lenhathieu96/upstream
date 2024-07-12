<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatalogueValue extends Model
{
    use HasFactory;
    protected $table = 'catalogue_value';
    protected $perPage = 15;

    public function getCatalogueStatusAttribute()
    {
        if ($this->STATUS == '1') {
            return 'Active';
        }

        return 'Inactive';
    }
}
