<?php 
session_start(); 
require_once('functions.php');
require_once('db_conn.php');

function unsetAgenda()
{
    unset($_SESSION['nume']);
    unset($_SESSION['telefon']);
    unset($_SESSION['email']);
    unset($_SESSION['functie']);
}

if(isset($_POST['inapoi'])){
    unsetAgenda();
    header("Location: agenda.php");
}

if(isset($_POST['addBtn'])){
    $name = $_POST['nume'];  
    $tel=$_POST['telefon'];
    $email = $_POST['email'];
    $functie = $_POST['functie'];
    $poza = $_FILES['imagine'];
    $img_name = $_FILES['imagine']['name'];
    $img_size = $_FILES['imagine']['size'];
    $tmp_name = $_FILES['imagine']['tmp_name'];
    $error = $_FILES['imagine']['error'];
    $uid = $_SESSION['uid'];


    $pattern = "/^07[0-9]{8}$/";

    if(isset($_POST['nume'])){
        $_SESSION['nume']=$_POST['nume'];
    }
    if(isset($_POST['telefon'])){
        $_SESSION['telefon']=$_POST['telefon'];
    }
    if(isset($_POST['email'])){
        $_SESSION['email']=$_POST['email'];
    }
    if(isset($_POST['functie'])){
        $_SESSION['functie']=$_POST['functie'];
    }

    if (empty($name) || empty($tel) || empty($email) || empty($functie) || empty($img_name)) {
        header("Location: adaugaPersoana.php?error=Completati toate campurile");
        exit();
    }
    if(preg_match("/([%\$#\*]+)/", $name))
    {
        header("Location: adaugaPersoana.php?error=Nume invalid");
        exit();
    }
    else{
        $_SESSION['nume']=$_POST['nume'];
    }
    if (!preg_match($pattern, $tel)) {
        header("Location: adaugaPersoana.php?error=Telefon invald");
        exit();
    }
    else{
        $_SESSION['telefon']=$_POST['telefon'];
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: adaugaPersoana.php?error=Email invalid");
        exit();
    }
    else{
        $_SESSION['email']=$_POST['email'];
    }
    if(preg_match("/([%\$#\*]+)/", $functie))
    {
        header("Location: adaugaPersoana.php?error=Functie invalida");
        exit();
    }
    else{
        $_SESSION['functie']=$_POST['functie'];
    }
    if ($error === 0) {
        if ($img_size > 125000) {
            header("Location: adaugaPersoana.php?error=Fisierul este prea mare");
            exit();
        }else {
            $img_ex = pathinfo($img_name, PATHINFO_EXTENSION);
            $img_ex_lc = strtolower($img_ex);
            $allowed_exs = array("jpg", "jpeg", "png"); 
            if (in_array($img_ex_lc, $allowed_exs)) {
                $new_img_name = uniqid("IMG-", true).'.'.$img_ex_lc;
                $img_upload_path = 'uploads/agenda/'.$new_img_name;
                move_uploaded_file($tmp_name, $img_upload_path);
                $sqll = "SELECT * FROM agenda WHERE nume='$name' AND idUtilizator=$uid";
                $resultt = mysqli_query($conn, $sqll);
                if (mysqli_num_rows($resultt) > 0) {
                    header("Location: adaugaPersoana.php?error=Numele indrodus deja exista");
                    exit();
                }
                $sqll = "SELECT * FROM agenda WHERE telefon='$tel' AND idUtilizator=$uid";
                $resultt = mysqli_query($conn, $sqll);
                if (mysqli_num_rows($resultt) > 0) {
                    header("Location: adaugaPersoana.php?error=Numarul de telefon indrodus deja exista");
                    exit();
                }
                $sqll = "SELECT * FROM agenda WHERE email='$email' AND idUtilizator=$uid";
                $resultt = mysqli_query($conn, $sqll);
                if (mysqli_num_rows($resultt) > 0) {
                    header("Location: adaugaPersoana.php?error=Email-ul indrodus deja exista");
                    exit();
                }
                $sql = "INSERT INTO agenda(idUtilizator, nume, telefon, email, functie, poza) VALUES($uid, '$name', '$tel', '$email', '$functie', '$new_img_name')";
                $result = mysqli_query($conn, $sql);
                if ($result) {
                    unsetAgenda();
                    header("Location: agenda.php");
                }else {
                    header("Location: adaugaPersoana.php?error=A aparut o eroare neasteptata");
                    exit();
                }
            }
            else {
                header("Location: adaugaPersoana.php?error=Fisierul incarcat nu este imagine");
                exit();
            }
        }
    }else {
        header("Location: adaugaPersoana.php?error=A aparut o eroare neasteptata");
        exit();
    }

}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <link rel="stylesheet" type="text/css" href="css/adaugaPersoana.css">
</head>
<body>
     <form action="adaugaPersoana.php" method="post" enctype="multipart/form-data" style="border-color: #332097;">
        <h2 style="color: #332097; font-size: 35px;">Adauga contact</h2>
        <p class="<?php echo isset($_GET['error']) ? 'error' : 'noErr'; ?>"><?php if (isset($_GET['error'])) {echo $_GET['error'];}else{echo "_";} ?></p>
        <label>Nume</label>
        <input type="text" name="nume" placeholder="Nume" value="<?php
                    if (isset($_SESSION['nume'])) {
                        echo $_SESSION['nume'];
                    }
                    ?>"><br>
        <label>Telefon</label>
        <input type="text" name="telefon" placeholder="Telefon" value="<?php
                    if (isset($_SESSION['telefon'])) {
                        echo $_SESSION['telefon'];
                    }
                    ?>"><br>
        <label>Email</label>
        <input type="text" name="email" placeholder="Email" value="<?php
                    if (isset($_SESSION['email'])) {
                        echo $_SESSION['email'];
                    }
                    ?>"><br>
        <label>Functie</label>
        <input type="text" name="functie" placeholder="Functie" value="<?php
                    if (isset($_SESSION['functie'])) {
                        echo $_SESSION['functie'];
                    }
                    ?>"><br>
        <label>Imagine</label>
        <input type="file" name="imagine" accept="image/*"><br>
        <div class="butoane">
            <button name="addBtn" class="adauga">Adauga persoana</button>
        <button name="inapoi" class="inapoi">Inapoi</button>
        </div>
     </form>
</body>
</html>