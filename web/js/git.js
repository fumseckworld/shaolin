$(function ()
{
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

    $("#compare-version-clear").on('click',function (){
        $("#changes_content").text('');
    });

    $("#search-version").on('click',function (){
        $("#releases").toggleClass('d-none');
    });

    $("#contributors_select").on('change',function ()
    {
        let repository = $("#contributors_select").attr('data-repository');
        let author = $( "#contributors_select option:selected" ).val();
        let labels = $(this).attr('data-months').split(',');
        let start_date = labels[1];
        let end_date = labels[labels.length -2];

      $.post('/contributions.php',{author:author,labels:labels,repository:repository},function (data)
      {




          var ctx = document.getElementById('contributions').getContext('2d');

          var myLineChart = new Chart(ctx, {
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
                  title: {
                      display: true,

                      text: "All " +author + " commits between " +start_date +' and ' + end_date
                  }
              }
          });

      });
    });


});