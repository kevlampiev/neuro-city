<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\View;

abstract class BaseCrudController extends Controller
{
    protected $model;
    protected $dataService;
    protected $viewFolder;
    protected $modelName;

    public function index(Request $request)
    {
        $data = $this->dataService::index($request);
        return view($this->viewFolder . '.index', $data);
    }

    public function create(Request $request)
    {
        $item = $this->dataService::create($request);
        return view($this->viewFolder . '.edit', $this->dataService::provideEditor($item, 'add'));
    }

    public function store(Request $request): RedirectResponse
    {
        $this->dataService::store($request);
        return redirect()->route($this->viewFolder . '.index');
    }

    public function edit(Request $request, Model $model)
    {
        $this->dataService::edit($request, $model);
        return view($this->viewFolder . '.edit', $this->dataService::provideEditor($model, 'edit'));
    }

    public function update(Request $request, Model $model): RedirectResponse
    {
        $this->dataService::update($request, $model);
        return redirect()->route($this->viewFolder . '.index');
    }

    public function delete(Model $model): RedirectResponse
    {
        $this->dataService::delete($model);
        return redirect()->route($this->viewFolder . '.index');
    }
}
