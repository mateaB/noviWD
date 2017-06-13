<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<?php 
session_start();

header('Content-Type: text/html; charset=utf-8');

$prikazi = "display:none";

if (isset($_SESSION['korisnickoIme'])) {
    header("Location:upisiProgramPravi.php");
    exit();
    $tipKorisnika = $_SESSION['tip_korisnika'];
    $korisnik = $_SESSION['korisnik_id'];
    $korisnikIme = $_SESSION['korisnickoIme'];
}

    $ispisi = "  ";
    include("baza.class.php");
    $db = new Baza();
    $db->spojiDB();

    if(isset($_POST['vrstaProgramaSelect'])){
        $vrsta = $_POST['vrstaProgramaSelect'];
    }

    /* prema broju polaznika
    SELECT p.naziv, p.opis
FROM  `trening` t
JOIN trening_program pt ON pt.trening_id = t.id
JOIN program p ON pt.program_id = p.id
where t.status_id = 1
GROUP BY pt.program_id
ORDER BY t.broj_polaznika DESC 

SELECT  p.id,p.naziv, p.opis, p.vrsta_programa_id, (p.broj_dozvoljenih_mjesta - COUNT( kp.`program_id`) )
            FROM  `korisnik_program` kp
            JOIN program p ON p.id = kp.`program_id` 
            where p.vrsta_programa_id= $vrsta
            GROUP BY kp.program_id
            order by 5 desc limit 3
*/
if(isset($_POST['pretrazi'])){
//ovaj upit je po broju upisanih
    if($vrsta != 0){
        $upit1 =" SELECT  p.id,p.naziv, p.opis, p.vrsta_programa_id, (p.broj_dozvoljenih_mjesta - COUNT( kp.`program_id`) )
            FROM  `korisnik_program` kp
            JOIN program p ON p.id = kp.`program_id` 
            where p.vrsta_programa_id= $vrsta
            GROUP BY kp.program_id
            order by 5 desc limit 3 ;";

        $odgovor = $db->selectDB($upit1);  

        if($odgovor->num_rows != 0){  
            $ispisi .=  "<table id='tablica'> <th> Naziv </th> <th> Opis </th>";
            while (list($id, $naziv, $opis, $vrsta_programa_id, $broj_dozvoljenih_mjesta) = $odgovor->fetch_array())
            {
                    $ispisi .= "<tr><td>" . $naziv . "</td>
                                    <td>" . $opis . "
                                    </td> 
                                </tr>"; 
       
                }
            $ispisi .= "</table>";      
        }        
        else{
            $ispisi = "Trenutno nemamo tu vrstu programa u ponudi!";
        }     

    }
    else{
        $ispisi = "Molimo daberite vrstu programa";
    }

}


?>

<!DOCTYPE html>
<html>
    <head>
        <title> Popis 3 top programa po vrstama </title>
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
                <figcaption class="naslov"> Popis 3 top programa po vrstama </figcaption>
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


    
    <div class="container" >
        <div <?php if(isset($sakrij)) echo "style= " . '"' . $sakrij . '";' ?> >
            <section>            
                <form id="programi" method = "POST" name="origrami" action="programiNeregistrirani.php">
              
                    <label for="vrstaProgramaSelect"> Program: </label>
                    <select class="vrstaProgramaSelect" name="vrstaProgramaSelect">
                        <?php 
                            include_once("baza.class.php");
                            $db = new Baza();
                            $db->spojiDB();
                            $upit = "SELECT * 
                                    FROM vrsta_programa;";
                            $odgovor = $db->selectDB($upit);
                            $sviIspisi = '<option values = "0"> </option>' ;             
                            while (list($id, $naziv) = $odgovor->fetch_array()) 
                                    {
                                        $sviIspisi .= '<option value="'. $id .'" ';
                                        if($id == $vrsta) $sviIspisi .= ' selected ';
                                        $sviIspisi .= '>'. $naziv.'</option>';
                                    }
                                    echo $sviIspisi;
                           $db->zatvoriDB();
                        ?>
                    </select>

                    <input  type="submit" value="pretrazi" name="pretrazi">
                </form>       
            </section>
        </div>

        <div>
            <?php            
                if(isset($ispisi)){
                    echo $ispisi;
                }   
            ?>     
        </div>
    </div>  
           
    
    <?php 
        include 'footer.php';
    ?>
    </body>
</html>