<?php

namespace App\Dataservices;

use App\Dataservices\BaseCRUDDataservice;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;  

class BigSearchDataservice
{
    public static function index($searchStr): array
    {
        // Выполнение поиска через DB::select
        $results = DB::select("
            SELECT id, obj_type, obj_text FROM v_big_search AS search_documents
            WHERE search_documents.search_vector @@ plainto_tsquery(?, ?)
        ", ['russian', $searchStr]);

        // Преобразуем массив результатов в коллекцию
        $collection = collect($results);

        // Получаем текущую страницу из параметров запроса, если не указана, устанавливаем 1
        $currentPage = LengthAwarePaginator::resolveCurrentPage();

        // Определяем количество элементов на странице
        $perPage = 15;

        // Извлекаем элементы для текущей страницы
        $currentPageResults = $collection->slice(($currentPage - 1) * $perPage, $perPage)->values();

        // Создаем пагинатор
        $paginatedResults = new LengthAwarePaginator(
            $currentPageResults, // Элементы текущей страницы
            $collection->count(), // Общее количество элементов
            $perPage, // Количество элементов на страницу
            $currentPage, // Текущая страница
            ['path' => request()->url(), 'query' => request()->query()] // Параметры запроса для сохранения
        );

        // Возвращаем результат с параметрами
        return [
            'searchStr' => $searchStr,
            'searchResult' => $paginatedResults
        ];
    }

}
