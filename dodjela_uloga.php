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
else if($_SESSION['tip_korisnika'] != 2){
     header("Location:indeks.php");
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
$odabranaUloga = $_POST['odabranaUloga'];
$greska = " ";
$email = $_POST['email_reg'];
$ime = $_POST['ime_reg'];
$prezime = $_POST['prezime_reg'];


if(!empty($_POST["kor_ime_reg"])){
    $korisnicko = $_POST["kor_ime_reg"];
}

$greskaMail = " ";
$greskaKorIme = " ";
$potrebnaProvjera = "";


     if($odabrana > 0)
     {             
     echo "bčaaa";   
         //evidencija bodova 
        $upit2 = "INSERT INTO `evidencija_bodova`(`skupljeno`, `potorseno`, `datum_izmjene`, `korisnik_korisnik_id`) VALUES (0, 0, NOW(), $odabrana);";
        $prijenos2 = $db->updateDB($upit2);
       
        $upitUpisi = "UPDATE `korisnik` SET tip_korisnika = '$odabranaUloga', ime = '$ime', prezime = '$prezime', email = '$email', korisnicko_ime = '$korisnicko'  where korisnik_id = '$odabrana';";
        $prijenos = $db->selectDB($upitUpisi);

        $radnja = "Korisniku $odabrana je dodjeljen tip $odabranaUloga!";
     }
    if($odabrana == 0)
     {
      if(empty($ime)  || empty($prezime) || empty($korisnicko) || empty($lozinka) || empty($potvrdaLozinke))
         {
             $greska .= "Nisu unesena sva polja <br>";
         }
         else{
            
             //email
              if(preg_match( "/^(\w{1,}){1,}(\.{0,})(\-{0,})\w{0,}@(\w{2,}\.){1,2}\w{2,}$/", $email))
              {
                  $greskaMail = " ";
              }
              else
              {
                  $greska .="Mail treba biti u obliku 'nesto@nesto.nesto'<br>";
              }
              
              
           
              $upit = " SELECT * FROM `korisnik` WHERE `korisnicko_ime`='$korisnicko' or `email`='$email'";
              $odgovor = $db->selectDB($upit);
              $row = mysqli_fetch_array($odgovor);


                
              if(!empty($row["ime"]) && !empty($row["prezime"]) && !empty($row["zakljucan"]) && !empty($row["aktiviran"]))
              {
                  if($row["zakljucan"] == 3)
                  {
                      $greska .= "Račun s tim korisničkim imenom je zaključan! <br>";
                  }

                  if($row["ime"] == $ime && $row["prezime"] == $prezime)
                  {
                      $potrebnaProvjera .= "U bazi već postoji osoba s tim imenom! <br> ";       
                  }

                  if ($row["email"] == $email) 
                  {
                      $greska .= "Unesite drugi mail. Takav zapis vec postoji u bazi <br>";
                      $greskaMail = "!";
                  }

                  if($row["aktiviran"] == 1)
                  {
                      $greska = "Ovaj racun je već aktiviran <br>";
                  }

                  $db->zatvoriDB();
              }

              if($greska === " ")
              {    
                $upit2 = "INSERT INTO `korisnik`"
                        . "( `ime`, `prezime`, `email`, `korisnicko_ime`, `lozinka`, `kriptirana`,"
                        . " `brKoraka`, `aktivacijski`,`tip_korisnika`, aktiviran) "
                        . "VALUES ('$ime','$prezime','$email',"
                        . "'$korisnicko','$lozinka','$kriptirana', 1,'$kod','$odabranaUloga', 1)";
                $prijenos = $db->updateDB($upit2);
                $radnja = "Admin je dodao osobu '$korisnicko' i dodjelio joj tip '$odabranaUloga";
              }
          }
      }

      //dnevnik
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
		<title> Dodjeli uloge korisnicima </title>
        <meta charset="utf-8">
        <meta name="author" content="Matea">
        <meta name="keywords" content="novi_proizvod, upis_proizvoda">
        <meta name="description" content="Stranica je rađena 06.03.2017">

    <link href="css/osnova.css" rel="stylesheet" type="text/css">		


	</head>
	<body >
		<header>
    <figure>
      <figcaption class="naslov"> Dodjeli uloge korisnicima </figcaption>
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
    <form id="dodjela_uloga" method = "POST" name="dodjela_uloga" action="dodjela_uloga.php"> 
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
          <select id="uloge" name="odabranaUloga" >
            <?php
                include_once("baza.class.php");
                $db = new Baza();
                $db->spojiDB();
                $upit = "SELECT * 
                          FROM  `tip_korisnika`; ";
                        
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