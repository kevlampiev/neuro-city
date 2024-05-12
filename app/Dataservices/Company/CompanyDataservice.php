<?php


namespace App\DataServices\Company;


use App\Models\Company;
use Illuminate\Http\Request;


class CompanyDataservice
{

    public static function getAll()
    {
        return Company::withCount('agreements')
            ->orderBy('name')->get();
    }


    public static function getFiltered(string $filter)
    {
        $searchStr = '%' . str_replace(' ', '%', $filter) . '%';
        return Company::withCount('agreements')
            ->where('name', 'like', $searchStr)
            ->orderBy('name')->get();
    }


    public static function index(Request $request): array
    {
        $filter = ($request->get('searchStr')) ?? '';
        if ($filter === '') $counterparties = self::getAll();
        else $counterparties = self::getFiltered($filter);
        return ['counterparties' => $counterparties, 'filter' => $filter];
    }
}
