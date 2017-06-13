<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

session_start();

header('Content-Type: text/html; charset=utf-8');

if (isset($_SESSION['korisnickoIme'])) {
    header("Location:index.php");
    exit();
}


if(isset($_POST["registracija"])){
  $greska = " ";
  $email = $_POST["email_reg"];
  $ime = $_POST["ime_reg"];
  $prezime = $_POST["prezime_reg"];
  $lozinka = $_POST["lozinka_reg"];
  $potvrdaLozinke = $_POST["pot_lozinka_reg"];
  $koraci = $_POST["brKoraka"];
  $greskaMail = " ";
  $greskaKorIme = " ";
  $potrebnaProvjera = "";
  if(!empty($_POST["kor_ime_reg"]))
  {
      $korisnicko = $_POST["kor_ime_reg"];
  }

  if(empty($ime)  || empty($prezime) || empty($korisnicko) || empty($lozinka) || empty($potvrdaLozinke))
       {
           $greska .= "Nisu unesena sva polja <br>";
       }
       else{
          //recaptcha
          $siteKey = '6LfWuyIUAAAAAEcF0Unz01aOujfTBGPyQ5g8ShQW';
          $secret = '6LfWuyIUAAAAADcnOQiXQK3vasC05M1VvAdwWazx';


          function provjeraZnakova($unos) {
              $znakovi = ["(", ")", "{", "}", "'", "!", "#"];
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

          $polje=[$ime, $prezime, $korisnicko];
          for ($i=0; $i < 2 ;$i++)
          {
            if(provjeraZnakova($polje[$i]) == true)
            {
                $greska = "Nedozvoljeni znakovi se pojavljuju <br>";
            }
          }
             
           //lozinka
           if(preg_match( '/^(?=(.*[A-Z]){2,})(?=(.*[a-z]){2,})(?=(.*[0-9]){1,}).{5,15}$/', $lozinka)){           
           }
           else{
               $greska .= "Lozinka sadrži barem dva velika slova, dva mala slova, jedan broj i duljine je od 5 do 15 znakova! <br>";
           }
           
           //email
            if(preg_match( "/^(\w{1,}){1,}(\.{0,})(\-{0,})\w{0,}@(\w{2,}\.){1,2}\w{2,}$/", $email)){
                $greskaMail = " ";
            }
            else
            {
                $greska .="Mail treba biti u obliku 'nesto@nesto.nesto'<br>";
            }
            
            /*
            $recaptcha = new recaptcha($secret);
            $resp = $recaptcha->verify($_POST['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']);

            if (!$resp->isSuccess()) {
            $greska .= "Recaptcha krivo unesena.</br>";
            }
            */
            include("baza.class.php");
            $db = new Baza();
            $db->spojiDB();
            $upit = " SELECT * FROM `korisnik` WHERE `korisnicko_ime`='$korisnicko' or `email`='$email'";
            $odgovor = $db->selectDB($upit);
            $row = mysqli_fetch_array($odgovor);


              
            if(!empty($row["ime"]) && !empty($row["prezime"]) && !empty($row["zakljucan"]) && !empty($row["aktiviran"]))
            {
                if($row["zakljucan"] == 3)
                {
                    $greska .= "Vaš račuj je zaključan! Javite se administratoru! <br>";
                }
                if($row["blokiran"] == 1)
                {
                  $greska = "Ovaj račuj je blokiran. Molimo Vas napustite stranicu!";
                }
                if($row["ime"] == $ime && $row["prezime"] == $prezime)
                {
                    $potrebnaProvjera .= "Već postoji u bazi osoba s Vašim imenom i prezimenom. Jeste li sigurni da niste registrirani? <br> ";       
                }

                if ($row["email"] == $email) 
                {
                  $greska .= "Unesite drugi mail. Takav već postoji u bazi <br>";
                  $greskaMail = "!";
                }

                if($row["aktiviran"] == 1)
                {
                    $greska = "Ovaj racun je već aktiviran <br>";
                 }

                 $db->zatvoriDB();
            }

            if($greska === " "){  
              $datum_registracije = time();
              $petSati = strtotime($datum_registracije. ' + 5 hours');
              echo $petSati;
              $SESSION["vrijemeRegistracijeTrajeDo"] = $petSati;
              $salt=sha1($datum_registracije);
              $kriptirana = sha1($salt . " " . $lozinka);
              $kod = sha1($salt . "--" . $korisnicko);
              $adresa="/WebDiP/2016_projekti/WebDiP2016x013/";
              $aktivacijski_link = "http://$_SERVER[HTTP_HOST]".$adresa."aktivacija.php?activate=".$kod;
              
              $db = new Baza();
              $db->spojiDB();
              $upit2 = "INSERT INTO `korisnik`"
                      . "( `ime`, `prezime`, `email`, `korisnicko_ime`, `lozinka`, `kriptirana`,"
                      . " `brKoraka`, `aktivacijski`,`tip_korisnika`) "
                      . "VALUES ('$ime','$prezime','$email',"
                      . "'$korisnicko','$lozinka','$kriptirana','$koraci','$kod','3')";
              $prijenos = $db->updateDB($upit2);

              echo "Slanje maila!";
              $mail_to = "$email";
              $mail_from = "From: WebDiP_2017@foi.hr";
              $mail_subject = "Aktivacijski kod";
              $mail_body = "Za aktiviranje svojeg racuna pritisni na aktivacijski link: " . $aktivacijski_link ;
              $poruka="";
              if (mail($mail_to, $mail_subject, $mail_body, $mail_from)) {
                  $poruka.="Poslana poruka za: '$mail_to'! <br>";
              } 
              else {
                  $poruka.="Problem kod poruke za: '$mail_to'! <br>";
              }
               $potvrdiReg = '" display:none "';               
            }            
   }
}
?>
<html>
	<head>
		<title> Registracija </title>
      <meta charset="utf-8">
      <meta name="author" content="Matea">
      <meta name="keywords" content="popis_proizvoda, izlist">
      <meta name="description" content="Stranica je raÃ„â€?ena 30.04.2017">

      <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
      <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>     
      <script type="text/javascript" src="js/matbodulu_jquery.js"></script>
      <link href="css/matbodulu.css" rel="stylesheet" type="text/css">
      <script type="text/javascript" src="js/matbodulu_jquery.js"></script>
      <script type="text/javascript" src="js/matbodulu.js"> </script>

       <!--  <script src='https://www.google.com/recaptcha/api.js'></script> -->
	</head>
  
	<body onload="registracijaJS()">

        <header>
            <figure style="margin: 0px">
                <figcaption class="naslov"> Registracija </figcaption>
                <img src="slike/skok.jpg" class="prvaSlika" alt="Logo foi-a" usemap="#mapa1"/>
                <map name="mapa1">
                    <area href="index.php" alt="index" shape="rect" target="_blank" coords="0,0,200,200"/>
                    <area href="#o_meni" alt="o_meni" shape="rect" target="_parent" coords="200,0,400,200"/>
                </map>
            </figure>
        </header>

   <?php
      include 'nav.php';
   ?>

    
<br>

      <?php
           
                if(isset($poruka))
                {
                    echo $poruka;
                    ECHO "<a href = " . '"' ;
                    IF(isset($aktivacijski_link))
   echo $aktivacijski_link;
                    echo '"' . "?>" .  $aktivacijski_link . " </a>";
                }
               
                if(isset($greska))
                {
                echo $greska;
                }
                
                 if(isset($potrebnaProvjera))
                {
                echo $potrebnaProvjera;
                }
            ?> 


      <div  <?php if(isset($potvrdiReg)) echo "style =  $potvrdiReg"; ?> >
              <section id="registracija">
              <h2> Registriraj se </h2>
              <div class="popisGresaka" style="color:black;" name="popisGresaka">
                   
                  </div>
              <form  method = "POST" name="registracija" action="registracija.php" novalidate>

                <label for="ime_reg"> Ime: </label>
                <input type="text" name = "ime_reg" id="ime_reg">    
                <label id="greskaIme"> </label>      
                <!-- plcoholeder u textarea - super za dodati -->
                 <br>     
                <label for="prezime_reg"> Prezime: </label>
                <input type="text" name="prezime_reg" id="prezime_reg">  
                <label id="greskaPrezime" name="greskaPrezime" > </label>          
                <br>
                <!-- plcoholeder u textarea - super za dodati -->

                <label for="kor_ime_reg" > Korisničko ime: </label>
                <input type="text" name="kor_ime_reg" id="kor_ime_reg" required>
                <?php if(isset($greskaKorIme)) echo $greskaKorIme; ?>
                <!-- plcoholeder u textarea - super za dodati -->
                 <br>     
                 <label for="email_reg"> E-mail: </label>
                <input type="email" name="email_reg" id="email_reg" required>
                <?php if(isset($greskaMail)) echo $greskaMail; ?>
                <!-- plcoholeder u textarea - super za dodati -->
                 <br>     
                 <label for="lozinka_reg"> Lozinka: </label>
                <input type="password"  name= "lozinka_reg" id="lozinka_reg" required>
                 <label id="greskaLozinka" > </label>          
                <!-- plcoholeder u textarea - super za dodati -->
                 <br>     
                 <label for="pot_lozinka_reg" > Potvrda lozinke: </label>
                 <input type="password" name="pot_lozinka_reg" id="pot_lozinka_reg" required>
                  <label id="greskaLozinka" > </label>          
                 <br>
                 <label id="razlicite"> </label>          
                <!-- plcoholeder u textarea - super za dodati -->
                 <br>     
                
                  <label for="odabir"> Izaberite prijavu</label>
                      <select id="odabir" name="brKoraka" class="odabir" >
                          <option value="0" selected="selected">Obična prijava</option>
                          <option value="1">Prijava u 2 koraka</option>
                      </select>
                  <br>
                  <br>
              <!--   <div class="g-recaptcha"  data-sitekey="6LfWuyIUAAAAADcnOQiXQK3vasC05M1VvAdwWazx"></div>
      -->
                  <input  type="submit" value="Predaj registraciju" name="registracija" id='predaj'> 
                  <input type="reset" name="reset" id="reset" value="Izbrisi unesene podatke!" onclick="registracijaJS()">
                  
                </form>
            </section>
      </div>

      
<?php 
      include 'footer.php';
     ?>


	</body>
</html>