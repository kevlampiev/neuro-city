<?php

namespace App\Http\Controllers\Budget;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CFSItemGroup;
use App\Http\Requests\Budget\CfrGroupRequest;
use Error;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class CFSGroupController extends Controller
{
    public function index()
    {
        $CFOGroups = CFSItemGroup::where('cfs_section','operations')->orderBy('weight')->orderBy('name')->get();
        $CFIGroups = CFSItemGroup::where('cfs_section','investment')->orderBy('weight')->orderBy('name')->get();
        $CFFGroups = CFSItemGroup::where('cfs_section','finance')->orderBy('weight')->orderBy('name')->get();

        return view('cfss.cfs-groups', ['CFOGroups'=>$CFOGroups, 'CFIGroups'=>$CFIGroups, 'CFFGroups'=>$CFFGroups]);
    }

     /**
     * Show the form for creating a new resource.
     *
     * @param Request $request
     * @return Application|Factory|View|Response
     */
    public function create(Request $request)
    {
        $cfsGroup = new CFSItemGroup();
        if (!empty($request->old())) {
            $cfsGroup->fill($request->old());
        }
        return view('cfss.cfs-group-edit', [
            'cfsGroup' => $cfsGroup,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param InsuranceCompanyRequest $request
     * @return RedirectResponse
     */
    public function store(CfrGroupRequest $request): RedirectResponse
    {
        $cfsGroup = new CFSItemGroup();
        try {
            $cfsGroup->fill($request->all());
            $cfsGroup->created_by = Auth::user()->id;
            $cfsGroup->save();
            session()->flash('message', 'Добавлена новая группа статей отчета о движении денежных средств');
        } catch (Error $e) {
            session()->flash('error', 'Ошибка добавления статьи отчета о движении денежных средств');
        }
        return redirect()->route('cfsGroups');
    }

    
    /**
     * Show the form for editing the specified resource.
     * @param Request $request
     * @param CFSGroup $cfsGroup
     * @return View
     */
    public function edit(Request $request, CFSItemGroup $cfsGroup): View
    {
        if (!empty($request->old())) {
            $cfsGroup->fill($request->old());
        }
        return view('cfss.cfs-group-edit', [
            'cfsGroup' => $cfsGroup,
        ]);
    }


    /**
     * Update the specified resource in storage.
     * @param CfrGroupRequest $request
     * @param CFSGroup $cfsGroup
     * @return RedirectResponse
     */
    public function update(CfrGroupRequest $request, CFSItemGroup $cfsGroup): RedirectResponse
    {

        $cfsGroup->fill($request->all())->save();
        session()->flash('message', 'Информация о группе изменена');
        return redirect()->route('cfsGroups');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param CFSGroup $cfsGroup
     * @return RedirectResponse
     */
    public function destroy(CFSItemGroup $cfsGroup)
    {
        $cfsGroup->delete();
        session()->flash('message', 'Информация о группе удалена');
        return redirect()->route('cfsGroups');
    }


}
