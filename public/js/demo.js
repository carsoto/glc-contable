
function colorRandom() {
  /*var r = Math.floor(Math.random() * 255);
  var g = Math.floor(Math.random() * 255);
  var b = Math.floor(Math.random() * 255);
  return "rgb(" + r + "," + g + "," + b + ")";*/
  var letters = '0123456789ABCDEF'.split('');
  var color = '#';
  for (var i = 0; i < 6; i++ ) {
    color += letters[Math.floor(Math.random() * 16)];
  }
  return color;
};

var var_ventas = new Array();
var var_ganancias = new Array();
var var_pedidos = new Array();
var var_pedidos_entrantes = new Array();
var var_pedidos_status = new Array();

$(document).ready(function(){
  var vv = document.getElementById('ventas-por-meses');
  if(vv != null){
    $.ajax({
      url: 'admin/dashboard',
      type: 'GET',
      processData: false,
      contentType: false,
        success: function(response){
          //console.log(response.ventas);

          /************************************* VENTAS ****************************************************/
          $.each(response.ventas, function(key, valores) {
            
            var color = colorRandom();
            var data = {
                label: key,
                data: valores,
                lineTension: 0.7,
                //fill: true,
                //spanGaps: true,
                //fillColor : "blue",
                backgroundColor: color,
                borderColor: color,
            };
            var_ventas.push(data);
          });

          var data_ventas = {
            labels: ["Ene.", "Feb.", "Mar.", "Abr.", "May.", "Jun.", "Jul.", "Ago.", "Sep.", "Oct.", "Nov.", "Dic."],
            datasets: var_ventas
          };
     
          var chart_ventas = document.getElementById('ventas-por-meses').getContext('2d');
          new Chart(chart_ventas, {
              type: 'bar',
              animation: true,
              animationSteps: 100,
              animationEasing: "easeOutQuart",
              scaleFontSize: 16,
              responsive: true,
              showTooltip: true,
              data: data_ventas,
          });

          /************************************ GANANCIAS ************************************************/
          $.each(response.ganancias, function(key, valores) {
            var color = colorRandom();
            var data = {
                label: key,
                data: valores,
                //lineTension: 0.7,
                //fill: true,
                //spanGaps: true,
                //fillColor : "blue",
                //backgroundColor: color,
                //borderColor: color,
                lineTension: 0,
                fill: false,
                borderColor: color,
                backgroundColor: 'transparent',
                borderDash: [],
                pointBorderColor: color,
                pointBackgroundColor: color,
                pointRadius: 3,
                pointHoverRadius: 5,
                pointHitRadius: 15,
                pointBorderWidth: 2,
                pointStyle: 'rectRounded'
            };
            var_ganancias.push(data);
          });

          var data_ganancias = {
            labels: ["Ene.", "Feb.", "Mar.", "Abr.", "May.", "Jun.", "Jul.", "Ago.", "Sep.", "Oct.", "Nov.", "Dic."],
            datasets: var_ganancias
          };
     
          var chart_ganancias = document.getElementById('ganancias-por-meses').getContext('2d');
          new Chart(chart_ganancias, {
              type: 'line',
              data: data_ganancias,
              options: {
                animation: false,
                //legend: {display: false},
                maintainAspectRatio: false,
                responsive: true,
                //responsiveAnimationDuration: 0,
                tooltips: {
                  mode: 'label',
                  label: 'mylabel',
                  callbacks: {
                    label: function(tooltipItem, data) {
                      return '$ ' + tooltipItem.yLabel.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","); 
                    }, 
                  },
                },

                scales: {
                  yAxes: [{
                    ticks: {
                      beginAtZero: true,
                      callback: function(value, index, values) {
                        if(parseInt(value) >= 1000){
                          return '$ ' + value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                        } else {
                          return '$ ' + value;
                        }
                      }
                    }
                  }]
                }
              }
          });

          /************************************ PEDIDOS ************************************************/
          $.each(response.pedidos, function(key, valores) {
            
            var color = colorRandom();
            var data = {
                label: key,
                data: valores,
                lineTension: 0.7,
                //fill: true,
                //spanGaps: true,
                //fillColor : "blue",
                backgroundColor: color,
                borderColor: color,
            };
            var_pedidos.push(data);
          });

          var data_pedidos = {
            labels: ["Ene.", "Feb.", "Mar.", "Abr.", "May.", "Jun.", "Jul.", "Ago.", "Sep.", "Oct.", "Nov.", "Dic."],
            datasets: var_pedidos
          };

          var chart_pedidos = document.getElementById('pedidos-por-meses').getContext('2d');
          new Chart(chart_pedidos, {
              type: 'bar',
              animation: true,
              animationSteps: 100,
              animationEasing: "easeOutQuart",
              scaleFontSize: 16,
              responsive: true,
              showTooltip: true,
              data: data_pedidos,
          });

          /**************************************************** PEDIDOS ENTRANTES *******************************************************/
          $.each(response.pedidos_entrantes, function(key, valores) {
            
            var color = colorRandom();
            var data = {
                label: key,
                data: valores,
                lineTension: 0.7,
                //fill: true,
                //spanGaps: true,
                //fillColor : "blue",
                backgroundColor: color,
                borderColor: color,
            };
            var_pedidos_entrantes.push(data);
          });

          var data_pedidos_entrantes = {
            labels: ["Ene.", "Feb.", "Mar.", "Abr.", "May.", "Jun.", "Jul.", "Ago.", "Sep.", "Oct.", "Nov.", "Dic."],
            datasets: var_pedidos_entrantes
          };

          var chart_pedidos_entrantes = document.getElementById('pedidos-entrantes-por-meses').getContext('2d');
          new Chart(chart_pedidos_entrantes, {
              type: 'bar',
              animation: true,
              animationSteps: 100,
              animationEasing: "easeOutQuart",
              scaleFontSize: 16,
              responsive: true,
              showTooltip: true,
              data: data_pedidos_entrantes,
          });

          /**************************************************** PEDIDOS POR ESTATUS ****************************************************/
          var data_pedidos_status = {
            labels: [
              "ACTIVOS",
              "INACTIVOS",
              "VENDIDOS"
            ],
            datasets: [{
              data: [response.pedidos_status.ACTIVO, response.pedidos_status.INACTIVO, response.pedidos_status.VENDIDO],
              backgroundColor: [
              "#00A65A",
              "#DD4B39",
              "#3C8DBC"
            ],
            //borderColor: "black",
            //borderWidth: 2
            }]
          };

          var chart_pedidos_status = document.getElementById('pedidos-por-status').getContext('2d');
          var myDoughnutChart = new Chart(chart_pedidos_status, {
              type: 'doughnut',
              data: data_pedidos_status,
              options: {
                  //rotation: -Math.PI,
                  //cutoutPercentage: 30,
                  //circumference: Math.PI,
                  /*legend: {
                    position: 'center'
                  },*/
                  animation: {
                    animateRotate: false,
                    animateScale: true
                  }
                }
          });
        },

        error: function (xhr, ajaxOptions, thrownError) {
          swal("Ocurrió un error!", "Por favor, intente de nuevo", "error");
      }
    });  
  }
  
});


//var var_ventas = [dataFirst, dataSecond];
//console.log(var_ventas);
