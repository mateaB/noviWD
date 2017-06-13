<?php
session_start();

header('Content-Type: text/html; charset=utf-8');

$greska = "";

if (!isset($_SESSION['korisnickoIme'])) {
    $ispisi .= "Morate biti prijavljeni";
    header("Location:prijava.php");
    exit();
} 
else if($_SESSION['tip_korisnika'] != 1 ){
   header("Location:index.php");
   exit();
}
else{
  $korisnik = $_SESSION['korisnik_id'];
  $korisnikIme = $_SESSION['korisnickoIme'];
  $tipKorisnika = $_SESSION['tip_korisnika'];
}


include("baza.class.php");
$db = new Baza();
$db->spojiDB();

$updateALL = "UPDATE `kupon_programi` SET `aktivan`=0 WHERE `do`> now()";
$provedi = $db->updateDB($updateALL);

if (isset($_POST["Uvedi"])) {
   
    $odabrani = $_POST['odabraniKupon'];
    $odabraniProgram = $_POST['odabraniProgram'];
    $datumOd = $_POST['datumOd'];
    $datumDo = $_POST['datumDo'];
    $Bodovi = $_POST['bodovi'];

   
    if( empty($datumOd) || empty($datumDo) || empty($Bodovi))
    {
      $greska .= "Nisu unesena sva polja <br>";
    }

    if($odabrani == -1 or $odabraniProgram == -1)
    {
      $greska .= "Provjerite jeste li odabrali I kupon I program!";
    }        
    
    if($datumOd > $datumDo){
      $greska .= "Odabrali ste da kupon završava prije nego što počinje biti aktivan! Molimo da ispravite tu gresku!";
    } 
        

    if($greska === "")
    { 
      echo "usa";
      $sakrij = "display: none";

      $upitUpisi = "INSERT INTO `kupon_programi`(`kuponi_id`, `program_id`, `od`, `do`, `moderator`, `potrebno_bodova`, `aktivan`) VALUES ($odabrani, $odabraniProgram, '$datumOd', '$datumDo', $korisnik, $Bodovi, 1);";
      $prijenos = $db->updateDB($upitUpisi);
    
       //dnevnik
      $datum = date("Y-m-d H:i:s");
      $radnja = "Korisnik  $korisnikIme je dodao dodatne informacije o kuponu!";
      $dnevnik = "INSERT INTO `dnevnik`(  `korisnik`, `datum`, `Opis`, `tip_akcije`)  values  ('$korisnik','$datum ',' $radnja ', 4)";
      $db->updateDB($dnevnik);        
    }
  $db->zatvoriDB();
}



?>

<!DOCTYPE html>
<html>
	<head>
		<title> Odaberi i opiši kupon </title>
        <meta charset="utf-8">
        <meta name="author" content="Matea">
        <meta name="keywords" content="novi_kupon, opis_kupona">
        <meta name="description" content="Stranica je rađena 06.03.2017">

         <link href="css/osnova.css" rel="stylesheet" type="text/css">		

	</head>
	<body >
		<header>
    <figure>
      <figcaption class="naslov"> Dodaj informacije o kuponu </figcaption>
      <img src="slike/novi_program.jpg" class="prvaSlika" alt="novi program" usemap="#mapa1"/>
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
	  <section id="noviKuponM" > 
      <form id="novi_kupon" method = "POST" name="noviTrening" action="novi_kupon_MODERATOR.php">
          <h2> Za stvaranje dodatnih informacija o KUPONu popunite sljedeće stavke: </h2>

        <select id="kategorija" name="odabraniProgram" multiple="multiple" size="5">
            <option value="-1" selected="selected" > &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; == Odaberi kategoriju == 
            </option>
            <?php
              include_once("baza.class.php");
              $db = new Baza();
              $db->spojiDB();
              $upit = "SELECT p.`id` , p.`naziv` , vp.`moderator` 
                      FROM  `vrstaProgramaModerator` vp
                      JOIN program p ON p.vrsta_programa_id = vp.`vrsta_programa`
                      where vp.moderator = '$korisnik'";
              $odgovor = $db->selectDB($upit);               
             
              while (list($id, $naziv) = $odgovor->fetch_array()) 
                      {
                          echo '<option value="'. $id .'">'.$naziv.'</option>';
                      }
             $db->zatvoriDB();

            ?>     
        </select>
        <br>
        <br>

        <select id="kategorija" name="odabraniKupon" multiple="multiple" size="5">
            <option value="-1" selected="selected" > &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; == Odaberi kategoriju == 
            </option>
            <?php
              include_once("baza.class.php");
              $db = new Baza();
              $db->spojiDB();
              $upit = "SELECT `id`, `naziv`, `opis`, `slika` FROM `kuponi`;";
              $odgovor = $db->selectDB($upit);               
             
              while (list($id, $naziv) = $odgovor->fetch_array()) 
                      {
                          echo '<option value="'. $id .'">'.$naziv.'</option>';
                      }
             $db->zatvoriDB();
            ?>     
        </select>
     
          <label for="bodovi" id="lblKolicina"> 
            Broj potrbnih bodova za kupon: 
          </label>
          <input type="number" name="bodovi" min="1" id="bodovi">
        <br>     
         
        <br>  

        <label for="datumOd"> Kupon je aktivan od: </label>
        <input type="date" name="datumOd" id="datum" placeholder="11.11.2012.">   

        <label for="datumDo"> Kupon je aktivan do: </label>
        <input type="date" name="datumDo" id="datum" placeholder="11.11.2012.">   
        
        
      
        <input type="submit" name="Uvedi" value="Aktiviraj kupon"> 
        <input type="reset" value="Vraćanje na inicijalne postavke"> 
      </form>
    </section>
  </div>


  <?php 
      include 'footer.php';
     ?>

	</body>
</html>