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


    include_once("baza.class.php");
    $db = new Baza();
    $db->spojiDB();

    $nadeni = "";
    $upit = "SELECT  `korisnik_id` ,  `ime` ,  `prezime` , korisnicko_ime
              FROM korisnik
              WHERE  `broj_pokusaja` = 3; ";

    $odgovor = $db->selectDB($upit);               
    if($odgovor->num_rows != 0){
       while (list($id, $ime, $prezime, $korisnickoIme) = $odgovor->fetch_array()) 
            {
                $nadeni .= "<option value='$id'>". $ime . " " . $prezime . ", " . $korisnickoIme . "</option>";
            }
            echo $nadeni;
    }
    else{
      $sakrij = "display:none";
      $greska = "Nemate koga otključati!";
  }

}

if(isset($_POST["submit"]))
{
    $greska = "";
    if(isset($_POST['zakljucaniKorisnici']))
    {
        $zakljucan = $_POST["zakljucaniKorisnici"];
    }


        
    if(empty($greska))
    {
        
        $upit2 = "UPDATE `korisnik` SET zakljucan = '0'  WHERE korisnik_id='$zakljucan'";
        $odgovor = $db->updateDB($upit2);
        $upitBroj = "UPDATE `korisnik` SET broj_pokusaja = 0  WHERE korisnik_id='$zakljucan'";
        $odgovor = $db->updateDB($upitBroj);
        $greska .= "Profil korisnika je sada otkljucan <br>";
        $greska .= "<a href=otkljucavanje_korisnika.php> Želim otključati još profila </a> ";
        $db->zatvoriDB();
    }
    
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Otkljucavanje korisnika</title>
        <meta charset="utf-8">
        <meta name="author" content="Matea">
        <meta name="keywords" content="popis_proizvoda, izlist">
        <meta name="description" content="Stranica je rađena 06.03.2017">

         <link href="css/osnova.css" rel="stylesheet" type="text/css">
         
	</head>
	<body>
		<header>
        <figure style="margin: 0px">
            <figcaption class="naslov"> Otključavanje korisnika </figcaption>
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

     
         <?php 
          if(isset($greska)) 
          {
              echo $greska;
              $sakrij = "display: none;";
          }
      ?>
      
   
    <div <?php  if(isset($sakrij)) echo "style= " . '"' .  $sakrij . '";' ?> >
        <form name="otkljucaj" action="otkljucavanje_korisnika.php" method="POST" >
             <select id="zakljucaniKorisnici" name="zakljucaniKorisnici">
                <?php
               echo $nadeni;
                ?>
            </select>
            <input id="submit" type="submit" name="submit" value="Otkljucaj profil"> <br>
        </form>
    </div>
      
        
      <?php 
        include 'footer.php';
       ?>
    </body>
</html>

