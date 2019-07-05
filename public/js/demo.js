/*var url = "{{url('stock/chart')}}";
var Years = new Array();
var Labels = new Array();
var Prices = new Array();*
$(document).ready(function(){
  $.get(url, function(response){
    response.forEach(function(data){
        Years.push('2018', '2019', '2020');
        Labels.push('ENERO', 'FEBRERO', 'MARZO');
        Prices.push('100', '200', '300');
    });
    
  });*
  var ctx = document.getElementById("bar-chart-uno").getContext('2d');
        var myChart = new Chart(ctx, {
          type: 'bar',
          data: {
              labels: ['Mes 5', 'Mes 4', 'Mes 3', 'Mes 2', 'Mes 1', 'Mes Actual'],
              datasets: [{
                  label: 'Infosys Price',
                  data: ["8954", "9521", "8594", "5847", "5784", "5628"],
                  borderWidth: 1
              }]
          },
          options: {
              scales: {
                  yAxes: [{
                      ticks: {
                          beginAtZero:true
                      }
                  }]
              }
          }
      });
});*/

/*var ctx = document.getElementById('bar-chart-uno').getContext('2d');
var myChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'],
        datasets: [{
            label: '# of Votes',
            data: [12, 19, 3, 5, 2, 3],
            backgroundColor: [
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(153, 102, 255, 0.2)',
                'rgba(255, 159, 64, 0.2)'
            ],
            borderColor: [
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 159, 64, 1)'
            ],
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero: true
                }
            }]
        }
    }
});*/

var dataFirst = {
    label: "2018",
    data: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11],
    lineTension: 0,
    fill: true,
    //fillColor : "red",
    //borderColor: 'red'
};

var dataSecond = {
    label: "2019",
    data: [12, 11, 10, 9, 8, 7, 6, 5, 4, 3, 2, 1],
    lineTension: 0,
    fill: true,
    //fillColor : "blue",
    //borderColor: 'blue'
};
   
var speedData = {
    labels: ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],
    datasets: [dataFirst, dataSecond]
};
 
var ctx = document.getElementById('bar-chart-uno').getContext('2d');
var lineChart = new Chart(ctx, {
    type: 'line',
    animation: true,
    animationSteps: 100,
    animationEasing: "easeOutQuart",
    scaleFontSize: 16,
    responsive: true,
    showTooltip: true,
    data: speedData,
});