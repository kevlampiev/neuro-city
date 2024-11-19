@extends('layouts.big-form')

@section('title')
    Редактирование правила
@endsection

@section('content')

    <h3> 
            Изменение правила ADesk
    </h3>
    <form method="POST" enctype="multipart/form-data" id="rule-form">
    @csrf
        <div class="row">
            
            <div class="col-md-12">
                <div class="bg-white border m-2 p-2">

                    <input type="hidden" name="id" value="{{ $model->id }}">       

                    <div class="form-group">
                        <label for="name">Наименование правила</label>
                        <input class="form-control" type="text" value="{{$model->name}}" name="name">
                    </div>

                </div>
                
                @include('payments.adesk-rules-components.adesk-fields')

                @include('payments.adesk-rules-components.cfs-fields')

                @include('payments.adesk-rules-components.pl-fields')    



                <div class="row mt-3">
                    <div class="col-md-12">
                        <button type="submit mr-2" class="btn btn-primary" @click="onSubmit()">
                                Изменить
                        </button>
                        <a class="btn btn-secondary ms-2" href="{{ route('import.adesk.rules.index') }}">Отмена</a>
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
                            has_accrual: @json($model->has_accrual),
                            accrual_date_offset: @json($model->accrual_date_offset),
                            pl_item_id: @json($model->pl_item_id),
                            accrual_description: @json($model->accrual_description)
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
                        plItems: {!! json_encode($plItems) !!},
                        bankSearch: '',
                        agreementSearch: '',
                        projectSearch: '',
                        cfsItemSearch: '',
                        beneficiarySearch: '',
                        plItemSearch: '',
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
                    filteredPlItems() {
                        return this.plItems.filter(item =>
                            item.name.toLowerCase().includes(this.plItemSearch.toLowerCase())
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
            }).mount("#rule-form")
        });
</script>
@endsection
