<?php

namespace App\Http\Controllers\Droid;

use App\Dataservices\Droid\DroidTypeDataservice;
use App\Http\Controllers\BaseCRUDController;
use App\Models\DroidType;
use Illuminate\Http\Request;

class DroidTypeController extends BaseCRUDController
{
    public function __construct(DroidTypeDataservice $dataservice)
    {
        // Передаем параметры для работы с Project
        parent::__construct($dataservice, DroidType::class, 'droidTypes');
    }

    // public function index(Request $request)
    // {
    //     $data = $this->dataservice->index($request);
    //     return view("droids.droid-types.index", $data); // Переход на представление с элементами
    // }

    // Переопределяем метод store с использованием ProjectRequest
    public function store(Request $request)
    {
        $validated = $this->validate($request, [
            'name' => 'required|string|min:4',
            'description' => 'string|required',
            
        ], 
        [
            'name.required' => 'Поле "Название проекта" обязательно для заполнения.',
            'name.min' => 'Поле "Название проекта" должно содержать минимум :min символа.',
        ]);
        return parent::store($request);
    }

    // Переопределяем метод update с использованием ProjectRequest
    public function update(Request $request, $id)
    {
        $validated = $this->validate($request, [
            'name' => 'required|string|min:4',
            'description' => 'string|required',
        ], 
        [
            'name.required' => 'Поле "Название проекта" обязательно для заполнения.',
            'name.min' => 'Поле "Название проекта" должно содержать минимум :min символа.',
        ]);
        return parent::update($request, $id);
    }


}
