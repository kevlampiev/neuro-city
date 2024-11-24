<div class="bg-white border m-2 p-2"> 
    <h4>Параметры операции Adesk</h4>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="adesk_type_operation_code">Тип операции</label>
                <select name="adesk_type_operation_code" class="form-control" id="adesk_type_operation_code">
                    <option value="1" {{($model->adesk_type_operation_code==1)?'selected':''}}> Приход </option>
                    <option value="2" {{($model->adesk_type_operation_code==2)?'selected':''}}> Расход </option>
                    <option value="3" {{($model->adesk_type_operation_code==31)?'selected':''}}> Перемещение </option>
                </select>
            </div>                             
            @include('partials.error', ['field' => 'adesk_type_operation_code'])            

            <div class="form-group">
                <label for="adesk_bank_name">Банковский счет</label>
                <input class="form-control"  type="text" value="{{$model->adesk_bank_name}}" name="adesk_bank_name">
            </div>

            <div class="form-group">
                <label for="adesk_company_name">Компания</label>
                <input class="form-control" type="text" value="{{$model->adesk_company_name}}" name="adesk_company_name">
            </div>

            <div class="form-group">
                <label for="adesk_description">Описание содержит</label>
                <input class="form-control" type="text" value="{{$model->adesk_description}}" name="adesk_description">
            </div>
        </div>
        <div class="col-md-6">    

            <div class="form-group">
                <label for="adesk_cfs_category_name">Статья ОДДС ADesk</label>
                <input class="form-control" type="text" value="{{$model->adesk_cfs_category_name}}" name="adesk_cfs_category_name">
            </div>

            <div class="form-group">
                <label for="adesk_contractor_name">Контрагент</label>
                <input class="form-control" type="text" value="{{$model->adesk_contractor_name}}" name="adesk_contractor_name">
            </div>

            <div class="form-group">
                <label for="adesk_project_name">Проект</label>
                <input class="form-control" type="text" value="{{$model->adesk_project_name}}" name="adesk_project_name">
            </div>
        </div>    
    </div>    
</div>