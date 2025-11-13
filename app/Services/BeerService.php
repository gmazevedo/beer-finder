<?php

namespace App\Services;

use App\Models\Beer;

class BeerService
{
    public function getBeers(string $sortBy, string $sortDirection)
    {
        $query = Beer::query();

        if ($sortBy && $sortDirection)
        {
            $query->orderBy($sortBy, $sortDirection);
        }

        return $query->paginate(15);
    }
}
