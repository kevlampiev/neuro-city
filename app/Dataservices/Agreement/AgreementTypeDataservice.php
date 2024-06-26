<?php


namespace App\Dataservices\Agreement;


use App\Models\AgreementType;

class AgreementTypeDataservice
{
    public static function provideData(): array
    {
        // return ['agrTypes' => AgreementType::withCount('agreements')->orderBy('name')->get(), 'filter' => ''];
        return ['agrTypes' => AgreementType::orderBy('name')->get(), 'filter' => ''];
    }
}
