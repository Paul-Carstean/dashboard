<?php
require_once('db_conn.php');

function contact($nume, $id, $tel, $email, $functie, $imagine){
	$element="<form class=\"aform\" action=\"agenda.php\" method=\"post\">
              <div class=\"container\">
                <img src=\"$imagine\" alt=\"Descriere imagine\">
                <div class=\"info\">
                  <p><b>Nume: </b><text>$nume</text></p>
                  <input type=\"hidden\" name=\"id\" value=\"$id\">
                  <input type=\"hidden\" name=\"imagine\" value=\"$imagine\">
                  <p><b>Telefon: </b><text>$tel</text></p>
                  <p><b>Email: </b><text>$email</text></p>
                  <p><b>Functie: </b><text>$functie</text></p>
                  <div class=\"abutoane\">
                    <button name=\"sterge\" class=\"asterge\">sterge</button>
                    <button name=\"edit\" class=\"aedit\">editeaza</button>
                  </div>
                </div>
              </div>
            </form>
            ";
     echo $element;
}

function editContact($nume, $tel, $email, $functie){
  $element = "<label>Nume</label>
        <input type=\"text\" name=\"nume\" value=\"$nume\"><br>
        <label>Telefon</label>
        <input type=\"text\" name=\"telefon\" value=\"$tel\"><br>
        <label>Email</label>
        <input type=\"text\" name=\"email\" value=\"$email\"><br>
        <label>Functie</label>
        <input type=\"text\" name=\"functie\" value=\"$functie\"><br>
        <label>Imagine</label>";
  echo $element;
}

function comanda($id, $modelID, $montare, $tipMontare, $modelStalpi, $inaltime, $numarMetri, $transport, $nume, $telefon, $oras, $dataLimita, $pret, $comFinalizata){
  $conectie = mysqli_connect("localhost", "root", "", "dashboard_db");
  $sqll = "SELECT * FROM model WHERE id=$modelID";
  $resultt = mysqli_query($conectie, $sqll);
  $row = mysqli_fetch_assoc($resultt);
  $model = "uploads/modele/".$row['poza'];
  $modelName = $row['nume'];
  $trans = "cu";
  if($transport){
    $trans = "fara";
  }
  $dataCurenta = date('Y-m-d');
  $dataLimitaa = date('Y-m-d', strtotime($dataLimita));
  $border = "";
  if ($dataLimitaa < $dataCurenta) {
      $border = "style=\"border: 7px solid #9b2929;\"";
  }
  if($montare){
    $element = "<form class=\"cform\" action=\"home.php\" method=\"post\">
                  <div class=\"comanda\" $border>
                    <img src=\"$model\" alt=\"Imaginea ta\">
                    <div class=\"detalii\">
                        <div>
                            <h2 style=\"font-size:30px; font-weight: bold; margin-bottom: 15px;\">Comanda $id</h2>
                            <hr style=\"margin: 0; border: 1px solid #332097; margin-right: 40px; margin-bottom: 20px;\">
                        </div>
                        <div class=\"rnd\">
                            <text><b>Model: </b><text>$modelName <b>, </b></text></text>
                            <text>cu stalpi <text>$modelStalpi </text></text>
                            <text>la <text>$inaltime</text></text>
                        </div>
                        <div class=\"rnd\">
                            <text><b>Numar metri: </b><text>$numarMetri m <b>,</b></text></text>
                            <text><b>cu montare </b>$tipMontare </text>
                            <text><b>$trans </b>transport</text>
                        </div>
                        <div class=\"rnd\">
                            <text><b>Cumparator: </b><text>$nume,</text></text>
                            <text><b>Oras: </b><text>$oras,</text></text>
                            <text><b>Telefon: </b><text>$telefon</text></text>
                        </div>
                        <div class=\"rnd\">
                            <text><b>Data limita: </b><text>$dataLimita, </text></text>
                            <text><b>Pret: </b><text>$pret lei</text></text>
                        </div>
                        <div class=\"butoane\">
                            <input type=\"hidden\" name=\"fid\" value=\"$id\">
                            <button class=\"sterge\" name=\"sterge\">Sterge</button>
                            <button class=\"edit\" name=\"editeaza\">Editeaza</button>
                            <button class=\"final\" name=\"final\">Finalizeaza comanda</button>
                        </div>
                    </div>
                  </div>
                </form>";
  }
  else{
    $element = "<form class=\"cform\" action=\"home.php\" method=\"post\">
                  <div class=\"comanda\" $border>
                    <img src=\"$model\" alt=\"Imaginea ta\">
                    <div class=\"detalii\">
                      <div>
                        <h2 style=\"font-size:30px; font-weight: bold; margin-bottom: 15px;\">Comanda $id</h2>
                        <hr style=\"margin: 0; border: 1px solid #332097; margin-right: 40px; margin-bottom: 20px;\">
                      </div>
                      <div class=\"rnd\">
                        <text><b>Model: </b><text>$modelName <b>, </b></text></text>
                        <text>cu stalpi <text>$modelStalpi </text></text>
                        <text>la <text>$inaltime</text></text>
                      </div>
                      <div class=\"rnd\">
                        <text><b>Numar metri: </b><text>$numarMetri m <b>,</b></text></text>
                        <text><b>fara montare </b> </text>
                        <text><b>$trans </b>transport</text>
                      </div>
                      <div class=\"rnd\">
                        <text><b>Cumparator: </b><text>$nume,</text></text>
                        <text><b>Oras: </b><text>$oras,</text></text>
                        <text><b>Telefon: </b><text>$telefon</text></text>
                      </div>
                      <div class=\"rnd\">
                        <text><b>Data limita: </b><text>$dataLimita, </text></text>
                        <text><b>Pret: </b><text>$pret lei</text></text>
                      </div>
                      <div class=\"butoane\">
                        <input type=\"hidden\" name=\"fid\" value=\"$id\">
                        <button class=\"sterge\" name=\"sterge\">Sterge</button>
                        <button class=\"edit\" name=\"editeaza\">Editeaza</button>
                        <button class=\"final\" name=\"final\">Finalizeaza comanda</button>
                      </div>
                    </div>
                  </div>
                </form>";
  }
  echo $element;
}

function comandaFinalizata($id, $modelID, $montare, $tipMontare, $modelStalpi, $inaltime, $numarMetri, $transport, $nume, $telefon, $oras, $dataLimita, $pret, $comFinalizata){
  $conectie = mysqli_connect("localhost", "root", "", "dashboard_db");
  $sqll = "SELECT * FROM model WHERE id=$modelID";
  $resultt = mysqli_query($conectie, $sqll);
  $row = mysqli_fetch_assoc($resultt);
  $model = "uploads/modele/".$row['poza'];
  $modelName = $row['nume'];
  $trans = "cu";
  if($transport){
    $trans = "fara";
  }
  
  $border = "style=\"border: 7px solid #216b25\"";
  if($montare){
    $element = "<form class=\"cform\" action=\"home.php\" method=\"post\">
                  <div class=\"comanda\" $border>
                  <img src=\"$model\" alt=\"Imaginea ta\">
                  <div class=\"detalii\">
                      <div>
                          <h2 style=\"font-size:30px; font-weight: bold; margin-bottom: 15px;\">Comanda $id</h2>
                          <hr style=\"margin: 0; border: 1px solid #332097; margin-right: 40px; margin-bottom: 20px;\">
                      </div>
                      <div class=\"rnd\">
                          <text><b>Model: </b><text>$modelName <b>, </b></text></text>
                          <text>cu stalpi <text>$modelStalpi </text></text>
                          <text>la <text>$inaltime</text></text>
                      </div>
                      <div class=\"rnd\">
                          <text><b>Numar metri: </b><text>$numarMetri m <b>,</b></text></text>
                          <text><b>cu montare </b>$tipMontare </text>
                          <text><b>$trans </b>transport</text>
                      </div>
                      <div class=\"rnd\">
                          <text><b>Cumparator: </b><text>$nume,</text></text>
                          <text><b>Oras: </b><text>$oras,</text></text>
                          <text><b>Telefon: </b><text>$telefon</text></text>
                      </div>
                      <div class=\"rnd\">
                          <text><b>Data limita: </b><text>$dataLimita, </text></text>
                          <text><b>Pret: </b><text>$pret lei</text></text>
                      </div>
                      <div class=\"butoane\">

                      
                          <button class=\"sterge\" name=\"sterge\">Sterge</button>
                          <text class=\"comFinalizata\">Comanda Finalizata</text>
                      </div>
                  </div>
              </div>
              </from>";
  }
  else{
    $element = "<form class=\"cform\" action=\"home.php\" method=\"post\">
                  <div class=\"comanda\" $border>
                    <img src=\"$model\" alt=\"Imaginea ta\">
                    <div class=\"detalii\">
                      <div>
                        <h2 style=\"font-size:30px; font-weight: bold; margin-bottom: 15px;\">Comanda $id</h2>
                        <hr style=\"margin: 0; border: 1px solid #332097; margin-right: 40px; margin-bottom: 20px;\">
                      </div>
                      <div class=\"rnd\">
                        <text><b>Model: </b><text>$modelName <b>, </b></text></text>
                        <text>cu stalpi <text>$modelStalpi </text></text>
                        <text>la <text>$inaltime</text></text>
                      </div>
                      <div class=\"rnd\">
                        <text><b>Numar metri: </b><text>$numarMetri m <b>,</b></text></text>
                        <text><b>fara montare </b> </text>
                        <text><b>$trans </b>transport</text>
                      </div>
                      <div class=\"rnd\">
                        <text><b>Cumparator: </b><text>$nume,</text></text>
                        <text><b>Oras: </b><text>$oras,</text></text>
                        <text><b>Telefon: </b><text>$telefon</text></text>
                      </div>
                      <div class=\"rnd\">
                        <text><b>Data limita: </b><text>$dataLimita, </text></text>
                        <text><b>Pret: </b><text>$pret lei</text></text>
                      </div>
                      <div class=\"butoane\">
                        <button class=\"sterge\" name=\"sterge\">Sterge</button>
                        <text class=\"comFinalizata\">Comanda Finalizata</text>
                      </div>
                    </div>
                  </div>
                </form>";
  }
  echo $element;
}

?>