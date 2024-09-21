<?php


namespace App\Dataservices;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BaseCRUDDataservice
{
    protected Model $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function index(Request $request):array
    {
        return ['items' => $this->model->paginate(15)];
    }  

    public function create(Request $request): Model
    {
        $model = new $this->model;
        if (!empty($request->old())) $model->fill($request->old());
        return $model;
    }

    public function edit(Request $request, Model $model): void
    {
        if (!empty($request->old())) $model->fill($request->old());
    }

    public function store(Request $request): void
    {
        $model = new $this->model;
        $model->fill($request->except('id'));
        $model->created_by = Auth::user()->id;
        $model->save();
    }

    public function update(Request $request, Model $model): void
    {
        $model->fill($request->all());
        $model->save();
    }

    public function delete(Model $model): void
    {
        $model->delete();
    }

    public function provideEditor(Model $model, $routeName): array
    {
        return ['model' => $model, 'route' => $routeName];
    }
}
    

