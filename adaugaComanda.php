<?php 
session_start(); 
require_once('functions.php');
require_once('db_conn.php');

function unsetComanda(){
    unset($_SESSION['model']);
    unset($_SESSION['montare']);
    unset($_SESSION['tipMontare']);
    unset($_SESSION['modelStalpi']);
    unset($_SESSION['inaltime']);
    unset($_SESSION['numarMetri']);
    unset($_SESSION['transport']);
    unset($_SESSION['cnume']);
    unset($_SESSION['ctelefon']);
    unset($_SESSION['oras']);
    unset($_SESSION['dataLimita']);
    unset($_SESSION['pret']);
}

if(isset($_POST['inapoi'])){
    unsetComanda();
    header("Location: home.php");
}

if(isset($_POST['adauga'])){

    $model = $_POST['model'];  
    $montare=$_POST['montare'];
    $tipMontare = $_POST['tipMontare'];
    $modelStalpi = $_POST['modelStalpi'];
    $inaltime = $_POST['inaltime'];
    $numarMetri = $_POST['numarMetri'];
    $transport = $_POST['transport'];
    $nume = $_POST['nume'];
    $telefon = $_POST['telefon'];
    $oras = $_POST['oras'];
    $dataLimita = $_POST['dataLimita'];
    $pret = $_POST['pret'];
    $pattern = "/^07[0-9]{8}$/";

    if(isset($_POST['model'])){
        $_SESSION['model']=$_POST['model'];
    }
    if(isset($_POST['montare'])){
        $_SESSION['montare']=$_POST['montare'];
    }
    if(isset($_POST['tipMontare'])){
        $_SESSION['tipMontare']=$_POST['tipMontare'];
    }
    if(isset($_POST['modelStalpi'])){
        $_SESSION['modelStalpi']=$_POST['modelStalpi'];
    }
    if(isset($_POST['inaltime'])){
        $_SESSION['inaltime']=$_POST['inaltime'];
    }
    if(isset($_POST['numarMetri'])){
        $_SESSION['numarMetri']=$_POST['numarMetri'];
    }
    if(isset($_POST['transport'])){
        $_SESSION['transport']=$_POST['transport'];
    }
    if(isset($_POST['nume'])){
        $_SESSION['cnume']=$_POST['nume'];
    }
    if(isset($_POST['telefon'])){
        $_SESSION['ctelefon']=$_POST['telefon'];
    }
    if(isset($_POST['oras'])){
        $_SESSION['oras']=$_POST['oras'];
    }
    if(isset($_POST['dataLimita'])){
        $_SESSION['dataLimita']=$_POST['dataLimita'];
    }
    if(isset($_POST['pret'])){
        $_SESSION['pret']=$_POST['pret'];
    }
    if (empty($model) || empty($montare) || empty($tipMontare) || empty($modelStalpi) || empty($inaltime) || empty($numarMetri) || empty($transport) || empty($nume) || empty($telefon) || empty($oras) || empty($dataLimita) || empty($pret)) {
        header("Location: adaugaComanda.php?error=Completati toate campurile");
        exit();
    }
    if(!is_numeric($numarMetri)){
        header("Location: adaugaComanda.php?error=Numarul de metri trebuie sa fie un numar");
        exit();
    }
    if(preg_match("/([%^@\$#\*]+)/", $nume))
    {
        header("Location: adaugaComanda.php?error=Nume invalid");
        exit();
    }
    if (!preg_match($pattern, $telefon)) {
        header("Location: adaugaComanda.php?error=Telefon invald");
        exit();
    }
    if(preg_match("/([%^@\$#\*]+)/", $oras))
    {
        header("Location: adaugaComanda.php?error=Oras invalid");
        exit();
    }
    $dataCurenta = date('Y-m-d');
    $dataLimitaa = date('Y-m-d', strtotime($dataLimita));

    if ($dataLimitaa < $dataCurenta) {
        header("Location: adaugaComanda.php?error=Data este invalida");
        exit();
    }
    if(!is_numeric($pret)){
        header("Location: adaugaComanda.php?error=Pretul trebuie sa fie un numar");
        exit();
    }
    $sqll = "SELECT * FROM model WHERE nume='$model'";
    $resultt = mysqli_query($conn, $sqll);
    $row = mysqli_fetch_assoc($resultt);
    $modelID = $row['id'];
    $mont = false;
    $trans = false;
    if($montare == "Da"){
        $mont = true;
    }
    if($transport == "Da"){
        $trans = true;
    }
    $sql = "INSERT INTO comenzi(modelID, montare, tipMontare, modelStalpi, inaltime, numarMetri, transport, nume, telefon, oras, dataLimita, pret) VALUES($modelID, $mont, '$tipMontare', '$modelStalpi', '$inaltime', $numarMetri, $trans, '$nume', '$telefon', '$oras', '$dataLimita', $pret)";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        unsetComanda();
        header("Location: home.php");
    }else {
        header("Location: adaugaComanda.php?error=A aparut o eroare neasteptata");
        exit();
    }
}

?>

<!DOCTYPE html>
<html>
<head>
	<title>Dashboard</title>
	<link rel="stylesheet" type="text/css" href="css/adaugaComanda.css">
    <script type="text/javascript">
        function openPopup(url) {
            var w = 800;
            var h = 600;
            var left = (screen.width/2)-(w/2);
            var top = (screen.height/2)-(h/2);
            window.open(url, 'popup', 'width='+w+', height='+h+', top='+top+', left='+left+', resizable=no, location=no, menubar=no, status=no, toolbar=no');
        }
    </script>
</head>
<body>
    <form action="adaugaComanda.php" method="post" style="border-color:#332097;">
        <div class="com">
            <h2 style="color: #332097; font-size: 40px; margin-bottom: 5px;">Adauga comanda</h2>
            <p class="<?php echo isset($_GET['error']) ? 'error' : 'noErr'; ?>"><?php if (isset($_GET['error'])) {echo $_GET['error'];}else{echo "_";} ?></p>
            <div class="rand">
                <label for="model">Model:</label>
                <select class="model" id="model" name="model" >
                    <option value=""></option>
                    <?php 
                    $result=getData("model");
                    while($row=mysqli_fetch_assoc($result)){
                        $aux = $row['nume'];
                        if(isset($_SESSION['model']) && $_SESSION['model'] == $row['nume']){
                            echo "<option value=\"$aux\" selected>$aux</option>";
                        }
                        else{
                            echo "<option value=\"$aux\";>$aux</option>";
                        }
                    }
                    ?>
                </select>
                <button class="addMod" type="button" onclick="openPopup('adaugaModel.php')">adauga model</button>
            </div>
            <div class="rand">
                <label for="montare">Montare:</label>
                <select class="mont" id="montare" name="montare">
                    <option value=""></option>
                    <option value="Da" <?php if(isset($_SESSION['montare']) && $_SESSION['montare'] == 'Da') echo "selected";?>>Da</option>
                    <option value="Nu" <?php if(isset($_SESSION['montare']) && $_SESSION['montare'] == 'Nu') echo "selected";?>>Nu</option>
                </select>
                <label for="tipMontare">Tip montare:</label>
                <select class="tipMont" id="tipMontare" name="tipMontare">
                    <option value=""></option>
                    <option value="fara montare" <?php if(isset($_SESSION['tipMontare']) && $_SESSION['tipMontare'] == 'fara montare') echo "selected";?>>fara montare</option>
                    <option value="normala" <?php if(isset($_SESSION['tipMontare']) && $_SESSION['tipMontare'] == 'normala') echo "selected";?>>normala</option>
                    <option value="cu fundatie" <?php if(isset($_SESSION['tipMontare']) && $_SESSION['tipMontare'] == 'cu fundatie') echo "selected";?>>cu fundatie</option>
                    <option value="cu bordura" <?php if(isset($_SESSION['tipMontare']) && $_SESSION['tipMontare'] == 'cu bordura') echo "selected";?>>cu bordura</option>
                    <option value="cu fundatie si bordura" <?php if(isset($_SESSION['tipMontare']) && $_SESSION['tipMontare'] == 'cu fundatie si bordura') echo "selected";?>>cu fundatie si bordura</option>
                </select>
            </div>
            <div class="rand">
                <label for="modelStalpi">Model stalpi:</label>
                <select class="modelStalpi" id="modelStalpi" name="modelStalpi">
                    <option value=""></option>
                    <option value="normali" <?php if(isset($_SESSION['modelStalpi']) && $_SESSION['modelStalpi'] == 'normali') echo "selected";?>>normali</option>
                    <option value="tuburi mari" <?php if(isset($_SESSION['modelStalpi']) && $_SESSION['modelStalpi'] == 'tuburi mari') echo "selected";?>>tuburi mari</option>
                    <option value="tuburi mici" <?php if(isset($_SESSION['modelStalpi']) && $_SESSION['modelStalpi'] == 'tuburi mici') echo "selected";?>>tuburi mici</option>
                </select>
                <label for="inaltime">Inaltime:</label>
                <select class="inaltime" id="inaltime" name="inaltime">
                    <option value=""></option>
                    <option value="2m" <?php if(isset($_SESSION['inaltime']) && $_SESSION['inaltime'] == '2m') echo "selected";?>>2m</option>
                    <option value="2.5m" <?php if(isset($_SESSION['inaltime']) && $_SESSION['inaltime'] == '2.5m') echo "selected";?>>2.5m</option>
                    <option value="3m" <?php if(isset($_SESSION['inaltime']) && $_SESSION['inaltime'] == '3m') echo "selected";?>>3m</option>
                </select>
            </div>
            <div class="rand">
                <label for="numarMetri">Numar metri:</label>
                <input class="numarMetri" type="text" id="numarMetri" name="numarMetri" style="text-align: center;" placeholder="Numar metri" value="<?php
                    if (isset($_SESSION['numarMetri'])) {
                        echo $_SESSION['numarMetri'];
                    }
                    ?>">
                <label for="transport">Transport:</label>
                <select class="transport" id="transport" name="transport">
                    <option value=""></option>
                    <option value="Da" <?php if(isset($_SESSION['transport']) && $_SESSION['transport'] == 'Da') echo "selected";?>>Da</option>
                    <option value="Nu" <?php if(isset($_SESSION['transport']) && $_SESSION['transport'] == 'Nu') echo "selected";?>>Nu</option>
                </select>
            </div>
            <div class="rand">
                <p style="margin: 0; color: #332097; font-size: 25px;">Informatii cumparator:</p>
            </div>
            <div class="rand">
                <label for="nume">Nume:</label>
                <input type="text" class="nume" name="nume" placeholder="Nume" style="text-align: center;" value="<?php
                    if (isset($_SESSION['cnume'])) {
                        echo $_SESSION['cnume'];
                    }
                    ?>"><br>
            </div>
            <div class="rand">
                <label for="telefon">Telefon:</label>
                <input type="text" name="telefon" placeholder="Telefon" style="text-align: center;" value="<?php
                    if (isset($_SESSION['ctelefon'])) {
                        echo $_SESSION['ctelefon'];
                    }
                    ?>"><br>                
                <label for="oras">Oras:</label>
                <input type="text" id="oras" name="oras" placeholder="Oras" style="text-align: center;" value="<?php
                    if (isset($_SESSION['oras'])) {
                        echo $_SESSION['oras'];
                    }
                    ?>"><br>
            </div>
            <div class="rand">
                <label for="dataLimita">Data limita:</label>
                <input class="dataLimita" type="date" id="dataLimita" name="dataLimita" style="text-align: center;" value="<?php
                    if (isset($_SESSION['dataLimita'])) {
                        echo $_SESSION['dataLimita'];
                    }
                    ?>">
                <label for="pret">Pret:</label>
                <input type="text" class="numarMetri" id="pret" name="pret" placeholder="Pret" style="text-align: center;" value="<?php
                    if (isset($_SESSION['pret'])) {
                        echo $_SESSION['pret'];
                    }
                    ?>"><br>
            </div>
            <div class="butoane rand">
                <button name="inapoi" class="inapoi" type="submit">inapoi</button>
                <button class="adauga" name="adauga" type="submit">adauga</button>
            </div>
        </div>
    </form>
    <?php
    unsetComanda(); 
    ?>
</body>
</html>