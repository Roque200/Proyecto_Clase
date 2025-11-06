<html>
  <head>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {

        var data = google.visualization.arrayToDataTable([
          ['Instituto', 'Investigadores'],
          <?php 
            if($datosGraficas && is_array($datosGraficas) && count($datosGraficas) > 0):
              foreach($datosGraficas as $dato):
          ?>
          ['<?php echo htmlspecialchars($dato['instituto'], ENT_QUOTES, 'UTF-8') ?>', <?php echo (int)$dato['total_investigadores'] ?>],
          <?php 
              endforeach;
            else:
          ?>
          ['Sin datos', 0],
          <?php 
            endif;
          ?>
        ]);

        var options = {
          title: 'Instituciones e Investigadores',
          pieHole: 0.4,
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart'));

        chart.draw(data, options);
      }
    </script>
  </head>
  <body>
    <h1>Bienvenido al sistema</h1>
    <div id="piechart" style="width: 900px; height: 500px;"></div>
  </body>
</html>