<?php

namespace App\Dataservices\Product;

use App\Dataservices\BaseCRUDDataservice;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductDataservice extends BaseCRUDDataservice
{
    public function __construct()
    {
        // Передаем модель Project
        parent::__construct(new Product());
    }

}
