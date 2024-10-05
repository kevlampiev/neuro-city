<?php

namespace App\Dataservices;

use App\Dataservices\BaseCRUDDataservice;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

class BigSearchDataservice
{

    public static function availableObjects(): array 
    {
        $doumentCounter = 0;
        $result = ['document', 'note'];
        if (Auth::user()->hasPermissionTo('s-agreements')) $result[]='agreement';
        if (Auth::user()->hasPermissionTo('s-counterparty')) {
             $result[]='company';
             $result[]='company_note';
             $result[]='company_employee';
        }
        if (Auth::user()->hasPermissionTo('s-projects')) $result[]='project'; 

        return $result;
    }

    public static function index($searchStr): array
{
    // Получаем массив доступных типов объектов
    $availableObjects = self::availableObjects();
    // dd($availableObjects);

    // Преобразуем массив в строку для SQL запроса
    $placeholders = implode(',', array_fill(0, count($availableObjects), '?'));

    // Выполнение поиска через DB::select с добавлением условия для obj_type
    $results = DB::select("
        SELECT id, obj_type, obj_text 
        FROM v_big_search AS search_documents
        WHERE search_documents.search_vector @@ plainto_tsquery(?, ?)
        AND search_documents.obj_type IN ($placeholders)
    ", array_merge(['russian', $searchStr], $availableObjects));

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
