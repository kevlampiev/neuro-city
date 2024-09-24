<?php


namespace App\Dataservices\Counterparty;


use App\Models\Company;
use Illuminate\Http\Request;


class CounterpartyDataservice
{

    public static function getAll()
    {
        // return Company::withCount('agreements')
        //     ->orderBy('name')->get();

        return Company::query()->where('our_company','=',false)->orderBy('name')->paginate(15);

    }


    public static function getFiltered(string $filter)
    {
        $searchStr = '%' . preg_replace('/\s+/', '%', mb_strtolower($filter??"")) . '%';
        // return Company::withCount('agreements')
        //     ->where('name', 'like', $searchStr)
        //     ->orderBy('name')->get();
        return Company::query()
            ->where('our_company','=',false)
            ->whereRaw('LOWER(name) like ?',[$searchStr])
            ->orderBy('name')->paginate(15);
    
    }


    public static function index(Request $request): array
    {
        $filter = ($request->get('searchStr')) ?? '';
        if ($filter === '') $counterparties = self::getAll();
        else $counterparties = self::getFiltered($filter);
        return ['counterparties' => $counterparties, 'filter' => $filter];
    }
}
