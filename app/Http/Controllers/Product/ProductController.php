<?php

namespace App\Http\Controllers\Product;

use App\Dataservices\Product\ProductDataservice;
use App\Http\Controllers\BaseCRUDController;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends BaseCRUDController
{
    public function __construct(ProductDataservice $dataservice)
    {
        // Передаем параметры для работы с Product
        parent::__construct($dataservice, Product::class, 'products');
    }

    public function index(Request $request)
    {
        $data = $this->dataservice->index($request);
        return view("products.index", $data); // Переход на представление с элементами
    }

    // Переопределяем метод store с использованием валидации полей
    public function store(Request $request)
    {
        $validated = $this->validate($request, [
            'name' => 'required|string|min:3',
            'is_service' => 'required|boolean',
            'description' => 'string|nullable',
        ], 
        [
            'name.required' => 'Поле "Название продукта/услуги" обязательно для заполнения.',
            'name.min' => 'Поле "Название продукта/услуги" должно содержать минимум :min символа.',
            'is_service.required' => 'Поле признака продукт или услуга должно быть задано',
            'is_service.boolean' => 'Поле признака продукт или услуга должно быть логическим ',
            'description.string' => 'Поле описания продукта/услуги должно быть текстовым'
        ]);
        
        return parent::store($request);
    }

    // Переопределяем метод update с использованием валидации полей
    public function update(Request $request, $id)
    {
        $validated = $this->validate($request, [
            'name' => 'required|string|min:3',
            'is_service' => 'required|boolean',
            'description' => 'string|nullable',
        ], 
        [
            'name.required' => 'Поле "Название продукта/услуги" обязательно для заполнения.',
            'name.min' => 'Поле "Название продукта/услуги" должно содержать минимум :min символа.',
            'is_service.required' => 'Поле признака продукт или услуга должно быть задано',
            'is_service.boolean' => 'Поле признака продукт или услуга должно быть логическим ',
            'description.string' => 'Поле описания продукта/услуги должно быть текстовым'
        ]);
        return parent::update($request, $id);
    }


}
