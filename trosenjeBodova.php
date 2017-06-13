<?php
    session_start();

    header('Content-Type: text/html; charset=utf-8');

    if (!isset($_SESSION['korisnickoIme'])) {
        $greska.= "Morate biti prijavljeni";
        header("Location:prijava.php");
        exit();
    }
    else if($_SESSION['tip_korisnika'] != 2){
         header("Location:indeks.php");
        exit();
    }
    else{
        $korisnik = $_SESSION['korisnik_id'];
        $korisnikIme = $_SESSION['korisnickoIme'];
        $tipKorisnika = $_SESSION['tip_korisnika'];
    }

if (isset($_POST["pretrazi"])) {
    $orderBy = "";
    $ispisi="";
    $odabrani = $_POST['zaSelect'];
    $odabranaOsoba = $_POST['odabranaOsoba'];
    $nadiOsobu = "";

    include("baza.class.php");
    $db = new Baza();
    $db->spojiDB();


    if($odabrani == "sve" && $odabranaOsoba == 0){
        $ispisi = "<h2> Korisnici su radili akcije  <h2>";
        $korisniciAkcije = "SELECT a.naziv, a.opis, k.ime, k.prezime, ak.`datum`, a.broj_bodova 
                            FROM  `akcija_korisnik` ak
                            JOIN korisnik k ON k.korisnik_id = ak.`korisnik_id` 
                            JOIN akcija a ON ak.`akcija_id` = a.id";
        $sveRadnje = $db->selectDB($korisniciAkcije);

        $ispisi .=  "<h2> KORISNIK - AKCIJE  <h2>
                        <table class='tablica'>
                         <th> Ime i prezime </th> 
                         <th> Akcija </th>
                         <th> Opis akcije  </th>
                         <th> Datum izvršavanja </th>
                         <th> Broj bodova akcije </th>";

    
        if($sveRadnje->num_rows != 0){
        while (list($aNaziv, $aOpis, $ime, $prezime, $datum_izmjene, $bodovi) = $sveRadnje->fetch_array()){       
                     $ispisi .= "<tr> <td > " . $ime . " " . $prezime . "</td> ";

                    $ispisi .= "                      
                                    <td> " . $aNaziv."
                                    <td>" . $aOpis. "
                                    </td> 
                                    <td>" . $datum_izmjene. "

                                    <td>" . $bodovi . "
                                    </td>"; 
                                     
                    } 
                }else{
                    $ispisi .= " <td style='width:100%'> Nemamo podatke o kotisniku! </td> ";
                }     
    }
    else{
        if($odabrani == "datumUzlazno"){
            $orderBy = "order by ak.`datum` ";
            $ispisi .= "<h2> KORISNIK - AKCIJE   <h2> <br> <h2> datum uzlazno </h2>";
        }
        else if($odabrani == "datumSilazno"){
            $orderBy = "order by ak.`datum`  desc";
            $ispisi .= "<h2> KORISNIK - AKCIJE   <h2> <br> <h2> datum silazno </h2>";
        }

        if($odabranaOsoba != 0){
                $korisnikNaden = "SELECT ime, prezime
                                    FROM  `korisnik` 
                                    WHERE  `korisnik_id`='$odabranaOsoba';";
                $sveRadnje = $db->selectDB($korisnikNaden);
                $nadiOsobu = "where ak.korisnik_id= '$odabranaOsoba' ";
                while (list( $ime, $prezime) =$sveRadnje->fetch_array()){
                    $ispisi .= "<h1 style='float:center'> $ime $prezime </h1>";
            }
        }

        $korisniciAkcije = "SELECT a.naziv, a.opis, k.ime, k.prezime, ak.`datum` , a.broj_bodova
                            FROM  `akcija_korisnik` ak
                            JOIN korisnik k ON k.korisnik_id = ak.`korisnik_id` 
                            JOIN akcija a ON ak.`akcija_id` = a.id "  . $nadiOsobu . " " . $orderBy . " ;";
        $sveRadnje = $db->selectDB($korisniciAkcije);

        $ispisi .=  "<h2> KORISNIK - AKCIJE  <h2>
                        <table class='tablica'>
                         <th> Ime i prezime </th> 
                         <th> Akcija </th>
                         <th> Opis akcije  </th>
                         <th> Datum izvršavanja </th>
                         <th> Broj bodova akcije </th>";

    
        if($sveRadnje->num_rows != 0){
        while (list($aNaziv, $aOpis, $ime, $prezime, $datum_izmjene, $bodovi) = $sveRadnje->fetch_array()){       
                     $ispisi .= "<tr> <td > " . $ime . " " . $prezime . "</td> ";

                    $ispisi .= "                      
                                    <td> " . $aNaziv."
                                    <td>" . $aOpis. "
                                    </td> 
                                    <td>" . $datum_izmjene. "
                                    <td>" . $bodovi . "
                                    </td>"; 
                    } 
                }else{
                    $ispisi .= " <td style='width:100%'> Nemamo podatke o kotisniku! </td> ";
                }     
    }
    
    $ispisi .= "</tr> </table>";  
    $db->zatvoriDB();   
}
else{
    echo $korisnik;
    include_once("baza.class.php");
    $db = new Baza();
    $db->spojiDB();
    $ispisi = "<h2> Korisnici su skupljali bodove na akcijama  <h2>";
    $korisniciAkcije = "SELECT a.naziv, a.opis, k.ime, k.prezime, ak.`datum`, a.broj_bodova 
                            FROM  `akcija_korisnik` ak
                            JOIN korisnik k ON k.korisnik_id = ak.`korisnik_id` 
                            JOIN akcija a ON ak.`akcija_id` = a.id ;";
        $sveRadnje = $db->selectDB($korisniciAkcije);

        $ispisi .=  "<h2> KORISNIK - AKCIJE  <h2>
                        <table class='tablica'>
                         <th> Ime i prezime </th> 
                         <th> Akcija </th>
                         <th> Opis akcije  </th>
                         <th> Datum izvršavanja </th>
                         <th> Broj bodova akcije </th>";

    
        if($sveRadnje->num_rows != 0){
        while (list($aNaziv, $aOpis, $ime, $prezime, $datum, $bodovi) = $sveRadnje->fetch_array()){  
                    $ispisi .= "<tr> <td > " . $ime . " " . $prezime . "</td> ";

                    $ispisi .= "                      
                                    <td> " . $aNaziv ."
                                    <td>" . $aOpis . "
                                    </td> 
                                    <td>" . $datum . "
                                    </td>
                                    <td>" . $bodovi . "
                                    </td>"; 
                    } 
                }else{
                    $ispisi .= " <td style='width:100%'> Nemamo podatke o kotisniku! </td> ";
                }     
    $ispisi .= "</tr> </table>";   
    $db->zatvoriDB();   
}

 
?>

<html>
	<head>
		<title> Trošenje bodova </title>
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
                <figcaption class="naslov"> Korisnik - akcije </figcaption>
                <img src="slike/skok.jpg" class="prvaSlika" alt="Logo foi-a" usemap="#mapa1"/>
                <map name="mapa1">
                    <area href="index.html" alt="index" shape="rect" target="_blank" coords="0,0,200,200"/>
                    <area href="#o_meni" alt="o_meni" shape="rect" target="_parent" coords="200,0,400,200"/>
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
                <form  method = "POST" action="trosenjeBodova.php"> 
                    <div style="display: inline-block;">
                        <select id="zaSelect" name="zaSelect" style="margin-left: 0px; width: auto;" name="odabraniProgram">
                            <option value="sve"> Pregled svih akcija korisnika
                            </option>;
                            <option value="datumUzlazno"> Po datumu uzlazno
                            </option>;
                            <option value="datumSilazno"> Po datumu silazno 
                            </option>                           
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
                    <input type="submit" name="sve" value="Prikaži sve">
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