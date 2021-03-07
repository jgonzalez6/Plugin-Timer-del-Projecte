<style>
    #formulari {
        width: 55em;
        margin:auto;
        padding: 10px;
        background: blue;
        border-radius: 5px;
        color: white;
        text-align: center;
    }
</style>

<form id=formulari method="post" style="float: unset">
    <fieldset>
        <legend align="center">Controls del timer del projecte</legend>
    <input type="submit" name="Inici" value="Inici"/>
    <input type="submit" name="Stop" value="Stop"/>
        <input type="submit" name="Registrar" value="Registrar Temps"/>
    <input type="submit" name="Borrar" value="Reiniciar Comptador"/>
    </fieldset>
</form>
<br>
<form id=formulari method="post" style="float: bottom">
    <fieldset>
        <legend align="center">Informació de temps emprat en el projecte</legend>
        <input type="submit" name="Hores" value="Mostrar Total Temps en Hores"/>
        <input type="submit" name="Minuts" value="Mostrar Total Temps en Minuts"/>
        <input type="submit" name="Segons" value="Mostrar Total Temps en Segons"/>
        <input type="submit" name="Llistar" value="Mostrar totes les entrades de temps"/>
    </fieldset>
</form>


<?php
#Connexió a la base de dades
$db_host = "localhost";
$db_nom = "timer";
$db_usuari = "user";
$db_password = "aplicacions";

try {
    $connexio = new PDO("mysql:host=$db_host;dbname=$db_nom", $db_usuari, $db_password);
    $consulta = "SELECT * FROM comptador";
    $query = $connexio->query($consulta);
    $dades = [];
    $horaInici=$dades[0];
    while ($rows = $query->fetch(PDO::FETCH_ASSOC)) {
        $dades[] = $rows;
    }
} catch (PDOException $exception) {
    echo $exception->getMessage();
}


# BOTÓ INICI COMPTADOR
if (isset($_POST["Inici"])){
    $insertar= $connexio -> prepare ("INSERT INTO comptador (Codi,Temps_Inicial)
                  VALUES (Codi,curtime())");
    $insertar -> execute();
    echo "<br><h1 align='center' >Comptador de temps Iniciat</h1><br>";
}

# BOTÓ INSERTAR FINAL COMPTADOR
if (isset($_POST["Stop"])){
    $update= $connexio -> prepare ("UPDATE comptador SET Temps_Final = curtime() WHERE Codi = (SELECT COUNT(*) FROM comptador)");
    $update -> execute();
    echo "<br><h1 align='center' >Comptador de temps Parat</h1><br>";
    echo "<br><h3 align='center' >Si vols registrar el temps definitivament prem el botó registrar temps al menú.</h3><br>";
}
# BOTÓ REGISTRAR TEMPS A LA BASE
if (isset($_POST["Registrar"])){
    $update= $connexio -> prepare ("UPDATE comptador SET Temps_Transcorregut = (SELECT TIMEDIFF(Temps_Final, Temps_Inicial) FROM comptador WHERE Codi = (SELECT COUNT(*) FROM comptador)) WHERE Codi = (Select count(*) FROM comptador);");
    $update -> execute();
    echo "<br><h1 align='center' >El temps que has ingressat s'ha registrat correctament a la base de dades.</h1><br>";
}

#BOTÓ MOSTRAR ENTRADES TEMPS
if (isset($_POST["Llistar"])) {
    mostrarEntradesTemps($dades);
}

#BOTÓ REINICIAR BASE DE DADES
if (isset($_POST["Borrar"])) {
    $borrar= $connexio -> prepare ("TRUNCATE table comptador");
    $borrar -> execute();
    echo "<br><h1 align='center' >Has reiniciat el comptador de temps.</h1><br>";
    echo "<br><h3 align='center' >Totes les entrades s'han eliminat de la base de dades.</h3><br>";
}

# BOTÓ MOSTRAR TEMPS TOTAL EN HORES
if (isset($_POST["Hores"])){
    calcularTemps($dades,3600,"hores");
}

# BOTÓ MOSTRAR TEMPS TOTAL EN MINUTS
if (isset($_POST["Minuts"])){
    calcularTemps($dades,60,"minuts");
}

# BOTÓ MOSTRAR TEMPS TOTAL EN SEGONS
if (isset($_POST["Segons"])){
    calcularTemps($dades,1,"segons");
}

#FUNCIO PER CALCULAR EL TEMPS EN HORES, MINUTS I SEGONS
function calcularTemps($base,$conversio,$string){
    $suma=array_sum(array_column($base, 'Temps_Transcorregut'))/$conversio;
    $temps=number_format((float)$suma, 2,".","" );
    echo "<h1 align='center' ><br>En total has treballat en el projecte un total de " .$temps. " ".$string. ".<br><br>Molt bé, segueix així!";
}


#FUNCIO MOSTRAR TOTES LES ENTRADES DE TEMPS
function mostrarEntradesTemps($base){
    foreach ($base as $clau => $valor) {
        echo "<h3><i><u>Entrada de temps registrada: </u></i></h3>";
        foreach ($valor as $clau2 => $valor2) {
            echo "<p style='color: blue'>" . $clau2 . " : <b>" . $valor2 . "</b><br>";}
    }
}


?>

