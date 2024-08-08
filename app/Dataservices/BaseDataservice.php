<?php

namespace App\Dataservices;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

abstract class BaseDataservice
{
    protected static $model;

    public static function index(Request $request): array
    {
        return ['items' => static::$model::paginate(15)];
    }

    public static function create(Request $request): Model
    {
        $model = new static::$model();
        if (!empty($request->old())) $model->fill($request->old());
        return $model;
    }

    public static function edit(Request $request, Model $model)
    {
        if (!empty($request->old())) $model->fill($request->old());
    }

    public static function store(Request $request): void
    {
        $model = new static::$model();
        $model->fill($request->all());
        $model->save();
    }

    public static function update(Request $request, Model $model): void
    {
        $model->fill($request->all());
        $model->save();
    }

    public static function delete(Model $model): void
    {
        $model->delete();
    }

    public static function provideEditor(Model $model, $routeName): array
    {
        return ['model' => $model, 'route' => $routeName];
    }
}
