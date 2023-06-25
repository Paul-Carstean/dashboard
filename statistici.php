<?php 
session_start(); 
require_once('functions.php');
require_once('db_conn.php');

$_SESSION['noAnSel'] = true;
 

if(isset($_POST['an'])){
  if(empty($_POST['anSel']) || !isset($_POST['anSel'])){
    $_SESSION['noAnSel'] = true;
  }
  else{
    unset($_SESSION['noAnSel']);
    $_SESSION['an'] = $_POST['anSel'];
    $conexiune = mysqli_connect("localhost", "root", "", "dashboard_db");
    $an = $_POST['anSel'];
    $sqll = "SELECT * FROM model";
    $resulttt = mysqli_query($conexiune, $sqll);
    $n = 0;
    while($row = mysqli_fetch_assoc($resulttt)){
      $metriModel[$n] = 0;
      $numeModel[$n] = $row['nume'];
      $IDmodel[$n] = $row['id'];
      $n = $n + 1;
    }
    $uid = $_SESSION['uid'];
    if($an === "totiAnii"){
      $sql = "SELECT * FROM comenzi WHERE idUtilizator=$uid";
    }
    else{
      $sql = "SELECT * FROM comenzi WHERE YEAR(dataLimita) = $an AND idUtilizator=$uid";
    }
    
    $resultt = mysqli_query($conexiune, $sql);
    $data = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
    $price = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
    $montDa = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
    $montNu = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
    $transDa = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
    $transNu = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
    if($resultt){
      while ($row = mysqli_fetch_assoc($resultt)) {
        $_SESSION['isData'] = true;
        $r = 0;
        while($r < $n){
          if($row['modelID'] == $IDmodel[$r]){
            $metriModel[$r] = $metriModel[$r] + $row['numarMetri'];
          }
          $r = $r + 1;
        }
        if($row['montare']){
            $montDa[0] = $montDa[0] + 1;
          }
          else{
            $montNu[0] = $montNu[0] + 1;
          }
          if($row['transport']){
            $transDa[0] = $transDa[0] + 1;
          }
          else{
            $transNu[0] = $transNu[0] + 1;
          }
        $month = date("m", strtotime($row['dataLimita']));
        if($month === '01'){
          $data[0] = $data[0] + $row['numarMetri'];
          $price[0] = $price[0] + $row['pret'];
        }
        if($month === '02'){
          $data[1] = $data[1] + $row['numarMetri'];
          $price[1] = $price[1] + $row['pret'];
        }
        if($month === '03'){
          $data[2] = $data[2] + $row['numarMetri'];
          $price[2] = $price[2] + $row['pret'];
        }
        if($month === '04'){
          $data[3] = $data[3] + $row['numarMetri'];
          $price[3] = $price[3] + $row['pret'];
        }
        if($month === '05'){
          $data[4] = $data[4] + $row['numarMetri'];
          $price[4] = $price[4] + $row['pret'];
        }
        if($month === '06'){
          $data[5] = $data[5] + $row['numarMetri'];
          $price[5] = $price[5] + $row['pret'];
        }
        if($month === '07'){
          $data[6] = $data[6] + $row['numarMetri'];
          $price[6] = $price[6] + $row['pret'];
        }
        if($month === '08'){
          $data[7] = $data[7] + $row['numarMetri'];
          $price[7] = $price[7] + $row['pret'];
        }
        if($month === '09'){
          $data[8] = $data[8] + $row['numarMetri'];
          $price[8] = $price[8] + $row['pret'];
        }
        if($month === '10'){
          $data[9] = $data[9] + $row['numarMetri'];
          $price[9] = $price[9] + $row['pret'];
        }
        if($month === '11'){
          $data[10] = $data[10] + $row['numarMetri'];
          $price[10] = $price[10] + $row['pret'];
        }
        if($month === '12'){
          $data[11] = $data[11] + $row['numarMetri'];
          $price[11] = $price[11] + $row['pret'];
        }
      }
    }
    $_SESSION['data'] = json_encode($data);
    $_SESSION['pret'] = json_encode($price);
    $_SESSION['montDa'] = json_encode($montDa);
    $_SESSION['montNu'] = json_encode($montNu);
    $_SESSION['transDa'] = json_encode($transDa);
    $_SESSION['transNu'] = json_encode($transNu);
    $_SESSION['numeModel'] = json_encode($numeModel);
    $_SESSION['metriModel'] = json_encode($metriModel);
  }
  
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Dashboard</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <link rel="stylesheet" href="css/homeStyle.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
  <form action="statistici.php" method="post">
    <nav class="navbar navbar-default">
      <div class="container-fluid" style="height: 90px;">
        <div class="navbar-header">
          <a class="navbar-brand" style="color: #332097; margin-left: 12%; margin-top: 9%;" href="home.php">Dashboard</a>
        </div>
        <div class="navbar-form navbar-center">
          <div class="input-group">
            <input type="text" class="form-control" placeholder="Cauta">
            <span class="input-group-btn">
              <button class="btn btn-default" type="button"><i class="fa-solid fa-magnifying-glass fa-lg"></i></button>
            </span>
          </div>
        </div>
      </div>
    </nav>

    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-3 col-md-2 sidebar">
          <ul class="nav nav-sidebar">
            <li class="nav-li"><a class="nav-a"  href="home.php"><i class="fa-solid fa-clipboard"></i>  Comenzi</a></li>
            <li class="nav-li"><a class="nav-a"  href="agenda.php"><i class="fa-solid fa-address-book"></i>  Agenda</a></li>
            <li class="nav-li"><a class="nav-a" style="color: #5843c6;" href="statistici.php"><i class="fa-solid fa-chart-column"></i>  Statistici</a></li>
          </ul>
            <li class="navli"><button type="submit" ><a href="logout.php"><i class="fas fa-sign-out-alt"></i>  Logout</a></li></button>
          <ul>
          </ul>
          
        </div>
      </div>
    </div>
    <div class="continut">
      <div class="selAn">
        <text class="selText">Selecteaza anul: </text>
        <select class="selSel" name="anSel">
          <option value="">an</option>
          <option value="totiAnii" <?php if(isset($_SESSION['an'])){if($_SESSION['an'] == 'totiAnii'){ echo "selected";}}?>>toti anii</option>
          <option value="2023" <?php if(isset($_SESSION['an'])){if($_SESSION['an'] == '2023'){ echo "selected";}}?>>2023</option>
          <option value="2022" <?php if(isset($_SESSION['an'])){if($_SESSION['an'] == '2022'){ echo "selected";}}?>>2022</option>
          <option value="2021" <?php if(isset($_SESSION['an'])){if($_SESSION['an'] == '2021'){ echo "selected";}}?>>2021</option>
          <option value="2020" <?php if(isset($_SESSION['an'])){if($_SESSION['an'] == '2020'){ echo "selected";}}?>>2020</option>
        </select>
        <button class="sel" type="submit" name="an">selecteaza</button>
      </div>

      <?php
      if(isset($_SESSION['noAnSel'])){
        echo "<div style=\"margin-left:42%; font-size:25px; color:#332097;\">Niciun an selectat</div>";
      }
      else if(isset($_SESSION['isData'])){

        $aux = "<div class=\"chart\">
                  <div class=\"oneChart\">
                    <canvas class=\"myChart\" id=\"myChart1\"></canvas>
                  </div>
                  <div class=\"oneChart\">
                    <canvas class=\"myChart\" id=\"myChart2\"></canvas>
                  </div>
                  <div class=\"oneChart\">
                    <canvas class=\"myChart\" id=\"myChart3\"></canvas>
                  </div>
                  <div class=\"oneChart\">
                    <canvas class=\"myChart\" id=\"myChart4\"></canvas>
                  </div>
                  <div class=\"oneChart\">
                    <canvas class=\"myChart\" id=\"myChart5\"></canvas>
                  </div>
                </div>
                ";
        echo $aux;
      }
      else{
        echo "<div style=\"margin-left:38%; font-size:25px; color:#332097;\">Nu sunt comenzi in anul selectat</div>";
      }
      ?>

  </form>

      
      
    </div>

  <script type="text/javascript">
    const labels = ['Ian', 'Feb','Mar', 'Apr', 'Mai', 'Iun', 'Iul', 'Aug', 'Sep', 'Oct', 'Noi', 'Dec'];
    const backgroundColor = [
                              'rgba(255, 0, 0, 0.2)',
                              'rgba(255, 128, 0, 0.2)',
                              'rgba(255, 255, 0, 0.2)',
                              'rgba(0, 204, 0, 0.2)',
                              'rgba(0, 255, 128, 0.2)',
                              'rgba(0, 128, 255, 0.2)',
                              'rgba(0, 0, 255, 0.2)',
                              'rgba(102, 0, 204, 0.2)',
                              'rgba(204, 0, 204, 0.2)',
                              'rgba(255, 0, 128, 0.2)',
                              'rgba(128, 128, 128, 0.2)',
                              'rgba(32, 32, 32, 0.2)'
                            ];
    const backgroundColorNu = ['rgba(255, 0, 0, 0.2)'];
    const backgroundColorDa = ['rgba(0, 0, 255, 0.2)'];
    const borderColor = [
                              'rgb(255, 0, 0)',
                              'rgb(255, 128, 0)',
                              'rgb(255, 255, 0)',
                              'rgb(0, 204, 0)',
                              'rgb(0, 255, 128)',
                              'rgb(0, 128, 255)',
                              'rgb(0, 0, 255)',
                              'rgb(102, 0, 204)',
                              'rgb(204, 0, 204)',
                              'rgb(255, 0, 128)',
                              'rgb(128, 128, 128)',
                              'rgb(32, 32, 32)'
                            ];
    const borderColorNu = ['rgb(255, 0, 0)'];
    const borderColorDa = ['rgb(0, 0, 255)'];
    const data1 = {
      labels: labels,
      datasets: [{
        label: 'Total numar metri vandut', 
        data: <?php $data = $_SESSION['data']; echo $data;?>,
        backgroundColor: backgroundColor,
        borderColor: borderColor,
        borderWidth: 1
      }]
    };
    const config1 = {
      type: 'bar',
      data: data1,
      options: {
        scales: {
          y: {
            beginAtZero: true
          }
        }
      },
    };
    const data2 = {
      labels: labels,
      datasets: [{
        label: 'Total venit',
        data: <?php $pret = $_SESSION['pret']; echo $pret;?>,
        backgroundColor: backgroundColor,
        borderColor: borderColor,
        borderWidth: 1
      }]
    };
    const config2 = {
      type: 'bar',
      data: data2,
      options: {
        scales: {
          y: {
            beginAtZero: true
          }
        }
      },
    };
    const data3 = {
      labels: ['Montare'],
      datasets: [{
        label: 'Comenzi cu montare',
        data: <?php $montDa = $_SESSION['montDa']; echo $montDa;?>,
        backgroundColor: backgroundColorDa,
        borderColor: borderColorDa,
        borderWidth: 1
        },
        {
        label: 'Comenzi fara montare',
        data: <?php $montNu = $_SESSION['montNu']; echo $montNu;?>,
        backgroundColor: backgroundColorNu,
        borderColor: borderColorNu,
        borderWidth: 1
        }]
    };
    const config3 = {
      type: 'bar',
      data: data3,
      options: {
        scales: {
          y: {
            beginAtZero: true
          }
        }
      },
    };
    const data4 = {
      labels: ['Transport'],
      datasets: [{
        label: 'Comenzi cu transport',
        data: <?php $transDa = $_SESSION['transDa']; echo $transDa;?>,
        backgroundColor: backgroundColorDa,
        borderColor: borderColorDa,
        borderWidth: 1
        },
        {
        label: 'Comenzi fara transport',
        data: <?php $transNu = $_SESSION['transNu']; echo $transNu;?>,
        backgroundColor: backgroundColorNu,
        borderColor: borderColorNu,
        borderWidth: 1
        }]
    };
    const config4 = {
      type: 'bar',
      data: data4,
      options: {
        scales: {
          y: {
            beginAtZero: true
          }
        }
      },
    };
    const data5 = {
      labels: <?php $numeModel = $_SESSION['numeModel']; echo $numeModel;?>,
      datasets: [{
        label: 'Numar metri per model',
        data: <?php $metriModel = $_SESSION['metriModel']; echo $metriModel;?>,
        backgroundColor: backgroundColor,
        borderColor: borderColor,
        borderWidth: 1
        }]
    };
    const config5 = {
      type: 'bar',
      data: data5,
      options: {
        scales: {
          y: {
            beginAtZero: true
          }
        }
      },
    };
    var myChart5 = new Chart(
      document.getElementById('myChart5'), 
      config5
    );
    var myChart1 = new Chart(
      document.getElementById('myChart1'), 
      config1
    );
    var myChart2 = new Chart(
      document.getElementById('myChart2'), 
      config2
    );
    var myChart3 = new Chart(
      document.getElementById('myChart3'), 
      config3
    );
    var myChart4 = new Chart(
      document.getElementById('myChart4'), 
      config4
    );
    var myChart5 = new Chart(
      document.getElementById('myChart5'), 
      config5
    );
  </script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <?php  
  unset($_SESSION['isData']);
  unset($_SESSION['an']);
  unset($_SESSION['noAnSel']);
  unset($_SESSION['anSel']);
  ?>
</body>
</html>
