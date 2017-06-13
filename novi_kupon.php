<?php
session_start();

header('Content-Type: text/html; charset=utf-8');
$greska = "";

if (!isset($_SESSION['korisnickoIme'])) {
    $ispisi .= "Morate biti prijavljeni";
    header("Location:prijava.php");
    exit();
} 
else if($_SESSION['tip_korisnika'] != 2 ){
   header("Location:index.php");
   exit();
}
else{
  $korisnik = $_SESSION['korisnik_id'];
  $korisnikIme = $_SESSION['korisnickoIme'];
  $tipKorisnika = $_SESSION['tip_korisnika'];
}



if (isset($_POST["Upisi"])) {
    $nazivKupona = $_POST["naziv"];
   
    if(empty($nazivKupona))
    {
      $greska .= "Nisu unesena sva polja <br>";
    }
   

$uploadOk = 0;  

// echo '<br><br><br><br><br><br><br><br> psotoji1111111';
if (!empty($_FILES["fileToUpload"]["name"])) {

  $target_dir = "/var/www/WebDiP/2016_projekti/WebDiP2016x013/slike/";
  //lokacija mape na koju spremaš na barki raèunalo

  $target_dir1 = "https://barka.foi.hr/WebDiP/2016_projekti/WebDiP2016x013/slike/";
  // web mjesto, adresa koju kasnije spremaš v bazu i prek koje dolaziš do slike

  $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
  $target_file1 = $target_dir1 . basename($_FILES["fileToUpload"]["name"]);
  $uploadOk = 1;
  $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
  // Check if image file is a actual image or fake image
  if ($target_file1 == $target_dir1 )
  {
  }
  else 
  {
      $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
      if($check !== false) 
      {
      // echo "File is an image - " . $check["mime"] . ".";
      $uploadOk = 1;
      } 
      else 
      {
        echo "File is not an image.";
        $uploadOk = 0;
      }

    // Check if file already exists 

    $i = 1;
    while (file_exists($target_file))
    {
      $target_file1 =$target_dir1 .$i.  basename($_FILES["fileToUpload"]["name"]);
      $target_file = $target_dir .$i. basename($_FILES["fileToUpload"]["name"]);
      $i++;
      $uploadOk = 1;
    }
    // Check file size
    if ($_FILES["fileToUpload"]["size"] > 5000000) 
    {
      echo "Sorry, your file is too large.";
      $uploadOk = 0;
    }
    // Allow certain file formats
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
    && $imageFileType != "gif" ) 
    {
      echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
      $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0)
    {
    echo "Sorry, your file was not uploaded.";
    // if everything is ok, try to upload file
    } 
    else 
    {
      if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) 
      {
      //echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
      } 
      else 
      {
      echo "Sorry, there was an error uploading your file.";
      }
    }

  }



}  



/*upload*/


//echo '<br><br><br><br><br><br><br><br>'.$target_file1;







if ($uploadOk) 
{
$stmt->bindParam(':Slika1', $target_file1, PDO::PARAM_STR);
}
else{
$stmt->bindParam(':Slika1', $_POST['Slika1'], PDO::PARAM_STR);
}


//$target_file1   -> to ti je adresa uploadane slike,, ovu adresu spremaš u bazu
//$_POST['Slika1']  -> to je adresa defaultne slike, u sluèaju da nije uploadana nikakva slika



  if($greska === ""){ 
    $sakrij = "display: none";
    include_once("baza.class.php");
    $db = new Baza();
    $db->spojiDB();
    $datum = date("Y-m-d H:i:s");
   
    echo $odabrani;

    $upitUpisi = "INSERT INTO `program`( `naziv`, `opis`, `vrsta_programa_id`, 
    `broj_slobodnih_mjesta`, `broj_dozvoljenih_mjesta`) VALUES ('$nazivPrograma', '$opisPrograma',  '$odabrani' , 0, '$br_mogucih_mjesta');";
    $prijenos = $db->selectDB($upitUpisi);


    $postaviModeratoraPrograma = "INSERT INTO `program_moderator`(`korisnik_korisnik_id`, program_id) VALUES ($moderator, (select id from program where `naziv`= $nazivPrograma and `vrsta_programa_id` = $odabrani and broj_dozvoljenih_mjesta=$br_mogucih_mjesta) );";
    $dodajModeratora = $db->selectDB($postaviModeratoraPrograma);

    $poruka = "Dodali ste novi program! <br> Možete 
    <ul>
      <li>
        <a href=vidiPrograme.php> pregledati ostale programe. </a> 
      </li>
      <li>
        <a href=dodajTrening.php> dodati trening programu. </a> 
      </li>
      <li> <a href=novi_program.php>promijeniti dodani program </a>
      </li>
    <ul>";
                
   
     //dnevnik
        $datum = date("Y-m-d H:i:s");
        $radnja = "Korisnik  $korisnik je dodao novi program!";
        $dnevnik = "INSERT INTO `dnevnik`(`korisnik`,`datum`, `Opis`) VALUES ('" . $korisnik . "','" . $datum . "','" . $radnja . "')";
        $db->selectDB($dnevnik);


    $db->zatvoriDB();

  }
   
} 
?>

<!DOCTYPE html>
<html>
	<head>
		<title> Novi kupon </title>
        <meta charset="utf-8">
        <meta name="author" content="Matea">
        <meta name="keywords" content="novi_proizvod, upis_proizvoda">
        <meta name="description" content="Stranica je rađena 06.03.2017">

         <link href="css/osnova.css" rel="stylesheet" type="text/css">		


	</head>
	<body >
		<header>
    <figure>
      <figcaption class="naslov"> Novi kupon </figcaption>
      <img src="slike/skok.jpg" class="prvaSlika" alt="novi program" usemap="#mapa1"/>
      <map name="mapa1">
          <area href="index.php" alt="index" shape="rect" target="_blank" coords="0,0,200,200"/>
          <area href="#noviProgram" alt="noviProizvod" shape="rect" target="_parent" coords="200,0,400,200"/>
      </map>
    </figure>
    </header>

 <?php
    include 'nav.php';
   ?>
    
    <?php
	     if(isset($greska)) {
        echo $greska;
        }

        if (isset($poruka)) {
          $sakrij = "display:none";
          echo $poruka;
        }
    ?>

  <div <?php  if(isset($sakrij)) echo "style= " . '"' .  $sakrij . '";' ?> >   
	  <section id="noviProgram" > 
      <form id="novi_kupon" method = "POST" name="noviKupon" action="novi_kupon.php" enctype="multipart/form-data">
          <h2> Za stvaranje novog KUPONA popunite sljedeće stavke: </h2>
          <label for="naziv" id="lblNaziv"> Naziv programa: </label> 
          <br>
          <input type="text" maxlength="30" name="naziv" id="naziv" required> 
          <br>
          <label id="greskeNaziv"> </label> 
          <br>
          <label id="usklicnik"> </label> 
          <br>
           

        <br>     
        <input type="file" name="fileToUpload" id="fileToUpload" value="Odaberite sliku">
        <br>
        
        <input type="submit" name="Upisi" value="Uvedi program"> 
        <input type="reset" value="Vraćanje na inicijalne postavke"> 
      </form>
    </section>
  </div>


  <?php 
    include 'footer.php';
   ?>

      <script type="text/javascript" src="js/matbodulu.js">         
      </script>
	</body>
</html>