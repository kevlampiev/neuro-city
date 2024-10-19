<?php

namespace App\Http\Controllers\Budget;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CFSItemGroup;
use App\Models\CFSItem;
use App\Http\Requests\Budget\CfrGroupRequest;
use App\Http\Requests\Budget\CfrItemRequest;
use Error;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class CFSItemController extends Controller
{
    
     /**
     * Show the form for creating a new resource.
     *
     * @param Request $request
     * @return Application|Factory|View|Response
     */
    public function create(Request $request, CFSItemGroup $cfsGroup)
    {
        $cfsItem = new CFSItem();
        $cfsItem->group_id = $cfsGroup->id;
        
        if (!empty($request->old())) {
            $cfsGroup->fill($request->old());
        }
        return view('cfss.cfs-item-edit', [
            'cfsItem' => $cfsItem,
            'cfsGroups' => CFSItemGroup::orderBy('cfs_section')->orderBy('direction')->orderBy('name')->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CfrItemRequest $request
     * @return RedirectResponse
     */
    public function store(CfrItemRequest $request): RedirectResponse
    {
        $cfsItem = new CFSItem();
        try {
            $cfsItem->fill($request->all());
            $cfsItem->created_by = Auth::user()->id;
            $cfsItem->save();
            session()->flash('message', 'Добавлена новая статья отчета о движении денежных средств');
        } catch (Error $e) {
            session()->flash('error', 'Ошибка добавления статьи отчета о движении денежных средств');
        }
        return redirect()->route('cfsGroups');
    }

    
    /**
     * Show the form for editing the specified resource.
     * @param Request $request
     * @param CFSItem $cfsItem
     * @return View
     */
    public function edit(Request $request, CFSItem $cfsItem): View
    {
  
        if (!empty($request->old())) {
            $cfsItem->fill($request->old());
        }
        return view('cfss.cfs-item-edit', [
            'cfsItem' => $cfsItem,
            'cfsGroups' => CFSItemGroup::orderBy('cfs_section')->orderBy('direction')->orderBy('name')->get(),
        ]);
    }


    /**
     * Update the specified resource in storage.
     * @param CfrItemRequest $request
     * @param CFSItem $cfsGroup
     * @return RedirectResponse
     */
    public function update(CfrItemRequest $request, CFSItem $cfsItem): RedirectResponse
    {
        $cfsItem->fill($request->all())->save();
        session()->flash('message', 'Информация о статье изменена');
        return redirect()->route('cfsGroups');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param CFSItem $cfsItem
     * @return RedirectResponse
     */
    public function destroy(CFSItem $cfsItem)
    {
        $cfsItem->delete();
        session()->flash('message', 'Информация о статье удалена');
        return redirect()->route('cfsGroups');
    }


}
