<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<?php 
session_start();

header('Content-Type: text/html; charset=utf-8');

$prikazi = "display:none";

if (!isset($_SESSION['korisnickoIme'])) 
{
    $greska.= "Morate biti prijavljeni";
    header("Location:prijava.php");
    exit();
}
else
{
    $korisnikIme = $_SESSION['korisnickoIme'];
    $korisnik = $_SESSION['korisnik_id'];
    $tipKorisnika = $_SESSION['tip_korisnika'];
}




if (isset($_POST["Upisi"])) {
    include_once("baza.class.php");
    $db = new Baza();
    $db->spojiDB();
    $zapisiProgram = $_POST['program_id'];
    echo "blaaaa";
    $upisan = "SELECT korisnik_id, program_id FROM korisnik_program where korisnik_id = '$korisnik' and program_id='$zapisiProgram';";
    $nasao = $db->selectDB($upisan);
    if($nasao->num_rows == 0){
        $upitUpisi = "INSERT INTO `korisnik_program`(`korisnik_id`, `program_id`, `datum`) VALUES ('$korisnik', '$zapisiProgram', now());";
        $prijenos = $db->selectDB($upitUpisi);

        $upit = "SELECT p.naziv, kp.datum
                FROM  `korisnik` k
                JOIN korisnik_program kp ON kp.korisnik_id = k.korisnik_id
                JOIN program p ON p.id = kp.program_id
                where k.korisnik_id = '$korisnik' ORDER BY kp.datum desc;";
        $odgovor = $db->selectDB($upit);  

    $akcijaKupnja =  "SELECT  `skupljeno` ,  `potorseno` ,
                        `datum_izmjene` ,  `korisnik_korisnik_id` 
                            FROM  `evidencija_bodova` eb
                            JOIN korisnik k ON k.korisnik_id = eb.`korisnik_korisnik_id` 
                            WHERE k.korisnik_id = " . $korisnik . "
                            ORDER BY eb.`datum_izmjene` desc limit 1"; 
    $odgovorTrenutno = $db->selectDB($akcijaKupnja);  

    while ((list($skupljeno, $potorseno, $datum_izmjene) = $odgovorTrenutno->fetch_row()) )
        { 
            $datum = date('Y-m-d H:i:s');
            $skupljeno += 1;
            $dodajBodic = 'INSERT INTO `evidencija_bodova`(`skupljeno`, `potorseno`, `datum_izmjene`, `korisnik_korisnik_id`) VALUES (' .$skupljeno . ', 0, ' . '"' . $datum . '"' . ' , ' . $korisnik . ' );';
            $dodajBod = $db->updateDB($dodajBodic);
            echo $dodajBod;
        }
        $zapisiBod = 'INSERT INTO `akcija_korisnik`(`datum`, `akcija_id`, `korisnik_id`) VALUES ( ' . '"' .$datum . '"' . ', 7 , ' . $korisnik . ')';
        $provediZapis = $db->selectDB($zapisiBod);

        $ispisiUpisane = "Upisali se program! Četitamo i radujemo se vašim rezultatima! Ukoliko želite upisati još programa, možete ih naći <a href=upisiProgramPravi.php> ovdje </a>. <br> <br>";

        if($odgovor->num_rows != 0){
            $ispisiUpisane .=  "<table class='tablica'> <th> Naziv programa </th> <th> Datum </th>";
            while (list($program , $datum) = $odgovor->fetch_array() ){
                $ispisiUpisane .= "<tr><td>" . $program . "</td>
                                <td>" . $datum . "
                                </td>
                            </tr>";
            }
            $ispisiUpisane .= "</table>"; 
            $db->zatvoriDB();
        } 
    }
    else
    {
        $ispisiM = "VEĆ STE UPISALI TAJ PROGRAM! Slobodno pogledajte ostale";
    }
}


if (isset($_POST["svi"])) {
    include("baza.class.php");
    $db = new Baza();
    $db->spojiDB();
    $ispisi = "  ";

    $SVIprogrami = "select * from program";
    $nadeniSvi = $db->selectDB($SVIprogrami);

  $ispisi .=  "<table id='tablica'> <th> Naziv </th> <th> Opis </th> <th> Broj slobodnih mjesta </th> <th> Upisi </th> <th>  </th>";
    while (list($id, $naziv) = $nadeniSvi->fetch_array())
    {
        $upit2 = "SELECT p.naziv, p.id, p.opis, p.broj_dozvoljenih_mjesta, COUNT( kp.`program_id`) 
                FROM  `korisnik_program` kp
                 JOIN program p ON p.id = kp.`program_id` 
                 where kp.program_id = $id
                GROUP BY kp.program_id";
        $odgovor = $db->selectDB($upit2);

        $upit3 = "SELECT p.naziv, p.id, p.opis, p.broj_dozvoljenih_mjesta
                FROM program p
                where p.id = $id";
        $odgovor3 = $db->selectDB($upit3);


        if($odgovor->num_rows != 0){  
          
                while (list($naziv, $id, $opis, $broj_dozvoljenih_mjesta, $broj_upisanih ) = $odgovor->fetch_array()) 
                     {
                        $brojSlobodnih = $broj_dozvoljenih_mjesta - $broj_upisanih;
                        $ispisi .= "<tr> <td>" . $naziv . "</td>
                                        <td>" . $opis . "</td>";
                                        
                                   if($brojSlobodnih > 0){
                                          $ispisi .=  "
                                        <td>  " . $brojSlobodnih . "<td> 
                                            <form action='upisiProgramPravi.php' method='POST'>
                                             <input type='hidden' name='program_id' value= '$id'>
                                             <input type='submit' value='Upisi' name='Upisi'>
                                            </form>
                                            </td>";
                                        }     
                                        else{
                                            $ispisi .="<td> <br> Sva mjesta su popunjena!<br></td>";
                                        }                                 
                                 $ispisi .=  " </tr>"; 
                    }
        } 
        else if($odgovor3->num_rows != 0){  
            
                while (list($naziv, $id, $opis, $brojSlobodnih ) = $odgovor3->fetch_array()) 
                     {
                       
                        $ispisi .= "<tr> <td>" . $naziv . "</td>
                                        <td>" . $opis . "</td>";
                                        
                                   if($brojSlobodnih > 0){
                                          $ispisi .=  "
                                        <td>  " . $brojSlobodnih . "</td> 
                                        <td>
                                            <form action='upisiProgramPravi.php' method='POST'>
                                             <input type='hidden' name='program_id' value= '$id'>
                                             <input type='submit' value='Upisi' name='Upisi'>
                                            </form>
                                            </td>";
                                        }     
                                        else{
                                            $ispisi .="<td> <br> Sva mjesta su popunjena!<br></td>";
                                        }                                 
                                 $ispisi .=  " </tr>"; 
                    }
        }
        else{
            $ispisi .= "Trenutno nemamo tu vrstu programa u ponudi!";
        }

    }
        $ispisi .= "</table>";      

    $db->zatvoriDB();
}

if (isset($_POST["pretrazi"])) 
{
    include("baza.class.php");
    $db = new Baza();
    $db->spojiDB();

    $koliko = 0;
    $vrsta = $_POST['vrstaProgramaSelect'];
    $ispisi = " ";
  

    if(!empty($_POST["pretraziBroj"])){
        $koliko = $_POST['pretraziBroj'];
        if($koliko > 0)
        {    
            $i = 0;
          $upit2 = "SELECT p.naziv, p.id, p.opis, p.broj_dozvoljenih_mjesta, COUNT( kp.`program_id`) 
            FROM  `korisnik_program` kp
            JOIN program p ON p.id = kp.`program_id` 
            WHERE p.vrsta_programa_id = '$vrsta'
            GROUP BY kp.program_id";
        $odgovor = $db->selectDB($upit2);

        $upit3 = "SELECT p.naziv, p.id, p.opis, p.broj_dozvoljenih_mjesta
                    FROM program p where vrsta_programa_id= '$vrsta' ";
        $odgovor3 = $db->selectDB($upit3);

        $ispisi .=  "<table > <th> Naziv </th> <th> Opis </th> <th> Broj slobodnih mjesta <th> Upisi </th> <th>  </th>";

        if($odgovor->num_rows != 0){  
           
                while (list($naziv, $id, $opis, $broj_dozvoljenih_mjesta, $broj_upisanih ) = $odgovor->fetch_array()) 
                     {
                        $brojSlobodnih = $broj_dozvoljenih_mjesta - $broj_upisanih;
                        $ispisi .= "<tr> <td>" . $naziv . "</td>
                                        <td>" . $opis . "</td>";
                                        
                                   if($brojSlobodnih > 0){
                                          $ispisi .=  "
                                        <td>  " . $brojSlobodnih . "</td> 
                                        <td>
                                            <form action='upisiProgramPravi.php' method='POST'>
                                             <input type='hidden' name='program_id' value= '$id'>
                                             <input type='submit' value='Upisi' name='Upisi'>
                                            </form>
                                            </td>";
                                        }     
                                        else{
                                            $ispisi .="<td> Sva mjesta su popunjena! </td>";
                                        }                                 
                                 $ispisi .=  " </tr>"; 
                    }
        } 
        if($odgovor3->num_rows != 0){  
           
                while (list($naziv, $id, $opis, $brojSlobodnih ) = $odgovor3->fetch_array()) 
                     {
                       
                        $ispisi .= "<tr> <td>" . $naziv . "</td>
                                        <td>" . $opis . "</td>";
                                        
                                   if($brojSlobodnih > 0){
                                          $ispisi .=  "
                                        <td>  " . $brojSlobodnih . "</td> <td>
                                            <form action='upisiProgramPravi.php' method='POST'>
                                             <input type='hidden' name='program_id' value= '$id'>
                                             <input type='submit' value='Upisi' name='Upisi'>
                                            </form>
                                            </td>";
                                        }     
                                        else{
                                            $ispisi .="<td> <br> Sva mjesta su popunjena!<br></td>";
                                        }                                 
                                 $ispisi .=  " </tr>"; 
                    }
        }
        if($odgovor->num_rows == 0 and $odgovor3->num_rows == 0)
        {
            $ispisi .= "Trenutno nemamo tu vrstu programa u ponudi!";
        }
        $ispisi .= "</table>";
    }
    }
    else 
    {
        $upit2 = "SELECT p.naziv, p.id, p.opis, p.broj_dozvoljenih_mjesta, COUNT( kp.`program_id`) 
            FROM  `korisnik_program` kp
            JOIN program p ON p.id = kp.`program_id` 
            WHERE p.vrsta_programa_id = '$vrsta'
            GROUP BY kp.program_id";
        $odgovor = $db->selectDB($upit2);

        $upit3 = "SELECT p.naziv, p.id, p.opis, p.broj_dozvoljenih_mjesta
                    FROM program p where vrsta_programa_id= '$vrsta' ";
        $odgovor3 = $db->selectDB($upit3);

        $ispisi .=  "<table > <th> Naziv </th> <th> Opis </th> <th> Broj slobodnih mjesta <th> Upisi </th> <th>  </th>";

        if($odgovor->num_rows != 0){  
           
                while (list($naziv, $id, $opis, $broj_dozvoljenih_mjesta, $broj_upisanih ) = $odgovor->fetch_array()) 
                     {
                        $brojSlobodnih = $broj_dozvoljenih_mjesta - $broj_upisanih;
                        $ispisi .= "<tr> <td>" . $naziv . "</td>
                                        <td>" . $opis . "</td>";
                                        
                                   if($brojSlobodnih > 0){
                                          $ispisi .=  "
                                        <td>  " . $brojSlobodnih . "</td> 
                                        <td>
                                            <form action='upisiProgramPravi.php' method='POST'>
                                             <input type='hidden' name='program_id' value= '$id'>
                                             <input type='submit' value='Upisi' name='Upisi'>
                                            </form>
                                            </td>";
                                        }     
                                        else{
                                            $ispisi .="<td> Sva mjesta su popunjena! </td>";
                                        }                                 
                                 $ispisi .=  " </tr>"; 
                    }
        } 
        if($odgovor3->num_rows != 0){  
           
                while (list($naziv, $id, $opis, $brojSlobodnih ) = $odgovor3->fetch_array()) 
                     {
                       
                        $ispisi .= "<tr> <td>" . $naziv . "</td>
                                        <td>" . $opis . "</td>";
                                        
                                   if($brojSlobodnih > 0){
                                          $ispisi .=  "
                                        <td>  " . $brojSlobodnih . "</td> <td>
                                            <form action='upisiProgramPravi.php' method='POST'>
                                             <input type='hidden' name='program_id' value= '$id'>
                                             <input type='submit' value='Upisi' name='Upisi'>
                                            </form>
                                            </td>";
                                        }     
                                        else{
                                            $ispisi .="<td> <br> Sva mjesta su popunjena!<br></td>";
                                        }                                 
                                 $ispisi .=  " </tr>"; 
                    }
        }
        if($odgovor->num_rows == 0 and $odgovor3->num_rows == 0)
        {
            $ispisi .= "Trenutno nemamo tu vrstu programa u ponudi!";
        }
        $ispisi .= "</table>";
    }
$db->zatvoriDB();
}

?>
<!DOCTYPE html>
<html>
    <head>
        <title> Upis programa </title>
        <meta charset="utf-8">
        <meta name="author" content="Matea">
        <meta name="keywords" content="prijava, korisnik">
        <meta name="description" content="Stranica je raÄ‘ena 06.03.2017">  
        <link href="css/osnova.css" rel="stylesheet" type="text/css">
        <style type="text/css">
            

            table {
                margin: 20px;
                border: 2px solid maroon;
            }

            table tr td{
                text-align: center;
                border: 1px solid maroon;
            }

            table tr td:nth-child(2){
                text-align: left;
                text-indent: 40px;
            }


            table caption{
                margin: 20px;
            }

            table th{
                background-color: maroon;
                color: white;
            }

            table a{
                color: maroon;
            }

            table tr:hover{
                background-color: maroon;
                color: white;
            }


            table tr:hover a{
                color: white;
            }

        </style>
    </head>

    <body>
        <header>
            <figure style="margin: 0px">
                <figcaption class="naslov"> Obrazac za prijavu </figcaption>
                <img src="slike/skok.jpg" class="prvaSlika" alt="Logo skok" usemap="#mapa1"/>
                <map name="mapa1">
                    <area href="index.php" alt="index" shape="rect" target="_blank" coords="0,0,200,200"/>
                    <area href="#prijava" alt="prijava" shape="rect" target="_parent" coords="200,0,400,200"/>
                </map>
            </figure>
        </header>

 <?php
      include 'nav.php';
   ?>

    
    
           
    <div <?php if(isset($sakrij)) echo "style= " . '"' . $sakrij . '";' ?> >
        <form id="popis_proizvoda" method = "POST" name="popis_proizvoda" action="upisiProgramPravi.php">
            <label for="pretraziBroj"> Broj programa: </label>
            <input type="text" name="pretraziBroj" id="pretraziBroj">
            <label for="vrstaProgramaSelect"> Program: </label>

            <select class="vrstaProgramaSelect" name="vrstaProgramaSelect">
                <?php 
                    include_once("baza.class.php");
                    $db = new Baza();
                    $db->spojiDB();
                    $upit = "SELECT * 
                            FROM vrsta_programa;";
                    $odgovor = $db->selectDB($upit);   

                   
                    while (list($id, $naziv) = $odgovor->fetch_array()) 
                            {
                                echo '<option value="'. $id .'">'.' Naziv: '.$naziv.'</option>';
                            }
                   $db->zatvoriDB();
                ?>
            </select>

            
            <br>
            <input  type="submit" value="pretrazi" name="pretrazi">
            <input type="submit" name="svi" value="svi">
            <input type="submit" name="azuriranje" value="Azuriraj" style=<?php echo $prikazi; ?> >
               
        </form> 

        <div>
         <?php            
                        if(isset($ispisiUpisane))
                        {
                            echo $ispisiUpisane;
                        }

                        if(isset($ispisi))
                        {
                            echo $ispisi;
                        }   
                        if(isset($ispisiM)) echo $ispisiM;
                    ?>   
        </div>
    </div>
  
   
    <?php 
        include 'footer.php';
     ?>
 
    </body>
   
</html>