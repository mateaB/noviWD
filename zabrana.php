<?php
session_start();

header('Content-Type: text/html; charset=utf-8');

$greska = "";
$sakrij = "display:inline-block";
$prikaziSADA = "display:none";

if (!isset($_SESSION['korisnickoIme'])) {
    $greska.= "Morate biti prijavljeni";
    header("Location:prijava.php");
    exit();
}
else if($_SESSION['tip_korisnika'] != 1){
     header("Location:index.php");
    exit();
}
else{
    $korisnik = $_SESSION['korisnik_id'];
    $korisnikIme = $_SESSION['korisnickoIme'];
    $tipKorisnika = $_SESSION['tip_korisnika'];
}

if(isset($_POST['odabranaOsoba'])){
   $odabrana =$_POST['odabranaOsoba'];
   echo $odabrana;
}


if(isset($_POST["Unesi"]))
{
  include("baza.class.php");
  $db = new Baza();
  $db->spojiDB();
  $greska = " ";

  if(isset($_POST['odabranProgra'])){
    $program = $_POST['odabranProgra'];
  }

   $datum = date("Y-m-d H:i:s");
   if($odabrana > 0)
   {
    $upitUpisi = "INSERT INTO `korisnik_program`(`korisnik_id`, `program_id`, `datum`, `zabrana`) VALUES ($odabrana, $program, $datum, 1);";
    $prijenos = $db->selectDB($upitUpisi);

   }
    //dnevnik
    $radnja = "Korisniku $odabrana je zabraljen pristup programu!";
    $datum = date("Y-m-d H:i:s");
    $dnevnik = "INSERT INTO `dnevnik`(  `korisnik`, `datum`, `Opis`, `tip_akcije`)  values  ('$korisnik','$datum ',' $radnja ', 4)";
    $db->selectDB($dnevnik);
}


if (isset($_POST["zapisi"])) {
  $prikaziSADA = "display:inline-block";

    include_once("baza.class.php");
    $sakrij = "display: none";

    $db = new Baza();
    $db->spojiDB();

    if(isset($_POST['odabranaOsoba']))
    {
      $odabrana = $_POST['odabranaOsoba'];
      $nadiOsobu = "SELECT  `korisnik_id` ,  `ime` ,  `prezime` ,  `korisnicko_ime` ,  `email` ,  `tip_korisnika` 
                  FROM  `korisnik` 
                  WHERE korisnik_id = $odabrana";
      $odgovor = $db->selectDB($nadiOsobu);


      while (list( $id, $ime, $prezime, $korisnicko_ime, $email,
       $tip_korisnika) = $odgovor->fetch_array())
      {
          $Ime = " value='$ime' ";
          $Prezime = " value='$prezime' "; 
          $Korisnicko = " value='$korisnicko_ime' ";
          $Email = " value='$email' "; 
          $odabranaUloga = $tip_korisnika; 
      }



    }

    $db->zatvoriDB();

  }
   
?>

<!DOCTYPE html>
<html>
	<head>
		<title> Zabrana programa korisniku </title>
        <meta charset="utf-8">
        <meta name="author" content="Matea">
        <meta name="keywords" content="novi_proizvod, upis_proizvoda">
        <meta name="description" content="Stranica je rađena 06.03.2017">

    <link href="css/osnova.css" rel="stylesheet" type="text/css">		


	</head>
	<body >
		<header>
    <figure>
      <figcaption class="naslov"> Zabrana programa korisniku </figcaption>
      <img src="slike/skok.jpg" class="prvaSlika" alt="novi program" usemap="#mapa1"/>
      <map name="mapa1">
          <area href="index.php" alt="index" shape="rect" target="_blank" coords="0,0,200,200"/>
          <area href="#uloge" alt="uloge" shape="rect" target="_parent" coords="200,0,400,200"/>
      </map>
    </figure>
    </header>

 <?php
      include 'nav.php';
   ?>

    
    <?php
        if (isset($poruka)) {
          $sakrij = "display:none";
          echo $poruka;
        }
    ?>

 
 <div <?php  if(isset($sakrij)) echo "style= " . '"' .  $sakrij . '";' ?> >  
  <section id="uloge" > 
    <form id="dodjela_uloga" method = "POST" name="dodjela_uloga" action="zabrana.php"> 
        <label for="odabranaOsoba"> Odaberite osobu kojoj želite dodijeliti ulogu: 
        </label> 
        <br>      
        <select id="osoba" name="odabranaOsoba">
            <?php
              include_once("baza.class.php");
              $db = new Baza();
              $db->spojiDB();
              $upit = "SELECT  `korisnik_id` ,  `ime` ,  `prezime` ,  `korisnicko_ime` ,  `email` ,  `lozinka` ,  `tip_korisnika` ,  `kriptirana` ,  `aktivacijski` ,  `aktiviran` ,  `token` ,  `brKoraka` , `broj_pokusaja` ,  `zakljucan` ,  `zadnjaPrijava` ,  `blokiran` 
                      FROM  `korisnik` 
                      WHERE  `zakljucan` !=1
                      AND  `blokiran` !=1";
              $odgovor = $db->selectDB($upit);               
               $ispisiOsobu .= '<option value="0"> </option>';
              while (list($korisnik_id, $ime, $prezime) = $odgovor->fetch_array()) 
                      {
                          $ispisiOsobu = '<option value="'. $korisnik_id .'" ';
                          if(isset($odabrana)){
                           if($korisnik_id == $odabrana){ $ispisiOsobu .= ' selected="selected"' ; }
                          }
                          $ispisiOsobu .= ' >'. $ime . " " . $prezime . '</option>';
                          echo $ispisiOsobu;
                      }
             $db->zatvoriDB();
            ?>     
        </select>

        <br>

         <input type="submit" name="zapisi" value="Odaberi"> 

   </div>

    <?php
        if(isset($greska))
        {
        echo $greska;
        }
        
        if(isset($potrebnaProvjera))
        {
          echo $potrebnaProvjera;
        }
    ?> 


<div <?php if(isset($prikaziSADA)) echo "style =  $prikaziSADA"; ?> >
      <section class="dodjela_uloga">

          <div class="popisGresaka" style="color:black;" name="popisGresaka"> </div>
          

                <label for="ime_reg"> Ime: </label>
                <input type="text" name = "ime_reg" id="ime_reg" 
                <?php 
                if(isset($Ime)) echo $Ime;
                 ?>
                 >    
               <br>
                <label for="prezime_reg"> Prezime: </label>
                <input type="text" name="prezime_reg" id="prezime_reg"
                 <?php 
                if(isset($Prezime)) echo $Prezime;
                 ?>

                >  
               
                <br>
                <!-- plcoholeder u textarea - super za dodati -->

                <label for="kor_ime_reg" > Korisničko ime: </label>
                <input type="text" name="kor_ime_reg" id="kor_ime_reg" 
                 <?php 
                if(isset($Korisnicko)) echo $Korisnicko;
                 ?>

                >

                <?php if(isset($greskaKorIme)) echo $greskaKorIme; ?>
                <!-- plcoholeder u textarea - super za dodati -->
                 <br>     
                 <label for="email_reg"> E-mail: </label>
                <input type="email" name="email_reg" id="email_reg"
                 <?php 
                if(isset($Email)) echo $Email;
                 ?>
                  >
                <?php if(isset($greskaMail)) echo $greskaMail; ?>
                 <br>     
                 
          <label for="uloge"> Odaberite tip korisnika: </label>
          <select id="uloge" name="odabranProgra" >
            <?php
                include_once("baza.class.php");
                $db = new Baza();
                $db->spojiDB();
                $upit = "SELECT p.id, p.naziv
                          FROM  korisnik_program kp join program p on p.id = kp.program_id where kp.korisnik_id = $odabrana; ";
                        
                $odgovor = $db->selectDB($upit);               
               
                while (list($tip_korisnika_id, $naziv) = $odgovor->fetch_array()) 
                        {
                             $vrstaProgramcica = '<option value="'.    
                             $tip_korisnika_id .'" ';
                             if(isset($odabranaUloga)){
                               if($tip_korisnika_id == $odabranaUloga)
                                { $vrstaProgramcica .= ' selected="selected"' ; 
                                }
                             }
                          $vrstaProgramcica .= ' >'. $naziv . '</option>';
                          echo $vrstaProgramcica;
                        }
                $db->zatvoriDB();
            ?>
          </select>
          <br>

          <input  type="submit" value="Spremi promijene" name="Unesi" id='predaj'> 
          <input type="reset" name="reset" id="reset" value="Izbrisi unesene podatke!">
                  
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