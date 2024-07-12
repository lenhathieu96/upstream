<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FarmCatalogue extends Model
{
    use HasFactory;
    protected $table = 'farm_catalogue';

    public function catalogue_value()
    {
    	return $this->hasMany(CatalogueValue::class,"TYPEZ","CATALOGUE_TYPEZ");
    }

}
