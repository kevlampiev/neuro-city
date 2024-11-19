<div class="bg-white border m-2 p-2"> 
    <h4>План заполнения платежа</h4>
    <div class="row">
        <div class="col-md-6">
            {{-- БАНКОВСКИЙ СЧЕТ --}}
            <div class="form-group">
                <label for="bank">Банковский счет</label>
                <div class="row">
                    <div class="col-md-3">
                        <input type="text" class="form-control" 
                        placeholder="Поиск банк.счета" 
                        @keydown.enter.prevent="$nextTick(() => $refs.bank.focus())"
                        v-model="bankSearch">
                    </div>    
                    <div class="col-md-9">
                        <select name="bank_account_id" class="form-control" id="bank" v-model="form.bank_account_id">
                            <option v-for="account in filteredAccounts" :value="account.id">
                                Владелец: @{{ account.owner_name }} [Банк: @{{ account.bank_name }}, Р.сч: @{{ account.account_number }}]
                            </option>
                        </select>
                    </div>    
                </div>
            </div>
            @include('partials.error', ['field' => 'bank_account_id'])

            {{-- ДОГОВОР --}}
            <div class="form-group">
                <label for="agreement">Номер договора</label>
                <div class="row">
                    <div class="col-md-3">
                        <input type="text" class="form-control" 
                        placeholder="Поиск договора" 
                        @keydown.enter.prevent="$nextTick(() => $refs.agreement.focus())"
                        v-model="agreementSearch">
                    </div>    
                    <div class="col-md-9">
                        <select name="agreement_id" class="form-control" id="agreement" v-model="form.agreement_id">
                            <option v-for="agreement in filteredAgreements" :value="agreement.id">
                                @{{ agreement.name }} № @{{ agreement.agr_number }} от @{{ agreement.date_open }}
                                между @{{ agreement.buyer_name }} и @{{ agreement.seller_name }}
                            </option>
                        </select>
                    </div>
                </div>
            </div>
            @include('partials.error', ['field' => 'agreement_id'])

            <div class="form-group">
                <label for="inputAmount">НДС, %</label>
                <input type="text" class="form-control" id="inputAmount" name="VAT"
                       v-model="form.VAT">
            </div>
            @include('partials.error', ['field' => 'VAT'])
        </div>
        
        <div class="col-md-6">        
            {{-- ПРОЕКТ --}}
            <div class="form-group">
                <label for="project_id">
                    Проект <span class="text-secondary fst-italic">( данные ADеsk: {{ $model->adesk_project_name }} )</span>
                </label>
                <div class="row">
                    <div class="col-md-3">
                        <input type="text" class="form-control" 
                        placeholder="Поиск проекта" 
                        @keydown.enter.prevent="$nextTick(() => $refs.project_id.focus())"
                        v-model="projectSearch">
                    </div>
                    <div class="col-md-9">    
                        <select name="project_id" class="form-control" id="project_id" 
                                v-model="form.project_id">
                            <option v-for="project in filteredProjects" :value="project.id">@{{ project.name }}</option>
                        </select>
                    </div>
                </div>
            </div>
            @include('partials.error', ['field' => 'project_id'])

            {{-- СТАТЬЯ ОДДС --}}
            <div class="form-group">
                <label for="cfs_item_id">
                    Статья ОДДС <span class="text-secondary fst-italic">( данные ADеsk: {{ $model->adesk_cfs_category_name }} )</span>
                </label>
                <div class="row">
                    <div class="col-md-3">
                        <input type="text" class="form-control" 
                        placeholder="Поиск статьи ОДДС" 
                        @keydown.enter.prevent="$nextTick(() => $refs.cfs_item_id.focus())"
                        v-model="cfsItemSearch">
                    </div>
                    <div class="col-md-9">    
                        <select name="cfs_item_id" class="form-control" id="cfs_item_id" v-model="form.cfs_item_id">
                            <option v-for="item in filteredCfsItems" :value="item.id">@{{ item.name }}</option>
                        </select>
                    </div>
                </div>        
            </div>
            @include('partials.error', ['field' => 'cfs_item_id'])

            
        </div>
    </div>
</div>
