@forelse($user->roles as $index=>$role)
    <div id="role{{$loop->index}}">
        <div>
            <h6 >
                    роль: &nbsp;
                    <strong> {{$role->name}} &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; </strong>
                <a href="{{route('roleSummary', ['role'=>$role])}}"> &#9776;Карточка роли &nbsp; &nbsp;</a>
                    <a onclick="return confirm('Действительно отозвать роль у пользователя?')"
                       href="{{route('detachRoleFromUser', ['role'=>$role, 'user' => $user])}}"> &#10007;Отозвать роль &nbsp; &nbsp;</a>

            </h6>
            <div >
                <div class="accordion-body">
                    <ul>
                        @forelse($role->permissions as $permission)
                            <li>{{$permission->name}}</li>
                        @empty
                            <p class="font-italic text-secondary">У роли нет разрешений</p>
                        @endempty
                    </ul>
                </div>
            </div>
        </div>
    </div>
@empty
    <p class="font-italic text-secondary">У пользователя нет назначенных ролей</p>
@endforelse


