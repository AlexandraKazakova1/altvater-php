$(document).ready (function() {
    const labels = [
        'Понеділок',
        'Вівторок',
        'Середа',
        'Четвер',
        "П'ятниця",
        'Субота',
        'Неділя',
    ];
    const data = {
        labels: labels,
        datasets: [{
            backgroundColor: 'rgba(47, 94, 151, 0.2)',
            borderColor: '#2F5E97',
            fill: true,
            cubicInterpolationMode: 'monotone',
            tension: 0.4,
            data: [60, 20, 100, 90, 200, 150, 195, 120],
        }]
    };
    const config = {
        type: 'line',
        data: data,
        options: {
            radius: 0,
            plugins: {
                legend: {
                    display: false,
                }
            },
            responsive: true,
            interaction: {
                intersect: false,
            },
            scales: {
                x: {
                    display: false,
                },
                y: {
                    display: false,
                    suggestedMin: 0,
                    suggestedMax: 200.20
                }
            }
        },
    };
    var myChart = new Chart(
        document.getElementById('myChart'),
        config
    );

    $('.custom-select').select2({
        minimumResultsForSearch: 1000 
    });
});