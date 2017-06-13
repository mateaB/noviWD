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
if(isset($_POST["pretrazi"])){
    include("baza.class.php");
    $db = new Baza();
    $db->spojiDB();
    $odabraniProgram = $_POST["odabraniProgram"];
    echo "blaaaa $odabraniProgram";

    $kuponIdDistinct = "SELECT DISTINCT kupon, vlasnik_kosarice FROM  `kosarica` where  vlasnik_kosarice= $korisnik";
    $dohvatiDistinct = $db->selectDB($kuponIdDistinct); 

    $ispisiUpisane = " Trenutno u košarici imate: <br> <br>";
                    
    $ispisiUpisane .=  "<table class='tablica'> <th> Naziv kupona </th> <th> Opis kupona </th> <th> Vrijedi do </th> <th> Trenutno u kosarici </th> <th> Program </th>";

    while (list($odabraniKupon, $vlasnik) = $dohvatiDistinct->fetch_array() )
    {
        $upit = "SELECT kp.id, k.naziv, k.opis, kp.do, COUNT( * ), p.naziv
                FROM  `kuponi` k
                JOIN kupon_programi kp ON kp.kuponi_id = k.id
                JOIN program p ON p.id = kp.program_id
                JOIN kosarica kos ON kos.kupon = kp.id
                WHERE kp.id =$odabraniKupon and kos.vlasnik_kosarice=$korisnik and kos.status_kupnje_id=3 and p.id=$odabraniProgram
                GROUP BY kp.id;";
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
    $db->zatvoriDB();
}
else{
    include("baza.class.php");
    $db = new Baza();
    $db->spojiDB();
    echo "blaaaa";
    $kuponIdDistinct = "SELECT DISTINCT kupon, vlasnik_kosarice FROM  `kosarica` where  vlasnik_kosarice= $korisnik";
    $dohvatiDistinct = $db->selectDB($kuponIdDistinct); 

    $ispisiUpisane = " Trenutno u košarici imate: <br> <br>";
                    
    $ispisiUpisane .=  "<table class='tablica'> <th> Naziv kupona </th> <th> Opis kupona </th> <th> Vrijedi do </th> <th> Trenutno u kosarici </th> <th> Program </th>";

    while (list($odabraniKupon, $vlasnik) = $dohvatiDistinct->fetch_array() )
    {
        $upit = "SELECT kp.id, k.naziv, k.opis, kp.do, COUNT( * ), p.naziv
                FROM  `kuponi` k
                JOIN kupon_programi kp ON kp.kuponi_id = k.id
                JOIN program p ON p.id = kp.program_id
                JOIN kosarica kos ON kos.kupon = kp.id
                WHERE kp.id =$odabraniKupon and kos.vlasnik_kosarice=$korisnik and kos.status_kupnje_id=3 
                GROUP BY kp.id;";
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
    $db->zatvoriDB();

}
   

?>

<html>
	<head>
		<title> Košarica </title>
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
                <figcaption class="naslov"> Košarica </figcaption>
                <img src="slike/logo.png" class="prvaSlika" alt="Logo foi-a" usemap="#mapa1"/>
                <map name="mapa1">
                    <area href="index.php" alt="index" shape="rect" target="_blank" coords="0,0,200,200"/>
                    <area href="#popisPrograma" alt="o_meni" shape="rect" target="_parent" coords="200,0,400,200"/>
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

<div>
            <section>           
                <form id="popisPrograma" method = "POST" name="popisPrograma" action="kosarica.php"> 
                    <div style="display: inline-block;">
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

                    </div>
 
                    <input  type="submit" value="Pretrazi" name="pretrazi">
                </form>                       
            </section>

        
        </div>
 
        <?php 
            include 'footer.php';
         ?>

    </body>
</html>