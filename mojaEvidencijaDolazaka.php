<?php
session_start();

header('Content-Type: text/html; charset=utf-8');


if (!isset($_SESSION['korisnickoIme'])) 
{
    $ispisi .= "Morate biti prijavljeni";
    header("Location:prijava.php");
    exit();
} 
else
{
  $korisnik = $_SESSION['korisnik_id'];
  $korisnikIme = $_SESSION['korisnickoIme'];
  $tipKorisnika = $_SESSION['tip_korisnika'];
  $greska = "";
}

include("baza.class.php");
$db = new Baza();
$db->spojiDB();
$nadoknada = "display:none";

if (isset($_POST["zapisi"])) 
{
  $sakrij = " display:none";

  if(isset($_POST['odabranTrening'])){
      $odabranTrening = $_POST['odabranTrening'];


    $nadoknada = "display:inline-block";

    $ispisiZapisane = " ";

    $zabiljezeni = "SELECT ed.`opis_napretka` , k.korisnik_id, k.ime, k.prezime
                    FROM  `evidencija_dolazaka` ed
                    JOIN korisnik k ON k.korisnik_id = ed.`korisnik_sportas` 
                    WHERE ed.trening_id = '$odabranTrening' and k.korisnik_id = '$korisnik' 
                    ";
    $nadiZabiljezene = $db->selectDB($zabiljezeni);

    if($nadiZabiljezene->num_rows > 0)
    {
      while (list($opis, $id, $ime, $prezime) = $nadiZabiljezene->fetch_array())
      {
            $ispisiZapisane .= "<h3>" . $ime . " "  . $prezime . "</h3> <br>";

              $ispisiZapisane .= "
                              <p>" . $opis . "
                              </p> 
                          <br>"; 
      }
    }
    else{
      $ispisiZapisane = "Nemamo zapisa o ovom treningu!";
    }
  }
  else{
    $ispisiZapisane = "Nemamo zapisa o Vašim treninzima. Posjetite nas kasnije!";
  }

}


if (isset($_POST["svi"])) 
{
  include_once("baza.class.php");
  $db = new Baza();
  $db->spojiDB();
  $ispisi = "";
    

  $nadiPrograme = "SELECT * 
                  FROM program p join korisnik_program kp on kp.program_id = p.id where kp.korisnik_id = '$korisnik'";
  $pronadeniProgrami = $db->selectDB($nadiPrograme);

  $ispisi .=  "<table class='tablica'>
                   <th> Mjesec</th> 
                   <th> Dan u tjednu i vrijeme početka </th>
                   <th> Opis  </th>
                   <th> Naziv programa </th>";

 
      while (list($idProgramcic, $nazivProgramcica, $opis, $vrsta,  $brojdozvoljenih, $korisnikID, $programID) = $pronadeniProgrami->fetch_array()){
          $ispisi .= "<tr> <td colspan='7' style='width:100%'>  $nazivProgramcica </td> </tr>";
              $upit = "SELECT t.mjesec, t.dan_u_tjednu, t.vrijeme_pocetka, ed.opis_napretka, p.naziv
                        FROM  `evidencija_dolazaka` ed
                        JOIN trening t ON t.id = ed.`trening_id` 
                        JOIN trening_program tp ON tp.trening_id = t.id
                        JOIN korisnik k ON k.korisnik_id = ed.korisnik_sportas
                        JOIN program p ON p.id = tp.program_id
                        WHERE k.korisnik_id = '$korisnik' and tp.program_id = '$idProgramcic'"; 
                                                  
                        $odgovor = $db->selectDB($upit);  
                          
                          if($odgovor->num_rows != 0){
                          while (list($mjesec, $dan, $vrijeme, $opis, $nazivPrograma) = $odgovor->fetch_array()){                       
                                      $ispisi .= "<tr> <td> " . $mjesec  ."
                                                      <td>" . $dan . " " . $vrijeme . "
                                                      </td>                             
                                                      <td>" . $opis . "
                                                      </td> 
                                                      <td>" . $nazivPrograma . "
                                                      </td> 
                                                  </tr>"; 
                                      } 
                                  }else{
                                      $ispisi .= "<tr> <td colspan='7' style='width:100%'>  Trenutno nemamo informaciju o treningu! </td> </tr>";
                                  }
          }            

    
    $radnja = "Korisnik $korisnikIme je pregledao evidenciju dolazaka!";
    $dnevnik = "INSERT INTO `dnevnik`(  `korisnik`, `datum`, `Opis`, `tip_akcije`)  values  ('$korisnik',now(),' $radnja ', 4)";
    $provedi = $db->updateDB($dnevnik);

    $ispisi .= "</table>";   
    $db->zatvoriDB();   
}
/*
if (isset($_POST["Uvedi"])) 
{
   
    $odabranTrening = $_POST['odabranTrening'];
    $opis = $_POST['opis'];


    foreach ($_POST['korisnici'] as $selectedOption)
    {
          echo "ti";
          $izvrsi ="INSERT INTO `evidencija_dolazaka`(`opis_napretka`, `zabiljezio`, `korisnik_sportas`, `trening_id`) VALUES ( " . '"' . $opis . '"' . " ,  $korisnik  ,  $selectedOption ,  $odabranTrening );";
          $spremi = $db->updateDB($izvrsi);
          echo $izvrsi;
    }


   //dnevnik
      $datum = date("Y-m-d H:i:s");
      $radnja = "Korisnik  $korisnik je zabilježio dolazak i opisao kotisnikov napredak trening!";
      $dnevnik = "INSERT INTO `dnevnik`(`korisnik`,`datum`, `Opis`) VALUES ('" . $korisnik . "','" . $datum . "','" . $radnja . "')";
      $db->selectDB($dnevnik);


  $db->zatvoriDB();
 
}   
  */
?>

<!DOCTYPE html>
<html>
	<head>
		<title> Moja evidencija dolazaka </title>
        <meta charset="utf-8">
        <meta name="author" content="Matea">
        <meta name="keywords" content="novi_proizvod, upis_proizvoda">
        <meta name="description" content="Stranica je rađena 06.03.2017">

         <link href="css/osnova.css" rel="stylesheet" type="text/css">		

	</head>
	<body >
		<header>
    <figure>
      <figcaption class="naslov"> Moja evidencija dolazaka </figcaption>
      <img src="slike/skok.jpg" class="prvaSlika" alt="novi program" usemap="#mapa1"/>
      <map name="mapa1">
          <area href="index.php" alt="index" shape="rect" target="_blank" coords="0,0,200,200"/>
          <area href="#uloge" alt="evidencija" shape="rect" target="_parent" coords="200,0,400,200"/>
      </map>
    </figure>
    </header>

 <?php
      include 'nav.php';
   ?>


    

     
	  <section id="uloge" > 
      <form id="dodjela_uloga" method = "POST" name="dodjela_uloga" action="mojaEvidencijaDolazaka.php"> 
          <label for="uloge"> Odaberite trening: </label>
          <select id="uloge" name="odabranTrening" >
            <?php
                include_once("baza.class.php");
                    $odabraniProgram = $_POST['odabraniProgram'];
                
                    $db = new Baza();
                    $db->spojiDB();
                    $upit = "SELECT t.`id` , t.`dan_u_tjednu` , t.`vrijeme_pocetka` , t.`mjesec` , p.id, p.naziv
                              FROM  `trening` t
                              JOIN trening_program tp ON tp.trening_id = t.id
                              JOIN program p ON tp.program_id = p.id
                              JOIN korisnik_program kp ON kp.program_id = tp.program_id
                              WHERE kp.korisnik_id = $korisnik;";
                                                      
                    $odgovor = $db->selectDB($upit);               
                   
                    while (list($idTreninga, $dan, $vrijeme, $mjesec, $idprograma, $nazivPrograma) = $odgovor->fetch_array()) 
                            {
                                $odabir =  '<option value="'. $idTreninga . '" ';

                                if(isset($odabranTrening))
                                {
                                  if($idTreninga == $odabranTrening)
                                $odabir .= ' selected ';
                                }
                                
                              $odabir .= ' >' . $nazivPrograma . " ". $dan . " " . $vrijeme . " " . $mjesec .'</option>';
                              echo $odabir;
                            }
                    $db->zatvoriDB();   
            ?>
          </select>

        
  
          <input type="submit" name="zapisi" value="Prikazi"> 
          <input type="submit" name="svi" value="Svi"> 

  
    </form>
   </section>

    <?php
        if (isset($poruka)) {
          $sakrij = "display:none";
          echo $poruka;
        }
    ?>

    <div>
      <?php 
        if(isset($ispisiZapisane))        
          echo $ispisiZapisane;
        if(isset($ispisi))
          echo $ispisi;

     ?>
    </div>

  <?php 
    include 'footer.php';
  ?>

	</body>
</html>