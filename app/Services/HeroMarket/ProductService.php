<?php

namespace App\Services\HeroMarket;

use App\Models\Cooperative;
use Illuminate\Support\Facades\Http;

class ProductService
{
    public function getProductByEnterprise(array $attribute)
    {
        $cooperative = Cooperative::find($attribute['id']);
        $heromarketUrl = config('upstream.HEROMARKET_URL');
        $endpoint = $heromarketUrl . '/api/v2/users/products';

        $category =  !empty($attribute['category_id']) ? ['category_id' => $attribute['category_id']] : [];
        $response = Http::withOptions([
            'verify' => false,
        ])->post($endpoint, array_merge(['email' => $cooperative->email], $category));

        $response = json_decode($response->getBody(), true);

        return $response;
    }

    public function getCategoriesByEnterprise(int $cooperativeId)
    {
        $cooperative = Cooperative::find($cooperativeId);
        $heromarketUrl = config('upstream.HEROMARKET_URL');
        $endpoint = $heromarketUrl . '/api/v2/users/categories';


        $response = Http::withOptions([
            'verify' => false,
        ])->post($endpoint, ['email' => $cooperative->email]);

        $response = json_decode($response->getBody(), true);

        return $response;
    }
}