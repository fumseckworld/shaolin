<h1><?= _('welcome')?></h1>

<form action="@route(server:post)" method="post"    enctype="multipart/form-data">
    @csrf
    <input type="file" name="files[]" multiple>
    <button type="submit" >envoyer</button>

</form>

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