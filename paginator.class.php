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
?>


<!DOCTYPE html>
<html>

    <head>

        <title>View Records</title>
         <link href="css/matbodulu.css" rel="stylesheet" type="text/css">


    </head>

    <body>
        <?php
        $ispisi = "";
        include("baza.class.php");
        $db = new Baza();
        $db->spojiDB();
        $page_name = "stranicenje.php";

        $per_page = 10;

        $nadiPrograme = "SELECT * 
                        FROM program";
        $pronadeniProgrami = $db->selectDB($nadiPrograme);

        $ispisi .=  "<table class='tablica'>
                         <th> Naziv kupona</th> 
                         <th> Opis </th>
                         <th> Slika </th>
                         <th> Vrijedi do </th>
                         <th> Naziv programa </th>
                         <th> Potrebno bodova </th>
                         <th> Stavi u kosaricu </th>";
        for ($i=0; $i < $pronadeniProgrami->num_rows; $i++) { 
            while (list($idProgramcic, $nazivProgramcica, $opis, $vrsta,  $brojdozvoljenih) = $pronadeniProgrami->fetch_array()){
                $ispisi .= "<tr> <td colspan='7' style='width:100%'>  $nazivProgramcica </td> </tr>";

                $upit3 = "SELECT * 
                                FROM kuponi k
                                JOIN kupon_programi kp ON kp.kuponi_id = k.id
                                JOIN program p ON p.id = kp.program_id
                                WHERE kp.do > NOW() 
                                AND kp.potrebno_bodova <= ( 
                                SELECT (eb.skupljeno - eb.potorseno)
                                FROM evidencija_bodova eb
                                JOIN korisnik k ON k.korisnik_id = eb.korisnik_korisnik_id
                                WHERE eb.`korisnik_korisnik_id` = $korisnik and kp.program_id = $idProgramcic
                                ORDER BY eb.datum_izmjene DESC 
                                LIMIT 1)"; 
                                //GROUP BY p.Naziv";
                                
                $podaci = $db->selectDB($upit3);
                $total_results=$podaci->num_rows;
                $total_pages = ($podaci->num_rows/10);
                if (isset($_GET['page']) && is_numeric($_GET['page'])) {
                    $show_page = $_GET['page'];

                    if ($show_page > 0 && $show_page <= $total_pages) {

                        $start = ($show_page - 1) * $per_page;

                        $end = $start + $per_page;
                    } else {
                        $start = 0;

                        $end = $per_page;
                    }
                } 
                else {
                    $start = 0;
                    $end = $per_page;
                }

             for ($i = $start; $i < $end; $i++) {
                    if ($i == $total_results) {
                        break;
                    }
            // $greska = "Pregled statistike lajkova po lijekovima";
                 for ($i = $start; $i < $end; $i++) {
                    if($podaci->num_rows != 0){
                    while (list($idKupon, $nazivKupona, $opisKupona, $slika,  $do, $moderator, $idKuponPrograma, $potrebno_bodova, $idprograma, $nazivPrograma, $opisPrograma, $vrsta_programa_id, 
                        $broj_dozvoljenih_mjesta) = $podaci->fetch_array()){                       
                                $ispisi .= "<tr>                        
                                                <td> " . $nazivKupona ."
                                                <td>" . $opisKupona . "
                                                </td> 
                                                 <td> <a href='prikaziDetaljeKupona.php'> <img src=" . '"' . $slika . '"' ."/> </a>
                                                </td>                                     
                                                <td>" . $do . "
                                                </td> 
                                                <td>" . $nazivPrograma . "
                                                </td> 
                                                <td>" . $potrebno_bodova . "
                                                </td> 
                                                <td>                             
                                                    <form action='vidiKupone.php' method='POST'>
                                                     <input type='hidden' name='idKupon' value= '$idKuponPrograma'>
                                                     <input type='submit' value='Stavi u kosaricu' name='StaviUKosaricu'>
                                                    </form>
                                                </td>
                                            </tr>"; 
                                }                             
                            }else{
                                $ispisi .= "<tr> <td colspan='7' style='width:100%'>  Trenutno nemamo kupona za ovaj program! </td> </tr>";
                            }
                        }
                }
            }
    }
    echo $ispisi;
?>
    </body>