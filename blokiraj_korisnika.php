<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
session_start();

header('Content-Type: text/html; charset=utf-8');
/*
 include("baza.class.php");
$db = new Baza();
$db->spojiDB();
$upit = "SELECT k.korisnik_id, k.ime, k.Prezime
FROM  `korisnik` k
JOIN tip_korisnika tk ON k.tip_korisnika = tk.`tip_korisnika_id` 
WHERE k.`tip_korisnika` = 2";


/*
( 
SELECT  `tip_korisnika_id` 
FROM tip_korisnika
WHERE  `naziv` = " .  '"' . "administrator" . "'" . " ) ";  


while (list($idZaKupiti,  $kupNaziv, $kupOpis, $vrijediDo, $brojKupljenih, $nazivPrograma) = $odgovor->fetch_array() ){

    */

if (!isset($_SESSION['korisnickoIme'])){
    header("Location:index.php");
   
    exit();
}
else if($_SESSION['tip_korisnika'] != 2){
       header("Location:index.php");
       exit();
}
else{
    $sakrij = " ";
    $korisnikIme = $_SESSION["korisnickoIme"];
    $korisnik = $_SESSION['korisnik_id'];
    $tipKorisnika = $_SESSION['tip_korisnika'];
}


if(isset($_POST["submit"])){
    $greska = "";

    if(isset($_POST['sviKorisnici'])){
        $blokiran = $_POST["sviKorisnici"];
    }
  
        
    if(empty($greska))
    {
        include("baza.class.php");
        $db = new Baza();
        $db->spojiDB();
        $upit2 = "UPDATE `korisnik` SET blokiran = 1  WHERE korisnik_id ='$blokiran'";
        $odgovor = $db->updateDB($upit2);
        
        $greska .= "Profil korisnika je sada BLOKIRAN <br>";

         //DNEVNIK
            $datum = date("Y-m-d H:i:s");
            $korisnik = $_SESSION['korisnik_id'];

            $radnja = "Korisnika " . $blokiran . " ste blokirali! ";
            $dnevnik = "INSERT INTO `dnevnik`(  `korisnik`, `datum`, `Opis`, `tip_akcije`)  values  ('$korisnik','$datum ',' $radnja ', 4)";
            $db->selectDB($dnevnik);

        
        $db->zatvoriDB();
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title> BLOKIRANJE </title>
        <meta charset="utf-8">
        <meta name="author" content="Matea">
        <meta name="keywords" content="popis_proizvoda, izlist">
        <meta name="description" content="Stranica je rađena 06.03.2017">

         <link href="css/osnova.css" rel="stylesheet" type="text/css">
         
	</head>
	<body>
		<header>
        <figure style="margin: 0px">
            <figcaption class="naslov"> BLOKIRANJE KORISNIKA </figcaption>
            <img src="slike/logo.png" class="prvaSlika" alt="Logo foi-a" usemap="#mapa1"/>
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
                $sakrij = "display: none;";
            }
        ?>
        
   
<div <?php  if(isset($sakrij)) echo "style= " . '"' .  $sakrij . '";' ?> >
            <form name="otkljucaj" action="blokiraj_korisnika.php" method="POST" >
            <label for="sviKorisnici"> Ime i prezime, korisničko ime korisnika: </label>
                 <select id="sviKorisnici" name="sviKorisnici">
                    <?php
                    include("baza.class.php");
                    $db = new Baza();
                    $db->spojiDB();
                    $upit = "SELECT `korisnik_id`, `ime`, `prezime`, `korisnicko_ime` FROM `korisnik`";
                    $odgovor = $db->selectDB($upit);               
                   
                    while (list($id, $ime, $prezime, $korisnickoIme) = $odgovor->fetch_array()) 
                            {
                                echo '<option value="'.$id.'">'.$ime . " " . $prezime . ", " . $korisnickoIme . '</option>';
                            }
                   
                    $db->zatvoriDB();
                    ?>
                </select>
                <input id="submit" type="submit" name="submit" value="Blokiraj "> <br>
            </form>
        </div>
        
     <?php 
            include 'footer.php';
         ?>
    </body>
</html>

