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
    public function store(FormRequest $request)
    {
        //Проработать валидацию

        $validatedRequest = ProjectRequest::createFrom($request); // Преобразуем в нужный тип
        // $validatedRequest->validate();
        return parent::store($validatedRequest);
    }

    // Переопределяем метод update с использованием ProjectRequest
    public function update(FormRequest $request, $id)
    {
        $validatedRequest = ProjectRequest::createFrom($request); // Преобразуем в нужный тип
        // $validatedRequest->validate();
        return parent::update($validatedRequest, $id);
    }


}
