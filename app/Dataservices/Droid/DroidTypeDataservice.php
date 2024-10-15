<?php

namespace App\Dataservices\Droid;

use App\Dataservices\BaseCRUDDataservice;
use App\Models\DroidType;
use Illuminate\Http\Request;

class DroidTypeDataservice extends BaseCRUDDataservice
{
    public function __construct()
    {
        // Передаем модель Project
        parent::__construct(new DroidType());
    }

    // Здесь можно добавить любые специфические методы для работы с DroidType
}
