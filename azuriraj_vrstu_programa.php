<?php
session_start();

header('Content-Type: text/html; charset=utf-8');

$greska = "";
$sakrij = "display:none";

if (!isset($_SESSION['korisnickoIme'])) {
    $ispisi .= "Morate biti prijavljeni";
    header("Location:prijava.php");
    exit();
} 
else if($_SESSION['tip_korisnika'] != 2 ){
   header("Location:index.html");
   exit();
}
else{
  $korisnik = $_SESSION['korisnik_id'];
  $korisnikIme = $_SESSION['korisnickoIme'];
  $tipKorisnika = $_SESSION['tip_korisnika'];
}

if(isset($_POST['odabranaVrsta'])){
   $odabrani = $_POST['odabranaVrsta'];
}

if (isset($_POST["Azuriraj"])) {
    $sakrij = "display:inline-block";
    include_once("baza.class.php");
    $db = new Baza();
    $db->spojiDB();
    $sakrij = "display:inline-block";
    $odabraniModerator[] = "";

    $sveOVrsti = "SELECT vp.id, vp.naziv, vpm.vrsta_programa, vpm.moderator
                  FROM  `vrstaProgramaModerator` vpm
                  JOIN vrsta_programa vp ON vp.id = vpm.vrsta_programa where vp.id = $odabrani ";
    $nadenoOVrsti = $db->selectDB($sveOVrsti);
    while (list( $id, $naziv, $vrstaID, $moderator) = $nadenoOVrsti->fetch_array()){
        $idVrste = $id;
        $nazivVrste = " value = '$naziv' ";
        $odabraniModerator[] += $moderator;
      }
    
   
     //dnevnik
        $datum = date("Y-m-d H:i:s");
        $radnja = "Korisnik  $korisnik je krenuo s azuriranjem vrste programa!";
        $dnevnik = "INSERT INTO `dnevnik`(`korisnik`,`datum`, `Opis`) VALUES ('" . $korisnik . "','" . $datum . "','" . $radnja . "')";
        $db->updateDB($dnevnik);


    $db->zatvoriDB();

  }


if (isset($_POST['Upisi']) ) {
    $sakrij = "display:inline-block";
    $nazivPrograma = $_POST['naziv'];

    if( empty($nazivPrograma) )
    {
      $greska .= "Niste unijeli naziv vrste programa <br>";
    }
  
    
   
        

  if($greska === "")
  { 
    include_once("baza.class.php");
    $db = new Baza();
    $db->spojiDB();
    $datum = date("Y-m-d H:i:s");
   

    $upitUpisi = "UPDATE `vrsta_programa` SET `naziv`='$nazivPrograma' where id=$odabrani;";
    $prijenos = $db->updateDB($upitUpisi);

    $brisi = "DELETE FROM `vrstaProgramaModerator` WHERE `vrsta_programa`= $odabrani ";
    $izbrisano = $db->updateDB($brisi);

   foreach ($_POST['korisnici'] as $selectedOption){
        if($selectedOption > 0){
          $izvrsi = "INSERT INTO `vrstaProgramaModerator`(`vrsta_programa`, `moderator`) VALUES ((select id from vrsta_programa where naziv = '$nazivPrograma'), '$selectedOption');";
            $spremi = $db->updateDB($izvrsi);

        }
      }


    $poruka = "Promijenili ste vrstu programa! <br> Možete 
    <ul>
      <li>
        <a href=vidiPrograme.php> pregledati programe </a> 
      </li>
      <li>
        <a href=novi_trening.php> dodati trening programu. </a> 
      </li>
      <li> <a href=azurirajProgram.php>promijeniti program </a>
      </li>
    <ul>";
                
   
     //dnevnik
        $datum = date("Y-m-d H:i:s");
        $radnja = "Korisnik  $korisnikIme je promijenila/o vrstu programa!";
        $dnevnik = "INSERT INTO `dnevnik`(  `korisnik`, `datum`, `Opis`, `tip_akcije`)  values  ('$korisnik','$datum ',' $radnja ', 4)";
        $napravi = $db->updateDB($dnevnik);

    $db->zatvoriDB();
  }


    
} 

?>

<!DOCTYPE html>
<html>
	<head>
		<title> Ažuriraj vrstu programa </title>
        <meta charset="utf-8">
        <meta name="author" content="Matea">
        <meta name="keywords" content="novi_proizvod, upis_proizvoda">
        <meta name="description" content="Stranica je rađena 06.03.2017">

         <link href="css/osnova.css" rel="stylesheet" type="text/css">		

	</head>
	<body >
		<header>
    <figure>
      <figcaption class="naslov"> Ažuriranje vrste programa </figcaption>
      <img src="slike/novi_program.jpg" class="prvaSlika" alt="novi program" usemap="#mapa1"/>
      <map name="mapa1">
          <area href="index.html" alt="index" shape="rect" target="_blank" coords="0,0,200,200"/>
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

    <form id="nova_vrsta_programa" method = "POST" name="novaVrstaPrograma" action="azuriraj_vrstu_programa.php">
      <select id="moderator" name="odabranaVrsta" multiple="multiple" size="5">
            <option value="-1" > &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; == Odaberi kategoriju == 
            </option>
            <?php include_once("baza.class.php");
              $db = new Baza();
              $db->spojiDB();
              $upit = "SELECT *
                        FROM  vrsta_programa;";
              $odgovor = $db->selectDB($upit);               
             
              while (list($id, $naziv) = $odgovor->fetch_array()) 
                      {
                          $vrstaProgramcica = '<option value="'. $id .'" ';
                          if($id == $odabrani){
                             $vrstaProgramcica .= ' selected';
                          }
                          $vrstaProgramcica .= ' >'. $naziv . '</option>';
                          echo $vrstaProgramcica;
                      }
             $db->zatvoriDB();
            ?>     
      </select>
              <input type="submit" name="Azuriraj" value="Azuriraj"> 


    <div <?php  if(isset($sakrij)) echo "style= " . '"' .  $sakrij . '";' ?> >   
    <section id="noviProgram" > 


          <h2> Za stvaranje nove vrste programa popunite sljedeće stavke: </h2>
          <label for="naziv" id="lblNaziv"> Naziv vrste programa: </label> 
          <br>
          <input type="text" maxlength="30" name="naziv" id="naziv" 
          <?php if(isset($nazivVrste)) echo $nazivVrste;
           ?>
         > 
          <br>
          
         <select name='korisnici[]' id='korisnici' multiple='multiple' >
            <option value="-1" selected="selected" > &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; == Odaberi kategoriju == 
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
                         $ispisiModer = '<option value="'. $id .'" ';
                         foreach ($odabraniModerator as $selected) {
                            if($id == $selected){ $ispisiModer .= ' selected' ; }
                          }
                           $ispisiModer .= ' >' . $ime . " " . $prezime . '</option>';
                          echo $ispisiModer;
                      }
             $db->zatvoriDB();
            ?>     
        </select>
        <br>
        <br>
        <br>     
         
        <input type="submit" name="Upisi" value="Uvedi program"> 
        <input type="reset" value="Vraćanje na inicijalne postavke"> 
      </div>
    </form>
  </section>

  <?php 
    include 'footer.php';
   ?>

	</body>
</html>