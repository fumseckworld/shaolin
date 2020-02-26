<h1><?= _('welcome')?></h1>

@print(form)

@url('/')
root
@endurl


@admin
    admin
@else
    guest
@endadmin

@redac
    redac
@else
    not redac
@endredac
@for user in users
    {{ user.name }}
@endfor