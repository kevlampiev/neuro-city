{{-- @extends('layouts.big-form') 

@section('title')
    Редактирование платежа
@endsection

@section('content')
    <h3> 
        @if ($model->adesk_id)
            Изменение импортированного платежа на 
            <span class="{{ ($model->adesk_type_operation_code == 1) ? 'text-success' : 'text-danger' }}">
                {{ ($model->adesk_type_operation_code == 1) ? 'приход' : 'РАСХОД' }} № {{ $model->adesk_id }}
            </span>    
        @else
            Добавление платежа
        @endif
    </h3>
    <form method="POST" enctype="multipart/form-data" 
        action="{{ route('import.adesk.payments.update', ['adesk_id' => $model->adesk_id]) }}" 
        id="payment-form">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-md-12">
                <input type="hidden" name="adesk_id" value="{{ $model->adesk_id }}">
                <input type="hidden" name="adesk_type_operation_code" value="{{ $model->adesk_type_operation_code }}">

                <div class="form-group">
                    <label for="inputDateOpen">Дата платежа</label>
                    <input type="date" class="form-control" id="inputDateOpen" 
                           name="date_open" value="{{ $model->date_open }}">
                </div>

                @include('partials.error', ['field' => 'date_open'])

                
                <div class="form-group">
                    <label for="bank">Банковский счет 
                        <span class="text-secondary fst-italic">( данные ADеsk: {{ $model->adesk_bank_name }} )</span> 
                    </label>
                    <div class="row">   
                        <select name="bank_account_id" class="form-control" id="bank">
                                <option value="" {{ $model->bank_account_id == null ? 'selected' : '' }}>
                                    *НЕ ОПРЕДЕЛЕН*
                                </option>
                            @foreach ($accounts as $account)
                                <option value="{{ $account->id }}" {{ $model->bank_account_id == $account->id ? 'selected' : '' }}>
                                    Владелец: {{ $account->owner->name }} [Банк: {{ $account->bank->name }}, Р.сч: {{ $account->account_number }}]
                                </option>
                            @endforeach
                        </select>
                    </div>    
                </div>

                @include('partials.error', ['field' => 'bank_account_id'])

                <div class="form-group">
                    <label for="agreement">Номер договора {{$model->agreement_id}}</label>
                        <select name="agreement_id" class="form-control" id="agreement">
                            <option value="" {{ $model->agreement_id == null ? 'selected' : '' }}>
                                *НЕ ОПРЕДЕЛЕН*
                            </option>
                            @foreach ($agreements as $agreement)
                                <option value="{{ $agreement->id }}" {{ $model->agreement_id == $agreement->id ? 'selected' : '' }}>
                                    {{ $agreement->id }} {{ $agreement->name }} № {{ $agreement->agr_number }} от {{ $agreement->date_open }}
                                    между {{ $agreement->buyer->name }} и {{ $agreement->seller->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                @include('partials.error', ['field' => 'agreement_id'])

                <div class="form-group">
                    <label for="inputAmount">Сумма</label>
                    <input type="text" class="form-control" id="inputAmount" name="amount" value="{{ $model->amount }}">
                </div>

                @include('partials.error', ['field' => 'amount'])

                <div class="form-group">
                    <label for="inputVAT">В т.ч. НДС</label>
                    <input type="text" class="form-control" id="inputVAT" name="VAT" value="{{ $model->VAT }}">
                </div>
                @include('partials.error', ['field' => 'VAT'])

                <div class="form-group">
                    <label for="project_id">Проект 
                        <span class="text-secondary fst-italic">( данные ADеsk: {{ $model->adesk_project_name ?? '--' }} )</span> 
                    </label>
                    <select name="project_id" class="form-control" id="project_id">
                        <option value="" {{ $model->project_id == null ? 'selected' : '' }}>*БЕЗ ПРОЕКТА*</option>
                        @foreach ($projects as $project)
                            <option value="{{ $project->id }}" {{ $model->project_id == $project->id ? 'selected' : '' }}>
                                {{ $project->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                @include('partials.error', ['field' => 'project_id'])

                <div class="form-group">
                    <label for="cfs_item_id">Статья ОДДС
                        <span class="text-secondary fst-italic">( данные ADеsk: {{ $model->adesk_cfs_category_name ?? '--' }} )</span> 
                    </label>
                    <select name="cfs_item_id" class="form-control" id="cfs_item_id">
                        <option value="" {{ $model->cfs_item_id == null ? 'selected' : '' }}>*СТАТЬЯ НЕ ОПРЕДЕЛЕНА*</option>
                        @foreach ($cfsItems as $item)
                            <option value="{{ $item->id }}" {{ $model->cfs_item_id == $item->id ? 'selected' : '' }}>
                                {{ $item->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="beneficiary_id">В пользу кого осуществелен платеж (если плательщик не является стороной по договору)</label>
                    <select name="beneficiary_id" class="form-control" id="beneficiary_id">

                        @foreach ($beneficiaries as $beneficiary)
                            <option value="{{ $beneficiary->id }}">{{ $beneficiary->name }}</option>
                        @endforeach
                    </select>
                </div>

                @include('partials.error', ['field' => 'beneficiary_id'])

                <div class="form-group">
                    <label for="description">Описание</label>
                    <textarea id="description" class="form-control" placeholder="Введите описание" name="description">{{ $model->description }}</textarea>
                </div>


                @include('partials.error', ['field' => 'description'])

                <ul class="p-0 m-0">
                    @foreach($errors->all() as $error)
                        <li class="m-0 p-0">{{ $error }}</li>
                    @endforeach
                </ul>


                <div class="form-group">
                    <label for="has_accrual">Начисление</label>
                    <input type="checkbox" id="has_accrual" name="has_accrual" 
                           class="form-check-input" {{ $model->has_accrual ? 'checked' : '' }}>
                </div>

                <div class="form-group">
                    <label for="accrual_date_open">Дата начисления</label>
                    <input type="date" class="form-control" id="accrual_date_open" 
                           name="accrual_date_open" value="{{ $model->accrual_date_open }}">
                </div>

                @include('partials.error', ['field' => 'accrual_date_open'])

                <div class="form-group">
                    <label for="pl_item_id">Статья начисления</label>
                    <select name="pl_item_id" class="form-control" id="pl_item_id">
                        <option value="" {{ $model->pl_item_id == null ? 'selected' : '' }}>*СТАТЬЯ НЕ ОПРЕДЕЛЕНА*</option>
                        @foreach ($plItems as $item)
                            <option value="{{ $item->id }}" {{ $model->pl_item_id == $item->id ? 'selected' : '' }}>
                                {{ $item->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                @include('partials.error', ['field' => 'pl_item_id'])


                <div class="row mt-3">
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary">
                            @if ($model->id)
                                Изменить
                            @else
                                Добавить
                            @endif
                        </button>
                        <a class="btn btn-secondary" href="{{ route('import.adesk.payments.index') }}">Отмена</a>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection --}}


@extends('layouts.big-form')

@section('title')
    Редактирование платежа ADesk
@endsection

@section('content')

    <h3 class={{($model->adesk_type_operation_code==1)?'text-success':'text-danger'}}> 
            Изменение платежа ADesk {{($model->adesk_type_operation_code==1)?' по приходу':' по расходу'}}
    </h3>
    <form method="POST" enctype="multipart/form-data" 
        action="{{ route('import.adesk.payments.update', ['adesk_id' => $model->adesk_id]) }}"
        @submit.prevent="onSubmit" ref="paymentForm" id="payment-form">
        @csrf
        @if ($model->adesk_id)
            @method('PUT')
        @endif

        <div class="row">
            
            <div class="col-md-12">
                
                <input type="hidden" name="adesk_id" value="{{ $model->adesk_id }}">
                <input type="hidden" name="adesk_type_operation_code" value="{{ $model->adesk_type_operation_code }}">

                <div class="form-group">
                    <label for="inputDateOpen">Дата платежа</label>
                    <input type="date" class="form-control" id="inputDateOpen"
                        name="date_open" v-model="form.date_open">
                </div>                      
        
                @include('partials.error', ['field' => 'date_open'])
                

                {{-- БАНКОВСКИЙ СЧЕТ --}}
                <div class="form-group">
                    <label for="bank">
                        Банковский счет
                        <span class="text-secondary fst-italic">( данные ADеsk: {{ $model->adesk_bank_name }} )</span>
                    </label>
                    <div class="row">
                        <div class="col-md-2">
                            <input type="text" class="form-control" 
                            placeholder="Поиск банковского счета" 
                            @keydown.enter.prevent="$nextTick(() => $refs.bank.focus())"
                            v-model="bankSearch">
                        </div>    
                        <div class="col-md-10">
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
                    <label for="agreement">
                        Номер договора
                    </label>
                    <div class="row">
                        <div class="col-md-2">
                            <input type="text" class="form-control" 
                            placeholder="Поиск договора" 
                            @keydown.enter.prevent="$nextTick(() => $refs.agreement.focus())"
                            v-model="agreementSearch">
                        </div>    
                        <div class="col-md-10">
                            <select name="agreement_id" class="form-control" id="agreement" v-model="form.agreement_id">
                                <option v-for="agreement in filteredAgreements" :value="agreement.id">
                                    @{{ agreement.name }} № @{{ agreement.agr_number }} от @{{ agreement.date_open }}
                                    между @{{ agreement.buyer_name }} и @{{ agreement.seller_name }}
                                </option>
                            </select>
                        </div>
                    <div>        
                </div>

                @include('partials.error', ['field' => 'agreement_id'])

                <div class="row">
                    <div class="col-md-7">
                        <div class="form-group">
                            <label for="inputAmount">Сумма</label>
                            <input type="text" class="form-control" id="inputAmount" name="amount"
                                v-model="form.amount" @blur="formatNumber('amount')" @focus="removeFormatting('amount')">
                        </div>

                        @include('partials.error', ['field' => 'amount'])
                    </div>    
                    <div class="col-md-1 d-flex align-items-end">
                         <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                            Ставки НДС
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                            <li><a class="dropdown-item" href="#" @click="setVAT(20)">20%</a></li>
                            <li><a class="dropdown-item" href="#" @click="setVAT(10)">10%</a></li>
                            <li><a class="dropdown-item" href="#" @click="setVAT(0)">Без НДС</a></li>
                        </ul>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="inputVAT">В т.ч. НДС</label>
                            <input type="text" class="form-control" id="inputVAT" name="VAT"
                                v-model="form.VAT" @blur="formatNumber('VAT')" @focus="removeFormatting('VAT')">
                        </div>
                        @include('partials.error', ['field' => 'VAT'])
                    </div>    
                </div>


                {{-- ПРОЕКТ --}}
                <div class="form-group">
                    <label for="project_id">
                        Проект <span class="text-secondary fst-italic">( данные ADеsk: {{ $model->adesk_project_name }} )</span>
                    </label>
                    <div class="row">
                        <div class="col-md-2">
                            <input type="text" class="form-control" 
                            placeholder="Поиск проекта" 
                            @keydown.enter.prevent="$nextTick(() => $refs.project_id.focus())"
                            v-model="projectSearch">
                        </div>
                        <div class="col-md-10">    
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
                        <div class = "col-md-2">
                            <input type="text" class="form-control" 
                            placeholder="Поиск статьи ОДДС" 
                            @keydown.enter.prevent="$nextTick(() => $refs.cfs_item_id.focus())"
                            v-model="cfsItemSearch">
                        </div>
                        <div class = "col-md-10">    
                            <select name="cfs_item_id" class="form-control" id="cfs_item_id" v-model="form.cfs_item_id">
                                <option v-for="item in filteredCfsItems" :value="item.id">@{{ item.name }}</option>
                            </select>
                        </div>
                    </div>        
                </div>
                @include('partials.error', ['field' => 'cfs_item_id'])

                <div class="form-group">
                    <label for="description">Описание</label>
                    <textarea id="description" class="form-control" placeholder="Введите описание"
                              name="description" v-model="form.description"></textarea>
                </div>

                @include('partials.error', ['field' => 'description'])

                <div class="row mt-3">
                    <div class="col-md-12">
                        <button type="submit mr-2" class="btn btn-primary" @click="onSubmit()">
                            @if ($model->id)
                                Изменить
                            @else
                                Добавить
                            @endif
                        </button>
                        <a class="btn btn-secondary" href="{{ route('import.adesk.payments.index') }}">Отмена</a>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/vue@3/dist/vue.global.min.js"></script>
<script defer>
        document.addEventListener("DOMContentLoaded", function() {
            Vue.createApp({
                data() {
                    return {
                        form: {
                            date_open: @json($model->date_open),
                            bank_account_id: @json($model->bank_account_id),
                            agreement_id: @json($model->agreement_id),
                            amount: @json($model->amount),
                            VAT: @json($model->VAT),
                            description: @json($model->description),
                            project_id: @json($model->project_id),
                            cfs_item_id: @json($model->cfs_item_id),
                            beneficiary_id: @json($model->beneficiary_id),
                        },
                        accounts: {!! json_encode($accounts->map(fn($a) => [
                            'id' => $a->id,
                            'owner_name' => $a->owner->name,
                            'bank_name' => $a->bank->name,
                            'account_number' => $a->account_number,
                        ])) !!},
                        agreements: {!! json_encode($agreements->map(fn($a) => [
                            'id' => $a->id,
                            'name' => $a->name,
                            'agr_number' => $a->agr_number,
                            'date_open' => $a->date_open,
                            'buyer_name' => $a->buyer->name,
                            'seller_name' => $a->seller->name,
                        ])) !!},
                        projects: {!! json_encode($projects) !!},
                        cfsItems: {!! json_encode($cfsItems) !!},
                        beneficiaries: {!! json_encode($beneficiaries) !!},
                        
                        bankSearch: '',
                        agreementSearch: '',
                        projectSearch: '',
                        cfsItemSearch: '',
                        beneficiarySearch: ''
                    };
                },
                computed: {
                    filteredAccounts() {
                        return this.accounts.filter(account =>
                            (account.owner_name + account.bank_name + account.account_number)
                                .toLowerCase()
                                .includes(this.bankSearch.toLowerCase())
                        );
                    },
                    filteredAgreements() {
                        return this.agreements.filter(agreement =>
                            (agreement.name + agreement.agr_number + agreement.date_open + 
                            agreement.buyer_name + agreement.seller_name)
                                .toLowerCase()
                                .includes(this.agreementSearch.toLowerCase())
                        );
                    },
                    filteredProjects() {
                            return this.projects.filter(project =>
                                    project.name.toLowerCase().includes(this.projectSearch.toLowerCase())
                                );
                        },
                    filteredCfsItems() {
                        return this.cfsItems.filter(item =>
                            item.name.toLowerCase().includes(this.cfsItemSearch.toLowerCase())
                        );
                    },
                    filteredBeneficiaries() {
                        return this.beneficiaries.filter(item =>
                            item.name.toLowerCase().includes(this.beneficiarySearch.toLowerCase())
                        );
                    },
                },
                mounted() {
                    this.formatNumber('amount');
                    this.formatNumber('VAT');
                },
                methods: {
                    formatNumber(field) {
                        if (this.form[field] !== null && this.form[field] !== '') {
                            let rawValue = this.form[field].toString().replace(/\s/g, '').replace(/,/g, '.');
                            let value = parseFloat(rawValue).toLocaleString('ru-RU', {
                                minimumFractionDigits: 2, maximumFractionDigits: 2
                            });
                            this.form[field] = value;
                        }
                    },
                    removeFormatting(field) {
                        if (this.form[field]) {
                            this.form[field] = this.form[field].replace(/\s/g, '').replace(',', '.');
                        }
                    },
                    setVAT(rate) {
                        const amount = parseFloat(this.form.amount.replace(/\s/g, '').replace(',', '.'));
                        if (!isNaN(amount)) {
                            this.form.VAT = rate === 0 ? 0 : rate === 20 ? amount / 6 : amount / 11;
                            this.formatNumber('VAT');
                        }
                    },
                    handleProjectSelection() {
                        if (!this.form.project_id || (this.form.project_id == "*БЕЗ ПРОЕКТА*")) {
                            this.form.project_id = null;
                        }
                    },
                    onSubmit() {
                        this.removeFormatting('amount');
                        this.removeFormatting('VAT');  
                        handleProjectSelection();
                        this.$refs.paymentForm.submit();
                    }
                }
            }).mount("#payment-form")
        });
</script>
@endsection
