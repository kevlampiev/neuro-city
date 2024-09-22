<?php

namespace App\Http\Controllers\Project;

use App\Dataservices\Project\ProjectDataservice;
use App\Http\Controllers\BaseCRUDController;
use App\Models\Project;
use App\Http\Requests\Project\ProjectRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class ProjectController extends BaseCRUDController
{
    public function __construct(ProjectDataservice $dataservice)
    {
        // Передаем параметры для работы с Project
        parent::__construct($dataservice, Project::class, 'projects');
    }

    // Переопределяем метод store с использованием ProjectRequest
    public function store(Request $request)
    {
        $validated = $this->validate($request, [
            'name' => 'required|string|min:4',
            'description' => 'string|nullable',
            'date_open' => 'required|date',
            'date_close' => 'nullable|date|after_or_equal:date_open',
        ], 
        [
            'name.required' => 'Поле "Название проекта" обязательно для заполнения.',
            'name.min' => 'Поле "Название проекта" должно содержать минимум :min символа.',
            'date_open.required' => 'Поле "Дата начала проекта" обязательно для заполнения.',
            'date_open.date' => 'Поле "Дата начала проекта" должно быть корректной датой.',
            'date_close.date' => 'Поле "Дата окончания проекта" должно быть корректной датой.',
            'date_close.after_or_equal' => 'Поле "Дата окончания проекта" должно быть позже или равно дате начала.',
        ]);
        return parent::store($request);
    }

    // Переопределяем метод update с использованием ProjectRequest
    public function update(Request $request, $id)
    {
        // $validatedRequest = ProjectRequest::createFrom($request); // Преобразуем в нужный тип
        // $validatedRequest->validate();
        $validated = $this->validate($request, [
            'name' => 'required|string|min:4',
            'description' => 'string|nullable',
            'date_open' => 'required|date',
            'date_close' => 'nullable|date|after_or_equal:date_open',
        ], 
        [
            'name.required' => 'Поле "Название проекта" обязательно для заполнения.',
            'name.min' => 'Поле "Название проекта" должно содержать минимум :min символа.',
            'date_open.required' => 'Поле "Дата начала проекта" обязательно для заполнения.',
            'date_open.date' => 'Поле "Дата начала проекта" должно быть корректной датой.',
            'date_close.date' => 'Поле "Дата окончания проекта" должно быть корректной датой.',
            'date_close.after_or_equal' => 'Поле "Дата окончания проекта" должно быть позже или равно дате начала.',
        ]);
        return parent::update($request, $id);
    }


}
