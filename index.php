<?php 
session_start(); 
include "db_conn.php";

if (isset($_POST['email']) && isset($_POST['password'])) {
    function validate($data){
       $data = trim($data);
       $data = stripslashes($data);
       $data = htmlspecialchars($data);
       return $data;
    }

    $email = validate($_POST['email']);
    $pass = validate($_POST['password']);

    if (empty($email)) {
        header("Location: index.php?error=Introduceti email-ul");
        exit();
    }
    $_SESSION['aemail']=$_POST['email'];
    if(empty($pass)){
        header("Location: index.php?error=Introduceti parola");
        exit();
    }
    else{
        $pass = md5($pass);
        $sql = "SELECT * FROM utilizatori WHERE email='$email' AND parola='$pass'";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) === 1) {
            $row = mysqli_fetch_assoc($result);
            if ($row['email'] === $email && $row['parola'] === $pass) {
                $_SESSION['uname'] = $row['nume'];
                $_SESSION['uemail'] = $row['email'];
                $_SESSION['uid'] = $row['id'];
                header("Location: home.php");
                exit();
            }else{
                header("Location: index.php?error=Email sau parola gresita");
                exit();
            }
        }else{
            header("Location: index.php?error=Email sau parola gresita");
            exit();
        }
    }
}?>

<!DOCTYPE html>
<html>
<head>
    <title>Inregistrare</title>
    <link rel="stylesheet" type="text/css" href="css/loginStyle.css">
</head>
<body>
    <form action="index.php" method="post" style="border-color: #332097;">
        <h2 style="color: #332097; font-size: 40px;">Autentificare</h2>
        <p class="<?php echo isset($_GET['error']) ? 'error' : 'noErr'; ?>"><?php if (isset($_GET['error'])) {echo $_GET['error'];}else{echo "_";} ?></p>
        <label>Email</label>
        <input type="text" name="email" placeholder="Email" value="<?php
                    if (isset($_SESSION['aemail'])) {
                        echo $_SESSION['aemail'];
                    }
                    ?>"><br>

        <label>Parola</label>
        <input type="password" style="margin-bottom: 3%;" name="password" placeholder="Parola"><br>
        <button type="submit" >Autentificare</button>
            <a href="signup.php" class="ca" style="color: #332097;">Creaza un cont</a>
    </form>
    <?php 
        unset($_SESSION['aemail']);
    ?>
</body>
</html>