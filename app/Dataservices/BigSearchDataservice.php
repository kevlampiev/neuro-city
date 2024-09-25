<?php

namespace App\Dataservices;

use App\Dataservices\BaseCRUDDataservice;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BigSearchDataservice
{
    public static function index($searchStr):array
    {
        $results = DB::select("
            SELECT id, obj_type, obj_text FROM v_big_search AS search_documents
            WHERE search_documents.search_vector @@ plainto_tsquery(?, ?)
        ", ['russian', $searchStr]);

        return ['searchStr'=>$searchStr, 
        'searchResult' => collect($results)];
    }
    // Здесь можно добавить любые специфические методы для работы с Project
}
