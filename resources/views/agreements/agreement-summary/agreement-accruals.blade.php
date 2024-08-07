<div class="row m-1">
    <div class="col-md-12">
        <a href="{{route('admin.addAgrAccrual', ['agreement' => $agreement])}}" class="btn btn-outline-info">Добавить
            начисление</a>
        <div class="notes-container">

            <nav>
                <div class="nav nav-tabs nav-stacked" id="accrual-nav-tab" role="tablist">
                    <button class="nav-link active"
                            id="forecast-accruals-tab"
                            data-bs-toggle="tab"
                            data-bs-target="#forecast-accruals"
                            type="button"
                            role="tab"
                            aria-controls="forecast-accruals"
                            aria-selected="true">
                        <i class="bi bi-box-arrow-up-right" aria-hidden="true"></i>
                        Прогнозные начисления
                    </button>
                    <button class="nav-link"
                            id="operational-accruals-tab"
                            data-bs-toggle="tab"
                            data-bs-target="#operational-accruals"
                            type="button"
                            role="tab"
                            aria-controls="operational-accruals"
                            aria-selected="true">
                        <i class="bi bi-lightning-charge" aria-hidden="true"></i>
                        Операционные данные
                    </button>
                    <button class="nav-link"
                            id="confirmed-accruals-tab"
                            data-bs-toggle="tab"
                            data-bs-target="#confirmed-accruals"
                            type="button"
                            role="tab"
                            aria-controls="confirmed-accruals"
                            aria-selected="true">
                        <i class="bi bi-box-seam" aria-hidden="true"></i>
                        Подтвержденные начисления
                    </button>
                </div>
            </nav>


        </div>

        <div class="tab-content p-2" id="nav-tabContent">
            <div class="tab-pane fade show active" id="forecast-accruals" role="tabpanel" aria-labelledby="forecast-accruals-tab">
                <h4>Прогнозные начисления</h4>
                @include('Admin.agreements.agreement-summary.agreement-accrual-tables.forecast-accruals')
            </div>
            <div class="tab-pane fade " id="operational-accruals" role="tabpanel" aria-labelledby="operational-accruals-tab">
                <h4>Оперативные начисления</h4>
                @include('Admin.agreements.agreement-summary.agreement-accrual-tables.operational-accruals')
            </div>
            <div class="tab-pane fade " id="confirmed-accruals" role="tabpanel" aria-labelledby="confirmed-accruals-tab">
                <h4>Подтвержденные начисления</h4>
                @include('Admin.agreements.agreement-summary.agreement-accrual-tables.confirmed-accruals')
            </div>

        </div>

    </div>
</div>
