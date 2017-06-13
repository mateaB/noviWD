<?php
    session_start();

    header('Content-Type: text/html; charset=utf-8');

    if (!isset($_SESSION['korisnickoIme'])) {
        $ispisi .= "Morate biti prijavljeni";
        header("Location:prijava.php");
        exit();
    }
    else{
        $korisnikIme = $_SESSION['korisnickoIme'];
        $korisnik = $_SESSION['korisnik_id'];
          $tipKorisnika = $_SESSION['tip_korisnika'];

    }

   /* aktivniii
    SELECT * 
FROM kuponi k
JOIN kupon_programi kp ON kp.kuponi_id = k.id
WHERE kp.do > NOW( ) 
*/

/* u košaricii
SELECT * FROM `kosarica` k JOIN status_kupnje sk ON sk.id = k.`status_kupnje_id` join kuponi kup on kup.id = k.`kupon`
*/


if (isset($_POST["Upisi"])) {
    $sakrij = "display: none";
    include_once("baza.class.php");
    $db = new Baza();
    $db->spojiDB();
    $datum = date("Y-m-d H:i:s");
    $KUPLJENI = $_POST["kupon_id"];
    echo "blaaaaSADAA $KUPLJENI";
    $upitUpisi = "INSERT INTO `kupljeni_kuponi`(`kupac`, `datum`, `kod`, `kupon`) VALUES ('$korisnik' , now() , 'aaaaaaa', '$KUPLJENI');";
    $prijenos = $db->selectDB($upitUpisi);

    $upadeKosaricu = "UPDATE kosarica set status_kupnje_id = 1 WHERE kupon =$KUPLJENI and status_kupnje_id=3 and vlasnik_kosarice= $korisnik LIMIT 1";
    $UPADEJTATkOSARICU = $db->updateDB($upadeKosaricu);

    $upadeBodovi = "INSERT INTO `evidencija_bodova`( `potorseno`, `datum_izmjene`, `korisnik_korisnik_id`, `trenutno`) VALUES (( (select `potorseno` from evidencija_bodova where `korisnik_korisnik_id`=$korisnik GROUP BY  `korisnik_korisnik_id`  order by `datum_izmjene`desc limit 1) + (select potrebno_bodova from kupon_programi where kupon_id=$KUPLJENI)), NOW(), $korisnik,( (select trenutno from evidencija_bodova where `korisnik_korisnik_id`=$korisnik GROUP BY  `korisnik_korisnik_id`  order by `datum_izmjene`desc limit 1) -  (select potrebno_bodova from kupon_programi where kupon_id=$KUPLJENI) );";

    $sadasnjeStanjeBodova = $db->updateDB($upadeBodovi);

    $upit = "SELECT k.naziv, k.opis, k.slika, kp.do, kk.kod
            FROM  `kupljeni_kuponi` kk
            JOIN kupon_programi kp ON kp.id = kk.kupon
            JOIN kuponi k ON k.id = kp.kuponi_id
            WHERE kk.kupac = $korisnik;";
    $odgovor = $db->selectDB($upit);  

               
    $ispisiUpisane = "Kupili ste kupon! <br> Možete <a href=vidiKupone.php> pregledati još kupona. </a> <br> Vaša košarica trenutno izgleda ovako: ";
                
    $ispisiUpisane .=  "<table class='tablica'> <th> Naziv kupona </th> <th> Opis kupona </th> <th> Slika </th> <th> Vrijedni do </th> <th> Kod </th>";
    while (list($kupon, $kOpis , $slika,  $datum, $kod) = $odgovor->fetch_array() ){
        $ispisiUpisane .=   "<tr>
                                <td>" . $kupon . "</td>
                                <td>  $kOpis  </td>
                                <td> <img src=" . '"' . $slika . '"' . "/> </td> 
                                <td>  $datum </td>
                                <td> $kod  </td>
                            </tr>";
    }
    $ispisiUpisane .= "</table>"; 

     //dnevnik
        $datum = date("Y-m-d H:i:s");
        $radnja = "Korisnik  $korisnikIme je kupio kupon s id-e $KUPLJENI!";
        $dnevnik = "INSERT INTO `dnevnik`(  `korisnik`, `datum`, `Opis`, `tip_akcije`)  values  ('$korisnik','$datum ',' $radnja ', 4)";
        $db->selectDB($dnevnik);


    $db->zatvoriDB();
} 
    
if (isset($_POST["StaviUKosaricu"])) {
    $sakrij = "display: none";
    include("baza.class.php");
    $db = new Baza();
    $db->spojiDB();
    $zapisiKupon = $_POST["idKupon"];
    echo "blaaaa";
    $upitUpisi = "INSERT INTO `kosarica`(`datum`, `status_kupnje_id`, `vlasnik_kosarice`, `kupon`) VALUES (now(), 3, $korisnik,$zapisiKupon);";
    $prijenos =  $db->updateDB($upitUpisi);

    $kuponIdDistinct = "SELECT distinct kupon, vlasnik_kosarice FROM  `kosarica` where  vlasnik_kosarice= $korisnik";
    $dohvatiDistinct = $db->selectDB($kuponIdDistinct); 

    $ispisiUpisane = "Stavili ste kupon u košaricu! Trenutno u košarici imate: <br> <br>";
                    
    $ispisiUpisane .=  "<table class='tablica'> <th> Naziv kupona </th> <th> Opis kupona </th> <th> Vrijedi do </th> <th> Trenutno u kosarici </th> <th> Program </th>";

    while (list($odabraniKupon, $vlasnik) = $dohvatiDistinct->fetch_array() ){

        $upit = "SELECT kp.id, k.naziv, k.opis, kp.do, COUNT( k.id ), p.naziv
                FROM  `kuponi` k
                JOIN kupon_programi kp ON kp.kuponi_id = k.id
                JOIN program p ON p.id = kp.program_id
                JOIN kosarica kos ON kos.kupon = kp.id
                WHERE kp.id =$odabraniKupon and kos.vlasnik_kosarice=$korisnik and kos.status_kupnje_id=3
                GROUP BY kos.kupon;";
        $odgovor = $db->selectDB($upit); 


                   
       
        while (list($idZaKupiti,  $kupNaziv, $kupOpis, $vrijediDo, $brojKupljenih, $nazivPrograma) = $odgovor->fetch_array() ){
            $ispisiUpisane .= "<tr>
                                <td>" . $kupNaziv . "</td>
                                <td>" . $kupOpis  . " </td>
                                <td> " . $vrijediDo . "</td>
                                <td> " . $brojKupljenih . "</td>
                                <td> " . $nazivPrograma . "</td>
                                <td> 
                                    <form action='vidiKupone.php' method='POST'>
                                     <input type='hidden' name='kupon_id' value= '$idZaKupiti'>
                                     <input type='submit' value='Kupi' name='Upisi'>
                                    </form>
                                </td>
                        </tr>";
            }
        }
        $ispisiUpisane .= "</table>"; 

        $upit1 = "SELECT  `skupljeno` ,  `potorseno` ,
                                `datum_izmjene` ,  `korisnik_korisnik_id` 
        FROM  `evidencija_bodova` eb
        JOIN korisnik k ON k.korisnik_id = eb.`korisnik_korisnik_id` 
        WHERE k.korisnik_id = $korisnik
        ORDER BY eb.`datum_izmjene` desc limit 1"; 
        $odgovor = $db->selectDB($upit1);  


        while ((list($skupljeno, $potorseno,  $datum_izmjene) = $odgovor->fetch_row()) )
            { 
                $trenutno = ($skupljeno - $potorseno);
                $ispisiUpisane .= "<h4 > Trenutno stanje bodova: $trenutno </h4>";
            }

        //dnevnik
        $datum = date("Y-m-d H:i:s");
        $radnja = "Korisnik  $korisnikIme je stavio u košaricu kupon s id-e $zapisiKupon!";
        $dnevnik = "INSERT INTO `dnevnik`(  `korisnik`, `datum`, `Opis`, `tip_akcije`)  values  ('$korisnik','$datum ',' $radnja ', 4)";
        $db->selectDB($dnevnik);
        $db->zatvoriDB();
} 

if (isset($_POST["pretrazi"])) {
    include("baza.class.php");
    $db = new Baza();
    $db->spojiDB();
    $ispisi = "";
    $programId = $_POST["odabraniProgram"];

   
    $ispisi .=  "<table class='tablica'>
                     <th> Naziv kupona</th> 
                     <th> Opis </th>
                     <th> Slika </th>
                     <th> Vrijedi do </th>
                     <th> Naziv programa </th>
                     <th> Potrebno bodova </th>
                     <th> Stavi u kosaricu </th>";


    $upit = "SELECT * 
            FROM kuponi k
            JOIN kupon_programi kp ON kp.kuponi_id = k.id
            JOIN program p ON p.id = kp.program_id
            WHERE kp.do > NOW() and p.id = $programId
            AND kp.potrebno_bodova <= ( 
            SELECT (eb.skupljeno - eb.potorseno)
            FROM evidencija_bodova eb
            JOIN korisnik k ON k.korisnik_id = eb.korisnik_korisnik_id
            WHERE eb.`korisnik_korisnik_id` = $korisnik 
            ORDER BY eb.datum_izmjene DESC 
            LIMIT 1);"; 
            //GROUP BY p.Naziv";
            $odgovor = $db->selectDB($upit);  

    if($odgovor->num_rows != 0){
        while (list($idKupon, $nazivKupona, $opisKupona, $slika, $kuponi_id, $program_id, $od, $do, $moderator, $idKuponPrograma, $potrebno_bodova, $idprograma, $nazivPrograma, $opisPrograma, $vrsta_programa_id, 
            $broj_dozvoljenih_mjesta) = $odgovor->fetch_array()){     $ispisi .= "<tr> <td colspan='7' style='width:100%'>  $nazivPrograma </td> </tr>";                   
                    $ispisi .= "<tr>                        
                                    <td> " . $nazivKupona ."
                                    <td>" . $opisKupona . "
                                    </td> 
                                     <td> <a href='prikaziDetaljeKupona.php'> <img src=" . '"' . $slika . '"' ."/> </a>
                                    </td>                                     
                                    <td>" . $do . "
                                    </td> 
                                    <td>" . $nazivPrograma . "
                                    </td> 
                                    <td>" . $potrebno_bodova . "
                                    </td> 
                                    <td>                             
                                        <form action='vidiKupone.php' method='POST'>
                                         <input type='hidden' name='idKupon' value= '$idKuponPrograma'>
                                         <input type='submit' value='Stavi u kosaricu' name='StaviUKosaricu'>
                                        </form>
                                    </td>
                                </tr>"; 
                    } 
                }else{
                    $ispisi .= "<tr> <td colspan='7' style='width:100%'>  Trenutno nemamo kupona za ovaj program! </td> </tr>";
                }            

     
    $ispisi .= "</table>"; 

    $radnja = "Korisnik $korisnikIme je pregledao kupone!";
    $dnevnik = "INSERT INTO `dnevnik`(  `korisnik`, `datum`, `Opis`, `tip_akcije`)  values  ('$korisnik',now(),' $radnja ', 4)";
    $provedi = $db->updateDB($dnevnik);
    
    $db->zatvoriDB(); 

}
else{
    echo $korisnik;
    include_once("baza.class.php");
    $db = new Baza();
    $db->spojiDB();
    $ispisi = "";
/*
    $brojBodovaImam = "SELECT eb.skupljeno - eb.potroseno
                        FROM evidencija_bodova eb
                        JOIN korisnik k ON k.korisnik_id = eb.korisnik_korisnik_id
                        where k.korisnik_id = $korisnik
                        ORDER BY eb.datum_izmjene DESC 
                        LIMIT 1;";
*/
    

    $nadiPrograme = "SELECT * 
                    FROM program";
    $pronadeniProgrami = $db->selectDB($nadiPrograme);

    $ispisi .=  "<table class='tablica'>
                     <th> Naziv kupona</th> 
                     <th> Opis </th>
                     <th> Slika </th>
                     <th> Vrijedi do </th>
                     <th> Naziv programa </th>
                     <th> Potrebno bodova </th>
                     <th> Stavi u kosaricu </th>";

   
   for ($i=0; $i < $pronadeniProgrami->num_rows; $i++) { 
        while (list($idProgramcic, $nazivProgramcica, $opis, $vrsta,  $brojdozvoljenih) = $pronadeniProgrami->fetch_array()){
            $ispisi .= "<tr> <td colspan='7' style='width:100%'>  $nazivProgramcica </td> </tr>";
                $upit = "SELECT * 
                            FROM kuponi k
                            JOIN kupon_programi kp ON kp.kuponi_id = k.id
                            JOIN program p ON p.id = kp.program_id
                            WHERE kp.do > NOW() 
                            AND kp.potrebno_bodova <= ( 
                            SELECT (eb.skupljeno - eb.potorseno)
                            FROM evidencija_bodova eb
                            JOIN korisnik k ON k.korisnik_id = eb.korisnik_korisnik_id
                            WHERE eb.`korisnik_korisnik_id` = $korisnik and kp.program_id = $idProgramcic
                            ORDER BY eb.datum_izmjene DESC 
                            LIMIT 1)"; 
                            //GROUP BY p.Naziv";
                            $odgovor = $db->selectDB($upit);  
                            
                            if($odgovor->num_rows != 0){
                            while (list($idKupon, $nazivKupona, $opisKupona, $slika, $kuponi_id, $program_id, $od, $do, $moderator, $idKuponPrograma, $potrebno_bodova, $idprograma, $nazivPrograma, $opisPrograma, $vrsta_programa_id, 
                                $broj_dozvoljenih_mjesta) = $odgovor->fetch_array()){                       
                                        $ispisi .= "<tr>                        
                                                        <td> " . $nazivKupona ."
                                                        <td>" . $opisKupona . "
                                                        </td> 
                                                         <td> <a href='prikaziDetaljeKupona.php'> <img src=" . '"' . $slika . '"' ."/> </a>
                                                        </td>                                     
                                                        <td>" . $do . "
                                                        </td> 
                                                        <td>" . $nazivPrograma . "
                                                        </td> 
                                                        <td>" . $potrebno_bodova . "
                                                        </td> 
                                                        <td>                             
                                                            <form action='vidiKupone.php' method='POST'>
                                                             <input type='hidden' name='idKupon' value= '$idKuponPrograma'>
                                                             <input type='submit' value='Stavi u kosaricu' name='StaviUKosaricu'>
                                                            </form>
                                                        </td>
                                                    </tr>"; 
                                        } 
                                    }else{
                                        $ispisi .= "<tr> <td colspan='7' style='width:100%'>  Trenutno nemamo kupona za ovaj program! </td> </tr>";
                                    }
            }            

    }
    
     $radnja = "Korisnik $korisnikIme je pregledao kupone!";
    $dnevnik = "INSERT INTO `dnevnik`(  `korisnik`, `datum`, `Opis`, `tip_akcije`)  values  ('$korisnik',now(),' $radnja ', 4)";
    $provedi = $db->updateDB($dnevnik);

    $ispisi .= "</table>";   
    $db->zatvoriDB();   
}

       
?>

<html>
	<head>
		<title> Dnevnik </title>
        <meta charset="utf-8">
        <meta name="author" content="Matea">
        <meta name="keywords" content="popis_proizvoda, izlist">
        <meta name="description" content="Stranica je rađena 30.04.2017">

        <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
        <link href="css/matbodulu.css" rel="stylesheet" type="text/css">
         
	</head>
	<body>
		<header>
            <figure style="margin: 0px">
                <figcaption class="naslov"> Početna </figcaption>
                <img src="slike/logo.png" class="prvaSlika" alt="Logo foi-a" usemap="#mapa1"/>
                <map name="mapa1">
                    <area href="index.html" alt="index" shape="rect" target="_blank" coords="0,0,200,200"/>
                    <area href="#o_meni" alt="o_meni" shape="rect" target="_parent" coords="200,0,400,200"/>
                </map>
            </figure>
        </header>

<nav>
      <ul>
          <li>
              <a href="index.html"> Početna stranica </a>   
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
          $ispisiS .= " <li>
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
        
         <div>
            <?php            
                if(isset($ispisiUpisane)){
                    echo $ispisiUpisane;
                }
                if(isset($ispisi)) echo $ispisi;
            ?>     
        </div> 

<div <?php if(isset($sakrij)) echo "style= " . '"' . $sakrij . '";' ?> >
            <section>           
                <form id="popisPrograma" method = "POST" name="popisPrograma" action="vidiKupone.php"> 
                    <div style="display: inline-block;">
                    <select style="margin-left: 0px; width: auto;" name="prikazi">
                            <?php 
                                echo $pronadeni;
                            ?>
                        </select>
                         <select style="margin-left: 0px; width: auto;" name="odabraniProgram">
                            <?php 
                                include_once("baza.class.php");
                                $db = new Baza();
                                $db->spojiDB();
                                $upit = "SELECT * 
                                        FROM program;";
                                $odgovor = $db->selectDB($upit);               
                               
                                while (list($id, $naziv) = $odgovor->fetch_array()) 
                                        {
                                            echo '<option value="'. $id .'">'. $naziv.'</option>';
                                        }
                               $db->zatvoriDB();
                            ?>
                        </select>

                    
 
                    <input  type="submit" value="Pretrazi" name="pretrazi"> 
                    <br>
                    <a style="margin-left: 150px;" href="kosarica.php"> Želim vidjeti svoju košaricu </a>
                </form>                       
            </section>
        </div>


        
</div>
        <footer>

            <h5> Vrijeme koje sam utrošila u rješavanje aktivnog dokumenta je 24h.               
            </h5>          

            <a href="http://jigsaw.w3.org/css-validator/validator?uri=http%3A%2F%2Fbarka.foi.hr%2FWebDiP%2F2016%2Fzadaca_04%2Fmatbodulu%2Fnovi_proizvod.html&profile=css3&usermedium=all&warning=1&vextwarning=&lang=en">
                <figure>
                    <img src="slike/CSS3.png" alt="validacija CSS-a">
                    <figcaption> Validacija CSS-a </figcaption>
                </figure> 
            </a>          
        </footer>
    </body>
</html>