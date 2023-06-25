<?php 
session_start(); 
require_once('functions.php');
require_once('db_conn.php');

$ide = $_SESSION['hid'];

$sqle = "SELECT * FROM comenzi WHERE id=$ide";
$resulte = mysqli_query($conn, $sqle);
$rowe = mysqli_fetch_assoc($resulte);

$_SESSION['eModelID'] = $rowe['modelID'];
$_SESSION['eMontare'] = $rowe['montare'];
$_SESSION['eTipMontare'] = $rowe['tipMontare'];
$_SESSION['eModelStalpi'] = $rowe['modelStalpi'];
$_SESSION['eInaltime'] = $rowe['inaltime'];
$_SESSION['eNumarMetri'] = $rowe['numarMetri'];
$_SESSION['eTransport'] = $rowe['transport'];
$_SESSION['eNume'] = $rowe['nume'];
$_SESSION['eTelefon'] = $rowe['telefon'];
$_SESSION['eOras'] = $rowe['oras'];
$_SESSION['eDataLimita'] = $rowe['dataLimita'];
$_SESSION['ePret'] = $rowe['pret'];


function unsetComanda(){
    unset($_SESSION['emodel']);
    unset($_SESSION['emontare']);
    unset($_SESSION['etipMontare']);
    unset($_SESSION['emodelStalpi']);
    unset($_SESSION['einaltime']);
    unset($_SESSION['enumarMetri']);
    unset($_SESSION['etransport']);
    unset($_SESSION['ecnume']);
    unset($_SESSION['ectelefon']);
    unset($_SESSION['eoras']);
    unset($_SESSION['edataLimita']);
    unset($_SESSION['epret']);

    unset($_SESSION['eModel']);
    unset($_SESSION['eMontare']);
    unset($_SESSION['eTipMontare']);
    unset($_SESSION['eTodelStalpi']);
    unset($_SESSION['eInaltime']);
    unset($_SESSION['eNumarMetri']);
    unset($_SESSION['eTransport']);
    unset($_SESSION['eNume']);
    unset($_SESSION['eTelefon']);
    unset($_SESSION['eOras']);
    unset($_SESSION['eDataLimita']);
    unset($_SESSION['ePret']);
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
        $_SESSION['emodel']=$_POST['model'];
    }
    if(isset($_POST['montare'])){
        $_SESSION['emontare']=$_POST['montare'];
    }
    if(isset($_POST['tipMontare'])){
        $_SESSION['etipMontare']=$_POST['tipMontare'];
    }
    if(isset($_POST['modelStalpi'])){
        $_SESSION['emodelStalpi']=$_POST['modelStalpi'];
    }
    if(isset($_POST['inaltime'])){
        $_SESSION['einaltime']=$_POST['inaltime'];
    }
    if(isset($_POST['numarMetri'])){
        $_SESSION['enumarMetri']=$_POST['numarMetri'];
    }
    if(isset($_POST['transport'])){
        $_SESSION['etransport']=$_POST['transport'];
    }
    if(isset($_POST['nume'])){
        $_SESSION['ecnume']=$_POST['nume'];
    }
    if(isset($_POST['telefon'])){
        $_SESSION['ectelefon']=$_POST['telefon'];
    }
    if(isset($_POST['oras'])){
        $_SESSION['eoras']=$_POST['oras'];
    }
    if(isset($_POST['dataLimita'])){
        $_SESSION['edataLimita']=$_POST['dataLimita'];
    }
    if(isset($_POST['pret'])){
        $_SESSION['epret']=$_POST['pret'];
    }
    if (empty($model) || empty($montare) || empty($tipMontare) || empty($modelStalpi) || empty($inaltime) || empty($numarMetri) || empty($transport) || empty($nume) || empty($telefon) || empty($oras) || empty($dataLimita) || empty($pret)) {
        header("Location: editComanda.php?error=Completati toate campurile");
        exit();
    }
    if(!is_numeric($numarMetri)){
        header("Location: editComanda.php?error=Numarul de metri trebuie sa fie un numar");
        exit();
    }
    if(preg_match("/([%^@\$#\*]+)/", $nume))
    {
        header("Location: editComanda.php?error=Nume invalid");
        exit();
    }
    if (!preg_match($pattern, $telefon)) {
        header("Location: editComanda.php?error=Telefon invald");
        exit();
    }
    if(preg_match("/([%^@\$#\*]+)/", $oras))
    {
        header("Location: editComanda.php?error=Oras invalid");
        exit();
    }
    $dataCurenta = date('Y-m-d');
    $dataLimitaa = date('Y-m-d', strtotime($dataLimita));

    if ($dataLimitaa < $dataCurenta) {
        header("Location: editComanda.php?error=Data este invalida");
        exit();
    }
    if(!is_numeric($pret)){
        header("Location: editComanda.php?error=Pretul trebuie sa fie un numar");
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
    $sql = "UPDATE comenzi SET modelID='$modelID', montare='$mont', tipMontare='$tipMontare', modelStalpi='$modelStalpi', `inaltime`='$inaltime', `numarMetri`='$numarMetri', `transport`='$trans', nume='$nume', telefon='$telefon', oras='$oras', dataLimita='$dataLimita', pret='$pret' WHERE id='$ide'";

    $result = mysqli_query($conn, $sql);
    if ($result) {
        unsetComanda();
        header("Location: home.php");
    }else {
        header("Location: editComanda.php?error=A aparut o eroare neasteptata");
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
    <form action="editComanda.php" method="post" style="border-color:#332097;">
        <div class="com">
            <h2 style="color: #332097; font-size: 40px; margin-bottom: 5px;">Editeaza comanda</h2>
            <p class="<?php echo isset($_GET['error']) ? 'error' : 'noErr'; ?>"><?php if (isset($_GET['error'])) {echo $_GET['error'];}else{echo "_";} ?></p>
            <div class="rand">
                <label for="model">Model:</label>
                <select class="model" id="model" name="model" >
                    <option value=""></option>
                    <?php 
                    $result=getData("model");
                    while($row=mysqli_fetch_assoc($result)){
                        if(isset($_SESSION['emodel'])){
                           $aux = $row['nume'];
                            if($_SESSION['emodel'] == $row['nume']){
                                echo "<option value=\"$aux\" selected>$aux</option>";
                            }
                            else{
                                echo "<option value=\"$aux\";>$aux</option>";
                            } 
                        }
                        else{
                            $aux = $row['nume'];
                            if($_SESSION['eModelID'] == $row['id']){
                                echo "<option value=\"$aux\" selected>$aux</option>";
                            }
                            else{
                                echo "<option value=\"$aux\";>$aux</option>";
                            }
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
                    <option value="Da" <?php if(isset($_SESSION['emontare'])){if($_SESSION['emontare'] == 'Da'){ echo "selected";}}else if($_SESSION['eMontare'] == '1'){echo "selected";}?>>Da</option>
                    <option value="Nu" <?php if(isset($_SESSION['emontare'])){if($_SESSION['emontare'] == 'Nu'){ echo "selected";}}else if($_SESSION['eMontare'] == '0'){echo "selected";}?>>Nu</option>
                </select>
                <label for="tipMontare">Tip montare:</label>
                <select class="tipMont" id="tipMontare" name="tipMontare">
                    <option value=""></option>
                    <option value="fara montare" <?php if(isset($_SESSION['etipMontare'])){if($_SESSION['etipMontare'] == 'fara montare'){ echo "selected";}}else if($_SESSION['eTipMontare'] == 'fara montare'){echo "selected";}?>>fara montare</option>
                    <option value="normala" <?php if(isset($_SESSION['etipMontare'])){if($_SESSION['etipMontare'] == 'normala'){ echo "selected";}}else if($_SESSION['eTipMontare'] == 'normala'){echo "selected";}?>>normala</option>
                    <option value="cu fundatie" <?php if(isset($_SESSION['etipMontare'])){if($_SESSION['etipMontare'] == 'cu fundatie'){ echo "selected";}}else if($_SESSION['eTipMontare'] == 'cu fundatie'){echo "selected";}?>>cu fundatie</option>
                    <option value="cu bordura" <?php if(isset($_SESSION['etipMontare'])){if($_SESSION['etipMontare'] == 'cu bordura'){ echo "selected";}}else if($_SESSION['eTipMontare'] == 'cu bordura'){echo "selected";}?>>cu bordura</option>
                    <option value="cu fundatie si bordura" <?php if(isset($_SESSION['etipMontare'])){if($_SESSION['etipMontare'] == 'cu fundatie si bordura'){ echo "selected";}}else if($_SESSION['eTipMontare'] == 'cu fundatie si bordura'){echo "selected";}?>>cu fundatie si bordura</option>
                </select>
            </div>
            <div class="rand">
                <label for="modelStalpi">Model stalpi:</label>
                <select class="modelStalpi" id="modelStalpi" name="modelStalpi">
                    <option value=""></option>
                    <option value="normali" <?php if(isset($_SESSION['emodelStalpi'])){if($_SESSION['emodelStalpi'] == 'normali'){ echo "selected";}}else if($_SESSION['eModelStalpi'] == 'normali'){echo "selected";}?>>normali</option>
                    <option value="tuburi mari" <?php if(isset($_SESSION['emodelStalpi'])){if($_SESSION['emodelStalpi'] == 'tuburi mari'){ echo "selected";}}else if($_SESSION['eModelStalpi'] == 'tuburi mari'){echo "selected";}?>>tuburi mari</option>
                    <option value="tuburi mici" <?php if(isset($_SESSION['emodelStalpi'])){if($_SESSION['emodelStalpi'] == 'tuburi mici'){ echo "selected";}}else if($_SESSION['eModelStalpi'] == 'tuburi mici'){echo "selected";}?>>tuburi mici</option>
                </select>
                <label for="inaltime">Inaltime:</label>
                <select class="inaltime" id="inaltime" name="inaltime">
                    <option value=""></option>
                    <option value="2.5m" <?php if(isset($_SESSION['einaltime'])){if($_SESSION['einaltime'] == '2.5m'){ echo "selected";}}else if($_SESSION['eInaltime'] == '2.5m'){echo "selected";}?>>2.5m</option>
                    <option value="2.75m" <?php if(isset($_SESSION['einaltime'])){if($_SESSION['einaltime'] == '2.75m'){ echo "selected";}}else if($_SESSION['eInaltime'] == '2.75m'){echo "selected";}?>>2.75m</option>
                    <option value="3m" <?php if(isset($_SESSION['einaltime'])){if($_SESSION['einaltime'] == '3m'){ echo "selected";}}else if($_SESSION['eInaltime'] == '3m'){echo "selected";}?>>3m</option>
                </select>
            </div>
            <div class="rand">
                <label for="numarMetri">Numar metri:</label>
                <input class="numarMetri" type="text" id="numarMetri" name="numarMetri" style="text-align: center;" placeholder="Numar metri" value="<?php
                    if (isset($_SESSION['enumarMetri'])) {
                        echo $_SESSION['enumarMetri'];
                    }
                    else{
                        echo $_SESSION['eNumarMetri'];
                    }
                    ?>">
                <label for="transport">Transport:</label>
                <select class="transport" id="transport" name="transport">
                    <option value=""></option>
                    <option value="Da" <?php if(isset($_SESSION['etransport'])){if($_SESSION['etransport'] == 'Da'){ echo "selected";}}else if($_SESSION['eTransport'] == '1'){echo "selected";}?>>Da</option>
                    <option value="Nu" <?php if(isset($_SESSION['etransport'])){if($_SESSION['etransport'] == 'Nu'){ echo "selected";}}else if($_SESSION['eTransport'] == '0'){echo "selected";}?>>Nu</option>
                </select>
            </div>
            <div class="rand">
                <p style="margin: 0; color: #332097; font-size: 25px;">Informatii cumparator:</p>
            </div>
            <div class="rand">
                <label for="nume">Nume:</label>
                <input type="text" class="nume" name="nume" placeholder="Nume" style="text-align: center;" value="<?php
                    if (isset($_SESSION['ecnume'])) {
                        echo $_SESSION['ecnume'];
                    }
                    else{
                        echo $_SESSION['eNume'];
                    }
                    ?>"><br>
            </div>
            <div class="rand">
                <label for="telefon">Telefon:</label>
                <input type="text" name="telefon" placeholder="Telefon" style="text-align: center;" value="<?php
                    if (isset($_SESSION['ectelefon'])) {
                        echo $_SESSION['ectelefon'];
                    }
                    else{
                        echo $_SESSION['eTelefon'];
                    }
                    ?>"><br>                
                <label for="oras">Oras:</label>
                <input type="text" id="oras" name="oras" placeholder="Oras" style="text-align: center;" value="<?php
                    if (isset($_SESSION['eoras'])) {
                        echo $_SESSION['eoras'];
                    }
                    else{
                        echo $_SESSION['eOras'];
                    }
                    ?>"><br>
            </div>
            <div class="rand">
                <label for="dataLimita">Data limita:</label>
                <input class="dataLimita" type="date" id="dataLimita" name="dataLimita" style="text-align: center;" value="<?php
                    if (isset($_SESSION['edataLimita'])) {
                        echo $_SESSION['edataLimita'];
                    }
                    else{
                        echo $_SESSION['eDataLimita'];
                    }
                    ?>">
                <label for="pret">Pret:</label>
                <input type="text" class="numarMetri" id="pret" name="pret" placeholder="Pret" style="text-align: center;" value="<?php
                    if (isset($_SESSION['epret'])) {
                        echo $_SESSION['epret'];
                    }
                    else{
                        echo $_SESSION['ePret'];
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