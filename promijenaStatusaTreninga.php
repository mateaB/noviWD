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


$nadoknada = "display:none";

if (isset($_POST["zapisi"])) 
{
  $sakrij = " display:none";
  include("baza.class.php");
  $db = new Baza();
  $db->spojiDB();
  $odabranTrening = $_POST['odabranTrening'];
  echo "trening $odabranTrening";
  $odabranTip = $_POST['odabranTip'];
      echo "tip $odabranTip";

  $brojPrisutnih = $_POST['kol'];
      echo "kol $brojPrisutnih";


  $upitUpisi = "UPDATE `trening` SET `status_id`=$odabranTip,`broj_polaznika`=$brojPrisutnih WHERE id=$odabranTrening;";
  $prijenos = $db->updateDB($upitUpisi);
  if($odabranTip == 2)
  {
    $nadoknada = "display:inline-block";
  }

  //dnevnik
  $datum = date("Y-m-d H:i:s");
  $radnja = "Korisnik  $korisnik je promijenio status treningu $odabranTrening!";
  $dnevnik = "INSERT INTO `dnevnik`(`korisnik`,`datum`, `Opis`) VALUES ('" . $korisnik . "','" . $datum . "','" . $radnja . "')";
  $db->selectDB($dnevnik);


  $db->zatvoriDB();

}

if (isset($_POST["Uvedi"])) 
{
    include("baza.class.php");
    $db = new Baza();
    $db->spojiDB();
/*
    $odabrani = $_POST['odabraniProgram'];
    echo $odabrani . "<br> ";
    $datumOdrzavanja = $_POST['datumOdrzavanja'];

    $vrijeme = strtotime($_POST['Vrijeme']);
    $vrijeme_pocetka = date('h:i:s', $vrijeme);
    echo $vrijeme_pocetka. "<br> ";


    $br_mogucih_mjesta = $_POST['kol'];
    echo $br_mogucih_mjesta . "<br> ";


    $datum = strtotime($datumOdrzavanja);
    $danUTjednu = date('l', $datum);
    echo $danUTjednu. "<br> ";
    $mjesec = date('m', $datum);
    echo $mjesec. "<br> ";
    

    if( empty($datumOdrzavanja) || empty($br_mogucih_mjesta) )
    {
      $greska .= "Nisu unesena sva polja <br>";
    }
    else if($odabrani == -1)
    {
      $greska .= "Molimo Vas da odaberete vrstu programa!";
    }         
        

  if($greska === "" )
  { 
    echo "usa";
    $sakrij = "display: none";

    $upitic = "SELECT tp.program_id, t.dan_u_tjednu, t.vrijeme_pocetka, t.mjesec
              FROM  `trening_program` tp
              JOIN trening t ON t.id = tp.`trening_id`  where t.dan_u_tjednu = '$danUTjednu' and t.mjesec= '$mjesec' and t.vrijeme_pocetka= '$vrijeme_pocetka' and  tp.program_id = '$odabrani'";
    $odgovor = $db->selectDB($upitic);

    if($odgovor->num_rows == 0)
    {
      $upitUpisi = "INSERT INTO `trening`(`status_id`, `broj_polaznika`, `dan_u_tjednu`, `vrijeme_pocetka`, `mjesec`) VALUES (4,  '$br_mogucih_mjesta',  '$danUTjednu', '$vrijeme_pocetka', '$mjesec');";
      $prijenos = $db->updateDB($upitUpisi);
     
      $upitUpisi2 = "INSERT INTO `trening_program`(program_id, trening_id) VALUES ('$odabrani', (select id from trening where status_id = 4 and '$br_mogucih_mjesta' = broj_polaznika and '$danUTjednu' = dan_u_tjednu and mjesec='$mjesec' and vrijeme_pocetka = '$vrijeme_pocetka') ) ;";
      $prijenos = $db->updateDB($upitUpisi2);

          //dnevnik
          $datum = date("Y-m-d H:i:s");
          $radnja = "Korisnik  $korisnikIme je dodao nadoknadu!";
          $dnevnik = "INSERT INTO `dnevnik`(  `korisnik`, `datum`, `Opis`, `tip_akcije`)  values  ('$korisnik','$datum ',' $radnja ', 4)";
          $db->updateDB($dnevnik);
      }
      else
      {
        $greska = "U tom mjesecu već postoji trening u to vrijeme na taj dan! Molimo da izaberete drugi termin!";
      }
    }
   
      $odabrani = $_POST["odabranaVrstaPrograma"];
      */
      $datumOdrzavanja = $_POST["datumOdrzavanja"];
      $br_mogucih_mjesta = $_POST["kol"];
      $vrijeme_pocetka = $_POST['Vrijeme'];
      $moderator = $korisnik;

      $datum = strtotime($datumOdrzavanja);

      $danUTjednu = date('l', $datum);
          echo "trening $danUTjednu";

      $mjesec = date('m', $datum);

      echo $mjesec;
      

      if( empty($datumOdrzavanja) || empty($br_mogucih_mjesta) )
      {
        $greska .= "Nisu unesena sva polja <br>";
      }
      else if($odabrani == -1)
      {
        $greska .= "Molimo Vas da odaberete vrstu programa!";
      }         
            

      if($greska === "")
      { 
        $sakrij = "display: none";
        include_once("baza.class.php");
        $db = new Baza();
        $db->spojiDB();
       
        $nadiProgrameVrijemeDan = "SELECT `vrijeme_pocetka`, `dan_u_tjednu`, mjesec FROM `trening` WHERE `program_id`=$odabrani;";
        $pronasao = $db->selectDB($nadiProgrameVrijemeDan);
        if($pronasao->num_rows == 0)
        {
                $upitUpisi = "INSERT INTO `trening`(`status_id`, `program_id`,  `broj_polaznika`, `dan_u_tjednu`, `vrijeme_pocetka`) VALUES (4, $odabrani, $br_mogucih_mjesta, $danUTjednu,  $vrijeme_pocetka) ;";
                $prijenos = $db->selectDB($upitUpisi);
        }
        else
        {
           while (list($vrijemeNADENI, $dan_u_tjednuNADENI, $mjesecNADENI) = $pronasao->fetch_array() )
          {
                  if($dan_u_tjednuNADENI == $danUTjednu && $mjesecNADENI == $mjesec && $vrijemeNADENI == $vrijeme_pocetka)
                  {
                    $poruka = "Već je netko zauzeo ovaj program u tom mjesecu u to vrijeme!";
                  }
                  else
                  {
                     //upis u bazu DOZVOLJEN
                      $upitUpisi = "INSERT INTO `trening`(`status_id`, `program_id`,  `broj_polaznika`, `dan_u_tjednu`, `vrijeme_pocetka`) VALUES (3, $odabrani, $br_mogucih_mjesta,  $danUTjednu, $vrijeme_pocetka) ;";
                      $prijenos = $db->selectDB($upitUpisi);

                      $nadimail = "SELECT k.email, k.ime, k.prezime
                                  FROM program p
                                  JOIN  `korisnik_program` kp ON kp.`program_id` = p.id
                                  JOIN korisnik k ON k.korisnik_id = kp.`korisnik_id` 
                                  JOIN trening t ON t.program_id = p.id
                                  WHERE t.id =$odabranTrening";

                      while (list($mail, $ime, $prezime) = $pronasao->fetch_array() )
                      {
                        echo "Slanje maila!";
                        $mail_to = "$email";
                        $mail_from = "From: WebDiP_2017@foi.hr";
                        $mail_subject = "Nadoknada treninga";
                        $mail_body = "Obavještavamo Vas da će se trening koji se trebao održati u $dan_u_tjednuNADENI u $vrijemeNADENI održati u $danUTjednu u $vrijeme_pocetka. Vidimo se i oprostite na odgodi. <br>   Ugodan dan, 
                        Vaš sportski klub Skok. ";
                        $poruka="";
                        if (mail($mail_to, $mail_subject, $mail_body, $mail_from)) 
                        {
                            $poruka.="Poslana poruka za: '$mail_to'! <br>";
                        } 
                        else 
                        {
                            $poruka.="Problem kod poruke za: '$mail_to'! <br>";
                        }
                    }
                  }
                
            }
          }
        }



    $poruka = "Dodali ste novi TRENING! Želimo Vam puno uspjeha! ";

                    
       
   //dnevnik
      $datum = date("Y-m-d H:i:s");
      $radnja = "Korisnik  $korisnik je dodao novi trening!";
      $dnevnik = "INSERT INTO `dnevnik`(`korisnik`,`datum`, `Opis`) VALUES ('" . $korisnik . "','" . $datum . "','" . $radnja . "')";
      $db->selectDB($dnevnik);


  $db->zatvoriDB();
 
}   
  
?>

<!DOCTYPE html>
<html>
	<head>
		<title> Promijena statusa treninga </title>
        <meta charset="utf-8">
        <meta name="author" content="Matea">
        <meta name="keywords" content="novi_proizvod, upis_proizvoda">
        <meta name="description" content="Stranica je rađena 06.03.2017">

         <link href="css/matbodulu.css" rel="stylesheet" type="text/css">		

	</head>
	<body >
		<header>
    <figure>
      <figcaption class="naslov"> Promijena statusa treninga </figcaption>
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
        if (isset($poruka)) {
          $sakrij = "display:none";
          echo $poruka;
        }
    ?>

  <div <?php  if(isset($sakrij)) echo "style= " . '"' .  $sakrij . '";' ?> >   
	  <section id="uloge" > 
      <form id="dodjela_uloga" method = "POST" name="dodjela_uloga" action="promijenaStatusaTreninga.php"> 
          <label for="odabranaOsoba"> Odaberite status treninga: 
          </label> 
          <br>     

          <select id="osoba" name="odabranTip">
              <?php
                include_once("baza.class.php");
                $db = new Baza();
                $db->spojiDB();
                $upit = "SELECT id, naziv
                          FROM  status_treninga;";
                $odgovor = $db->selectDB($upit);               
               
                while (list($id, $naziv) = $odgovor->fetch_array()) 
                        {
                            $nadeni = '<option value="'. $id;
                            if($id == $odabranTip)
                              { $nadeni .= ' selected';
                          }
                              $nadeni .=' ">' . $naziv . '</option>';
                           echo $nadeni;
                        }
               $db->zatvoriDB();
              ?>     
          </select>


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
                            WHERE vpm.moderator = $korisnik and t.status_id != 1;";
                                                      
                    $odgovor = $db->selectDB($upit);               
                   
                    while (list($idTreninga, $dan, $vrijeme, $mjesec, $idprograma, $nazivPrograma) = $odgovor->fetch_array()) 
                            {
                                echo '<option value="'. $idTreninga .'">' . $nazivPrograma . " ". $dan . " " . $vrijeme . " " . $mjesec .'</option>';
                            }
                    $db->zatvoriDB();   
            ?>
          </select>
          <br>
          <label for="kol" id="lblKolicina"> Broj prisutnih: </label>
          <input type="number" name="kol" min="1" id="kol">

          <input type="submit" name="zapisi" value="Spremi"> 
      </form>
    </section>
  </div>

  <div <?php  if(isset($nadoknada)) echo "style= " . '"' .  $nadoknada . '";' ?> >

  <section id="noviTrening" > 
      <form id="novi_trening" method = "POST" name="noviTrening" action="promijenaStatusaTreninga.php">
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
          
        <br>    
          <label for="kol" id="lblKolicina"> 
            Mogući broj polaznika: 
          </label>
          <input type="number" name="kol" min="1" id="kol">
        <br>     
          
        
        <br>  

        <label for="datumOdrzavanja"> Mjesec održavanja treninga: </label>
        <input type="date" name="datumOdrzavanja" id="datum" placeholder="11.11.2012.">   
        
      
        <input type="submit" name="Uvedi" value="Uvedi trening"> 
        <input type="reset" value="Vraćanje na inicijalne postavke"> 
      </form>
    </section>
    </section>
  </div>


  <?php 
    include 'footer.php';
   ?>
      <script type="text/javascript" src="js/matbodulu.js">         
      </script>
	</body>
</html>