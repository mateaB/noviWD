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
      $upitUpisi = "INSERT INTO `trening`(`status_id`, `broj_polaznika`, `dan_u_tjednu`, `vrijeme_pocetka`, `mjesec`) VALUES (3,  '$br_mogucih_mjesta',  '$danUTjednu', '$vrijeme_pocetka', '$mjesec');";
      $prijenos = $db->updateDB($upitUpisi);
     
      $upitUpisi2 = "INSERT INTO `trening_program`(program_id, trening_id) VALUES ('$odabrani', (select id from trening where status_id = 3 and '$br_mogucih_mjesta' = broj_polaznika and '$danUTjednu' = dan_u_tjednu and mjesec='$mjesec' and vrijeme_pocetka = '$vrijeme_pocetka') ) ;";
      $prijenos = $db->updateDB($upitUpisi2);

       //dnevnik
          $datum = date("Y-m-d H:i:s");
          $radnja = "Korisnik  $korisnikIme je dodao novi trening!";
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
		<title> Novi trening </title>
        <meta charset="utf-8">
        <meta name="author" content="Matea">
        <meta name="keywords" content="novi_proizvod, upis_proizvoda">
        <meta name="description" content="Stranica je rađena 06.03.2017">

         <link href="css/osnova.css" rel="stylesheet" type="text/css">		

	</head>
	<body >
		<header>
    <figure>
      <figcaption class="naslov"> Novi trening </figcaption>
      <img src="slike/novi_program.jpg" class="prvaSlika" alt="novi program" usemap="#mapa1"/>
      <map name="mapa1">
          <area href="index.html" alt="index" shape="rect" target="_blank" coords="0,0,200,200"/>
          <area href="#noviProizvod" alt="noviProizvod" shape="rect" target="_parent" coords="200,0,400,200"/>
      </map>
    </figure>
    </header>

   
<nav>
      <ul>
          <li>
              <a href="index.php"> Početna stranica </a>   
          </li>
          <li>
              <a href="prijava.php"> Prijava </a>   
          </li>
          <li>  <a href="programiNeregistrirani.php"> Popis programa </a>
          </li>

          <?php 
          $ispisiS = "";
          if(!isset($tipKorisnika)){
              $ispisiS .= "<li> <a href='registracija.php'>  </a> </li>";
          }
          else{
            $ispisiS .= "<li> <a href='vidiKupone.php'> Kuponi </a> </li>";
            
            if($tipKorisnika == 2){
            $ispisiS .= "
            <li>
                <a href='nova_vrsta_programa.php'> Nova vrsta programa </a>   
            </li>
            
            <li>
                <a href='azuriraj_vrstu_programa.php'> Azuriraj vrstu programa </a>   
            </li>
            <li>
                <a href='novi_kupon.php'> Novi kupon </a>   
            </li>         
            <li>
                <a href='azurirajKupon.php'> Azuriraj kupon </a> 
            </li>  
            <li>
              <a href='lojalnost.php'> Lojalnost </a>
            </li>
             <li>
              <a href='dnevnik.php'> Dnevnik </a>
            </li>
            <li> <a href='trosenjeBodova.php'> Korisnik - akcija </a>
            </li>
             <li>
              <a href='dodjela_uloga.php'> Dodjela uloga </a>
            </li>
             <li>
              <a href='otkljucavanje_korisnika.php'> Otključavanje korisnika </a>
            </li>
            ";}

            if($tipKorisnika == 1){
              $ispisiS .= "
            <li>
                <a href='novi_program.php'> Novi program </a>   
            </li>
            
            <li>
                <a href='azurirajProgram.php'> Azuriraj program </a>   
            </li>
            <li>
                <a href='novi_kupon.php'> Novi kupon </a>   
            </li>         
            <li>
                <a href='azurirajKupon.php'> Azuriraj kupon </a> 
            </li> 
            <li>
                <a href='novi_trening.php'> Novi trening </a>   
            </li>         
            <li>
                <a href='azurirajTrening.php'> Azuriraj trening </a> 
            </li> 
            <li> <a href='promijenaStatusaTreninga.php'> Status treninga </a>
            </li>
            ";}

            if($tipKorisnika == 3){
              $ispisiS .= "
            <li>
              <a href='lojalnost.php'> Lojalnost </a>
            </li>  
             <li>
              <a href='evidencijaDolazaka.php'> Evidencija dolazaka </a>
            </li>
             <li>
              <a href='kosarica.php'> Kosarica </a>
            </li>
             <li>
              <a href='evidencijaBodova.php'> Stanje bodova </a>
            </li>
            ";}    
          $ispisiS .= " <div style = 'float: right' >
                <li>
                    <a href='odjava.php'>  Odjava </a>   
                </li> 
                <li>
                    $korisnikIme;
                </li>
            </div>";
          }
          echo $ispisiS;
           ?>
      </ul>      
    </nav>


    
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
	  <section id="noviTrening" > 
      <form id="novi_trening" method = "POST" name="noviTrening" action="novi_trening.php">
          <h2> Za stvaranje novog TRENINGA popunite sljedeće stavke: </h2>

        <select id="kategorija" name="odabraniProgram" multiple="multiple" size="5">
            <option value="-1" selected="selected" > &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; == Odaberi kategoriju == 
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
                          echo '<option value="'. $id .'">'.$naziv.'</option>';
                      }
             $db->zatvoriDB();

            ?>     
        </select>
      <br>   
        <label for="Vrijeme"  id="lblVrijeme"> Vrijeme početka </label>
        <br>
          <input type="time" name="Vrijeme" id="Vrijeme" id="lblTime">  
          <br>    
          <label id="greskeVrijeme">
          </label> 
          <br> 
          <label id="usklicnikVrijeme"> </label> 
        <br>    
          <label for="kol" id="lblKolicina"> 
            Mogući broj polaznika: 
          </label>
          <input type="number" name="kol" min="1" id="kol">
        <br>     
          <label id="greskeKolicina"> 
          </label> 
          <br> 
          <label id="usklicnikKol"> 
          </label> 
        
        <br>  

        <label for="datumOdrzavanja"> Mjesec održavanja treninga: </label>
        <input type="date" name="datumOdrzavanja" id="datum" placeholder="11.11.2012.">   
        <label id="greskeDatum"> </label> 
        <br>   
        <label id="usklicnikDatum"> </label> 
        <br>
        
      
        <input type="submit" name="Uvedi" value="Uvedi trening"> 
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