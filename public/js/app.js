$(function ()
{

    const contributors_element = $('#contributors');
    let contributors = contributors_element.attr('data-content');
    contributors = contributors.split(',');
    for (let i = 0;i<contributors.length;i++)
    {
        contributors_element.append('<div class="col-4"><div class="card ml-4 mr-4 mt-4 mb-4"><div class="card-body"><h5 class="card-title text-center text-uppercase">'+contributors[i]+'</h5></div></div></div>');
    }
    $("#git-search").keyup(function ()
    {
        let  user = $(this).val();

        $.post('users.php',{user:user},function (data)
        {

            $.post('contributions.php',{user:data[0].name},function (data)
            {

                let x = [];
                let contributions = data.contributions;
                let months = data.months;

                for (let i = 0; i < 12; i++)
                {
                    x.push({
                        label: months[i],
                        y: contributions[i]
                    });
                }
                var line = new CanvasJS.Chart("line", {
                    animationEnabled: true,
                    title:{
                        text: data.author_name + ' ' +data.year
                    },

                    axisX:{
                        title: data.repository,
                        interval: 1
                    },
                    axisY2:{
                        title: "Commit"
                    },
                    data: [
                        {
                            type: "line",
                            name: "companies",
                            axisYType: "secondary",
                            color: "#014D65",
                            dataPoints: x
                        }]
                });
                line.render();
            });

        });


    });
});
