<h6>Отдельные разрешения</h6>
<div class = "ml-3">

    @forelse($user->permissions as $index=>$permission)
        <ul>
            <li>
                Разрешение: {{$permission->name}} &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                <a onclick="return confirm('Действительно отозвать разрешение у пользователя')"
                   href="{{route('admin.detachPermissionFromUser', ['user' => $user, 'permission' => $permission])}}">
                    &#10007;Отозвать разрешение
                </a>
            </li>
        </ul>
    @empty
        <p class="pl-3 font-italic text-secondary">У пользователя нет отдельных разрешений</p>
    @endforelse
</div>
