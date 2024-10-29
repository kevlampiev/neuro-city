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

                <div class="form-group">
                    <label for="bank">Банковский счет</label>
                    <select name="bank_account_id" class="form-control" id="bank" v-model="form.bank_account_id">
                        <option v-for="account in accounts" :value="account.id">
                            Владелец: @{{ account.owner_name }} [Банк: @{{ account.bank_name }}, Р.сч: @{{ account.account_number }}]
                        </option>
                    </select>
                </div>

                @include('partials.error', ['field' => 'bank_account_id'])

                <div class="form-group">
                    <label for="agreement">Номер договора</label>
                    <select name="agreement_id" class="form-control" id="agreement" v-model="form.agreement_id">
                        <option v-for="agreement in agreements" :value="agreement.id">
                            @{{ agreement.name }} № @{{ agreement.agr_number }} от @{{ agreement.date_open }}
                            между @{{ agreement.buyer_name }} и @{{ agreement.seller_name }}
                        </option>
                    </select>
                </div>

                @include('partials.error', ['field' => 'agreement_id'])

                <div class="form-group">
                    <label for="inputAmount">Сумма</label>
                    <input type="text" class="form-control" id="inputAmount" name="amount"
                        v-model="form.amount" @blur="formatNumber('amount')" @focus="removeFormatting('amount')">
                </div>

                @include('partials.error', ['field' => 'amount'])

                <div class="form-group">
                    <label for="inputVAT">В т.ч. НДС</label>
                    <input type="text" class="form-control" id="inputVAT" name="VAT"
                        v-model="form.VAT" @blur="formatNumber('VAT')" @focus="removeFormatting('VAT')">
                </div>
                @include('partials.error', ['field' => 'VAT'])

                <div class="form-group">
                    <label for="project_id">Проект</label>
                    <select name="project_id" class="form-control" id="project_id" v-model="form.project_id">
                        <option :value="null">*БЕЗ ПРОЕКТА*</option>
                        <option v-for="project in projects" :value="project.id">@{{ project.name }}</option>
                    </select>
                </div>

                @include('partials.error', ['field' => 'project_id'])

                <div class="form-group">
                    <label for="cfs_item_id">Статья ОДДС</label>
                    <select name="cfs_item_id" class="form-control" id="cfs_item_id" v-model="form.cfs_item_id">
                        <option v-for="item in cfsItems" :value="item.id">@{{ item.name }}</option>
                    </select>
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
                        <button type="submit" class="btn btn-primary" @click="onSubmit()">
                            @if ($model->id)
                                Изменить
                            @else
                                Добавить
                            @endif
                        </button>
                        <a class="btn btn-secondary" href="{{ route('payments.index') }}">Отмена</a>
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
                            date_open: '{{ $model->date_open }}',
                            bank_account_id: '{{ $model->bank_account_id }}',
                            agreement_id: '{{ $model->agreement_id }}',
                            amount: '{{ $model->amount }}',
                            VAT: '{{ $model->VAT }}',
                            description: '{{ $model->description }}',
                            project_id: '{{ $model->project_id }}',
                            cfs_item_id: '{{ $model->cfs_item_id }}',
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
                    };
                },
                mounted() {
                    this.formatNumber('amount');
                    this.formatNumber('VAT');
                    console.log(this.$refs.paymentForm); // Для проверки, что форма найдена
                },
                methods: {
                    formatNumber(field) {
                        if (this.form[field] !== null && this.form[field] !== '') {
                            let rawValue = this.form[field].toString().replace(/\s/g, '').replace(/,/g, '.');
                            let value = parseFloat(rawValue);
                            if (!isNaN(value)) {
                                this.form[field] = value.toLocaleString('ru-RU', {
                                    minimumFractionDigits: 2,
                                    maximumFractionDigits: 2,
                                    useGrouping: true
                                }).replace(/\./g, ',');
                            }
                        }
                    },
                    removeFormatting(field) {
                        this.form[field] = this.form[field].replace(/\s/g, '').replace(/,/g, '.');
                    },
                    beforeSubmit() {
                        this.removeFormatting('amount');
                        this.removeFormatting('VAT');
                    },
                    onSubmit() {
                        this.beforeSubmit(); // Очищаем форматирование перед отправкой
                        
                    }
                }
            }).mount('#payment-form');
        });
    </script>
    
    @endsection
