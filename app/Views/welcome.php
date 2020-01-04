<div class="text-center mt-5">
    <h1><?= _('welcome') ?></h1>

    @for(tables as user)
        @switch(user)
            @case users
               <p> {{ Welcome willy }}</p>
            @break
            @default
              <p>  {{ $user }}</p>
        @endswitch

    @endfor
    @if(true)
        {{ bonjour }}
    @elseif(false)
        {{ jhello }}
    @else
        {{ super }}
    @endif

</div>
