<?php
session_start(); 
require_once('functions.php');
require_once('db_conn.php');

if(isset($_POST['plus'])){
  header("Location: adaugaComanda.php");
}

if(isset($_POST['searchButton'])){
  if($_POST['searchInput'] !== ""){
      $_SESSION['search'] = $_POST['searchInput'];
  }
}

if(isset($_POST['sterge'])){
  $id=$_POST['fid'];
  $con=mysqli_connect("localhost", "root", "", "dashboard_db");
  $sql="DELETE FROM comenzi WHERE id='$id'";
  $result=mysqli_query($con,$sql);
}

if(isset($_POST['editeaza'])){
  $_SESSION['hid']=$_POST['fid'];
  header("location: editComanda.php");
}

if(isset($_POST['final'])){
  $fid = $_POST['fid'];
  $conec=mysqli_connect("localhost", "root", "", "dashboard_db");
  $sql="UPDATE comenzi SET comFinalizata=1 WHERE id='$fid'";;
  $result=mysqli_query($conec,$sql);
  unset($_POST['final']);
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
</head>
<body>
  <form action="home.php" method="post">
    <nav class="navbar navbar-default">
      <div class="container-fluid" style="height: 90px; width: 100%;">
        <div class="navbar-header" style="position: fixed;">
          <a class="navbar-brand" style="color: #332097; margin-left: 12%; margin-top: 9%;" href="home.php">Dashboard</a>
        </div>
        <div class="navbar-form navbar-center" style="position: fixed;">
          <div class="input-group" >
            <input type="text" name="searchInput" class="form-control" placeholder="Search...">
            <span class="input-group-btn">
              <button class="btn btn-default" name="searchButton"><i class="fa-solid fa-magnifying-glass fa-lg"></i></button>
            </span>
          </div>
        </div>
      </div>
    </nav>

    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-3 col-md-2 sidebar">
          <ul class="nav nav-sidebar">
            <li class="nav-li"><a class="nav-a" style="color: #5843c6; " href="home.php"><i class="fa-solid fa-clipboard"></i>  Comenzi</a></li>
            <li class="nav-li"><a class="nav-a" href="agenda.php"><i class="fa-solid fa-address-book"></i>  Agenda</a></li>
            <li class="nav-li"><a class="nav-a" href="statistici.php"><i class="fa-solid fa-chart-column"></i>  Statistici</a></li>
          </ul>
            <li class="navli"><button type="submit" ><a href="logout.php"><i class="fas fa-sign-out-alt"></i>  Logout</a></button></li>
          <ul>
          </ul>
          
        </div>
      </div>
    </div>
    <div class="continut">
    <?php
    if(!isset($_SESSION['search']))
    echo "<div class=\"comanda-p\">
        <button name=\"plus\" class=\"plus-button-c\">+</button>
      </div>
      ";

    ?>
      
</form>
      <?php
            $result=getDataComenzi(0);
            $ok=0;
            if($result){
              while($row=mysqli_fetch_assoc($result)){
                  if(isset($_SESSION['search']))
                  {
                    $conectie = mysqli_connect("localhost", "root", "", "dashboard_db");
                    $mid = $row['modelID'];
                    $sqll = "SELECT * FROM model WHERE id=$mid";
                    $resultt = mysqli_query($conectie, $sqll);
                    $roww = mysqli_fetch_assoc($resultt);
                    $modelName = $roww['nume'];
                      if(str_contains(strtoupper($row['nume']),strtoupper($_SESSION['search']))  || str_contains(strtoupper($modelName),strtoupper($_SESSION['search'])) || str_contains(strtoupper($row['modelStalpi']),strtoupper($_SESSION['search'])) || str_contains(strtoupper($row['nume']),strtoupper($_SESSION['search'])) || str_contains(strtoupper($row['oras']),strtoupper($_SESSION['search']))){
                          comanda($row['id'], $row['modelID'], $row['montare'], $row['tipMontare'], $row['modelStalpi'], $row['inaltime'] ,$row['numarMetri'] ,$row['transport'] ,$row['nume'] ,$row['telefon'] ,$row['oras'] ,$row['dataLimita'] ,$row['pret'], $row['comFinalizata']);
                          $ok=$ok+1;
                      }
                  }
                  else{
                      comanda($row['id'], $row['modelID'], $row['montare'], $row['tipMontare'], $row['modelStalpi'], $row['inaltime'] ,$row['numarMetri'] ,$row['transport'] ,$row['nume'] ,$row['telefon'] ,$row['oras'] ,$row['dataLimita'] ,$row['pret'], $row['comFinalizata']);
                  }
              }
            }
            $result=getDataComenzi(1);
            if($result){
              while($row=mysqli_fetch_assoc($result)){
                  if(isset($_SESSION['search']))
                  {
                    $conectie = mysqli_connect("localhost", "root", "", "dashboard_db");
                    $mid = $row['modelID'];
                    $sqll = "SELECT * FROM model WHERE id=$mid";
                    $resultt = mysqli_query($conectie, $sqll);
                    $roww = mysqli_fetch_assoc($resultt);
                    $modelName = $roww['nume'];
                      if(str_contains(strtoupper($row['nume']),strtoupper($_SESSION['search']))  || str_contains(strtoupper($modelName),strtoupper($_SESSION['search'])) || str_contains(strtoupper($row['modelStalpi']),strtoupper($_SESSION['search'])) || str_contains(strtoupper($row['nume']),strtoupper($_SESSION['search'])) || str_contains(strtoupper($row['oras']),strtoupper($_SESSION['search']))){
                          comandaFinalizata($row['id'], $row['modelID'], $row['montare'], $row['tipMontare'], $row['modelStalpi'], $row['inaltime'] ,$row['numarMetri'] ,$row['transport'] ,$row['nume'] ,$row['telefon'] ,$row['oras'] ,$row['dataLimita'] ,$row['pret'], $row['comFinalizata']);
                          $ok=$ok+1;
                      }
                  }
                  else{
                      comandaFinalizata ($row['id'], $row['modelID'], $row['montare'], $row['tipMontare'], $row['modelStalpi'], $row['inaltime'] ,$row['numarMetri'] ,$row['transport'] ,$row['nume'] ,$row['telefon'] ,$row['oras'] ,$row['dataLimita'] ,$row['pret'], $row['comFinalizata']);
                  }
              }
            }
            if($ok === 0 && isset($_SESSION['search']))
                 echo "<div style=\"margin-left:350px; font-size:25px; color:#332097;\">Nicio comanda gasita</div>";
            unset($_SESSION['search']);
        ?>
    </div>
      

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <?php
      unset($_POST['final']);
    ?>
</body>
</html>
