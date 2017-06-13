<?php
session_start();

header('Content-Type: text/html; charset=utf-8');

if (!isset($_SESSION['korisnickoIme'])) {
    $ispisi .= "Morate biti prijavljeni";
    header("Location:prijava.php");
    exit();
}

if(isset($_POST["sortirajUzlazno"])){
    $order = " SORT BY datum";
}
else if(isset($_POST["sortirajSilazno"])){
    $order = "SORT BY datum  desc";
}
else{
    $order = " ";
}

$korisnickoIme = $_SESSION['korisnickoIme'];
$korisnik = $_SESSION['korisnik_id'];

   

if (isset($_POST["pretrazi"])) {
    include("baza.class.php");
    $db = new Baza();
    $db->spojiDB();


    $datum = $_POST['datum'];
    //datum
    $danas = date("Y-m-d H:i:s");
   
   /*
    if(!empty($datum)){
        if(preg_match( '/^(0[1-9]|[12][0-9]|3[01])[ .](0[1-9]|1[012])[.](19|20)\d\d/', $datum)){
            if(strtotime($datum) == FALSE){
                $greska .= "Datum je u pogresnom formatu!! <br>";
            }
            else 
            {
                $danas = strtotime($danas);
                $uneseni = strtotime($datum);
                
                if($uneseni > $danas){
                    $greska .= "Datum mora biti manji ili jednak sadašnjem!! <br>";
                }
            }
        }
        else {
            $ispisi = "Datum je u pogresnom formatu!";
        }
    }  
    */

    if(!empty($datum)){
        $ispisi = "";

        $osobaIme = "select ime, prezime from korisnik where korisnik_id = " . $korisnik . ";";
        $pronadi = $db->selectDB($osobaIme);
         while ((list($korisnikIme, $korisnikPrezime) = $pronadi->fetch_array()) )
            {
                $ispisi .= "<h2> $korisnikIme" . " " . "$korisnikPrezime  </h2>";
            }

        $upit = "SELECT `skupljeno`, `potorseno`, `datum_izmjene`
                 FROM `evidencija_bodova` 
                 WHERE `korisnik_korisnik_id` = '$korisnik' and datum_izmjene = '$datum' ;";
        $odgovor = $db->selectDB($upit);  

       
        $ispisi .=  "<table class='tablica'> <th> Datum </th> <th> Skupljeno </th> <th> Potroseno </th> ";
         while ((list( $skupljeno, $potorseno, $datum_izmjene) = $odgovor->fetch_array()) )
            {
                $ispisi .= "<tr>
                                 <td>" . $skupljeno . "
                                </td> 
                                 <td>" . $potorseno . "
                                </td>                                     
                                <td>" . $datum_izmjene . "
                                </td> 
                            </tr>"; 
            }
        $ispisi .= "</table>";      
    }
    $db->zatvoriDB();
}
else {
    include("baza.class.php");
    $db = new Baza();
    $db->spojiDB();
    $ispisi = "  ";



    $osobaIme = "select ime, prezime from korisnik where korisnik_id = " . $korisnik . ";";
    $pronadi = $db->selectDB($osobaIme);
     while ((list($korisnikIme, $korisnikPrezime) = $pronadi->fetch_array()) )
        {
            $ispisi .= "<h2> $korisnikIme" . " " . "$korisnikPrezime  </h2>";
        }

    $upit1 = "SELECT  `skupljeno` ,  `potorseno` ,
    `datum_izmjene` ,  `korisnik_korisnik_id` 
        FROM  `evidencija_bodova` eb
        JOIN korisnik k ON k.korisnik_id = eb.`korisnik_korisnik_id` 
        WHERE k.korisnik_id = $korisnik
        ORDER BY eb.`datum_izmjene` desc limit 1"; 
    $odgovor = $db->selectDB($upit1);  


    while ((list($skupljeno, $potorseno, $datum_izmjene) = $odgovor->fetch_row()) )
        { 
            $Trenutno = ($skupljeno - $potorseno);
            $ispisi .= "<h4> Trenutno: $Trenutno  </h4>";
        }

    $upit = "SELECT `skupljeno`, `potorseno`, `datum_izmjene`
             FROM `evidencija_bodova` 
             WHERE `korisnik_korisnik_id` = '$korisnik';";
    $odgovor = $db->selectDB($upit);  

   
    $ispisi .=  "<table class='tablica'> <th> Skupljeno  </th> <th> Potroseno </th> <th> Datum promijene </th>";
     while (list($skupljeno, $potorseno, $datum_izmjene) = $odgovor->fetch_array() )
        {
            $ispisi .= "<tr>
                             <td>" . $skupljeno . "
                            </td> 
                             <td>" . $potorseno . "
                            </td>                                     
                            <td>" . $datum_izmjene . "
                            </td> 
                        </tr>"; 
        }
    $ispisi .= "</table>";


    

    $db->zatvoriDB();        
}
   
?>

<html>
	<head>
		<title> Evidencija bodova </title>
        <meta charset="utf-8">
        <meta name="author" content="Matea">
        <meta name="keywords" content="popis_proizvoda, izlist">
        <meta name="description" content="Stranica je rađena 30.04.2017">

        <link href="css/osnova.css" rel="stylesheet" type="text/css">
         
	</head>
	<body>
		<header>
            <figure style="margin: 0px">
                <figcaption class="naslov"> Evidencija bodova </figcaption>
                <img src="slike/skok.jpg" class="prvaSlika" alt="Logo foi-a" usemap="#mapa1"/>
                <map name="mapa1">
                    <area href="index.html" alt="index" shape="rect" target="_blank" coords="0,0,200,200"/>
                    <area href="#glavniDio" alt="o_meni" shape="rect" target="_parent" coords="200,0,400,200"/>
                </map>
            </figure>
        </header>

  <?php
      include 'nav.php';
   ?>


           
        <div>
            <section id="glavniDio">           
                <form id="popis_proizvoda" method = "POST" name="popis_proizvoda" action="prikupljanjeBodova.php"> 
                    <div style="display: inline-block;">
                        <label for="datum" id="lblDatum"> Upisite datum: </label>
                        <input type="text" name="datum" id="datum" placeholder="2017-01-01">
                    </div>
 
                    <input  type="submit" value="Pretrazi" name="pretrazi">
                    <a href="vidiKupone.php"> Želim vidjeti aktivne kupone </a>
                </form>                       
            </section>
        </div>
        
        <div>
            <?php            
                if(isset($ispisi)){
                    echo $ispisi;
                }   
            ?>     
        </div> 

        <?php 
            include 'footer.php';
        ?>
    </body>
</html>