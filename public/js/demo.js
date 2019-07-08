
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

var variable_data = new Array();
var variable_data2 = new Array();
$(document).ready(function(){
  var vv = document.getElementById('ventas-por-meses');
  if(vv != null){
    $.ajax({
      url: 'admin/dashboard',
      type: 'GET',
      processData: false,
      contentType: false,
        success: function(response){
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
            variable_data.push(data);

          });

          var speedData1 = {
            labels: ["Ene.", "Feb.", "Mar.", "Abr.", "May.", "Jun.", "Jul.", "Ago.", "Sep.", "Oct.", "Nov.", "Dic."],
            datasets: variable_data
          };
     
          var ctx = document.getElementById('ventas-por-meses').getContext('2d');
          new Chart(ctx, {
              type: 'bar',
              animation: true,
              animationSteps: 100,
              animationEasing: "easeOutQuart",
              scaleFontSize: 16,
              responsive: true,
              showTooltip: true,
              data: speedData1,
          });

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
                borderDash: [2, 2],
                pointBorderColor: color,
                pointBackgroundColor: color,
                pointRadius: 3,
                pointHoverRadius: 5,
                pointHitRadius: 15,
                pointBorderWidth: 2,
                pointStyle: 'rectRounded'
            };
            variable_data2.push(data);

          });

          var speedData2 = {
            labels: ["Ene.", "Feb.", "Mar.", "Abr.", "May.", "Jun.", "Jul.", "Ago.", "Sep.", "Oct.", "Nov.", "Dic."],
            datasets: variable_data2
          };
     
          var ctx2 = document.getElementById('ganancias-por-meses').getContext('2d');
          new Chart(ctx2, {
              type: 'line',
              data: speedData2,
          });
        },

        error: function (xhr, ajaxOptions, thrownError) {
          swal("Ocurrió un error!", "Por favor, intente de nuevo", "error");
      }
    });  
  }
  
});


//var variable_data = [dataFirst, dataSecond];
//console.log(variable_data);
