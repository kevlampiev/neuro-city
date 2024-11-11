@extends('layouts.big-form')

@section('title')
    Редактирование платежа
@endsection

@section('content')
    <h3> 
        @if ($model->id)
            Изменение платежа
        @else
            Добавление платежа
        @endif
    </h3>
    <form method="POST" enctype="multipart/form-data" 
        action="{{ $model->id ? route('payments.update', ['payment' => $model->id]) : route('payments.store') }}"
        @submit.prevent="onSubmit" ref="paymentForm" id="payment-form">
        @csrf
        @if ($model->id)
            @method('PUT')
        @endif
        <div class="row">
            <div class="col-md-12">
                <input type="hidden" name="id" value="{{$model->id}}">

                <div class="form-group">
                    <label for="inputDateOpen">Дата платежа</label>
                    <input type="date" class="form-control" id="inputDateOpen"
                           name="date_open" v-model="form.date_open">
                </div>

                @include('partials.error', ['field' => 'date_open'])


                {{-- БАНКОВСКИЙ СЧЕТ --}}
                <div class="form-group">
                    <label for="bank">Банковский счет</label>
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
                    <label for="agreement">Номер договора</label>
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
                    <label for="project_id">Проект</label>
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
                    <label for="cfs_item_id">Статья ОДДС</label>
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
                        <a class="btn btn-secondary ms-2" href="{{ route('payments.index') }}">Отмена</a>
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
                            const allProjects = [
                                { id: null, name: "*БЕЗ ПРОЕКТА*" },
                                ...this.projects
                            ];

                            // Фильтруем только если есть поисковый запрос
                            return this.projectSearch
                                ? allProjects.filter(project =>
                                    project.name.toLowerCase().includes(this.projectSearch.toLowerCase())
                                )
                                : allProjects;
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
