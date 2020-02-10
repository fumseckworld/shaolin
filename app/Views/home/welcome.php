<h1><?= _('welcome')?></h1>


@print(form)

@for user in users
    {{ user.name }}
@endfor