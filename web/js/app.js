



function search_files()
{

    let input, filter, ul, li, a, i, txtValue;
    input = document.getElementById('search-file');
    filter = input.value.toUpperCase();
    ul = document.getElementById('files');
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


}

function search(elem,child)
{
    let input, filter, ul, li, a, i, txtValue;
    input = document.getElementById(elem);
    filter = input.value.toUpperCase();
    ul = document.getElementById(child);
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
}
$(function () {




                $("#contributors_select").change(function ()
                {
                    let name = $("#contributors_select option:selected" ).text();
                    let repository = $(this).attr('data-repository');
                    let label = $(this).attr('data-months');
                    label = label.split(',');


                    $.post('contributions.php',{name:name,repository:repository},function (data){

                        console.log(data);
                        if (data !== "not found")
                        {
                            let ctx = document.getElementById('contributions');
                            let chart = new Chart(ctx, {
                                // The type of chart we want to create
                                type: 'doughnut',
                                label: label,
                                // The data for our dataset

                                options:
                                {
                                    title: {
                                        display: true,
                                        text: name + ' Contributions'
                                    }
                                }

                                // Configuration options go here
                            });
                        }
                    });

                });

});