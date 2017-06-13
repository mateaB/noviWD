<?php
session_start();

header('Content-Type: text/html; charset=utf-8');

$greska = "";
$sakrij = "display:none;";

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

if(isset($_POST['odabraniProgram'])){
   $odabrani = $_POST['odabraniProgram'];
   echo $odabrani;
}

if (isset($_POST["Azuriraj"])) {
   
    $sakrij = "display:inline-block";
    include_once("baza.class.php");
    $db = new Baza();
    $db->spojiDB();
    $sakrij = "display:inline-block";
    $sveOProgramu = "SELECT  p.`id` ,  p.`naziv` ,  p.`opis` ,  p.`vrsta_programa_id`,   p.`broj_dozvoljenih_mjesta` 
            FROM  `program` p
             where p.id= $odabrani";
    $nadenoOProgramo = $db->selectDB($sveOProgramu);
    while (list( $id, $naziv, $opis, $vrsta_programa_id, $broj_dozvoljenih_mjesta) = $nadenoOProgramo->fetch_array()){
      $opisPrograma = $opis;
      $vrsta_programa_id = $vrsta_programa_id;
      $nazivPrograma = " value='$naziv'";
      $idPrograma = " value='$id'";
      $odabranaVrstaPrograma = $vrsta_programa_id;
      $brojDozvoljenih = "value = '$broj_dozvoljenih_mjesta'";

    }
    
   
     //dnevnik
        $datum = date("Y-m-d H:i:s");
        $radnja = "Korisnik  $korisnik je krenuo s azuriranjem programa!";
        $dnevnik = "INSERT INTO `dnevnik`(  `korisnik`, `datum`, `Opis`, `tip_akcije`)  values  ('$korisnik','$datum ',' $radnja ', 4)";
        $napravi = $db->updateDB($dnevnik);


    $db->zatvoriDB();

  }
   


if (isset($_POST["Upisi"])) {
  echo $odabrani;
    $nazivPrograma = $_POST['naziv'];
    echo $nazivPrograma;
    $opisPrograma = $_POST['opis'];
    echo $opisPrograma;
    $br_mogucih_mjesta = $_POST['kol'];
    $odabranaVrsta = $_POST['odabranaVrstaPrograma'];

    if(empty($nazivPrograma)  || empty($opisPrograma) || empty($br_mogucih_mjesta) )
    {
      $greska .= "Nisu unesena sva polja <br>";
    }
    else if($odabranaVrsta == -1){
      $greska .= "Molimo Vas da odaberete vrstu programa!";
    }
  

             
        

  if($greska === ""){ 
    echo "blaaaa";
    $sakrij = "display: inline-block";
    include("baza.class.php");
    $db = new Baza();
    $db->spojiDB();
    $datum = date("Y-m-d H:i:s");
   

    $upitUpisi = "UPDATE `program` SET  `naziv`= ' $nazivPrograma ',`opis`=  '$opisPrograma',`vrsta_programa_id`='$odabranaVrsta',`broj_dozvoljenih_mjesta`= '$br_mogucih_mjesta' WHERE id='$odabrani'";
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
        $radnja = "Korisnik  $korisnik je updatao program $nazivPrograma!";
       $dnevnik = "INSERT INTO `dnevnik`(  `korisnik`, `datum`, `Opis`, `tip_akcije`)  values  ('$korisnik','$datum ',' $radnja ', 4)";
        $napravi = $db->updateDB($dnevnik);


    $db->zatvoriDB();

  }
   
} 

if (isset($_POST["Brisi"])) {

    $sakrij = "display: inline-block";
    include("baza.class.php");
    $db = new Baza();
    $db->spojiDB();
    $datum = date("Y-m-d H:i:s");
    $nazivProgramaA = "select id, naziv, opis  from program where id= $odabrani";
    $nazivProgramaB = $db->selectDB($nazivProgramaA);

     while(list($id, $naziv, $opis) = $nazivProgramaB->fetch_array()){
      $nazivP = $naziv;
      $opisP = $opis;
    }
    

    $upitUpisi = "DELETE FROM `program` WHERE id='$odabrani'";
    $prijenos = $db->updateDB($upitUpisi);


    $poruka = "Obrisali ste program '$nazivP'. Možete:
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
        $radnja = "Korisnik  $korisnikIme je obrisao program $nazivP!";
        $dnevnik = "INSERT INTO `dnevnik`(  `korisnik`, `datum`, `Opis`, `tip_akcije`)  values  ('$korisnik','$datum ',' $radnja ', 4)";
        $napravi = $db->updateDB($dnevnik);


    $db->zatvoriDB();

   
} 

?>

<!DOCTYPE html>
<html>
	<head>
          <title> Ažuriranje programa </title>

        <meta charset="utf-8">
        <meta name="author" content="Matea">
        <meta name="keywords" content="novi_proizvod, upis_proizvoda">
        <meta name="description" content="Stranica je rađena 06.03.2017">

         <link href="css/osnova.css" rel="stylesheet" type="text/css">		

	</head>
	<body >
		<header>
    <figure>
      <figcaption class="naslov"> Ažuriraj program iz izbora svojih programa </figcaption>
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

  <form id="novi_program" method = "POST" name="noviProgram" action="azurirajProgram.php">
      <select id="kategorija" name="odabraniProgram" multiple="multiple" size="5">
              <option value="-1" > &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; == Odaberi program == 
              </option>
              <?php
                include_once("baza.class.php");
                $db = new Baza();
                $db->spojiDB();
                $upit = "SELECT id, naziv
                          FROM program;";
                $odgovor = $db->selectDB($upit);               
               
                while (list($id, $naziv) = $odgovor->fetch_array()) 
                        {
                            $odabraniProgram = '<option value="'. $id .'"';
                            if($id == $odabrani) {
                              $odabraniProgram .= ' selected="selected"' ;
                            }

                              $odabraniProgram .= '>'.$naziv.'</option>';
                            echo $odabraniProgram;
                        }
               $db->zatvoriDB();
              ?>     
      </select>

      <input type="submit" name="Azuriraj" value="Azuriraj"> 

      <input type="submit" name="Brisi" value="Obriši program"> 


  <div <?php  if(isset($sakrij)) echo "style= " . '"' .  $sakrij . '";' ?> >   
	  <section id="noviProgram" > 
          <h2> U bazi ovaj program trenutno ima sljedeće atribute </h2>
          <label for="naziv" id="lblNaziv"> Naziv programa: </label> 
          <br>
          <input type="text" maxlength="30" name="naziv" id="naziv" 
          <?php if(isset($nazivPrograma))  echo $nazivPrograma;
           ?>> 
          
          <br>
           
          <label for="opis"  id="lblOpis"> Opis programa: </label> 
          <br>
          <textarea rows="5" name="opis" cols="40" placeholder="Potrebno je opisati program u barem 3 rečenice. " id="opis">
          <?php if(isset($opisPrograma)) echo $opisPrograma;
           ?>
           
           </textarea>  
        
          <br> 

          <label for="kol" id="lblKolicina" required> 
            Moguć broj polaznika: 
          </label>
          <br>
          <input type="number" name="kol" min="1" id="kol" 
          <?php if(isset($brojDozvoljenih)) echo $brojDozvoljenih;
           ?>>
          <br>   

          <br>   

        <select id="kategorija" name="odabranaVrstaPrograma" multiple="multiple" >
            <option value="-1"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; == Odaberi kategoriju == 
            </option>
            <?php
              include_once("baza.class.php");
              $db = new Baza();
              $db->spojiDB();
              $upit = "SELECT * 
FROM  `vrstaProgramaModerator` vpm
JOIN vrsta_programa vp ON vp.id = vpm.`vrsta_programa`  where vpm.moderator = $korisnik;";
              $odgovor = $db->selectDB($upit);               
             
              while (list($vrstaProgId, $idModerator, $idVp, $nazivVrste) = $odgovor->fetch_array()) 
                      {
                          $vrstaProgramcica = '<option value="'. $idVp .'" ';
                           if($idVp == $odabranaVrstaPrograma){ $vrstaProgramcica .= ' selected="selected"' ; }
                          $vrstaProgramcica .= ' >'. $nazivVrste . '</option>';
                          echo $vrstaProgramcica;
                      }
             $db->zatvoriDB();
            ?>     
        </select>

        
        
        <br>
        <br>
        <br>     
         
        <input type="submit" name="Upisi" value="Spremi program"> 
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