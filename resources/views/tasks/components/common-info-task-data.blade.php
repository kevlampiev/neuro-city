<div class="row">
    <div class="col-md-8">
        <div class="card bg-light">
            <table class="table">
                <tbody>
                <tr>
                    <td class="text-right">Идентификатор</td>
                    <td><i> #{{$task->id}} </i></td>
                </tr>

                <tr>
                    <td class="text-right">Формулировка</td>
                    <td class="text-monospace
                                    {{$task->importance=='high'?'text-danger':''}}
                                     {{$task->importance=='low'?'text-secondary':''}}
                ">
                        <strong><i> {{$task->subject}} </i></strong>
                    </td>
                </tr>

                <tr>
                    <td class="text-right">Статус задачи</td>
                    <td class="text-monospace"><i>
                            @if($task->terminate_status=='cancel')
                                <span class="text-secondary">
                                            Отменена {{\Carbon\Carbon::parse($task->terminate_date)->format('d.m.Y')}}
                                        </span>
                            @elseif($task->terminate_status=='complete')
                                <span class="text-success">
                                            Выполнена {{\Carbon\Carbon::parse($task->terminate_date)->format('d.m.Y')}}
                                        </span>
                            @else
                                Выполняется ...
                            @endif

                        </i></td>
                </tr>

                <tr>
                    <td class="text-right">Сроки исполнения</td>
                    <td
                        class="text-monospace @if(($task->due_date<\Carbon\Carbon::now())&&(!$task->terminate_date))
                                           text-danger
                                        @elseif((\Carbon\Carbon::parse($task->due_date)->diffInDays(now())<3)&&(!$task->terminate_date))
                                           text-warning
                                        @endif
                    ">
                        <i> {{\Carbon\Carbon::parse($task->start_date)->format('d.m.Y')}} -
                            {{\Carbon\Carbon::parse($task->due_date)->format('d.m.Y')}}</i>
                    </td>
                </tr>
                <tr>
                    <td class="text-right">Постановщик задачи</td>
                    <td class="text-monospace"><i>{{$task->user->name}} </i></td>
                </tr>

                <tr>
                    <td class="text-right">Исполнитель</td>
                    <td class="text-monospace"><i>{{$task->performer->name}} </i></td>
                </tr>
                <tr>
                    <td class="text-right">Дополнительная информация</td>
                    <td class="text-monospace"><i>{{$task->description}} </i></td>
                </tr>
                @if($task->parent_task_id)
                    <tr>
                        <td class="text-right">Родительская задача</td>
                        <td class="text-monospace"><i><a
                                    href="{{route('taskCard', ['task' => \App\Models\Task::find($task->parent_task_id)])}}">
                                    {{\App\Models\Task::find($task->parent_task_id)->subject}}
                                </a> </i></td>
                    </tr>
                @endif
                @if($task->vehicle_id)
                    <tr>
                        <td class="text-right">Связаная единица техники</td>
                        <td class="text-monospace">
                            <i><a href="{{route('vehicleSummary', ['vehicle' => $task->vehicle])}}">
                                    {{$task->vehicle->vehicleType->name}}
                                    {{$task->vehicle->name}},
                                    VIN:{{$task->vehicle->vin}},
                                    Бортовой номер: {{$task->vehicle->bort_number}}
                            </i>
                        </td>
                    </tr>
                @endif
                @if($task->agreement_id)
                    <tr>
                        <td class="text-right">Связаный договор</td>
                        <td class="text-monospace">
                            <i><a href="{{route('agreementSummary', ['agreement' => $task->agreement])}}">
                                    {{$task->agreement->name}}
                                    № {{$task->agreement->agr_number}},
                                    от{{$task->agreement->date_open}}, <br>
                                    Стороны по договору: {{$task->agreement->company->name}}
                                    , {{$task->agreement->counterparty->name}} </i>
                        </td>
                    </tr>
                @endif
                @if($task->counterparty_id)
                    <tr>
                        <td class="text-right">Связанный контрагент</td>
                        <td class="text-monospace"><i>
                                <a href="{{route('counterpartySummary', ['counterparty' => $task->counterparty])}}">
                                    {{$task->counterparty->name}}
                                </a>
                            </i></td>
                    </tr>
                @endif
                @if($task->company_id)
                    <tr>
                        <td class="text-right">Связанная компания группы</td>
                        <td class="text-monospace"><i> {{$task->company->name}} </i></td>
                    </tr>
                @endif
                </tbody>
            </table>
        </div>

    </div>
    <div class="col-md-4">
        <div class="card bg-light">
            <table class="table">
                <tbody>
                <tr>
                    <td>Подписчики <br>
                        <ul class="list-group">
                            @forelse($task->followers as $follower)
                                <li class="list-group-item bg-light text-secondary font-italic">
                                    {{$follower->name}}
                                </li>
                            @empty
                                нет подписчиков
                            @endforelse
                        </ul>
                    </td>
                </tr>
                <tr>
                    <td>Последние сообщения <br>
                        {{-- @forelse($task->messages->slice(0,2) as $message)
                            <div class="text-secondary font-italic m-2 p-2 bg-white">
                                {{html_entity_decode(strip_tags($message->description))}}
                            </div>
                        @empty
                        @endforelse --}}
                    </td>
                </tr>

                </tbody>
            </table>
        </div>
    </div>
</div>
