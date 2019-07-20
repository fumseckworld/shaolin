$(function ()
{

    // COMPARE VERSION BEGIN

    $("#compare-version").on('click',function ()
    {
        let first = $( "#first-release option:selected" ).val();
        let second = $( "#second-release option:selected" ).val();
        let repository = $( this ).attr('data-content');

        $.post('/compare-versions.php',{first:first,second:second,repository:repository},function (data)
        {
            $("#changes_content").empty().append(data);
        });
    });

    // COMPARE VERSION END

    // SEARCH A RELEASE BEGIN



    // SEARCH RELEASE END

    // SEARCH CONTRIBUTORS BEGIN

    $("#search_contributor").on('keyup',function ()
    {
        let input, filter, ul, li, a, i, txtValue;
        input = document.getElementById("search_contributor");
        filter = input.value.toUpperCase();
        ul = document.getElementById("contributors");
        li = ul.getElementsByTagName("li");
        for (i = 0; i < li.length; i++)
        {
            a = li[i].getElementsByTagName("a")[0];
            txtValue = a.textContent || a.innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1)
            {
                li[i].style.display = "";
            } else {
                li[i].style.display = "none";
            }
        }
    });

    // END SEARCH CONTRIBUTOR

    // VERSION BEGIN

    $("#compare-version-clear").on('click',function ()
    {
        $("#changes_content").text('');
    });

    $("#search-version").on('click',function ()
    {
         let x = $("#releases");

         x.toggleClass('d-none');

         if (x.hasClass('d-none'))
             $(this).text('show');
         else
            $(this).text('hide');

    });

    $("#search_release").on('keyup',function ()
    {
        let input, filter, ul, li, a, i, txtValue;
        input = document.getElementById("search_release");
        filter = input.value.toUpperCase();
        ul = document.getElementById("releases");
        li = ul.getElementsByTagName("li");
        for (i = 0; i < li.length; i++)
        {
            a = li[i].getElementsByTagName("a")[0];
            txtValue = a.textContent || a.innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1)
            {
                li[i].style.display = "";
            } else {
                li[i].style.display = "none";
            }
        }
    });

    // END VERSION

    // CONTRIBUTIONS

    $("#contributors_select").on('change',function ()
    {
        let repository = $("#contributors_select").attr('data-repository');
        let author = $( "#contributors_select option:selected" ).val();
        let labels = $(this).attr('data-months').split(',');
        let start_date = labels[1];
        let end_date = labels[labels.length -2];

      $.post('/contributions.php',{author:author,labels:labels,repository:repository},function (data)
      {
            let ctx = document.getElementById('contributions').getContext('2d');
            new Chart(ctx,
            {
                type: 'line',
                data: {
                labels: labels,
                datasets:
                [{
                    label: 'commits',
                    fill: false,
                    data: data.split(','),
                }]
             },
            options:
            {
                title:
                {
                    display: true,
                    text: "All " +author + " commits between " +start_date +' and ' + end_date
                }
            }});

      });
    });

    // END CONTRIBUTIONS

    // TASK
    $("#add-todo").on('click',function ()
    {
        let repository = $(this).attr('data-repository');
        let created_at = $(this).attr('data-date');
        let contributor = $("#todo-contributor option:selected").val();
        let task = $("#todo-task").val();
        let todo_limit = $("#todo-end").val();
        let result = $("#todo-response");
        if (contributor === 'Select a contributor')
        {

            return  false;
        }

        if (task === '')
        {

            return false;
        }

        if (todo_limit === '')
        {

            return false;
        }

        $.post('/todo.php',{repository:repository,created_at:created_at,contributor:contributor,task:task,todo_limit:todo_limit},function (data)
        {
            if(data)
                result.empty().removeClass('d-none').addClass('alert-success').removeClass('alert-danger').html('Todo task was added successfully' + '  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>');
            else
                result.empty().removeClass('d-none').addClass('alert-danger').removeClass('alert-success').html('Todo has not been created' + '  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>');
        });

    });

    $("#close-all-todo").on('click',function ()
    {
        let repository = $(this).attr('data-repository');

        if (confirm('Close all todo ? '))
        {

            $.post('/close-all-todo.php',{repository:repository},function (data)
            {
                if(data)
                    location.reload();
            });

        }

    });

    $("#register").on('click',function ()
    {
        $("#register-form").toggleClass('hidden');
    });

    $("#login").on('click',function ()
    {
        $("#login-form").toggleClass('hidden');
    });
    // END TASK
});