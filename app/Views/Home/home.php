<h1>welcome</h1>

<ul>
    <li>@link(login:connexion:link)</li>
</ul>

@ago(2020-02-17)
@foreach($users as $user)
    @continue($user->id == 1)
    @if($user->id == 3)
        @continue
    @else
        <li>{{ $user->id }}</li>
    @endif

@endforeach

@history