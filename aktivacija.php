
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
 <?php
session_start();

header('Content-Type: text/html; charset=utf-8');

if (isset($_GET["activate"])) {
    $aktivacijski = $_GET["activate"];
    
    include("baza.class.php");
    $db = new Baza();
    $db->spojiDB();
    $upit = " SELECT * FROM `korisnik` WHERE `aktivacijski`='$aktivacijski'";
    $odgovor = $db->selectDB($upit);
    $row = mysqli_fetch_array($odgovor);
    $poruka = "";
    
    if (!empty($row)) {
        if (($row["aktiviran"]) == false) {
            
                 $korisnik = $row["korisnik_id"];
                $poruka = "Vaš korisnički račun je uspješno aktiviran <br>";
                $upit2 = "UPDATE `korisnik` SET `aktiviran`= 1  WHERE `korisnik_id`='$korisnik'";
                $prijenos2 = $db->updateDB($upit2);
               
                $upit = " SELECT * FROM `korisnik` WHERE `aktivacijski`='$aktivacijski'";
                $odgovor = $db->selectDB($upit);
                $row = mysqli_fetch_array($odgovor);

                //sesijaa
                $_SESSION['korisnickoIme'] = $row['korisnicko_ime'];
                $_SESSION['tip_korisnika'] = $row['tip_korisnika'];
                $_SESSION['korisnik_id'] = $row['korisnik_id'];
                $_SESSION['email'] = $row['email'];
                $_SESSION['ime'] = $row['ime'];
                $_SESSION['prezime'] = $row['prezime'];
                $_SESSION['aktiviran'] = $row['aktiviran'];

               $KORISNIKID =  $_SESSION['korisnik_id'];
                $datum = date("Y-m-d H:i:s");


                //evidencija bodova 
                 $upit2 = 'INSERT INTO `evidencija_bodova`(`skupljeno`, `potorseno`, `datum_izmjene`, `korisnik_korisnik_id`) VALUES (0, 0, ' . '"' . $datum . '"' . ' , ' . $KORISNIKID . ' );';
                $prijenos2 = $db->updateDB($upit2);
                echo $upit2;

                //dnevnik
                $radnja = "Račun je registriran!";
                $dnevnik = "insert into dnevnik (korisnik,datum,Opis, tip_akcije) values "
                        . " ($KORISNIKID,'{$datum}','{$radnja}', 4)";
                $db->selectDB($dnevnik);
        } 
        else {
            $poruka = "Korisnički račun je već aktiviran <br>";
        }
    }
    else {
        $poruka = "Nepostojeći aktivacijski kod <br>";              
    }
} else {
    $poruka = "Pokrenuli ste link bez aktivacijskog koda!";        
}

$db->zatvoriDB();
?>


<!DOCTYPE html>
<html>
    <head>
       <title> Aktivacijski  </title>
        <meta charset="utf-8">
        <meta name="author" content="Matea">
        <meta name="keywords" content="popis_proizvoda, izlist">
        <meta name="description" content="Stranica je raÄ‘ena 30.04.2017">

         <link href="css/matbodulu.css" rel="stylesheet" type="text/css">
         
    </head>
    <body>
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
              $ispisiS .= "<li> <a href='registracija.php'> Registracija </a> </li>";
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

        <?php echo $poruka; ?>
        <footer>     

        <h5> Vrijeme koje sam utroÅ¡ila u rjeÅ¡avanje aktivnog dokumenta je 24h. </h5>      

        <a href="http://jigsaw.w3.org/css-validator/validator?uri=http%3A%2F%2Fbarka.foi.hr%2FWebDiP%2F2016%2Fzadaca_04%2Fmatbodulu%2Fregistracija.html&profile=css3&usermedium=all&warning=1&vextwarning=&lang=en">
          <figure>
            <img src="slike/CSS3.png" alt="validacija CSS-a">
            <figcaption> Validacija CSS-a </figcaption>
          </figure> 
        </a>          
      </footer>
    </body>
</html>

