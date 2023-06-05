<?php 
session_start(); 
include "db_conn.php";

if (isset($_POST['name']) && isset($_POST['email'])
    && isset($_POST['password'])) {

    function validate($data){
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    $name = validate($_POST['name']);  
    $email = validate($_POST['email']);
    $pass = validate($_POST['password']);

    
    if (empty($name)) {
        header("Location: signup.php?error=Introduceti numele");
        exit();
    }
    if(preg_match("/([%\$#\*]+)/", $name))
    {
        header("Location: signup.php?error=Nume invalid");
        exit();
    }
    $_SESSION['inume']=$_POST['name'];

    if(empty($email)){
        header("Location: signup.php?error=Introduceti email-ul");
        exit();
     }
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        header("Location: signup.php?error=Email invalid");
        exit();
    }
    $_SESSION['iemail']=$_POST['email'];

    if(empty($pass)){
        header("Location: signup.php?error=Introduceti parola");
        exit();
    }
    if(strlen($pass) < 5){
        header("Location: signup.php?error=Parola trebuie sa contina minim 5 caractere");
        exit();
    }
    else{
        $pass = md5($pass);

        $sql = "SELECT * FROM users WHERE email='$email' ";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            header("Location: signup.php?error=Email-ul este deja utilizat");
            exit();
        }else{
            $sql2 = "INSERT INTO users(name, email, password) VALUES('$name', '$email', '$pass')";
            $result2 = mysqli_query($conn, $sql2);
            if($result2){
                unset($_SESSION['inume']);
                unset($_SESSION['iemail']);
                header("Location: home.php");  
            }else{
                header("Location: signup.php?error=A aparut o eroare neasteptata");
                exit();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Autentificare</title>
	<link rel="stylesheet" type="text/css" href="css/loginStyle.css">
</head>
<body>
    <form action="signup.php" method="post" style="border-color:#332097;">
        <h2 style="color: #332097; font-size: 30px; margin-bottom: 5px; margin-top: 5px;">Inregistrare</h2>
     	<p class="<?php echo isset($_GET['error']) ? 'error' : 'noErr'; ?>" style="margin: 15px"><?php if (isset($_GET['error'])) {echo $_GET['error'];}else{echo "_";} ?></p>
        <label>Nume</label>
        <input type="text" name="name" placeholder="Nume" value="<?php
                    if (isset($_SESSION['inume'])) {
                        echo $_SESSION['inume'];
                    }
                    ?>"><br>

        <label>Email</label>
        <input type="text" name="email" placeholder="Email" value="<?php
                    if (isset($_SESSION['iemail'])) {
                        echo $_SESSION['iemail'];
                    }
                    ?>"><br>

        <label>Parola</label>
        <input type="password" name="password" placeholder="Parola"><br>
     	<button type="submit">Inregistrare</button>
            <a href="index.php" class="ca" style="color: #332097;">Ai deja cont?</a>
    </form>
    <?php 
        unset($_SESSION['inume']);
        unset($_SESSION['iemail']);
    ?>
</body>
</html>