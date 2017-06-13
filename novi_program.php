<?php
session_start();

header('Content-Type: text/html; charset=utf-8');
$ispisi = "";
$greska = "";

if (!isset($_SESSION['korisnickoIme'])) {
    $ispisi .= "Morate biti prijavljeni";
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
  echo $korisnik;
}




if (isset($_POST["Upisi"])) {
    $nazivPrograma = $_POST["naziv"];
    $opisPrograma = $_POST["opis"];
    $br_mogucih_mjesta = $_POST["kol"];
    $odabrani = $_POST["odabranaVrstaPrograma"];

    if(empty($nazivPrograma)  || empty($opisPrograma) || empty($br_mogucih_mjesta) )
    {
      $greska .= "Nisu unesena sva polja <br>";
    }
    else if($odabrani == -1){
      $greska .= "Molimo Vas da odaberete vrstu programa!";
    }
    else{
      function provjeraZnakova($unos) {
          $znakovi = ["(", ")", "{", "}", "'", "!", "#"];
          for ($i = 0; $i < count($znakovi); $i++) {
              $nema = strpos($unos, $znakovi[$i]);
              if ($nema === false) {  
              } 
              else {
                  return true;
              }
          }
          return false;
      }

      $polje=[$nazivPrograma, $opisPrograma, $br_mogucih_mjesta];
      for ($i=0; $i < 2 ;$i++)
      {
        if(provjeraZnakova($polje[$i]) == true)
        {
            $greska = "Nedozvoljeni znakovi se pojavljuju!! <br>";
        }
      }
    }

             
        

  if($greska === ""){ 
    $sakrij = "display: none";
    include_once("baza.class.php");
    $db = new Baza();
    $db->spojiDB();
    $datum = date("Y-m-d H:i:s");
   
    echo $odabrani;

    $upitUpisi = "INSERT INTO `program`( `naziv`, `opis`, `vrsta_programa_id`, `broj_dozvoljenih_mjesta`) VALUES ('$nazivPrograma', '$opisPrograma',  '$odabrani' , '$br_mogucih_mjesta');";
    $prijenos = $db->updateDB($upitUpisi);


    $poruka = "Dodali ste novi program! <br> Možete 
    <ul>
      <li>
        <a href=vidiPrograme.php> pregledati ostale programe. </a> 
      </li>
      <li>
        <a href=dodajTrening.php> dodati trening programu. </a> 
      </li>
      <li> <a href=novi_program.php>promijeniti dodani program </a>
      </li>
    <ul>";
                
   
     //dnevnik
        $datum = date("Y-m-d H:i:s");
        $radnja = "Korisnik  $korisnikIme je dodao novi program!";
        $dnevnik = "INSERT INTO `dnevnik`(  `korisnik`, `datum`, `Opis`, `tip_akcije`)  values  ('$korisnik','$datum ',' $radnja ', 4)";
        $napravi = $db->updateDB($dnevnik);


    $db->zatvoriDB();

  }
   
} 
?>

<!DOCTYPE html>
<html>
	<head>
		<title> Upis novi program </title>
        <meta charset="utf-8">
        <meta name="author" content="Matea">
        <meta name="keywords" content="novi_proizvod, upis_proizvoda">
        <meta name="description" content="Stranica je rađena 06.03.2017">

         <link href="css/osnova.css" rel="stylesheet" type="text/css">		

	</head>
	<body >
		<header>
    <figure>
      <figcaption class="naslov"> Upiši novi program u izbor svojih programa </figcaption>
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
      <form id="novi_program" method = "POST" name="noviProgram" action="novi_program.php">
          <h2> Za stvaranje novog programa popunite sljedeće stavke: </h2>
          <label for="naziv" id="lblNaziv"> Naziv programa: </label> 
          <br>
          <input type="text" maxlength="30" name="naziv" id="naziv" required> 
          <br>
          <label id="greskeNaziv"> </label> 
          <br>
          <label id="usklicnik"> </label> 
          <br>
           
          <label for="opis"  id="lblOpis"> Opis programa: </label> 
          <br>
          <textarea rows="5" name="opis" cols="40" placeholder="Potrebno je opisati program u barem 3 rečenice. " id="opis" required></textarea>  
          <br>
          <label id="greskeOpis"  
          > </label> 
          <br> 
          <label id="usklicnikOpis"> </label> 
          <br> 

            <!--
            
            <label for="datum" name="datum" id="lblDatum"> Datum početka izvođenja programa: </label> <br>
            <input type="text" id="datum" placeholder="11.11.2012.">
             <br>     
            <label id="greskeDatum"  
            > </label> <br>   
            <label id="usklicnikDatum"> </label> <br>
             
            <label for="Vrijeme"  id="lblVrijeme"> Vrijeme početka </label> <br>
            <input type="time" name="Vrijeme" id="Vrijeme" id="lblTime">  
            <br>    
            <label id="greskeVrijeme"  
            ></label> <br> 
            <label id="usklicnikVrijeme"> </label> <br>    

             <label for="VrijemeZavrsetka" id="lblVrijemeZavrsetka"> Vrijeme završetka </label> <br>
            <input type="time" name="VrijemeZavrsetka" id="VrijemeZavrsetka" id="lblTime">  
            <br>    
            <label id="greskeVrijemeZavrsetka"  
            ></label> <br> 
            <label id="usklicnikVrijemeZavrsetka"> </label> <br>   
            
                
              <!--              
             <br>     
             <label for="tezina" id="lblTezina"> Težina </label> <br>
             <input type="range" min="0" max="100" id="tezina">
             <label id="greskeTezina"></label> <br>     
             <label id="usklicnikTezina"> </label> <br>
             -->

          <label for="kol" id="lblKolicina" required> 
            Moguć broj polaznika: 
          </label>
          <br>
          <input type="number" name="kol" min="1" id="kol">
          <br>     
          <label id="greskeKolicina"> 
          </label> 
          <br> 
          <label id="usklicnikKol"> 
          </label> 
          <br>   

          <br>   

        <select id="kategorija" name="odabranaVrstaPrograma" multiple="multiple" size="5">
            <option value="-1" selected="selected" > &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; == Odaberi kategoriju == 
            </option>
            <?php
              include_once("baza.class.php");
              $db = new Baza();
              $db->spojiDB();
              $upit = "SELECT DISTINCT vp.id, vp.naziv
FROM  `vrstaProgramaModerator` vpm
JOIN vrsta_programa vp ON vp.id = vpm.`vrsta_programa` 
WHERE vpm.moderator = $korisnik ;";
              $odgovor = $db->selectDB($upit);               
             
              while (list( $vpId, $naziv) = $odgovor->fetch_array()) 
                      {
                          echo '<option value="'. $vpId .'">'.$naziv.'</option>';
                      }
             $db->zatvoriDB();
            ?>    

        </select>

        <br>
        <label id="greskeKategorija"> </label> 
        <br>
        <label id="usklicnikKategorija"> </label> 
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