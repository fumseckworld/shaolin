
<h1>welcome</h1>
@foreach($users as $user)
    @continue($user->id == 1)
    <li>{{ $user->id }}</li>
@endforeach

@history