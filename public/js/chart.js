$(document).ready(function () {
});

// BAR CHART
function barChart() {

    var el = $('#chartBar')
    if(!el.length){
        return false;
    }

    var ctxThree = document.getElementById("chartBar").getContext('2d');
    var chartThree = new Chart(ctxThree, {
        type: 'bar',
        data: {
            labels: [
                "valpak",
                "google",
                "facebook",
                "telegram",
                "VK"],
            datasets: [{
                backgroundColor: [
                    "#F8A5CD",
                    "#A5E9F8",
                    "#D8F8A5",
                    "#F8A5A5",
                    "#A5F8E4"
                ],

                data: [19, 3, 17, 28, 24]
            }]
        },
        options: {
            legend: {
                display: false,
                position: 'bottom'
            },
            title: {
                fontSize: 20,
                display: true,
                text: 'Привлечения'
            }
        }
    });

    var myLegendContainerThree = document.getElementById("barLegendChart");
    myLegendContainerThree.innerHTML = chartThree.generateLegend();

}

$(function selectCheck() {
    var el = $('.select-box__day')
    if (!el.length) {
        return false;
    }
    var selected;
    el.change(function () {
        selected = $(this).find(':selected').attr('value')
        console.log(selected)

        var lock = false;

        if (!lock) {
            $.ajax({
                type: "POST",
                url: '/dashboard',
                data: {
                    value: selected
                },
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                },
                dataType: "json"
            })
        }

    });

});
