<?php

namespace App\Http\Controllers\Project;

use App\Dataservices\Project\ProjectDataservice;
use App\Http\Controllers\BaseCRUDController;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends BaseCRUDController
{
    public function __construct(ProjectDataservice $dataservice)
    {
        // Передаем параметры для работы с Project
        parent::__construct($dataservice, Project::class, 'projects');
    }
}
