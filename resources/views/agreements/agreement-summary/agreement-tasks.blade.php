<div class="row m-1">
    <div class="col-md-12">
        <a href="{{route('admin.addTaskForAgreement', ['agreement' => $agreement])}}" class="btn btn-outline-info">Добавить
            задачу к договору</a>
        <div class="notes-container">

            <table class="table">
                <thead>
                <tr>
                    <th scope="col">Номер задачи</th>
                    <th scope="col">Формулировка</th>
                    <th scope="col">Срок исполнения</th>
                    <th scope="col"></th>
                </tr>
                </thead>
                <tbody>


                @forelse($agreement->tasks as $index=>$task)
                    <tr>
                        <td>#{{$task->id}}</td>
                        <td>{{$task->subject}}</td>
                        <td>{{$task->due_date}}</td>
                        <td><a href="{{route('taskCard', ['task' => $task])}}"> Карточка задачи </a></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4">Нет актуальных задач по договору</td>
                    </tr>
                @endforelse


                </tbody>
            </table>


        </div>

    </div>
</div>
