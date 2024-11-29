<?php include 'includes/session.php'; ?>
<?php 
  include 'includes/timezone.php'; 
  $today = date('Y-m-d');
  $year = date('Y');
  if(isset($_GET['year'])){
    $year = $_GET['year'];
  }
?>
<?php include 'includes/header.php'; ?>
<body class="hold-transition skin-green sidebar-mini">
<div class="wrapper">

  <?php include 'includes/navbar.php'; ?>
  <?php include 'includes/menubar.php'; ?>

  <div class="content-wrapper">
    <section class="content-header">
    <h1>
    Welcome to Dashboard!
</h1>

      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-home"></i> Home</a></li>
        <li class="active">Dashboard</li>
      </ol>
    </section>

    <section class="content">
      <?php
        if(isset($_SESSION['error'])){
          echo "
            <div class='alert alert-danger alert-dismissible'>
              <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
              <h4><i class='icon fa fa-warning'></i> Error!</h4>
              ".$_SESSION['error']."
            </div>
          ";
          unset($_SESSION['error']);
        }
        if(isset($_SESSION['success'])){
          echo "
            <div class='alert alert-success alert-dismissible'>
              <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
              <h4><i class='icon fa fa-check'></i> Success!</h4>
              ".$_SESSION['success']."
            </div>
          ";
          unset($_SESSION['success']);
        }
      ?>
      <div class="row">
        <div class="col-lg-3 col-xs-6">
          <div class="small-box bg-purple">
            <div class="inner">
              <?php
                $sql = "SELECT * FROM books";
                $query = $conn->query($sql);

                echo "<h3>".$query->num_rows."</h3>";
              ?>
            <center>
              <b><p>Total Books</p></b>
              </center>
            </div>
            <div class="icon">
              <i class="fa fa-"></i>
            </div>
            <a href="book.php" class="small-box-footer">More info</i></a>
          </div>
        </div>
        <div class="col-lg-3 col-xs-6">
          <div class="small-box bg-orange">
            <div class="inner">
              <?php
                $sql = "SELECT * FROM users";
                $query = $conn->query($sql);

                echo "<h3>".$query->num_rows."</h3>";
              ?>
          
              <center><b><p>Total Users</p></b></center>
            </div>
            <div class="icon">
              <i class="fa fa-"></i>
            </div>
            <a href="student.php" class="small-box-footer">More info</i></a>
          </div>
        </div>
        <div class="col-lg-3 col-xs-6">
          <div class="small-box bg-maroon">
            <div class="inner">
              <?php
                $sql = "SELECT * FROM returns WHERE date_return = '$today'";
                $query = $conn->query($sql);

                echo "<h3>".$query->num_rows."</h3>";
              ?>
             
             <center> <b><p>Returned Today</p></b></center>
            </div>
            <div class="icon">
              <i class="fa fa-borrow"></i>
            </div>
            <a href="return.php" class="small-box-footer">More info</i></a>
          </div>
        </div>
        <div class="col-lg-3 col-xs-6">
          <div class="small-box bg-black">
            <div class="inner">
              <?php
                $sql = "SELECT * FROM borrow WHERE date_borrow = '$today'";
                $query = $conn->query($sql);

                echo "<h3>".$query->num_rows."</h3>";
              ?>
            
             <center><b><p>Borrowed Today</p></b></center> 
            </div>
            <div class="icon">
              <i class="fa fa-"></i>
            </div>
            <a href="borrow.php" class="small-box-footer">More info</a>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">Report</h3>
              <div class="box-tools pull-right">
                <form class="form-inline">
                  <div class="form-group">
                    <label>Select Year: </label>
                    <select class="form-control input-sm" id="select_year">
                      <?php
                        for($i=2015; $i<=2065; $i++){
                          $selected = ($i==$year)?'selected':'';
                          echo "
                            <option value='".$i."' ".$selected.">".$i."</option>
                          ";
                        }
                      ?>
                    </select>
                  </div>
                </form>
              </div>
            </div>
            <div class="box-body">
              <div class="chart">
                <br>
                <div id="legend" class="text-center"></div>
                <canvas id="barChart" style="height:350px"></canvas>
              </div>
            </div>
          </div>
        </div>
      </div>

      </section>
    </div>
  	<?php include 'includes/footer.php'; ?>

</div>

<?php
  $and = 'AND YEAR(date) = '.$year;
  $months = array();
  $return = array();
  $borrow = array();
  for( $m = 1; $m <= 12; $m++ ) {
    $sql = "SELECT * FROM returns WHERE MONTH(date_return) = '$m' AND YEAR(date_return) = '$year'";
    $rquery = $conn->query($sql);
    array_push($return, $rquery->num_rows);

    $sql = "SELECT * FROM borrow WHERE MONTH(date_borrow) = '$m' AND YEAR(date_borrow) = '$year'";
    $bquery = $conn->query($sql);
    array_push($borrow, $bquery->num_rows);

    $num = str_pad( $m, 2, 0, STR_PAD_LEFT );
    $month =  date('M', mktime(0, 0, 0, $m, 1));
    array_push($months, $month);
  }

  $months = json_encode($months);
  $return = json_encode($return);
  $borrow = json_encode($borrow);

?>
<?php include 'includes/scripts.php'; ?>
<script>
$(function(){
  var splineChartCanvas = $('#barChart').get(0).getContext('2d');
  var splineChart = new Chart(splineChartCanvas, {
    type: 'line', 
    data: {
      labels: <?php echo $months; ?>,
      datasets: [
        {
          label: 'Borrow',
          borderColor: 'rgb(255, 105, 180)', // Pink
          backgroundColor: 'rgba(255, 105, 180, 0.2)', // Transparent pink fill
          pointBackgroundColor: 'rgb(255, 105, 180)',
          pointBorderColor: '#fff',
          pointHoverBackgroundColor: '#fff',
          pointHoverBorderColor: 'rgb(255, 105, 180)',
          tension: 0.4, // Smooth curve
          data: <?php echo $borrow; ?>
        },
        {
          label: 'Return',
          borderColor: 'rgb(0, 0, 255)', // Blue
          backgroundColor: 'rgba(0, 0, 255, 0.2)', // Transparent blue fill
          pointBackgroundColor: 'rgb(0, 0, 255)',
          pointBorderColor: '#fff',
          pointHoverBackgroundColor: '#fff',
          pointHoverBorderColor: 'rgb(0, 0, 255)',
          tension: 0.4, // Smooth curve
          data: <?php echo $return; ?>
        }
      ]
    },
    options: {
      plugins: {
        legend: {
          display: true
        }
      },
      scales: {
        x: {
          beginAtZero: true,
          grid: {
            color: 'rgba(0,0,0,.05)'
          }
        },
        y: {
          beginAtZero: true,
          grid: {
            color: 'rgba(0,0,0,.05)'
          }
        }
      },
      responsive: true,
      maintainAspectRatio: true
    }
  });

  document.getElementById('legend').innerHTML = splineChart.generateLegend();
});
</script>

</body>
</html>
