<?php


namespace App\Dataservices\Company;


use App\Models\Company;

class CompanyDataservice
{
    public static function provideData(): array
    {
        // return ['companies' => Company::withCount('agreements')
        //     ->orderBy('name')->get(), 'filter' => ''];
        return ['companies' => Company::orderBy('name')->where('our_company', true)->get(), 'filter' => ''];
    }
}
