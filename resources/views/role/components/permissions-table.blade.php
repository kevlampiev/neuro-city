<div class="row">
    <div class="col-md-12">
        <a class="btn btn-outline-info"
           href="{{route('roleAttachPermission', ['role' => $role])}}">
            Добавить разрешение
        </a>
    </div>
</div>

<div class="row m-1">
    <div class="col-md-12">
        <table class="table">
            <thead>
            <tr>
                <th scope="col">№</th>
                <th scope="col">Название</th>
                <th scope="col">Код</th>
                <th scope="col"></th>
            </tr>
            </thead>
            <tbody>
            @forelse($role->permissions as $permission)
                <tr>
                    <th scope="row">{{$loop->index+1}}</th>
                    <td>{{$permission->name}}</td>
                    <td>{{$permission->slug}}</td>
                    <td>
                        <a onclick="return confirm('Действительно удалить разрешение у этой роли?')"
                            href="{{route('roleDetachPermission', ['role' => $role, 'permission' => $permission])}}">
                            &#10007;Удалить разрешение
                        </a>
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
