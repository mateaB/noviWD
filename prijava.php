<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<?php 
session_start();

header('Content-Type: text/html; charset=utf-8');
$frmKorisIme = "";
$greska = "";


if ($_SERVER["HTTPS"] != "on") {
    header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
    exit();
}

if (isset($_SESSION['korisnickoIme']) and $_SESSION['broj_pokusaja'] !=3) { 
    $korisnik = $_SESSION['korisnik_id'];
    $korisnikIme = $_SESSION['korisnickoIme'];
    $tipKorisnika = $_SESSION['tip_korisnika'];
    header("Location:index.php");
}

/*
$naziv = "guest";
if (isset($_COOKIE[$naziv])) {
    $frmKorisIme = $_COOKIE[$naziv];
    setcookie($naziv, " ", time() - 3600);
}
*/
//zaboravljena lozinka EMAIL
if (isset($_POST["zaboravljenaLozinka"])){
    $korIme = $_POST["korIme"];
    $poruka = "";
    include './baza.class.php';
    $db=new Baza();
    $db->spojiDB();
    $upit="SELECT * FROM `korisnik` WHERE `korisnicko_ime`='$korIme'";
    $prijenos = $db->selectDB($upit);
    $row = mysqli_fetch_array($prijenos);

    if(empty($korIme))
    {
        $greska .= "Unesi korisnicko ime!";
    }    
    else if(empty($row))
    {
        $greska .= "U bazi ne postoji korisnik s tim korisnièkim imenom. ";
    }
    else {
        //nova generirana lozinka
        $lozinka = $row["lozinka"];
        $datum = time();
        $salt= sha1($datum);
        $nova = sha1($salt . " " . $lozinka);
        
        $linkZaPrijavu = "https://barka.foi.hr/WebDiP/2016/zadaca_05/matbodulu/prijava.php";
        $mail_to =$row["email"]; 
        $mail_from = "From: WebDiP_2017_zadaca_04@foi.hr";
        $mail_subject = "Lozinka";
        $mail_body = "Vaša lozinka je sada:" . $nova . ". Sada se s njom možete prijaviti. " . $linkZaPrijavu ;
        $update = "UPDATE `korisnik` SET `lozinka`='$nova' WHERE `korisnicko_ime`='$korIme'";
        $prijenos = $db->updateDB($update);

        if (mail($mail_to, $mail_subject, $mail_body, $mail_from)) {
            $poruka .= "Poslana poruka za: '$mail_to'! <br>";
        } else {
            $poruka .= "Problem kod poruke za: '$mail_to'! <br>";
        }

        //dnevnik
        $datum = date("Y-m-d H:i:s");
        $imePrijavljenog = $row["korisnicko_ime"];
        $radnja = "Korisnik " . $imePrijavljenog . "  sada ima lozinku generiranu od sustava!";
        $dnevnik = "INSERT INTO `dnevnik`(  `korisnik`, `datum`, `Opis`, `tip_akcije`)  values  ('$korisnik','$datum ',' $radnja ', 1)";
        $db->selectDB($dnevnik);

        $db->zatvoriDB();
    }
}

if (isset($_POST["submit"])) {
    $korIme = $_POST["korIme"];
    $lozinka= $_POST["lozinka"];
    $token=$_POST["kod1"];
    $greska = "";
    $poruka = "";
    include './baza.class.php';
    $db=new Baza();
    $db->spojiDB();
    $upit="SELECT * FROM `korisnik` WHERE `korisnicko_ime`='$korIme'";
    $prijenos = $db->selectDB($upit);
    $row = mysqli_fetch_array($prijenos);
    echo $row["lozinka"];
    if(empty($korIme))
    {
        $greska .= "Unesi korisnicko ime!";
    }    
    else if(empty($row))
    {
        $greska .= "U bazi ne postoji korisnik s tim korisnièkim imenom. ";
    }
    else if( !empty($korIme) && $row["korisnicko_ime"] == $korIme && $row["lozinka"]==$lozinka && $row["broj_pokusaja"] < 3 && $row['blokiran'] == 0)
    { 
        if($row["brKoraka"] == 0)
        {
            $poruka .= "Uspjesno ste se prijavili";    
            $sakrij = "display: none;";
            
            $_SESSION['korisnickoIme'] = $row['korisnicko_ime'];
            $_SESSION['tip_korisnika'] = $row['tip_korisnika'];
            $_SESSION['korisnik_id'] = $row['korisnik_id'];
            $_SESSION['email'] = $row['email'];
            $_SESSION['ime'] = $row['ime'];
            $_SESSION['prezime'] = $row['prezime'];
            $_SESSION['aktiviran'] = $row['aktiviran'];
            $_SESSION['broj_pokusaja'] = $row['broj_pokusaja'];

            //DNEVNIK
            $datum = date("Y-m-d H:i:s");
            $korisnik = $_SESSION['korisnik_id'];

            $imePrijavljenog = $row["korisnicko_ime"];
            $radnja = "Korisnik " . $imePrijavljenog . "  se prijavio! ";
            $dnevnik = "INSERT INTO `dnevnik`(  `korisnik`, `datum`, `Opis`, `tip_akcije`)  values  ('$korisnik','$datum ',' $radnja ', 1)";
            $db->selectDB($dnevnik);

            $upit = "UPDATE  `korisnik` SET  `zadnjaPrijava` = NOW( ) WHERE  `korisnicko_ime` =  ' " . $korIme . "';";
            $db->updateDB($upit);

            $update ="UPDATE `korisnik` SET `broj_pokusaja`=0  WHERE `korisnicko_ime`='$korIme'";
            $prijenos = $db->updateDB($update);

            header("Location:index.php");
            exit();
        }   
        else if( $row["brKoraka"] == 1 && $token == $row["token"] && !empty($token) && $row['blokiran'] == 0 )
        {
            $poruka .= "Uspješno ste se prijavili.";     
            $sakrij = "display: none;";  

           

            $_SESSION['korisnickoIme'] = $row['korisnicko_ime'];
            $_SESSION['tip_korisnika'] = $row['tip_korisnika'];
            $_SESSION['korisnik_id'] = $row['korisnik_id'];
            $_SESSION['email'] = $row['email'];
            $_SESSION['ime'] = $row['ime'];
            $_SESSION['prezime'] = $row['prezime'];           
            $_SESSION['aktiviran'] = $row['aktiviran'];  
            $_SESSION['broj_pokusaja'] = $row['broj_pokusaja'];
            
            

             //DNEVNIK
            $korisnik = $_SESSION['korisnik_id'];
            $datum = date("Y-m-d H:i:s");
            $imePrijavljenog = $row["korisnicko_ime"];
            $radnja = "Korisnik " . $imePrijavljenog . "  se prijavio!";
          $dnevnik = "INSERT INTO `dnevnik`(  `korisnik`, `datum`, `Opis`, `tip_akcije`)  values  ('$korisnik','$datum ',' $radnja ', 1)";
            $db->selectDB($dnevnik);
            $update ="UPDATE `korisnik` SET `broj_pokusaja`=0  WHERE `korisnicko_ime`='$korIme'";
            $prijenos = $db->updateDB($update);

            header("Location:index.php");
            exit();
        }
        else
        {
            $dozvoli = "display: inline-block";
            $token= date("Y-m-d H:i:s");
            $token= sha1($token);
            echo $token;
            $greska .= "Token:".$token."<br>";
            $poruka .= "Token kod vam je poslan na mail: " . $row["email"] . "<br>" ;
            $mail_to =$row["email"]; 
            $mail_from = "From: WebDiP_2017_zadaca_04@foi.hr";
            $mail_subject = "Token";
            $mail_body = "Za prijavu unesite ovaj kod:" . $token ;
            $update = "UPDATE `korisnik` SET `token`='$token' WHERE `korisnicko_ime`='$korIme'";
            $prijenos = $db->updateDB($update);

            if (mail($mail_to, $mail_subject, $mail_body, $mail_from)) {
                $poruka .= "Poslana poruka za: '$mail_to'! <br>";
            } 
            else {
                $poruka .= "Problem kod poruke za: '$mail_to'! <br>";
            }

             // set Cookie
            $naziv = "guest";
            $id = "1";
            $vrijeme_ulaska = time();
            $petMinuta = strtotime($vrijeme_ulaska + 60*5);
            $vrijedi_do = time() + 60*5; 

            if(!isset($_COOKIE[$naziv])){
                setcookie($naziv, $id,  $vrijedi_do);
                echo "<b>Cookie:</b> $naziv <b>vrijedi do:</b> $vrijedi_do.\n";
                while (isset($_COOKIE[$naziv]) && !$_POST["submit"]){
                    $sada = strtotime(time());
                    if($sada < $_COOKIE[$petMinuta]){
                        $nakon5min = " ";
                    }
                    else{
                       $nakon5min = "disabled";
                       $submit = "disabled";
                       unset($_COOKIE[$naziv]);
                       $greska .= "Prošo je 5 minuta. Kod Vam više ne vrijedi!";
                    }       
                }
            }
        }
    }
    else if (!empty($korIme) && $row["korisnicko_ime"] == $korIme && $row["broj_pokusaja"] <= 2 && $row['blokiran'] == 0)
    {
        $broj = $row["broj_pokusaja"];
        $broj2 = intval($broj)+1;
        $greska .= " Pogrešno ste se prijavili:" .$broj2. "<br>";
        $update ="UPDATE `korisnik` SET `broj_pokusaja`='$broj2'  WHERE `korisnicko_ime`='$korIme'";
        $prijenos = $db->updateDB($update);
    }
    else {
        $greska .= "Pogrešno ste se prijavili više od 3 puta! Vaš račun je zaključan <br>";
        if($row["broj_pokusaja"] == 3 ){
            $update ="UPDATE `korisnik` SET `zakljucan`= 1  WHERE `korisnicko_ime`=$korIme";
            $prijenos = $db->updateDB($update);
            }


            $korisnik = $row['korisnik_id'];

            //dnevnik
            $datum = date("Y-m-d H:i:s");
            $radnja = "Korisniku je račun zaključan zbog pogrešno unesenih lozinki!";
            $dnevnik = "INSERT INTO `dnevnik`(  `korisnik`, `datum`, `Opis`, `tip_akcije`)  values  ('$korisnik','$datum ',' $radnja ', 4)";
            $db->selectDB($dnevnik);
    }
    $db->zatvoriDB();
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title> Obrazac za prijavu </title>
        <meta charset="utf-8">
        <meta name="author" content="Matea">
        <meta name="keywords" content="prijava, korisnik">
        <meta name="description" content="Stranica je raÄ‘ena 06.03.2017">  
        <link href="css/osnova.css" rel="stylesheet" type="text/css">

    </head>

    <body>
    <header>
        <figure style="margin: 0px">
            <figcaption class="naslov"> Obrazac za prijavu </figcaption>
            <img src="slike/skok.jpg" class="prvaSlika" alt="Logo foi-a" usemap="#mapa1"/>
            <map name="mapa1">
                <area href="index.php" alt="index" shape="rect" target="_blank" coords="0,0,200,200"/>
                <area href="#prijava" alt="prijava" shape="rect" target="_parent" coords="200,0,400,200"/>
            </map>
        </figure>
    </header>

 <?php
      include 'nav.php';
   ?>


    <section class="prijava">
        <h1 > Prijava </h1>
        
        <DIV>
           <?php 
           if(isset($greska)) echo $greska;
            if(isset($dozvoli)){
                }
            else {
                $dozvoli  = " display: none";
            }
            if(isset($poruka)){
                echo $poruka;
            }
            
            if(isset($cookieKorIme)){
                echo $cookieKorIme;
            }
            if(isset($potvrda)) echo $potvrda;
            ?>
        </DIV>
        <div <?php  if(isset($sakrij)) echo "style= " . '"' .  $sakrij . '";' ?> >   
            <form id="prijava" method = "POST" name="prijava" action="prijava.php">
                    <label for="korIme"> Korisničko ime: </label>
                    <input type="text" maxlength="30" name = "korIme" id="korIme">          
                    <!-- plcoholeder u textarea - super za dodati -->
                     <br>     
                    <!-- gumb -->
                    <label for="lozinka"> Lozinka: </label>
                    <input type="password" maxlength="100" name="lozinka" id="lozinka">     <br>     
                    <!-- plcoholeder u textarea - super za dodati -->
                    
                    <label for="zapamti"> Zapamti me: </label>
                    <input type="radio" id="zapamti" value="DA">  DA   
                    <input type="radio" id="zaboravi" value="NE" checked>  NE <br>      
                    <!-- plcoholeder u textarea - super za dodati -->
                    <label  <?php echo " style =' $dozvoli' " ?>  id="kod" class="kod" for="kod1"> Kod: </label>
                    <div style="display: inline-block; float: right;">
                            <input type="submit" name = "submit" id="slanje" value="Dovrši prijavu" 
                                        <?php
                                        if(isset($nakon5min))
                                        {
                                        echo $nakon5min;
                                        }
                                        ?> 
                            >
                            <br>

                            <input  <?php echo "style = '$dozvoli' " ?>  type="text" id="kod1" name="kod1">
                            <input type="submit" name = "zaboravljenaLozinka" id="zaboravljenaLozinka" value="Zaboravio/la sam lozinku">
                             <button> 
                                <a href="registracija.php" style="color: red;"> Registracija </a> 
                            </button>     
                    </div>
            </form> 
        </div>    
    </section>
   


     <?php 
        include 'footer.php';
     ?>

        
    </body>
</html>