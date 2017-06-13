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
$sakrij = "display:none";

if(isset($_POST['trening'])){
    $odabrani = $_POST['trening'];
  }

if(isset($_POST["Azuriraj"])){

  $sakrij = "display:inline-block";
    include_once("baza.class.php");
    $db = new Baza();
    $db->spojiDB();

    $sveOTreningu = "SELECT t.id, tp.program_id, p.naziv,  t.dan_u_tjednu, t.vrijeme_pocetka, t.mjesec, t.broj_polaznika
                    FROM  `trening_program` tp
                    JOIN trening t ON t.id = tp.`trening_id` join program p on p.id=tp.program_id where t.id= $odabrani ";
    $nadenoOTreningu = $db->selectDB($sveOTreningu);

    while (list($idTrening, $idProgram, $naziv, $danUTjednu, $vrijemePocetka, $mjesec, $broj_polaznika) = $nadenoOTreningu->fetch_array())
    {
      $NazivPrograma = " value='$naziv' ";
      $BrojPolaznika = " value='$broj_polaznika' ";
      $idTreninga = $idTrening;
      $idProgramaa = $idProgram;
      $Dan_u_tjednu = " value = '$danUTjednu' ";
      $Vrijeme = " value='$vrijemePocetka' ";
      $Mjesec =  $mjesec;
    }

    $db->zatvoriDB();

    $izbrisi = "delete from trening_proram where trening_id = $odabrani";
    $izvrsi = $db->selectDB($izbrisi);
    
}



if (isset($_POST["Uvedi"])) {
    include("baza.class.php");
    $db = new Baza();
    $db->spojiDB();
    $odabrani = $_POST['odabraniProgram'];
    echo $odabrani . "<br> ";
    $datumOdrzavanja = $_POST['datumOdrzavanja'];

    $vrijeme = strtotime($_POST['Vrijeme']);
    $vrijeme_pocetka = date('h:i:s', $vrijeme);
    echo $vrijeme_pocetka. "<br> ";

    $br_mogucih_mjesta = $_POST['kol'];
    echo $br_mogucih_mjesta . "<br> ";

    $moderator = $korisnik;

    $datum = strtotime($datumOdrzavanja);
    $danUTjednu = date('l', $datum);
    echo $danUTjednu. "<br> ";
    $mjesec = date('m', $datum);
    echo $mjesec. "<br> ";
    

    if( empty($datumOdrzavanja) || empty($br_mogucih_mjesta) )
    {
      $greska .= "Nisu unesena sva polja <br>";
    }
    else if($odabrani == -1){
      $greska .= "Molimo Vas da odaberete vrstu programa!";
    }         
        

  if($greska === "" ){ 
    echo "usa";
    $sakrij = "display: none";

    $upitic = "SELECT tp.program_id, t.dan_u_tjednu, t.vrijeme_pocetka, t.mjesec
FROM  `trening_program` tp
JOIN trening t ON t.id = tp.`trening_id`  where t.dan_u_tjednu = '$danUTjednu' and t.mjesec= '$mjesec' and t.vrijeme_pocetka= '$vrijeme_pocetka' and  tp.program_id = '$odabrani'";
    $odgovor = $db->selectDB($upitic);

   if($odgovor->num_rows == 0){
      $upitUpisi = "UPDATE `trening` SET  `broj_polaznika`='$br_mogucih_mjesta',  `dan_u_tjednu`= '$danUTjednu',  `vrijeme_pocetka ='$vrijeme_pocetka', , `mjesec`= '$mjesec'   WHERE  id = '$odabrani;";
      $prijenos = $db->updateDB($upitUpisi);
     
      $upitUpisi2 = "INSERT INTO `trening_program`(program_id, trening_id) VALUES ('$odabrani', (select id from trening where status_id = 3 and '$br_mogucih_mjesta' = broj_polaznika and '$danUTjednu' = dan_u_tjednu and mjesec='$mjesec') ) ;";
      $prijenos = $db->updateDB($upitUpisi2);

       //dnevnik
          $datum = date("Y-m-d H:i:s");
          $radnja = "Korisnik  $korisnikIme je dodao novi trening! '$upitUpisi' <br> '$upitUpisi2 ";
          $dnevnik = "INSERT INTO `dnevnik`(  `korisnik`, `datum`, `Opis`, `tip_akcije`)  values  ('$korisnik','$datum ',' $radnja ', 4)";
          $db->updateDB($dnevnik);
        }
        else{
          $greska = "U tom mjesecu već postoji trening u to vrijeme na taj dan! Molimo da izaberete drugi termin!";
        }

              /*

   $nadiProgrameVrijemeDan = "SELECT `vrijeme_pocetka`, `dan_u_tjednu`, mjesec FROM `trening` WHERE `program_id`=$odabrani;";
   $pronasao = $db->selectDB($nadiProgrameVrijemeDan);
                if($pronasao->num_rows == 0){

         while (list($vrijemeNADENI, $dan_u_tjednuNADENI, $mjesecNADENI) = $pronasao->fetch_array() ){
            $greska = "tu sammm";
              


            }
            else if ($dan_u_tjednuNADENI == $danUTjednu && $mjesecNADENI == $mjesec && $vrijemeNADENI == $vrijeme_pocetka){
                        $greska.= "Već je netko zauzeo ovaj program u tom mjesecu na u to vrijeme!";
            }
            else{
               //upis u bazu DOZVOLJEN
                $upitUpisi = "INSERT INTO `trening`(`status_id`, `program_id`,  `broj_polaznika`, `dan_u_tjednu`, `vrijeme_pocetka`) VALUES (3, $odabrani, $br_mogucih_mjesta,  $danUTjednu, $vrijeme_pocetka) ;";
                $prijenos = $db->updateDB($upitUpisi);
                $greska .= "Dodali ste novi TRENING! Želimo Vam puno uspjeha! ";
            }
          }
          */       
  }
  $db->zatvoriDB();
}
   

?>

<!DOCTYPE html>
<html>
	<head>
		<title> Ažuriraj trening  </title>
        <meta charset="utf-8">
        <meta name="author" content="Matea">
        <meta name="keywords" content="novi_proizvod, upis_proizvoda">
        <meta name="description" content="Stranica je rađena 06.03.2017">

         <link href="css/osnova.css" rel="stylesheet" type="text/css">		

	</head>
	<body >
		<header>
    <figure>
      <figcaption class="naslov"> Ažuriranje treninga </figcaption>
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

    <form id="novi_program" method = "POST" name="noviProgram" action="azurirajTrening.php">
      <select id="trening" name="trening" multiple="multiple" size="5">
              <option value="-1" > &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; == Dan, Mjesec, Vrijeme == 
              </option>
              <?php
                include_once("baza.class.php");
                $db = new Baza();
                $db->spojiDB();
                $upit = "SELECT id, dan_u_tjednu, vrijeme_pocetka, mjesec
                        FROM trening t join trening_program tp on tp.trening_id = t.id;";
                $odgovor = $db->selectDB($upit);               
               
                while (list($id, $dan, $vrijeme, $mjesec) = $odgovor->fetch_array()) 
                        {
                            $odabraniTrening = '<option value="'. $id .'"';
                            if($id == $odabrani) {
                              $odabraniTrening.= ' selected' ;
                            }

                              $odabraniTrening .= '>'.$dan . " " . $mjesec . " " . $vrijeme .'</option>';
                            echo $odabraniTrening;
                        }
               $db->zatvoriDB();
              ?>     
      </select>
        <input type="submit" name="Azuriraj" value="Azuriraj"> 
  


  <div <?php  if(isset($sakrij)) echo "style= " . '"' .  $sakrij . '";' ?> >   
	  <section id="noviTrening" > 
          <h2> O treningu imamo ove podatke: </h2>

        <select id="kategorija" name="odabraniProgram" multiple="multiple" size="5">
            <option value="-1" > &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; == Odaberi kategoriju == 
            </option>
            <?php
              include_once("baza.class.php");
              $db = new Baza();
              $db->spojiDB();
              $upit = "SELECT p.naziv, p.id
FROM  `vrstaProgramaModerator` vpm
JOIN vrsta_programa vp ON vp.id = vpm.`vrsta_programa` 
JOIN program p ON p.vrsta_programa_id = vp.id
WHERE vpm.moderator = $korisnik;";
              $odgovor = $db->selectDB($upit);               
             
              while (list($naziv, $id) = $odgovor->fetch_array()) 
                      {
                          $programcic =  '<option value="' . $id .'" ';
                          if($id == $idProgramaa) {
                            $programcic .= ' selected ' ; }
                          $programcic .= '>'.$naziv.'</option>';
                          echo $programcic;
                      }
             $db->zatvoriDB();

            ?>     
        </select>
      <br>   
        <label for="Vrijeme"  id="lblVrijeme"> Vrijeme početka </label>
        <br>
          <input type="time" name="Vrijeme" id="Vrijeme" id="lblTime" 
            <?php
            if(isset($Vrijeme)){
              echo $Vrijeme;
            }
            ?>
          >  
          <br>    
          
          <label for="kol" id="lblKolicina"> 
            Mogući broj polaznika: 
          </label>
          <input type="number" name="kol" min="1" id="kol"
            <?php
              if(isset($BrojPolaznika)){
                echo $BrojPolaznika;
              }
            ?>

          >
        <br>
        
        <br>  

        <label for="datumOdrzavanja"> Mjesec održavanja treninga: </label>
        <input type="date" name="datumOdrzavanja" id="datum" placeholder="11.11.2012.">   
       
      
        <input type="submit" name="Uvedi" value="Uvedi trening"> 
        <input type="reset" value="Vraćanje na inicijalne postavke"> 
    </section>
  </div>
</form>


 <?php 
      include 'footer.php';
     ?>

      <script type="text/javascript" src="js/matbodulu.js">         
      </script>
	</body>
</html>