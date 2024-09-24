<?php

namespace App\Dataservices\Project;

use App\Dataservices\BaseCRUDDataservice;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectDataservice extends BaseCRUDDataservice
{
    public function __construct()
    {
        // Передаем модель Project
        parent::__construct(new Project());
    }

    // Здесь можно добавить любые специфические методы для работы с Project
}
