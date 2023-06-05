<?php 
session_start(); 
require_once('functions.php');
require_once('db_conn.php');

if(isset($_POST['inapoi'])){
    unset($_SESSION['eid']);
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

    $pattern = "/^07[0-9]{8}$/";

    if (empty($name) || empty($tel) || empty($email) || empty($functie)) {
        header("Location: editContact.php?error=Completati toate campurile");
        exit();
    }
    if(preg_match("/([%\$#\*]+)/", $name))
    {
        header("Location: editContact.php?error=Nume invalid");
        exit();
    }
    if (!preg_match($pattern, $tel)) {
        header("Location: editContact.php?error=Telefon invald");
        exit();
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: editContact.php?error=Email invalid");
        exit();
    }
    if(preg_match("/([%\$#\*]+)/", $functie))
    {
        header("Location: editContact.php?error=Functie invalida");
        exit();
    }
    if(!empty($img_name)){
        if ($error === 0) {
            if ($img_size > 12500000) {
                header("Location: editContact.php?error=Fisierul este prea mare");
                exit();
            }else {
                $img_ex = pathinfo($img_name, PATHINFO_EXTENSION);
                $img_ex_lc = strtolower($img_ex);

                $allowed_exs = array("jpg", "jpeg", "png"); 

                if (in_array($img_ex_lc, $allowed_exs)) {
                    $new_img_name = uniqid("IMG-", true).'.'.$img_ex_lc;
                    $img_upload_path = 'uploads/agenda/'.$new_img_name;
                    move_uploaded_file($tmp_name, $img_upload_path);
                    $result=getData("agenda");
                    while($row=mysqli_fetch_assoc($result)){
                        if($row['id'] === $_SESSION['eid'])
                        {
                            if($row['nume'] !== $name){
                                $sqll = "SELECT * FROM agenda WHERE nume='$name'";
                                $resultt = mysqli_query($conn, $sqll);

                                if (mysqli_num_rows($resultt) > 0) {
                                    header("Location: editContact.php?error=Numele indrodus deja exista");
                                    exit();
                                }
                            }
                            if($row['telefon'] !== $tel){
                                $sqll = "SELECT * FROM agenda WHERE telefon='$tel'";
                                $resultt = mysqli_query($conn, $sqll);

                                if (mysqli_num_rows($resultt) > 0) {
                                    header("Location: editContact.php?error=Numarul de telefon indrodus deja exista");
                                    exit();
                                }
                            }
                            if($row['email'] !== $email){
                                $sqll = "SELECT * FROM agenda WHERE email='$email'";
                                $resultt = mysqli_query($conn, $sqll);

                                if (mysqli_num_rows($resultt) > 0) {
                                    header("Location: editContact.php?error=Email-ul indrodus deja exista");
                                    exit();
                                }
                            }
                            unlink("uploads/agenda/".$row['poza']);
                            $eid=$row['id'];
                            $sql = "UPDATE agenda SET nume='$name', telefon='$tel', email='$email', functie='$functie', poza='$new_img_name' WHERE id='$eid'";
                            $result = mysqli_query($conn, $sql);
                            if ($result) {
                                unset($_SESSION['eid']);
                                header("Location: agenda.php");
                            }else {
                                header("Location: editContact.php?error=A aparut o eroare neasteptata");
                                exit();
                            }
                        }
                    }                    
                }
                else {
                    header("Location: editContact.php?error=Fisierul incarcat nu este imagine");
                    exit();
                }
            }
        }else {
            header("Location: editContact.php?error=A aparut o eroare neasteptataaa");
            exit();
        }
    }
    else{
        $result=getData("agenda");
        while($row=mysqli_fetch_assoc($result)){
            if($row['id'] === $_SESSION['eid'])
            {
                if($row['nume'] !== $name){
                    $sqll = "SELECT * FROM agenda WHERE nume='$name'";
                    $resultt = mysqli_query($conn, $sqll);

                    if (mysqli_num_rows($resultt) > 0) {
                        header("Location: editContact.php?error=Numelee indrodus deja exista");
                        exit();
                    }
                }
                if($row['telefon'] !== $tel){
                    $sqll = "SELECT * FROM agenda WHERE telefon='$tel'";
                    $resultt = mysqli_query($conn, $sqll);

                    if (mysqli_num_rows($resultt) > 0) {
                        header("Location: editContact.php?error=Numarul de telefon indrodus deja exista");
                        exit();
                    }
                }
                if($row['email'] !== $email){
                    $sqll = "SELECT * FROM agenda WHERE email='$email'";
                    $resultt = mysqli_query($conn, $sqll);

                    if (mysqli_num_rows($resultt) > 0) {
                        header("Location: editContact.php?error=Email-ul indrodus deja exista");
                        exit();
                    }
                }
                $poza = $row['poza'];
                $eid=$row['id'];
                $sql = "UPDATE agenda SET nume='$name', telefon='$tel', email='$email', functie='$functie', poza='$poza' WHERE id='$eid'";
                $result = mysqli_query($conn, $sql);
                if ($result) {
                    unset($_SESSION['eid']);
                    header("Location: agenda.php");
                }else {
                    header("Location: editContact.php?error=A aparut o eroare neasteptata");
                    exit();
                }
            }
        } 
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
     <form action="editContact.php" method="post" enctype="multipart/form-data" style="border-color: #332097;">
        <h2 style="color: #332097; font-size: 35px; margin-top:24px;">Editeaza contact</h2>
        <p class="<?php echo isset($_GET['error']) ? 'error' : 'noErr'; ?>"><?php if (isset($_GET['error'])) {echo $_GET['error'];}else{echo "_";} ?></p>
        <?php
        $result=getData("agenda");
            while($row=mysqli_fetch_assoc($result)){
                if($row['id'] === $_SESSION['eid'])
                {
                        editContact($row['nume'], $row['telefon'],$row['email'],$row['functie']);
                }
            }
        ?>
        <div class="rand">
            <input type="file" name="imagine" style="width: 40%;" accept="image/*">
            <text>*Imaginea ramane aceasi daca nu se introduce alta</text>
        </div>
        <div class="butoane">
            <button name="addBtn" class="adauga" style="margin-left: 33%;">editeaza contact</button>
        <button name="inapoi" class="inapoi">Inapoi</button>
        </div>
     </form>
</body>
</html>