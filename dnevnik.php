<?php
session_start();

header('Content-Type: text/html; charset=utf-8');
$greska = "";
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



if(isset($_POST["svi"])){
    header("Location: dnevnik.php");
    exit();
}

if (isset($_POST["pretrazi"])) 
{
    include("baza.class.php");
    $db = new Baza();
    $db->spojiDB();
    $odabrani = $_POST['zaSelect'];
    $danas = date("Y-m-d H:i:s");

    if(isset($_POST['datumOd']))
    {
        $DatumOd = date( "Y-m-d H:i:s", strtotime($_POST['datumOd']));
    }    

    if(isset($_POST['datumDo']))
    {
        $DatumDo = date("Y-m-d H:i:s",strtotime($_POST['datumDo']));
    }

    if($DatumOd > $DatumDo){
        $greska .= "Nešto je pošlo po zlu. Datum od mora biti manji on datuma do kojeg želite pretražiti rezultate!";
    }
    
    if($DatumDo > $danas or $DatumOd > $danas ) {
            $greska .= "Ne možemo prikazati datume iz budućnosti!";
    }
    

    $ispisi = " ";



    if($odabrani == "datumUzlazno")
    {
        $orderBy = " order by d.`datum` ";
        $ispisi .= "<h2> Datum je prikazan uzlasno   <h2> <br>";
    }
    else if($odabrani == "datumSilazno")
    {
        $orderBy = " order by d.`datum` desc";
        $ispisi .= "<h2>  Datum je prikazan silazno <h2> <br>;";
    }
    else
    {
        $orderBy = " ";
    }

    //datum
    

    if(empty($greska))
    {
   
        if($_POST["osoba"] >= 1)
        {
            $osoba = $_POST["osoba"];
            $ispisi = "";

            $osobaIme = "select ime, prezime from korisnik where korisnik_id = " . $osoba . ";";
            $pronadi = $db->selectDB($osobaIme);
             while ((list($korisnikIme, $korisnikPrezime) = $pronadi->fetch_array()) )
                {
                    $ispisi .= "<h2> $korisnikIme " . " " . "$korisnikPrezime  </h2>";
                }

            if(empty($DatumOd) && empty($DatumDo))
            {
                $upit = "SELECT d.opis, d.datum, k.ime, k.prezime, tp.naziv
                     FROM  `dnevnik` d
                     JOIN korisnik k ON k.korisnik_id = d.korisnik
                     JOIN tipovi_akcija tp on tp.id=d.tip_akcije
                     WHERE d.korisnik = $osoba $orderBy ;";
            }
            else if(!empty($DatumOd))
            { 
                if(empty($DatumDo))
                {
                  
                $upit = "SELECT d.opis, d.datum, k.ime, k.prezime, ta.naziv
                            FROM  `dnevnik` d
                            JOIN korisnik k ON k.korisnik_id = d.korisnik
                            JOIN tipovi_akcija ta on ta.id = d.tip_akcije
                            WHERE d.korisnik =  " . $osoba . " and d.datum >=  " . $DatumOd . " and d.datum < " . $DatumDo . " order by 2 ;";
                }
                else
                {
                    $upit = "SELECT d.opis, d.datum, k.ime, k.prezime, ta.naziv
                                FROM  `dnevnik` d
                                JOIN korisnik k ON k.korisnik_id = d.korisnik
                                JOIN tipovi_akcija ta on ta.id = d.tip_akcije
                                WHERE d.korisnik =  
                            '$osoba' and d.datum > '$DatumOd' order by 2 desc;";
                }
            }
            else if (!empty($DatumDo)) 
            {
                $upit = "SELECT d.opis, d.datum, k.ime, k.prezime, ta.naziv
                        FROM  `dnevnik` d
                        JOIN korisnik k ON k.korisnik_id = d.korisnik
                        JOIN tipovi_akcija ta on ta.id = d.tip_akcije
                        WHERE d.korisnik =  '$osoba' and d.datum <  '$DatumDo'  order  by 2 ;";
            }

            $odgovor = $db->selectDB($upit);  

           
            $ispisi .=  "<table class='tablica'> <th> Opis </th> <th>  Datum  </th> ";
             while (list( $opis, $datum, $korisnikIme, $korisnikPrezime, $tip_akcije) = $odgovor->fetch_array()) 
                {
                    $ispisi .= "<tr>
                                    <td>" . $opis . "
                                    </td> 
                                    <td>" . $datum . "</td> 
                                    <td> " . $tip_akcije . "</td>
                                </tr>"; 
                }
            $ispisi .= "</table>";       
        }
        else if($_POST['osoba'] < 1 && empty($DatumOd) && empty($DatumDo))
        {
            header("Location: dnevnik.php");
            exit();
        }
        else
        {
            if(empty($DatumOd) && empty($DatumDo))
            {
                $upit = "SELECT d.opis, d.datum, k.ime, k.prezime, ta.naziv
                     FROM  `dnevnik` d
                     JOIN korisnik k ON k.korisnik_id = d.korisnik
                     JOIN tipovi_akcija tp on tp.id=d.tip_akcije  $orderBy ;";
            }
            else if(!empty($DatumOd))
            {  
                if(!empty($DatumDo))
                {
                $upit = "SELECT d.opis, d.datum, k.ime, k.prezime, ta.naziv
                            FROM  `dnevnik` d
                            JOIN korisnik k ON k.korisnik_id = d.korisnik
                            JOIN tipovi_akcija ta on ta.id = d.tip_akcije
                            WHERE d.datum >=  " . $DatumOd . " and d.datum < " . $DatumDo . " order by 2 ;";
                }
                else
                {
                    $upit = "SELECT d.opis, d.datum, k.ime, k.prezime, ta.naziv
                                FROM  `dnevnik` d
                                JOIN korisnik k ON k.korisnik_id = d.korisnik
                                JOIN tipovi_akcija ta on ta.id = d.tip_akcije
                                WHERE d.datum >=  " . $DatumOd . "order by 2;";
                }
            }
            else if (!empty($DatumDo)) 
            {
                $upit = "SELECT d.opis, d.datum, k.ime, k.prezime, ta.naziv
                        FROM  `dnevnik` d
                        JOIN korisnik k ON k.korisnik_id = d.korisnik
                        JOIN tipovi_akcija ta on ta.id = d.tip_akcije
                        WHERE d.datum <  " . $DatumDo . " order by 2;";
            }
            $odgovor = $db->selectDB($upit);  

           
            $ispisi .=  "<table class='tablica'> <th> Opis </th> <th>  Datum  </th> ";
             while ((list( $opis, $datum, $korisnikIme, $korisnikPrezime, $tip_akcije) = $odgovor->fetch_array()) )
                {

                    $ispisi .= "<tr>
                                    <td>" . $opis . "
                                    </td> 
                                    <td>" . $datum . "</td> 
                                    <td> " . $tip_akcije . "</td>
                                </tr>"; 
                }
            $ispisi .= "</table>";      

        }
   }
    $db->zatvoriDB();
}
else
{
    include("baza.class.php");
    $db = new Baza();
    $db->spojiDB();

    $upit = "SELECT d.opis, d.datum, k.ime, k.prezime, ta.naziv
            FROM  `dnevnik` d
            JOIN korisnik k ON k.korisnik_id = d.korisnik
            JOIN tipovi_akcija ta on ta.id=d.tip_akcije";
    $odgovor = $db->selectDB($upit);  

   
    $ispisi =  "<table class='tablica'> <th> Opis </th> <th> Datum </th> ";
    while ((list( $opis, $datum, $korisnikIme, $korisnikPrezime, $tip_akcije) = $odgovor->fetch_array()) )
    {
        $ispisi .= "<tr>
                        <td> ". $korisnikIme . ' ' . $korisnikPrezime ."
                        </td>
                        <td>" . $opis . "
                        </td> 
                        <td>" . $datum . "
                        </td>
                        <td> " . $tip_akcije . " </td>
                    </tr>"; 
    }

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
        <link href="css/osnova.css" rel="stylesheet" type="text/css">
         
	</head>
	<body>
		<header>
            <figure style="margin: 0px">
                <figcaption class="naslov"> Dnevnik </figcaption>
                <img src="slike/skok.jpg" class="prvaSlika" alt="Logo skok" usemap="#mapa1"/>
                <map name="mapa1">
                    <area href="index.html" alt="index" shape="rect" target="_blank" coords="0,0,200,200"/>
                    <area href="#o_meni" alt="o_meni" shape="rect" target="_parent" coords="200,0,400,200"/>
                </map>
            </figure>
        </header>
   
 <?php
      include 'nav.php';
   ?>


      <?php 
            if(isset($greska)) 
            {
                echo $greska;
            }
        ?>

        <div style="display: inline-block;">
        <div>
            <section>           
                <form id="popis_proizvoda" method = "POST" name="popis_proizvoda" action="dnevnik.php"> 
                    <div style="display: inline-block;">
                        <label for="osoba" id="lblOsoba"> Odaberite osobu: </label> 
                        <select style="margin-left: 0px; width: auto;" name="osoba">
                            <option value="0"> </option>
                            <?php 
                                include_once("baza.class.php");
                                $db = new Baza();
                                $db->spojiDB();
                                $upit = "SELECT * 
                                        FROM korisnik;";
                                $odgovor = $db->selectDB($upit);               
                               
                                while (list($id, $ime, $prezime) = $odgovor->fetch_array()) 
                                        {
                                            echo '<option value="'. $id .'">'.' '.$ime.' '.$prezime.'</option>';
                                        }
                               $db->zatvoriDB();
                            ?>
                        </select>
                        <br>
                        <label> Sortiraj po: </label>
                        <select id="zaSelect" name="zaSelect" style="margin-left: 0px; width: auto;" name="odabraniProgram">
                            <option value=" "> </option>
                            <option value="datumUzlazno"> Po datumu uzlazno
                            </option>;
                            <option value="datumSilazno"> Po datumu silazno 
                            </option>                        
                        </select>
                        <br>

                        <label for="datumOd" id="lblDatumOd"> Od: </label>
                        <input type="datetime-local" name="datumOd" id="datumOd">
                        <br>
                        <br>

                        <label for="datumDo" id="lblDatumDo"> Od: </label>
                        <input type="datetime-local" name="datumDo" id="datumDo">
                    </div>
                    <br>
                    <br>
                    <input  type="submit" value="pretrazi" name="pretrazi">
                    <input type="submit" name="svi" value="svi">
                </form>                       
            </section>
       
        <div>
            <?php            
                if(isset($ispisi)){
                    echo $ispisi;
                }   
            ?>     
        </div> 
    </div>
    </div>


    <?php 
        include 'footer.php';
    ?>
    </body>
</html>