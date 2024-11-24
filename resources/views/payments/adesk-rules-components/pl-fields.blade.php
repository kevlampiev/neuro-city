<div class="bg-white border m-2 p-2"> 
    <h4>План заполнения начисления</h4>

                <div class="form-group mt-3">
                    <input type="hidden" name="has_accrual" value="false">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="has_accrual" name="has_accrual" 
                            v-model="form.has_accrual">
                        <label class="form-check-label" for="has_accrual">Сделать начисление</label>
                    </div>
                </div>

                @include('partials.error', ['field' => 'has_accrual'])


                    <div v-show="form.has_accrual" class="bg-white p-3">
                        <div class="form-group">
                            <label for="inputDateOpen">Смещение начисления относительно платежа, дней</label>
                            <input type="number" class="form-control" id="inputDateOpen"
                                name="accrual_date_offset" v-model="form.accrual_date_offset">
                        </div> 
                        @include('partials.error', ['field' => 'accrual_date_offset'])


                        <div class="form-group">
                            <label for="pl_item_id"> Статья ОФР</label>
                            <div class="row">
                                <div class = "col-md-2">
                                    <input type="text" class="form-control" 
                                    placeholder="Поиск статьи ОФР" 
                                    @keydown.enter.prevent="$nextTick(() => $refs.pl_item_id.focus())"
                                    v-model="plItemSearch">
                                </div>
                                <div class = "col-md-10">    
                                    <select name="pl_item_id" class="form-control" id="pl_item_id" v-model="form.pl_item_id">
                                        <option v-for="item in filteredPlItems" :value="item.id">@{{ item.name }}</option>
                                    </select>
                                </div>
                            </div>        
                        </div>
                        @include('partials.error', ['field' => 'pl_item_id'])

                        <div class="form-group">
                            <label for="accrual_description">Описание</label>
                            <textarea id="accrual_description" class="form-control" placeholder="Введите описание для начисления, если оно отличается от платежа"
                                    name="accrual_description" v-model="form.accrual_description"></textarea>
                        </div>

                        @include('partials.error', ['field' => 'accrual_description'])
                    </div>
                

</div>