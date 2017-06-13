<?php
session_start();

header('Content-Type: text/html; charset=utf-8');


if (!isset($_SESSION['korisnickoIme'])) 
{
    $ispisi .= "Morate biti prijavljeni";
    header("Location:prijava.php");
    exit();
} 
else if($_SESSION['tip_korisnika'] != 1)
{
   header("Location:index.php");
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

  $odabranTrening = $_POST['odabranTrening'];
  $nadoknada = "display:inline-block";

  $ispisiZapisane = " ";

  $zabiljezeni = "SELECT ed.`opis_napretka` , k.korisnik_id, k.ime, k.prezime
                  FROM  `evidencija_dolazaka` ed
                  JOIN korisnik k ON k.korisnik_id = ed.`korisnik_sportas` 
                  WHERE ed.trening_id = '$odabranTrening' ";
  $nadiZabiljezene = $db->selectDB($zabiljezeni);

  if($nadiZabiljezene->num_rows > 0)
  {
    $ispisiZapisane .=  "<h4> Već ste zapisali informacije o </h4> <br>";
    $ispisiZapisane .= "<table class='tablica'> <th> Naziv </th> <th> Opis </th>";
    while (list($opis, $id, $ime, $prezime) = $nadiZabiljezene->fetch_array())
    {
            $ispisiZapisane .= "<tr><td>" . $ime . " "  . $prezime . "</td>
                            <td>" . $opis . "
                            </td> 
                        </tr>"; 
    }
    $ispisiZapisane .= "</table>";    
  }

}

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
  
?>

<!DOCTYPE html>
<html>
	<head>
		<title> Evidencija dolazaka </title>
        <meta charset="utf-8">
        <meta name="author" content="Matea">
        <meta name="keywords" content="novi_proizvod, upis_proizvoda">
        <meta name="description" content="Stranica je rađena 06.03.2017">

         <link href="css/osnova.css" rel="stylesheet" type="text/css">		

	</head>
	<body >
		<header>
    <figure>
      <figcaption class="naslov"> Evidencija dolazaka </figcaption>
      <img src="slike/skok.jpg" class="prvaSlika" alt="novi program" usemap="#mapa1"/>
      <map name="mapa1">
          <area href="index.php" alt="index" shape="rect" target="_blank" coords="0,0,200,200"/>
          <area href="#dolasci" alt="noviProizvod" shape="rect" target="_parent" coords="200,0,400,200"/>
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
<div>
  <?php if(isset($ispisiZapisane))         echo $ispisiZapisane;
 ?>
</div>
     
	  <section id="dolasci" > 
      <form id="dodjela_uloga" method = "POST" name="dodjela_uloga" action="evidencijaDolazaka.php"> 
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
                            JOIN vrstaProgramaModerator vpm ON p.vrsta_programa_id = vpm.vrsta_programa
                            WHERE vpm.moderator = $korisnik and t.status_id = 1;";
                                                      
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

        
<div <?php  if(isset($sakrij)) echo "style= " . '"' .  $sakrij . '";' ?> >
          <input type="submit" name="zapisi" value="Spremi"> 
  </div>

  <div <?php  if(isset($nadoknada)) echo "style= " . '"' .  $nadoknada . '";' ?> >

   <label for="odabranaOsoba"> Odaberite osobu/ osobe: </label> 
          <br>     

          <select name='korisnici[]' id='korisnici' multiple='multiple' >
              <?php
                include_once("baza.class.php");
                $db = new Baza();
                $db->spojiDB();
                $upit = "SELECT k.korisnik_id, k.ime, k.prezime
                          FROM  korisnik k join korisnik_program kp on kp.korisnik_id = k.korisnik_id join trening_program tp on tp.program_id = kp.program_id where tp.trening_id = '$odabranTrening';";
                $odgovor = $db->selectDB($upit);               
               
                while (list($id, $ime, $prezime) = $odgovor->fetch_array()) 
                        {
                            $nadeni = '<option value="'. $id . ' ">' . $ime . " " . $prezime . '</option>';
                           echo $nadeni;
                        }
               $db->zatvoriDB();
              ?>     
          </select>

          <br>
          <label for="opis" > Opis treninga: </label>
          <input type="text" name="opis"> 
        
      
        <input type="submit" name="Uvedi" value="Uvedi trening"> 
        <input type="reset" value="Vraćanje na inicijalne postavke"> 
      </form>
    </section>
    </section>
  </div>


    <?php 
      include 'footer.php';
     ?>

	</body>
</html>