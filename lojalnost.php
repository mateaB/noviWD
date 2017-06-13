<?php
    session_start();

    header('Content-Type: text/html; charset=utf-8');

    if (!isset($_SESSION['korisnickoIme'])) {
        $ispisi .= "Morate biti prijavljeni";
        header("Location:prijava.php");
        exit();
    }
    else if($_SESSION['tip_korisnika'] != 2) {
        header("Location:prijava.php");
        exit();
    }
    else{
      $tipKorisnika = $_SESSION['tip_korisnika'];
      $korisnikIme = $_SESSION['korisnickoIme'];
      $korisnik = $_SESSION['korisnik_id'];
  }
    //sort po datumu
/*
     Enter a date before 1980-01-01:
  <input type="date" name="bday" max="1979-12-31"><br>
  Enter a date after 2000-01-01:
  <input type="date" name="bday" min="2000-01-02"><br>
  */
 include("baza.class.php");
    $db = new Baza();
    $db->spojiDB();


if (isset($_POST["pretrazi"])) {
    $ispisi="";
    $odabrani = $_POST['zaSelect'];
    $odabranaOsoba = $_POST['odabranaOsoba'];
    $nadiOsobu = "";

    if($odabranaOsoba == 0 ){
      $korisnici = "SELECT `korisnik_id`, `ime`, `prezime`, `korisnicko_ime`  FROM `korisnik` ";
    $sviKorisnici = $db->selectDB($korisnici);
      if($odabrani == "trenutnoBodova")
      {
         header("Location:lojalnost.php");
        exit();
      }
      else if($odabrani == "potrosenimBodovima")
      {
        $ispisi = "<h2> Stanje po  <h2>";
       
        $ispisi .=  "<h2> POTROŠENIM BODOVIMA <h2>
                        <table class='tablica'>
                         <th> Ime i prezime </th> 
                         <th> Ukupno skupljeno </th>
                         <th> Potrošeni bodovi </th>
                         <th> Trenutno bodova </th>
                         <th> Zadnja promijena na datum </th>";

       
    $upit = "SELECT  k.ime, k.prezime, `skupljeno` ,  `potorseno` ,  `datum_izmjene` ,  `korisnik_korisnik_id` 
            FROM  `evidencija_bodova` eb
            JOIN korisnik k ON k.korisnik_id = eb.`korisnik_korisnik_id`
            GROUP BY eb.`korisnik_korisnik_id` 
            HAVING eb.datum_izmjene >= all(

            SELECT  `datum_izmjene` 
            FROM evidencija_bodova
            GROUP BY  `korisnik_korisnik_id` 
            )
            ORDER BY eb.potorseno DESC"; 
            $odgovor = $db->selectDB($upit);  

    
         if($odgovor->num_rows != 0)
                {
                  while (list($ime, $prezime, $skupljeno, $potorseno, $datum_izmjene, $korisnikOdabrani) = $odgovor->fetch_array())
                  {
                    $ispisi .= "<tr> <td > " . $ime . " " . $prezime . "</td> ";
                    $trenutno = $skupljeno - $potorseno;
                    $ispisi .= "<td> " . $skupljeno."
                                <td>" . $potorseno. "
                                </td> 
                                <td>" . $trenutno. "
                                </td>
                                <td>" . $datum_izmjene. "
                                </td>"; 
                    }
                }
                else
                {
                    $ispisi .= " <td colspan='4' style='width:100%'> Nemamo podatke o kotisniku! </td> ";
                }
      }
      else if($odabrani == "skupljenimBodovima")
      {
        $ispisi = "<h2> Stanje po   <h2>";
       
        $ispisi .=  "<h2> SKUPLJENIM BODOVIMA <h2>
                        <table class='tablica'>
                         <th> Ime i prezime </th> 
                         <th> Ukupno skupljeno </th>
                         <th> Potrošeni bodovi </th>
                         <th> Trenutno bodova </th>
                         <th> Zadnja promijena na datum </th>";

    
  
       
    $upit = "SELECT  k.ime, k.prezime, `skupljeno` ,  `potorseno` ,  `datum_izmjene` ,  `korisnik_korisnik_id` 
            FROM  `evidencija_bodova` eb
            JOIN korisnik k ON k.korisnik_id = eb.`korisnik_korisnik_id`
            GROUP BY eb.`korisnik_korisnik_id` 
            HAVING eb.datum_izmjene >= ALL(

            SELECT  `datum_izmjene` 
            FROM evidencija_bodova
            GROUP BY  `korisnik_korisnik_id`
            )
            ORDER BY eb.skupljeno DESC"; 
            $odgovor = $db->selectDB($upit);  

    
         if($odgovor->num_rows != 0)
                {
                  while (list($ime, $prezime, $skupljeno, $potorseno, $datum_izmjene, $korisnikOdabrani) = $odgovor->fetch_array())
                  {
                    $ispisi .= "<tr> <td > " . $ime . " " . $prezime . "</td> ";
                    $trenutno = $skupljeno - $potorseno;
                    $ispisi .= "<td> " . $skupljeno."
                                <td>" . $potorseno. "
                                </td> 
                                <td>" . $trenutno. "
                                </td>
                                <td>" . $datum_izmjene. "
                                </td>"; 
                    }
                }
                else
                {
                    $ispisi .= " <td colspan='4' style='width:100%'> Nemamo podatke o kotisniku! </td> ";
                }
    }
    else if($odabranaOsoba != 0){
      if($odabrani == "trenutnoBodova" )
      {
        $ispisi = "<h2> Stanje korisnika  <h2>";


        $ispisi .=  "<h2> TRENUTNO STANJE <h2>
                        <table class='tablica'>
                         <th> Ime i prezime </th> 
                         <th> Ukupno skupljeno </th>
                         <th> Potrošeni bodovi </th>
                         <th> Trenutno bodova </th>
                         <th> Datum zadnje izmjene </th>";

        $upit = "SELECT  k.ime, k.prezime, `skupljeno` ,  `potorseno` ,
                              (skupljeno - potorseno) ,  `datum_izmjene` ,  `korisnik_korisnik_id` 
                FROM  `evidencija_bodova` eb left
                JOIN  korisnik k ON k.korisnik_id = eb.`korisnik_korisnik_id` 
                WHERE k.korisnik_id = $odabranaOsoba  ORDER BY eb.`datum_izmjene` desc limit 1"; 
        $odgovor = $db->selectDB($upit); 
         if($odgovor->num_rows != 0)
         {    
            while (list($ime, $prezime , $skupljeno, $potorseno, $trenutno, $datum_izmjene, $idKorisnika) = $odgovor->fetch_array())
                {
                 $ispisi .= " <tr> <td> " . $ime . " " . $prezime . " </td> ";
                    
                        $ispisi .="  <td> " . $skupljeno."
                                     <td>" . $potorseno. "
                                     </td> 
                                     <td>" . $trenutno. "
                                     </td>
                                     <td>" . $datum_izmjene. "
                                     </td>
                                 </tr>"; 
              } 
          }
          else
          {
              $ispisi .= " <tr> <td colspan='5' style='width:100%'> Nemamo podatke o korisniku! </td></tr> ";
          }
      }
      else if ($odabrani == "skupljenimBodovima")
      {
        $ispisi = "<h2> Stanje prema  <h2>";


        $ispisi .=  "<h2> SKUPLJENI BODOVI <h2>
                        <table class='tablica'>
                         <th> Ime i prezime </th> 
                         <th> Ukupno skupljeno </th>
                         <th> Potrošeni bodovi </th>
                         <th> Trenutno bodova </th>
                         <th> Datum zadnje izmjene </th>";

        $upit = "SELECT  k.ime, k.prezime, `skupljeno` ,  `potorseno` ,
                              (skupljeno - potorseno) ,  `datum_izmjene` ,  `korisnik_korisnik_id` 
                FROM  `evidencija_bodova` eb left
                JOIN  korisnik k ON k.korisnik_id = eb.`korisnik_korisnik_id` 
                WHERE k.korisnik_id = $odabranaOsoba  ORDER BY eb.skupljeno desc "; 
        $odgovor = $db->selectDB($upit); 
         if($odgovor->num_rows != 0)
         {    
            while (list($ime, $prezime , $skupljeno, $potorseno, $trenutno, $datum_izmjene, $idKorisnika) = $odgovor->fetch_array())
                {
                 $ispisi .= " <tr> <td> " . $ime . " " . $prezime . " </td> ";
                    
                        $ispisi .="  <td> " . $skupljeno."
                                     <td>" . $potorseno. "
                                     </td> 
                                     <td>" . $trenutno. "
                                     </td>
                                     <td>" . $datum_izmjene. "
                                     </td>
                                 </tr>"; 
              } 
          }
          else
          {
              $ispisi .= " <tr> <td colspan='5' style='width:100%'> Nemamo podatke o korisniku! </td></tr> ";
          }

      }
      else if($odabrani == "potrosenimBodovima")
      {
        $ispisi = "<h2> Stanje prema  <h2>";


        $ispisi .=  "<h2> POTROŠENI BODOVI <h2>
                        <table class='tablica'>
                         <th> Ime i prezime </th> 
                         <th> Ukupno skupljeno </th>
                         <th> Potrošeni bodovi </th>
                         <th> Trenutno bodova </th>
                         <th> Datum zadnje izmjene </th>";

        $upit = "SELECT  k.ime, k.prezime, `skupljeno` ,  `potorseno` ,
                              (skupljeno - potorseno) ,  `datum_izmjene` ,  `korisnik_korisnik_id` 
                FROM  `evidencija_bodova` eb left
                JOIN  korisnik k ON k.korisnik_id = eb.`korisnik_korisnik_id` 
                WHERE k.korisnik_id = $odabranaOsoba  ORDER BY eb.potorseno desc "; 
        $odgovor = $db->selectDB($upit); 
         if($odgovor->num_rows != 0)
         {    
            while (list($ime, $prezime , $skupljeno, $potorseno, $trenutno, $datum_izmjene, $idKorisnika) = $odgovor->fetch_array())
                {
                 $ispisi .= " <tr> <td> " . $ime . " " . $prezime . " </td> ";
                    
                        $ispisi .="  <td> " . $skupljeno."
                                     <td>" . $potorseno. "
                                     </td> 
                                     <td>" . $trenutno. "
                                     </td>
                                     <td>" . $datum_izmjene. "
                                     </td>
                                 </tr>"; 
              } 
          }
          else
          {
              $ispisi .= " <tr> <td colspan='5' style='width:100%'> Nemamo podatke o korisniku! </td></tr> ";
          }
     }
  }
}
else{
    echo $korisnik;
    include_once("baza.class.php");
    $db = new Baza();
    $db->spojiDB();
    $ispisi = "";
/*
    $brojBodovaImam = "SELECT eb.trenutno 
                        FROM evidencija_bodova eb
                        JOIN korisnik k ON k.korisnik_id = eb.korisnik_korisnik_id
                        where k.korisnik_id = $korisnik
                        ORDER BY eb.datum_izmjene DESC 
                        LIMIT 1;";
    $imam = (int)$db->selectDB($brojBodovaImam);
*/
    

    $korisnici = "SELECT `korisnik_id`, `ime`, `prezime`, `korisnicko_ime`  FROM `korisnik` ";
    $sviKorisnici = $db->selectDB($korisnici);

    $ispisi .=  "<h2> TRENUTNO STANJE <h2>
                    <table class='tablica'>
                     <th> Ime i prezime </th> 
                     <th> Ukupno skupljeno </th>
                     <th> Potrošeni bodovi </th>
                     <th> Trenutno bodova </th>
                     <th> Zadnja promijena na datum </th>";

   
   for ($i=0; $i < $sviKorisnici->num_rows; $i++) { 
        while (list($idKorisnika, $ime, $prezime, $korisnickoIme) = $sviKorisnici->fetch_array()){
            $ispisi .= "<tr> <td > " . $ime . " " . $prezime . "</td> ";
            $upit = "SELECT  `skupljeno` ,  `potorseno` ,
                                   `datum_izmjene` ,  `korisnik_korisnik_id` 
                    FROM  `evidencija_bodova` eb
                    JOIN korisnik k ON k.korisnik_id = eb.`korisnik_korisnik_id` 
                    WHERE k.korisnik_id = $idKorisnika
                    ORDER BY eb.`datum_izmjene` desc limit 1"; 
                        $odgovor = $db->selectDB($upit);  
                            if($odgovor->num_rows != 0){
                            while (list($skupljeno, $potorseno, $datum_izmjene) = $odgovor->fetch_array()){
                            $trenutno = $skupljeno - $potorseno;                       
                                        $ispisi .= "                      
                                                        <td> " . $skupljeno."
                                                        <td>" . $potorseno. "
                                                        </td> 
                                                        <td>" . $trenutno. "
                                                        </td>
                                                        <td>" . $datum_izmjene. "
                                                        </td>"; 
                                        } 
                                    }else{
                                        $ispisi .= " <td colspan='4' style='width:100%'> Nemamo podatke o kotisniku! </td> ";
                                    }
        }     
    }     
    $ispisi .= "</tr> </table>";   
}


if ($odabrani == "datumUzlazno" or $odabrani == "datumSilazno"){
        if($odabrani == "datumUzlazno"){
        $orderBy = "order by eb.`datum_izmjene` ";
        $ispisi = "<h2> Stanje po datumu uzlazno <h2>";
        }
        else if($odabrani == "datumSilazno"){
            $orderBy = "order by eb.`datum_izmjene`  desc";
            $ispisi = "<h2> Stanje po datumu silazno  <h2>";
        }

        if($odabranaOsoba != 0){
            $nadiOsobu = "where eb.korisnik_korisnik_id= $odabranaOsoba";
        }


        $ispisi .=  "
                    <table class='tablica'>
                     <th> Ime i prezime </th> 
                     <th> Ukupno skupljeno </th>
                     <th> Potrošeni bodovi </th>
                     <th> Trenutno bodova </th>
                     <th> Zadnja promijena na datum </th>";

        $upit = "SELECT k.ime, k.prezime,  `skupljeno` ,  `potorseno` ,    `datum_izmjene` ,  `korisnik_korisnik_id` 
                FROM  `evidencija_bodova` eb
                JOIN korisnik k ON k.korisnik_id = eb.`korisnik_korisnik_id`" . $nadiOsobu . " " . $orderBy . " ;"; 
            $odgovor = $db->selectDB($upit);
            if($odgovor->num_rows != 0){
                while (list($ime, $prezime, $skupljeno, $potorseno, $datum_izmjene, $korisnikID1) = $odgovor->fetch_array()){  
                $ispisi .= "<tr> <td > " . $ime . " " . $prezime . "</td> ";    
                $trenutno = $skupljeno - $potorseno;
                            $ispisi .= "                      
                                            <td> " . $skupljeno."
                                            <td>" . $potorseno. "
                                            </td> 
                                            <td>" . $trenutno. "
                                            </td>
                                            <td>" . $datum_izmjene. "
                                            </td>"; 
                } 
            }else{
                $ispisi .= " <td style='width:100%'> Nemamo podatke o korisniku! </td> ";
            } 
    }
    
    $ispisi .= "</tr> </table>";  
}

 $db->zatvoriDB();   

?>

<html>
	<head>
		<title> Lojalnost </title>
        <meta charset="utf-8">
        <meta name="author" content="Matea">
        <meta name="keywords" content="popis_proizvoda, izlist">
        <meta name="description" content="Stranica je rađena 30.04.2017">

        <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
        <link href="css/osnova.css" rel="stylesheet" type="text/css">
         
	</head>
	<body>
		<header>
            <figure style="margin: 0px">
                <figcaption class="naslov"> Lojalnost </figcaption>
                <img src="slike/skok.jpg" class="prvaSlika" alt="Logo skok" usemap="#mapa1"/>
                <map name="mapa1">
                    <area href="index.php" alt="index" shape="rect" target="_blank" coords="0,0,200,200"/>
                    <area href="#lojalnost" alt="o_meni" shape="rect" target="_parent" coords="200,0,400,200"/>
                </map>
            </figure>
        </header>

   
 <?php
      include 'nav.php';
   ?>


      
         <div>
            <?php            
                if(isset($ispisiUpisane)){
                    echo $ispisiUpisane;
                }
            ?>     
        </div> 

<div <?php if(isset($sakrij)) echo "style= " . '"' . $sakrij . '";' ?> >
            <section>           
                <form id="lojalnost"  method = "POST" action="lojalnost.php"> 
                    <div style="display: inline-block;">
                        <select id="zaSelect" name="zaSelect" style="margin-left: 0px; width: auto;" name="odabraniProgram">
                            <option value="datumSilazno"> Po datumu silazno
                            </option>;
                            <option value="datumUzlazno"> Po datumu uzlazno
                            </option>;
                            <option value="trenutnoBodova"> Po trenutnim bodovima 
                            </option>  
                            <option value="potrosenimBodovima"> potrošenim bodovima </option>
                            <option value="skupljenimBodovima"> skupljenim bodovima </option>                         
                        </select>


                        <select id="osoba" name="odabranaOsoba">
                        <option value="0"> </option>
                          <?php
                            include_once("baza.class.php");
                            $db = new Baza();
                            $db->spojiDB();
                            $upit = "SELECT korisnik_id, ime, prezime
                                      FROM  `korisnik`;";
                            $odgovor = $db->selectDB($upit);               
                           
                            while (list($korisnik_id, $ime, $prezime) = $odgovor->fetch_array()) 
                                    {
                                        echo '<option value="'. $korisnik_id .'">' . $ime . " " . $prezime.'</option>';
                                    }
                           $db->zatvoriDB();
                          ?>     
                        </select>

                    
 
                    <input  type="submit" value="Pretrazi" name="pretrazi"> 
                    <br>
                    
                </form>                       
            </section>
        </div>


        <?php
            if(isset($ispisi)){
                $sakrij = "display: none;";
                echo $ispisi;
            }   
        ?>
        
    </div>
       <?php 
            include 'footer.php';
         ?>

    </body>
</html>