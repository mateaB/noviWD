<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$osvjezi = "disabled";
$nakon5min = " ";

// set Cookie
$naziv = "guest";
$id = "1";
$vrijeme_ulaska = time();
$petMinuta = strtotime( $vrijeme_ulaska + 60*5);
$vrijedi_do = time() + 60*10; // vrijedi 10 min

IF(!isset($_COOKIE[$naziv])){
setcookie($naziv, $id,  $vrijedi_do);
echo ''; "<b>Cookie:</b> $naziv <b>vrijedi do:</b> $vrijedi_do.\n";
}
 else {
    unset($_COOKIE[$naziv]);
            setcookie($naziv, $id, time() - 3600);
}
$potvrda = "";



if(isset($_POST["submit"])){
        
$naziv = $_POST["naziv"];
$opis = $_POST["opis"]; 
$vrijeme = $_POST["Vrijeme"];
$datum = $_POST['datum'];
$kolicina = $_POST["kol"];
$kategorija = $_POST["kategorija"];
$tezina = $_POST["tezina"];
$greska = "";
$usklicnikNaziv = "!";
$usklicnikOpis = "!";
$usklicnikDatum = "!";
$usklicnikKategorija = "!";
$usklicnikVrijeme = " ";
$usklicnikKol = " ";
if(empty($kolicina)){
    $usklicnikKol = "!";
}

 function provjeraZnakova($unos) {
        $znakovi = ["(", ")", "{", "}", "'", "!", "#", "“", "\\", "/"];
        for ($i = 0; $i < count($znakovi); $i++) {
            $nema = strpos($unos, $znakovi[$i]);
            if ($nema === false) {  
            } 
            else {
                return true;
            }
        }
        return false;
    }
    
    $polje=[$naziv, $opis, $datum, $vrijeme, $kolicina];
    $usklicnik = [$usklicnikNaziv, $usklicnikOpis, $usklicnikDatum, $usklicnikVrijeme, $usklicnikKol];
    for ($i=0; $i < 4 ;$i++)
    {
        if(provjeraZnakova($polje[$i]))
        {
            $greska = "Nedozvoljeni znakovi se pojavljuju <br>";
            
        }
        else {
            $usklicnik[$i] = " ";
        }
    }
    if( empty($opis)  || empty($naziv) || empty($datum) || empty($vrijeme) || empty($kolicina))
       {
           $greska = "Nisu unesena sva polja <br>";
       }

    if(($naziv !== ucfirst($naziv)) ||  (strlen($naziv)< 5)){
             $greska .= "Naziv mora imati najmanje 5 znakova i počinjati velikim slovom <br>";
       }
       else{
           $usklicnikNaziv = " ";
       }

//datum
    $danas = date("Y-m-d H:i:s");
   
    if(!empty($datum)){
        if(preg_match( '/^(0[1-9]|[12][0-9]|3[01])[ .](0[1-9]|1[012])[.](19|20)\d\d/', $datum)){
            if(strtotime($datum) == FALSE){
                $greska .= "Datum je u pogresnom formatu!! <br>";
            }
            else 
            {
                $usklicnikDatum = " ";
                $danas = strtotime($danas);
                $uneseni = strtotime($datum);
                
                if($uneseni > $danas){
                    $greska .= "Datum mora biti manji ili jednak sadašnjem!! <br>";
                    $usklicnikDatum = "!";
                }
            }
        }
        else {
            $greska = "Datum je u pogresnom formatu!";
            $usklicnikDatum = "!";
        }
    }  
    else
    {
        $greska .= "Niste upisali datum! <br>";
    }



    //select --> odabran
    if(isset($kategorija)){ 
        if ($kategorija >= 0) {
            $usklicnikKategorija = " ";
        } 
        else {
          $greska .=  "Morate odabrati kategoriju!";
        }
    }

    //coookie- 5 minuta
    if(isset($_COOKIE[$naziv])){
    while (isset($_COOKIE[$naziv])){
        $sada = strtotime(time());
        if($sada < $_COOKIE[$petMinuta]){
            $nakon5min = " ";
        }
        else{
           $nakon5min = "disabled";
           $osvjezi = "disabled";
        }       
    }
    }
    else{
        $osvjezi = " ";
    }
 
    if (empty($greska))
    {         
        $potvrda = "Dodali ste novi proizvod. ";
        $usklicnikOpis = " ";
        include("baza.class.php");
        $db=new Baza();
        $db->spojiDB();
        $upit2="INSERT INTO `proizvod`(`id`, `naziv`, `opis`, `datum`, `kolicina`, `tezina`, `kategorija`) VALUES ( '$naziv','$opis','$datum' ,'$kolicina','$tezina','$kategorija')";
        $prijenos=$db->updateDB($upit2);
        $upit2="INSERT INTO `dnevnik`( `opis`, `datum`, `korisnik`) VALUES ('$opis','$datum','$naziv')";
        $prijenos=$db->updateDB($upit2);
        $db->zatvoriDB();
        
    }
}   

?>
<!DOCTYPE html>
<html>
	<head>
	<title> Novi program  </title>
        <meta charset="utf-8">
        <meta name="author" content="Matea">
        <meta name="keywords" content="popis_proizvoda, izlist">
        <meta name="description" content="Stranica je rađena 30.04.2017">

         <link href="css/matbodulu.css" rel="stylesheet" type="text/css">
         
	</head>
	<body >
		<header>
        <figure style="margin: 0px">
            <figcaption class="naslov"> Novi program </figcaption>
            <img src="slike/skok.php" class="prvaSlika" alt="Logo foi-a" usemap="#mapa1"/>
            <map name="mapa1">
                <area href="index.php" alt="index" shape="rect" target="_blank" coords="0,0,200,200"/>
                <area href="#noviProgram" alt="o_meni" shape="rect" target="_parent" coords="200,0,400,200"/>
            </map>
        </figure>
    </header>

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
              $ispisi .= "<li> <a href='registracija.php'>  </a> </li>";
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

    
	
        
    <div style="color:black" class="popisGresaka" name="popisGresaka">
    <?php

    if(!empty($potvrda)){
            echo $potvrda;
    $sakrijSve = "display: none";
    }
    
    ?>
    </div>
        
            <div  <?php if(isset($sakrijSve)) echo " style =' $sakrijSve'; " ; ?>  > 
  		
	 <section id="noviProgram" > 
    <h2> Za stvaranje novog proizvoda popunite sljedeće stavke: </h2>
    <form method = "POST" name="novi_proizvod" action="novi_proizvod.php"  novalidate>

        
    <div style="color:black" class="popisGresaka" name="popisGresaka">
    <?php
    if(isset($greska))
    {
    echo $greska;
    }    
    ?>
    </div>
        
    <label for="naziv" id="lblNaziv"> Naziv proizvoda: </label> <br>

    <input type="text" maxlength="30" name="naziv" id="naziv"  <?php 
    echo $nakon5min;
    ?>
           > 
   
    <label id="usklicnikNaziv" name="usklicnikNaziv" > 
    <?php if(isset($usklicnikNaziv)){
    echo $usklicnikNaziv;} ?> </label>
    <br>


    <label for="opis" id="lblOpis"> Opis proizvoda: </label> <br>
    <textarea rows="5" cols="40"  name="opis" id="opis" <?php
    if(isset($nakon5min))
    {
    echo $nakon5min;
    }
    ?>>
    </textarea>  
    <br>
    <label id="usklicnikOpis" style="color:red" name="usklicnikOpis">  <?php if(isset($usklicnikOpis)){
    echo $usklicnikOpis;} ?>
    </label> <br>  

    <label for="datum" id="lblDatum"> Datum: </label> <br>
    <input type="text" name="datum" id="datum" placeholder="11.11.2012.">
    <?php
    if(isset($nakon5min))
    {
    echo $nakon5min;
    }
    ?>
           >
     <br>     
     <label id="usklicnikDatum" style="color: red" name="usklicnikDatum"> 
         <?php if(isset($usklicnikDatum)){
             echo $usklicnikDatum; }
             ?>
    </label> <br>

    <label for="Vrijeme" id="lblVrijeme"> Vrijeme: </label> <br>
    <input type="time" id="Vrijeme" name="Vrijeme" id="lblTime"  
    <?php
    if(isset($nakon5min))
    {
    echo $nakon5min;
    }
    ?>           >
    <label id="usklicnikVrijeme" style="color: red" name="usklicnikVrijeme" > <?php if(isset($usklicnikVrijeme)){
    echo $usklicnikVrijeme;} ?>  </label> <br>    

 <label for="tezina" id="lblTezina"> Težina </label>
 <input type="range" min="0" max="100" name="tezina" id="tezina">     
 <label id="usklicnikTezina" style="color: red">
     <?php if(isset($usklicnikTezina )){
    echo $usklicnikTezina;} ?>
 </label>    
        
 <br>
    <label for="kol" id="lblKolicina"> Kolicina: </label> <br>
    <input type="number" name="kol" min="1" id="kol"
    <?php
    if(isset($nakon5min))
    {
    echo $nakon5min;
    }
    ?>
           >
     <br>     
     <ladel style="color: red" id="usklicnikKol">
    <?php if(isset($usklicnikKol)){
    echo $usklicnikKol;} 
    ?>
    </label> <br>       
        <!--              
         <br>     
         <label for="tezina" id="lblTezina"> Težina </label> <br>
         <input type="range" min="0" max="100" id="tezina">
         <label id="greskeTezina"></label> <br>     
         <label id="usklicnikTezina"> </label> <br>
         -->


         <br>     
        <select id="kategorija" name="kategorija" multiple="multiple" size="5" <?php
            if(isset($nakon5min))
            {
            echo $nakon5min;
            }
        ?>>
            <option value="-1" selected="selected" > &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; == Odaberi kategoriju == </option>
            <option value="0">Grupni </option>
            <option value="1">Individualni </option>
            <option value="2">Individualni s trenerom</option>
            <option value="3">Par s trenerom</option>
        </select>
         
        <br>

        <label id="usklicnikKategorija" name="usklicnikKategorija" >
        <?php 
        if(isset($usklicnikKategorija)){
        echo $usklicnikKategorija;
        } ?>
        </label> <br>
        <br>
        <br>     
         
        <input type="submit" name = "submit" id="slanje" value="Uvedi program" <?php
    if(isset($nakon5min))
    {
    echo $nakon5min;
    }
    ?>
               >
        <input type="reset" value="Vraćanje na inicijalne postavke"
        <?php
    if(isset($nakon5min))
    {
    echo $nakon5min;
    }
    ?>
               >
       
        <input type="reset" value="Osvjezi" <?php echo $osvjezi; ?> >
        </form>
      </section>
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
