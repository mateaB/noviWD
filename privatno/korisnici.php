<?php
header('Content-Type: text/html; charset=utf-8');
$frmKorIme = "";
$greska = "";
if(isset($_SESSION['korisnik_id'])){
  $korisnikIme = $_SESSION['korisnickoIme'];
  $korisnik = $_SESSION['korisnik_id'];
  $tipKorisnika = $_SESSION['tip_korisnika'];
}

include_once("../baza.class.php");
$db = new Baza();
$db->spojiDB();
$upit="SELECT `korisnik_id`, `ime`, `prezime`, `korisnicko_ime`, `email`, `lozinka`, `tip_korisnika`,  `aktiviran`,   zakljucan,  `blokiran` FROM `korisnik` ";
$rezultat = $db->selectDB($upit);

if ($rezultat->num_rows != 0) {

    $table= " ";
    $table.= "<table class='tablica'>
                <tr>
                    <th>ID korisnika</th>
                    <th>Ime</th>
                    <th>Prezime</th>
                    <th>Korisnicko ime</th>
                    <th>Lozinka</th>
                    <th>Email</th>
                    <th>Tip korisnika</th>
                    <th>Status korisnika</th>
                    <th>Zakljucan</th>
                    <th> Blokiran </th>
                </tr>";
    while (list($id, $ime, $prezime, $korisnickoIme,  $email, $lozinka,   $tipKorisnika, $statusKorisnika, $zakljucan, $blokiran) = $rezultat->fetch_array())
    {
        $table.=  "<tr>
                    <td>" . $id . "</td>
                    <td>" . $ime . "</td>
                    <td>" . $prezime . "</td>
                    <td>" . $korisnickoIme . "</td>
                    <td>" . $lozinka . "</td>
                    <td>" . $email . "</td>
                    <td>" . $tipKorisnika . "</td>
                    <td>" . $statusKorisnika . "</td>
                    <td>" . $zakljucan . "</td>
                    <td> " . $blokiran . "</td>
                  </tr>";
    }
    $table.= "</table>";
}
else{
    $greska="Nije moguce dohvatiti";
}

?>
<!DOCTYPE html>
<html>
    <head>
          <title> Ažuriranje programa </title>

        <meta charset="utf-8">
        <meta name="author" content="Matea">
        <meta name="keywords" content="novi_proizvod, upis_proizvoda">
        <meta name="description" content="Stranica je rađena 06.03.2017">

         <link href="../css/matbodulu.css" rel="stylesheet" type="text/css">       

    </head>
      

        <body>
            <div id="header">
            <figure>
                <h1 id="pocetak">Web dizajn i programiranje</h1>
                <ul>
                    <li> <a  href='odjava.php ' class="mail-link"> Odjava </a> </li> 
                </ul>
            </figure>   
        </div>
<nav>
      <ul>
          <li>
              <a href="../index.php"> Početna stranica </a>   
          </li>
          <li>
              <a href="../prijava.php"> Prijava </a>   
          </li>
          <li>  <a href="../programiNeregistrirani.php"> Popis programa </a>
          </li>

          <?php 
          $ispisiS = " ";
          if(!isset($tipKorisnika)){
              $ispisiS .= "<li> <a href='../registracija.php'>  </a> </li>";
          }
          else{
            $ispisiS .= "<li> <a href='../vidiKupone.php'> Kuponi </a> </li>";
            
            if($tipKorisnika == 2){
            $ispisiS .= "
            <li>
                <a href='../nova_vrsta_programa.php'> Nova vrsta programa </a>   
            </li>
            
            <li>
                <a href='../azuriraj_vrstu_programa.php'> Azuriraj vrstu programa </a>   
            </li>
            <li>
                <a href='../novi_kupon.php'> Novi kupon </a>   
            </li>         
            <li>
                <a href='../azurirajKupon.php'> Azuriraj kupon </a> 
            </li>  
            <li>
              <a href='../lojalnost.php'> Lojalnost </a>
            </li>
             <li>
              <a href='../dnevnik.php'> Dnevnik </a>
            </li>
            <li> <a href='../trosenjeBodova.php'> Korisnik - akcija </a>
            </li>
             <li>
              <a href='../dodjela_uloga.php'> Dodjela uloga </a>
            </li>
             <li>
              <a href='../otkljucavanje_korisnika.php'> Otključavanje korisnika </a>
            </li>
            ";}

            if($tipKorisnika == 1){
              $ispisiS .= "
            <li>
                <a href='../novi_program.php'> Novi program </a>   
            </li>
            
            <li>
                <a href='../azurirajProgram.php'> Azuriraj program </a>   
            </li>
            <li>
                <a href='../novi_kupon.php'> Novi kupon </a>   
            </li>         
            <li>
                <a href='../azurirajKupon.php'> Azuriraj kupon </a> 
            </li> 
            <li>
                <a href='../novi_trening.php'> Novi trening </a>   
            </li>         
            <li>
                <a href='../azurirajTrening.php'> Azuriraj trening </a> 
            </li> 
            <li> <a href='../promijenaStatusaTreninga.php'> Status treninga </a>
            </li>
            ";}

            if($tipKorisnika == 3){
              $ispisiS .= "
            <li>
              <a href='../lojalnost.php'> Lojalnost </a>
            </li>  
             <li>
              <a href='../evidencijaDolazaka.php'> Evidencija dolazaka </a>
            </li>
             <li>
              <a href='../kosarica.php'> Kosarica </a>
            </li>
             <li>
              <a href='../evidencijaBodova.php'> Stanje bodova </a>
            </li>
            ";}    
          $ispisiS .= " <div style = 'float: right' >
                <li>
                    <a href='../odjava.php'>  Odjava </a>   
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
<div>
        <section class="sadrzaj">
            <h2 style="text-align: center" >Sadrzaj</h2>

            <div id="table-container">
                <?php echo $table ?>
            </div>

    </section>
</div>
            
</body>
</html>
