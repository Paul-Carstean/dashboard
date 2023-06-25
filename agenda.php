<?php
session_start(); 
require_once('functions.php');
require_once('db_conn.php');

  if(isset($_POST['plus'])) {
    header("Location: adaugaPersoana.php");
  }

  if(isset($_POST['searchButton'])){
    if($_POST['searchInput'] !== ""){
        $_SESSION['search'] = $_POST['searchInput'];
    }
  }

  if(isset($_POST['sterge'])) {
    $id=$_POST['id'];
    $img=$_POST['imagine'];
    $con=mysqli_connect("localhost", "root", "", "dashboard_db");
    unlink($img);
    $sql="DELETE FROM agenda WHERE id='$id'";
    $result=mysqli_query($con,$sql);
  }

  if(isset($_POST['edit'])){
    $_SESSION['eid']=$_POST['id'];
    header("location: editContact.php");
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
  <form action="agenda.php" method="post">
    <nav class="navbar navbar-default">
      <div class="container-fluid" style="height: 90px;">
        <div class="navbar-header">
          <a class="navbar-brand" style="color: #332097; margin-left: 12%; margin-top: 9%;" href="home.php">Dashboard</a>
        </div>
        <div class="navbar-form navbar-center">
          <div class="input-group">
            <input type="text" name="searchInput" class="form-control" placeholder="Cauta contact">
            <span class="input-group-btn">
              <button class="btn btn-default" name="searchButton" type="submit"><i class="fa-solid fa-magnifying-glass fa-lg"></i></button>
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
            <li class="nav-li"><a class="nav-a" style="color: #5843c6;" href="agenda.php"><i class="fa-solid fa-address-book"></i>  Agenda</a></li>
            <li class="nav-li"><a class="nav-a" href="statistici.php"><i class="fa-solid fa-chart-column"></i>  Statistici</a></li>
          </ul>
            <li class="navli"><button type="submit" ><a href="logout.php"><i class="fas fa-sign-out-alt"></i>  Logout</a></li></button>
          <ul>
          </ul>
          
        </div>
      </div>
    </div>
  <div class=continut>
    <?php
    if(!isset($_SESSION['search']))
    echo "
      <div class=\"pcontainer\">
        <button type=\"submit\" class=\"plus-button\" name=\"plus\">+</button>
      </div>";

    ?>
    </form>
      <?php
            $result=getDataID("agenda", $_SESSION['uid']);
            $ok=0;
            if($result){
              while($row=mysqli_fetch_assoc($result)){
                  if(isset($_SESSION['search']))
                  {
                      if(str_contains(strtoupper($row['nume']),strtoupper($_SESSION['search']))  || str_contains(strtoupper($row['functie']),strtoupper($_SESSION['search']))){
                          contact($row['nume'], $row['id'], $row['telefon'],$row['email'],$row['functie'],"uploads/agenda/".$row['poza']);
                          $ok=$ok+1;
                      }
                  }
                  else{
                      contact($row['nume'], $row['id'], $row['telefon'],$row['email'],$row['functie'],"uploads/agenda/".$row['poza']);
                  }
               }
            }
            if($ok === 0 && isset($_SESSION['search']))
                echo "<div style=\"margin-left:350px; font-size:25px; color:#332097;\">Niciun contact gasit</div>";
            unset($_SESSION['search']);
        ?>
    </div>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</body>
</html>
