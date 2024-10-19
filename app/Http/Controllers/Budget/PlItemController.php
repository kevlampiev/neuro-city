<?php

namespace App\Http\Controllers\Budget;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\PlItem;
use App\Models\PlItemGroup;
use Error;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Budget\PlItemRequest;

class PlItemController extends Controller
{
    
     /**
     * Show the form for creating a new resource.
     *
     * @param Request $request
     * @return Application|Factory|View|Response
     */
    public function create(Request $request, PlItemGroup $plGroup)
    {
        $plItem = new PlItem();
        $plItem->group_id = $plGroup->id;
        
        if (!empty($request->old())) {
            $plGroup->fill($request->old());
        }
        return view('cfss.pl-item-edit', [
            'plItem' => $plItem,
            'plGroups' => PlItemGroup::orderBy('pl_section')->orderBy('direction')->orderBy('name')->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param PlItemRequest $request
     * @return RedirectResponse
     */
    public function store(PlItemRequest $request): RedirectResponse
    {
        $plItem = new PlItem();
        try {
            $plItem->fill($request->all());
            $plItem->created_by = Auth::user()->id;
            $plItem->save();
            session()->flash('message', 'Добавлена новая статья отчета о прибылях и убытках');
        } catch (Error $e) {
            session()->flash('error', 'Ошибка добавления статьи отчета о прибылях и убытках');
        }
        return redirect()->route('plGroups');
    }

    
    /**
     * Show the form for editing the specified resource.
     * @param Request $request
     * @param PlItem $plItem
     * @return View
     */
    public function edit(Request $request, PlItem $plItem): View
    {
  
        if (!empty($request->old())) {
            $plItem->fill($request->old());
        }
        return view('cfss.pl-item-edit', [
            'plItem' => $plItem,
            'plGroups' => PlItemGroup::orderBy('pl_section')->orderBy('direction')->orderBy('name')->get(),
        ]);
    }


    /**
     * Update the specified resource in storage.
     * @param PlItemRequest $request
     * @param PlItem $plGroup
     * @return RedirectResponse
     */
    public function update(PlItemRequest $request, PlItem $plItem): RedirectResponse
    {
        $plItem->fill($request->all())->save();
        session()->flash('message', 'Информация о статье изменена');
        return redirect()->route('plGroups');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param PlItem $plItem
     * @return RedirectResponse
     */
    public function destroy(PlItem $plItem)
    {
        $plItem->delete();
        session()->flash('message', 'Информация о статье удалена');
        return redirect()->route('plGroups');
    }


}
