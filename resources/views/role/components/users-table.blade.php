<div class="row">
    <div class="col-md-12">
        <a class="btn btn-outline-info"
           href="{{route('roleAttachUser', ['role' => $role])}}">
            Добавить сотрудника
        </a>
    </div>
</div>

<div class="row m-1">
    <div class="col-md-12">
        <table class="table">
            <thead>
            <tr>
                <th scope="col">№</th>
                <th scope="col">ФИО</th>
                <th scope="col">Телефон</th>
                <th scope="col">e-mail</th>
                <th scope="col"></th>
                <th scope="col"></th>
            </tr>
            </thead>
            <tbody>
            @forelse($role->users as $user)
                <tr>
                    <th scope="row">{{$loop->index+1}}</th>
                    <td>{{$user->name}}</td>
                    <td>{{$user->phone}}</td>
                    <td>{{$user->email}}</td>
                    <td>
                        <a href="{{route('userSummary', ['user' => $user])}}"> &#9776;Карточка</a>
                    </td>
                    <td>
                        <a onclick="return confirm('Действительно отозвать роль у пользователя')"
                            href="{{route('roleDetachUser', ['role'=>$role, 'user' => $user])}}"> &#10007;Отозвать роль </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <th colspan="4" class="text-secondary font-italic">Нет данных для отображения</th>
                </tr>
            @endforelse
            </tbody>
        </table>

    </div>
</div>
