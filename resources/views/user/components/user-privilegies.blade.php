
<div class="row">
    <div class="col-md-12 mb-3">
        <a class="btn btn-outline-info" href="{{route('admin.userAddRole', ['user' => $user])}}">Добавить роль</a>
        <a class="btn btn-outline-info" href="{{route('admin.userAddPermission' , ['user' => $user])}}">Добавить отдельную привилегию</a>
    </div>
</div>

<div class="row m-1">
    <div class="col-md-12">
        @include('Admin.user.components.user-roles')
        @include('Admin.user.components.user-permissions')
    </div>
</div>


