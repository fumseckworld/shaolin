<h1>welcome</h1>

<ul>
    <li>{login:connexion}</li>
</ul>

@ago(2020-02-17)
@foreach($users as $user)
    @continue($user->id == 1)
    <li>{{ $user->id }}</li>
@endforeach

@history