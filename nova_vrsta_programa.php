<?php
session_start();

header('Content-Type: text/html; charset=utf-8');

$greska = "";

if (!isset($_SESSION['korisnickoIme'])) 
{
    $ispisi .= "Morate biti prijavljeni";
    header("Location:prijava.php");
    exit();
} 
else if($_SESSION['tip_korisnika'] != 2 ){
   header("Location:index.php");
   exit();
}
else
{
  $korisnik = $_SESSION['korisnik_id'];
  $korisnikIme = $_SESSION['korisnickoIme'];
  $tipKorisnika = $_SESSION['tip_korisnika'];
}




if (isset($_POST["Upisi"])) {
    $nazivPrograma = $_POST["naziv"];

    if(empty($nazivPrograma) )
    {
      $greska .= "Niste unijeli naziv vrste programa <br>";
    }

              
        

  if($greska === ""){ 
    $sakrij = "display: none";
    include("baza.class.php");
    $db = new Baza();
    $db->spojiDB();
    $datum = date("Y-m-d H:i:s");


    $upitUpisi = "INSERT INTO `vrsta_programa`(`naziv`) VALUES ('$nazivPrograma');";
    $prijenos = $db->updateDB($upitUpisi);

    foreach ($_POST['korisnici'] as $selectedOption){
        if($selectedOption > 0){
          $izvrsi = "INSERT INTO `vrstaProgramaModerator`(`vrsta_programa`, `moderator`) VALUES ((select id from vrsta_programa where naziv = '$nazivPrograma'), '$selectedOption');";
          $spremi = $db->updateDB($izvrsi);
      }
    }

    $poruka = "Dodali ste novu vrstu programa program!
    <div class='jejjjj'> </div> ";
                
   
     //dnevnik
        $datum = date("Y-m-d H:i:s");
        $radnja = "Korisnik  $korisnikIme je dodao novu vrstu programa!";
        $dnevnik = "INSERT INTO `dnevnik`(  `korisnik`, `datum`, `Opis`, `tip_akcije`)  values  ('$korisnik','$datum ',' $radnja ', 4)";
        $napravi = $db->updateDB($dnevnik);


    $db->zatvoriDB();

  }
   
} 
?>

<!DOCTYPE html>
<html>
	<head>
		<title> Nova vrsta programa </title>
        <meta charset="utf-8">
        <meta name="author" content="Matea">
        <meta name="keywords" content="novi_proizvod, upis_proizvoda">
        <meta name="description" content="Stranica je rađena 06.03.2017">

         <link href="css/osnova.css" rel="stylesheet" type="text/css">		
        <link href="css/vrsta_programa.css" rel="stylesheet" type="text/css">    


	</head>
	<body >
		<header>
    <figure>
      <figcaption class="naslov"> Nova vrsta programa </figcaption>
      <img src="slike/skok.jpg" class="prvaSlika" alt="novi program" usemap="#mapa1"/>
      <map name="mapa1">
          <area href="index.php" alt="index" shape="rect" target="_blank" coords="0,0,200,200"/>
          <area href="#noviProizvod" alt="noviProizvod" shape="rect" target="_parent" coords="200,0,400,200"/>
      </map>
    </figure>
    </header>

   
 <?php
      include 'nav.php';
   ?>




    <?php
	     if(isset($greska)) {
        echo $greska;
        }

        if (isset($poruka)) {
          $sakrij = "display:none";
          echo $poruka;
        }
    ?>

  <div <?php  if(isset($sakrij)) echo "style= " . '"' .  $sakrij . '";' ?> >   
	  <section id="noviProgram" > 
      <form id="nova_vrsta_programa" method = "POST" name="novaVrstaPrograma" action="nova_vrsta_programa.php">

          <h2> Za stvaranje nove vrste programa popunite sljedeće stavke: </h2>
          <label for="naziv" id="lblNaziv"> Naziv vrste programa: </label> 
          <br>
          <input type="text" maxlength="30" name="naziv" id="naziv" required> 
          <br>
          <br>
          <br>
          
        <select name='korisnici[]' id='korisnici' multiple='multiple' >
            <option value="-1"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; == Odaberi kategoriju == 
            </option>
            <?php
              include_once("baza.class.php");
              $db = new Baza();
              $db->spojiDB();
              $upit = "SELECT korisnik_id, ime, prezime
                      FROM korisnik where tip_korisnika=1;";
              $odgovor = $db->selectDB($upit);               
             
              while (list($id, $ime, $prezime) = $odgovor->fetch_array()) 
                      {
                          echo '<option value="'. $id .'">'. $ime . " ". $prezime .'</option>';
                      }
             $db->zatvoriDB();
            ?>     
        </select>
        <br>
        <br>
        <br>     
         
        <input type="submit" name="Upisi" value="Uvedi program"> 
        <input type="reset" value="Vraćanje na inicijalne postavke"> 
      </form>
    </section>
  </div>

  <?php 
    include 'footer.php';
   ?>

      <script type="text/javascript" src="js/matbodulu.js">         
      </script>
	</body>
</html>