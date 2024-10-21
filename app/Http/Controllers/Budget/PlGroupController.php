<?php

namespace App\Http\Controllers\Budget;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Budget\CfrGroupRequest;
use App\Models\PlItemGroup;
use Error;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Budget\PlGroupRequest;

class PlGroupController extends Controller
{
    public function index()
    {
        //'sales', 'cogs', 'total_production_costs', 'commercial_costs', 'management_costs','other_pl', 'DA', 'interests','tax'
        $PlSales = PlItemGroup::where('pl_section','sales')->orderBy('weight')->orderBy('name')->get();
        $PlCogs = PlItemGroup::where('pl_section','cogs')->orderBy('weight')->orderBy('name')->get();
        $PlIndirect = PlItemGroup::where('pl_section','indirect_costs')->orderBy('weight')->orderBy('name')->get();
        $PlDA = PlItemGroup::where('pl_section','DA')->orderBy('weight')->orderBy('name')->get();
        $PlInterests = PlItemGroup::where('pl_section','interests')->orderBy('weight')->orderBy('name')->get();
        $PlTax = PlItemGroup::where('pl_section','tax')->orderBy('weight')->orderBy('name')->get();
        

        return view('cfss.pl-groups', [
            'sales' => $PlSales, 
            'cogs' => $PlCogs, 
            'indirect_costs' => $PlIndirect, 
            'DA' => $PlDA, 
            'interests' => $PlInterests,
            'tax' => $PlTax,
        ]);
    }

     /**
     * Show the form for creating a new resource.
     *
     * @param Request $request
     * @return Application|Factory|View|Response
     */
    public function create(Request $request)
    {
        $plGroup = new PlItemGroup();
        if (!empty($request->old())) {
            $plGroup->fill($request->old());
        }
        return view('cfss.pl-group-edit', [
            'plGroup' => $plGroup,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param InsuranceCompanyRequest $request
     * @return RedirectResponse
     */
    public function store(PlGroupRequest $request): RedirectResponse
    {
        $plGroup = new PlItemGroup();
        try {
            $plGroup->fill($request->all());
            $plGroup->created_by = Auth::user()->id;
            $plGroup->save();
            session()->flash('message', 'Добавлена новая группа статей отчета о прибылях и убытках');
        } catch (Error $e) {
            session()->flash('error', 'Ошибка добавления статьи отчета о прибылях и убытках');
        }
        return redirect()->route('plGroups');
    }

    
    /**
     * Show the form for editing the specified resource.
     * @param Request $request
     * @param CFSGroup $cfsGroup
     * @return View
     */
    public function edit(Request $request, PlItemGroup $plGroup): View
    {
        if (!empty($request->old())) {
            $plGroup->fill($request->old());
        }
        return view('cfss.pl-group-edit', [
            'plGroup' => $plGroup,
        ]);
    }


    /**
     * Update the specified resource in storage.
     * @param CfrGroupRequest $request
     * @param CFSGroup $cfsGroup
     * @return RedirectResponse
     */
    public function update(PlGroupRequest $request, PlItemGroup $plGroup): RedirectResponse
    {

        $plGroup->fill($request->all())->save();
        session()->flash('message', 'Информация о группе изменена');
        return redirect()->route('plGroups');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param CFSGroup $cfsGroup
     * @return RedirectResponse
     */
    public function destroy(PlItemGroup $plGroup)
    {
        $plGroup->delete();
        session()->flash('message', 'Информация о группе удалена');
        return redirect()->route('plGroups');
    }


}
