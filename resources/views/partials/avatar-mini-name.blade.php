@if(!$user->photo)
    <img src="{{ asset('unknown_user.jpg') }}" alt="Нет фото"
         class="rounded-circle float-start border-info half-gray" 
         style="width: 25px; height: 25px; object-fit: cover;">
@else
    <img src="{{ route('avatar.get', ['filename' => $user->photo]) }}" alt="User photo"
         class="rounded-circle float-start border-info half-gray" 
         style="width: 25px; height: 25px; object-fit: cover;">
@endif
&thinsp; {{ $user->name }}