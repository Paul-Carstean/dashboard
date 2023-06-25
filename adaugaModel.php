<?php 
session_start(); 
require_once('functions.php');
require_once('db_conn.php');

function close(){
    echo '<script>';
    echo 'window.close();';
    echo '</script>';
}

if(isset($_POST['inapoi'])){
    close();
}

if(isset($_POST['adauga'])){
    $name = $_POST['nume'];  
    $img_name = $_FILES['imagine']['name'];
    $img_size = $_FILES['imagine']['size'];
    $tmp_name = $_FILES['imagine']['tmp_name'];
    $error = $_FILES['imagine']['error'];

    if(isset($_POST['nume'])){
        $_SESSION['mnume']=$_POST['nume'];
    }

    if (empty($name) || empty($img_name)) {
        header("Location: adaugaModel.php?error=Completati toate campurile");
        exit();
    }
    if(preg_match("/([!@^&%\$#\*]+)/", $name))
    {
        unset($_SESSION['nume']);
        header("Location: adaugaModel.php?error=Nume invalid");
        exit();
    }
    if ($error === 0) {
        if ($img_size > 5000000) {
            header("Location: adaugaModel.php?error=Imaginea este prea mare");
            exit();
        }else {
            $img_ex = pathinfo($img_name, PATHINFO_EXTENSION);
            $img_ex_lc = strtolower($img_ex);

            $allowed_exs = array("jpg", "jpeg", "png"); 

            if (in_array($img_ex_lc, $allowed_exs)) {
                $new_img_name = uniqid("IMG-", true).'.'.$img_ex_lc;
                $img_upload_path = 'uploads/modele/'.$new_img_name;
                move_uploaded_file($tmp_name, $img_upload_path);
                $sqll = "SELECT * FROM model WHERE nume='$name'";
                $resultt = mysqli_query($conn, $sqll);

                if (mysqli_num_rows($resultt) > 0) {
                    header("Location: adaugaModel.php?error=Numele indrodus deja exista");
                    exit();
                }
                $sql = "INSERT INTO model(nume, poza) VALUES('$name', '$new_img_name')";
                $result = mysqli_query($conn, $sql);
                if ($result) {
                    unset($_SESSION['mnume']);
                    close();
                }else {
                    header("Location: adaugaModel.php?error=A aparut o eroare neasteptata");
                    exit();
                }
            }
            else {
                header("Location: adaugaModel.php?error=Fisierul incarcat nu este imagine");
                exit();
            }
        }
    }else {
        header("Location: adaugaModel.php?error=A aparut o eroare neasteptata");
        exit();
    }

}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <link rel="stylesheet" type="text/css"  href="css/loginStyle.css">
</head>
<body>
     <form action="adaugaModel.php" method="post" enctype="multipart/form-data" style="border-color: #332097;">
        <h2 style="color: #332097; font-size: 40px; margin-top: 5px ;">Adauga model</h2>
        <p class="<?php echo isset($_GET['error']) ? 'error' : 'noErr'; ?>" style="margin: 8px"><?php if (isset($_GET['error'])) {echo $_GET['error'];}else{echo "_";} ?></p>
        <label>Nume model</label>
        <input type="text" name="nume" placeholder="Nume model" value="<?php
                    if (isset($_SESSION['mnume'])) {
                        echo $_SESSION['mnume'];
                    }
                    ?>"><br>
        <label>Poza model</label>
        <input type="file" name="imagine" accept="image/*"><br>
        <button type="submit" class="adauga" name="adauga">adauga</button>
        <button type="submit" name="inapoi" class="inapoi">inapoi</button>
     </form>

<?php 
unset($_SESSION['mnume']);
?>
</body>
</html>