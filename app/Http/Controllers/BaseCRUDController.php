<?php
namespace App\Http\Controllers;

use App\Dataservices\BaseCRUDDataservice;
use Illuminate\Http\Request;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Database\Eloquent\Model;

abstract class BaseCRUDController extends Controller
{
    protected $dataservice;
    protected $modelClass;
    protected $routePrefix; // Префикс маршрутов для редиректов
    protected $editViewName;

    public function __construct(BaseCRUDDataservice $dataservice, $modelClass, $routePrefix, $editViewName=null)
    {
        $this->dataservice = $dataservice;
        $this->modelClass = $modelClass;
        $this->routePrefix = $routePrefix;
        $this->editViewName = $editViewName??"edit";
        
    }

    // Получение списка элементов
    public function index(Request $request)
    {
        $data = $this->dataservice->index($request);
        return view("{$this->routePrefix}.index", $data); // Переход на представление с элементами
    }

    // Отображение формы создания
    public function create(Request $request)
    {
        $model = $this->dataservice->create($request);
        return view("{$this->routePrefix}.{$this->editViewName}", ['model' => $model]);
    }

    // Сохранение нового элемента
    public function store(FormRequest $request)
    {
        $this->dataservice->store($request);
        return redirect()->route("{$this->routePrefix}.index")->with('message', 'Запись успешно создана');
    }

    // Отображение формы редактирования
    public function edit($id, Request $request)
    {
        $model = $this->modelClass::findOrFail($id); // Поиск модели
        $this->dataservice->edit($request, $model);
        return view("{$this->routePrefix}.{$this->editViewName}", ['model' => $model]);
    }

    // Обновление существующего элемента
    public function update(FormRequest $request, $id)
    {
        $model = $this->modelClass::findOrFail($id);
        $this->dataservice->update($request, $model);
        return redirect()->route("{$this->routePrefix}.index")->with('message', 'Запись успешно изменена');
    }

    // Удаление элемента
    public function destroy($id)
    {
        $model = $this->modelClass::findOrFail($id);
        $this->dataservice->delete($model);
        return redirect()->route("{$this->routePrefix}.index")->with('message', 'Запись удалена');
    }
}
