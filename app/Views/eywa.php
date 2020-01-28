    <div class="mt-5">
        <div class="row mb-5">
            @for users in user
                <div class="col-6 mt-5">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">{{ user.name }} </h5>
                            <a href="mailto:{{ user.email }}" class="btn btn-primary">Contacter</a>
                            <p>{{ user.id }}</p>
                        </div>
                    </div>
                </div>
            @endfor
        </div>
    </div>
    <h1>{{ article_title }}</h1>
    @logged
        logged
    @else
        login
        @alert(danger,you must be logged)
    @endlogged

    @unless(connected)
        you must be logged
    @else
        @alert(success,you are logged successfully)
        you are logged
    @endunless