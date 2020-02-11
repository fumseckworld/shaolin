<h1><?= _('welcome')?></h1>


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

@print(form)

@for user in users
    {{ user.name }}
@endfor