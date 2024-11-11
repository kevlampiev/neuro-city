@extends('layouts.big-form')

@section('title')
    Редактирование начисления
@endsection

@section('content')
    <h3> 
        @if ($model->id)
            Изменение начисления
        @else
            Добавление начисления
        @endif
    </h3>
    <form method="POST" enctype="multipart/form-data" 
        action="{{ $model->id ? route('accruals.update', ['accrual' => $model->id]) : route('accruals.store') }}"
        @submit.prevent="onSubmit" ref="accrualForm" id="accrual-form">
        @csrf
        @if ($model->id)
            @method('PUT')
        @endif
        <div class="row">
            <div class="col-md-12">
                <input type="hidden" name="id" value="{{$model->id}}">

                <div class="form-group">
                    <label for="inputDateOpen">Дата начисления</label>
                    <input type="date" class="form-control" id="inputDateOpen"
                           name="date_open" v-model="form.date_open">
                </div>

                @include('partials.error', ['field' => 'date_open'])


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
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="inputAmount">Сумма</label>
                            <input type="text" class="form-control" id="inputAmount" name="amount"
                                v-model="form.amount" @blur="formatNumber('amount')" @focus="removeFormatting('amount')">
                        </div>

                        @include('partials.error', ['field' => 'amount'])
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
                    <label for="pl_item_id">Статья ОДДС</label>
                    <div class="row">
                        <div class = "col-md-2">
                            <input type="text" class="form-control" 
                            placeholder="Поиск статьи ОДДС" 
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
                        <a class="btn btn-secondary ms-2" href="{{ route('accruals.index') }}">Отмена</a>
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
                        agreement_id: @json($model->agreement_id),
                        amount: @json($model->amount),
                        description: @json($model->description),
                        project_id: @json($model->project_id),
                        pl_item_id: @json($model->pl_item_id)
                    },
                    agreements: {!! json_encode($agreements->map(fn($a) => [
                        'id' => $a->id,
                        'name' => $a->name,
                        'agr_number' => $a->agr_number,
                        'date_open' => $a->date_open,
                        'buyer_name' => $a->buyer->name,
                        'seller_name' => $a->seller->name,
                    ])) !!},
                    projects: {!! json_encode($projects) !!},
                    plItems: {!! json_encode($plItems) !!},
                    
                    agreementSearch: '',
                    projectSearch: '',
                    plItemSearch: '',
                };
            },
            computed: {
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
                filteredPlItems() {
                    return this.plItems.filter(item =>
                        item.name.toLowerCase().includes(this.plItemSearch.toLowerCase())
                    );
                },
            },
            mounted() {
                this.formatNumber('amount');
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
                onSubmit() {
                    this.removeFormatting('amount');
                    this.$refs.accrualForm.submit();
                }
            }
        }).mount("#accrual-form")
    });
</script>
@endsection
