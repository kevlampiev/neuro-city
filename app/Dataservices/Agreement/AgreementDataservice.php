<?php


namespace App\Dataservices\Agreement;

use App\Http\Requests\Agreement\AgreementRequest;
use App\Models\Agreement;
use App\Models\Document;
use App\Models\AgreementType;
use App\Models\Company;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PhpParser\Error;

class AgreementDataservice
{

    /**
     * получение договоров по куче условий
     */
    public static function getData(string $filter, string $status, string $cfs_item_id)
    {

        $searchStr = '%' . preg_replace('/\s+/', '%', mb_strtolower($filter??"")) . '%';
        // dd($searchStr);
        $query = Agreement::query();
    
        $query->where(function (Builder $query) use ($searchStr) {
            $query->whereRaw('LOWER(name) like ?', [$searchStr])
                ->orWhereRaw('LOWER(agr_number) like ?', [$searchStr])
                ->orWhereHas('buyer', function (Builder $query) use ($searchStr) {
                    $query->whereRaw('LOWER(name) like ?', [$searchStr]);
                })
                ->orWhereHas('AgreementType', function (Builder $query) use ($searchStr) {
                    $query->whereRaw('LOWER(name) like ?', [$searchStr]);
                })
                ->orWhereHas('seller', function (Builder $query) use ($searchStr) {
                    $query->whereRaw('LOWER(name) like ?', [$searchStr]);
                });
        });
    
        if ($status == "closed") {
            $query->whereNotNull('real_date_close');
        } elseif ($status == "current") {
            $query->whereNull('real_date_close');
        }
    
        if ($cfs_item_id != "all") {
            $cfsItemCode = ($cfs_item_id != "none") ? $cfs_item_id : null;
            $query->where('cfs_item_id', $cfsItemCode);
        }
    
        $agreements = $query->orderByDesc('date_open')->paginate(15);
    
        return $agreements;
    }

    /**
     * получение договоров в зависимости от условий
     */
    public static function index(Request $request): array
    {

        $filter = ($request->get('searchStr')) ?? '';
        $agreementStatus = ($request->get('status'))??'all';
        $cfs_item_id = ($request->get('cfs_item_id')) ?? 'all';

        $agreements = self::getData($filter, $agreementStatus, $cfs_item_id);

        return [
            'agreements' => $agreements,
            'filter' => $filter,
            'agreementStatus' => $agreementStatus,
            'agreementType' => AgreementType::orderBy('name')->get(),
            // 'cfs_item_id' =>$cfs_item_id,
            // 'cfsItems' => CFSItem::query()->orderBy('name')->get(),
        ];
    }

    /**
     *снабжение данными форму редактирования договора
     */
    public static function provideAgreementEditor(Agreement $agreement, $routeName): array
    {
        // $projects = Task::query()->where('parent_task_id','=',null)->select('id','subject')->get();
        // $projects->push(new Task(['id'=>null, 'subject' => "Без проекта"]));
        // $cfsGroups = CFSGroup::orderBy('cfs_section')->orderBy('name')->get();
        // $unitOfMeasurements = UnitOfMeasurement::orderBy('name')->get();
        if (!$agreement->agr_number) {
            $agreement->agr_number = now()->format('Y-m/').(Agreement::count() + 1);
            $agreement->date_open = now()->format('Y-m-d');
            $agreement->date_close = now()->addDays(365)->format('Y-m-d');
        }
        return [
            'agreement' => $agreement,
            'route' => $routeName,
            'agreementTypes' => AgreementType::query()->orderBy('name')->get(),
            'sellers' => Company::query()->orderBy('name')->get(),
            'buyers' => Company::query()->orderBy('name')->get(),
            // 'projects' => $projects,
            // 'cfsGroups' => $cfsGroups,
            // 'unitOfMeasurements' => $unitOfMeasurements
        ];
    }

    public static function create(Request $request): Agreement
    {
        $agreement = new Agreement();
        if (!empty($request->old())) $agreement->fill($request->old());
        return $agreement;
    }

    public static function edit(Request $request, Agreement $agreement)
    {
        if (!empty($request->old())) $agreement->fill($request->old());
    }

    public static function saveChanges(AgreementRequest $request, Agreement $agreement)
    {
        $agreement->fill($request->all());
        if (!$agreement->created_by) $agreement->created_by = Auth::user()->id;
        if ($agreement->id) $agreement->updated_at = now();
        else $agreement->created_at = now();
        $agreement->save();
    }

    public static function store(AgreementRequest $request)
    {
        try {
            $agreement = new Agreement();
            self::saveChanges($request, $agreement);
            session()->flash('message', 'Добавлен новый договор');
        } catch (Error $err) {
            session()->flash('error', 'Не удалось добавить новый договор');
        }

    }

    public static function update(AgreementRequest $request, Agreement $agreement)
    {
        try {
            self::saveChanges($request, $agreement);
            session()->flash('message', 'Данные договора обновлены');
        } catch (Error $err) {
            session()->flash('error', 'Не удалось обновить данные договора');
        }
    }

    public static function detachDocumentFromAgreements(Agreement $agreement, Document $document)
    {
        try {
            $agreement->documents()->detach($document);
            session()->flash('message', 'Документ откреплен от договора');
        } catch (Error $err) {
            session()->flash('error', 'Не удалось открепить документ от договора');
        }
    }

    
}
