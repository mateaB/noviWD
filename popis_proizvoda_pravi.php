<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<?php 
session_start();

header('Content-Type: text/html; charset=utf-8');
// include '../config/config.php';

$prikazi = "display:none";

if (!isset($_SESSION['korisnickoIme'])) {
    $greska.= "Morate biti prijavljeni";
    header("Location:prijava.php");
    exit();
}
else if($_SESSION["korisnik_id"] == 2){
    $prikazi = "display:inline-block";
}

$korisnickoIme = $_SESSION['korisnickoIme'];
$korisnik = $_SESSION['korisnik_id'];




if (isset($_POST["Upisi"])) {
    //$sakrij = "display: none";
    include_once("baza.class.php");
    $db = new Baza();
    $db->spojiDB();
    $zapisiProgram = $_POST['program_id'];
    echo "blaaaa";
    $upitUpisi = "INSERT INTO `korisnik_program`(`korisnik_id`, `program_id`, `datum`)
               VALUES ('$korisnik', '$zapisiProgram', now());";
    $prijenos = $db->selectDB($upitUpisi);

    $upit = "SELECT p.naziv, kp.datum
FROM  `korisnik` k
JOIN korisnik_program kp ON kp.korisnik_id = k.korisnik_id
JOIN program p ON p.id = kp.program_id
where k.korisnik_id = '$korisnik' ORDER BY kp.datum desc;";
    $odgovor = $db->selectDB($upit);  

               
    $ispisiUpisane = "Upisali se program! Četitamo i radujemo se vašim rezultatima! Ukoliko želite upisati još programa, možete ih naći <a href=popis_proizvoda_pravi.php> ovdje </a>. <br> <br>";
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


if (isset($_POST["svi"])) {
    $ispisi = "  ";
    include("baza.class.php");
    $db = new Baza();
    $db->spojiDB();
    $ispisi = "";
    $upit = "SELECT * FROM  `program` ORDER BY vrsta_programa_id;";
    $odgovor = $db->selectDB($upit);  

    if($odgovor->num_rows != 0){
        $ispisi .=  "<table id='tablica'> <th> Naziv </th> <th> Opis </th> <th> Vrsta id </th> <th>  </th>";
         while (list($id, $naziv, $opis, $vrsta_programa_id) = $odgovor->fetch_array())
             {
                $ispisi .= "<tr><td>" . $naziv . "</td>
                                <td>" . $opis . "
                                </td> 
                                <td> 
                                    <form action='popis_proizvoda_pravi.php' method='POST'>
                                     <input type='hidden' name='program_id' value= '$id'>
                                     <input type='submit' value='Upisi' name='Upisi'>
                                    </form>
                                </td>
                            </tr>"; 
            }
        $ispisi .= "</table>";    
    }   
    else{
            $ispisi = "Trenutno nemamo ništa u ponudi!";
        }         
    $db->zatvoriDB();
 }

if (isset($_POST["pretrazi"])) {
    $ispisi = "  ";
    include("baza.class.php");
    $db = new Baza();
    $db->spojiDB();

    $koliko = 0;
    $vrsta = $_POST['vrstaProgramaSelect'];
    $ispisi = "";
  

    if(!empty($_POST["pretraziBroj"])){
        $koliko = $_POST['pretraziBroj'];
        if($koliko > 0){    
            $i = 0;
            $upit = "SELECT * FROM  `program` WHERE vrsta_programa_id =  " . $vrsta . ";";
            $odgovor = $db->selectDB($upit);  

            if($odgovor->num_rows != 0){  
                $ispisi .=  "<table id='tablica'> <th> Naziv </th> <th> Opis </th> <th> Vrsta id </th> <th>  </th>";
                     while ((list($id, $naziv, $opis, $vrsta_programa_id) = $odgovor->fetch_array()) && ($koliko > $i) )
                     {
                        $ispisi .= "<tr><td>" . $naziv . "</td>
                                        <td>" . $opis . "
                                        </td> 
                                        <td> 
                                            <form action='popis_proizvoda_pravi.php' method='POST'>
                                             <input type='hidden' name='program_id' value= '$id'>
                                             <input type='submit' value='Upisi' name='Upisi'>
                                            </form>
                                        </td>
                                    </tr>"; 
                        $i++; 
                    }
                $ispisi .= "</table>";      
            }        
            else{
                $ispisi = "Trenutno nemamo tu vrstu programa u ponudi!";
            }     
    }
}
    else {
        $upit2 = "SELECT * FROM  `program` WHERE vrsta_programa_id =  " . $vrsta . ";";
        $odgovor = $db->selectDB($upit2);
        if($odgovor->num_rows != 0){  

            $ispisi .=  "<table id='tablica'> <th> Naziv </th> <th> Opis </th> <th> Vrsta id </th> <th>  </th>";
                while (list($id, $naziv, $opis, $vrsta_programa_id) = $odgovor->fetch_array()) 
                     {
                        $ispisi .= "<tr><td>" . $naziv . "</td>
                                        <td>" . $opis . "
                                        </td> 
                                        <td> 
                                            <form action='popis_proizvoda_pravi.php' method='POST'>
                                             <input type='hidden' name='program_id' value= '$id'>
                                             <input type='submit' value='Upisi' name='Upisi'>
                                            </form>
                                        </td>
                                    </tr>"; 
                    }
            $ispisi .= "</table>";           
        } 
        else{
            $ispisi = "Trenutno nemamo tu vrstu programa u ponudi!";
        }
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
        <link href="css/matbodulu.css" rel="stylesheet" type="text/css">
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
                <img src="slike/logo.png" class="prvaSlika" alt="Logo foi-a" usemap="#mapa1"/>
                <map name="mapa1">
                    <area href="index.html" alt="index" shape="rect" target="_blank" coords="0,0,200,200"/>
                    <area href="#prijava" alt="prijava" shape="rect" target="_parent" coords="200,0,400,200"/>
                </map>
            </figure>
        </header>

        <nav>
        <ul>
            <li>
                <a href="index.html"> Početna stranica </a>   
            </li>
            <li>
                <a href="novi_proizvod.php"> Novi proizvod </a>   
            </li>
            <li>
                <a href="prijava.php"> Prijava </a>   
            </li>
            <li>
                <a href="proizvod.html"> Proizvod </a>   
            </li>
            <li>
                <a href="otkljucavanje_korisnika.php"> Otkljucavanje zaključanih korisnika </a>
            </li>
            <li>
                <a href="registracija.php"> Registracija </a>   
            </li> 
             <li>
                 <a href="dnevnik.php"> Dnevnik </a>   
            </li> 
             <li>
                <a href="popis_proizvoda_pravi.php"> Popis proizvoda </a> 
            </li>  
            <li>
                <a href="odjava.php"> Odjava </a>   
            </li> 
            <li>
                <a href="o_autoru.html"> O autoru </a>   
            </li>
        </ul>      
    </nav>
    
    <div>
        <?php            
            if(isset($ispisi)){
                echo $ispisi;
            }   
        ?>     
    </div> 

    <div>
        <?php            
            if(isset($ispisiUpisane)){
                echo $ispisiUpisane;
            }
        ?>     
    </div> 
           
        <div <?php if(isset($sakrij)) echo "style= " . '"' . $sakrij . '";' ?> >
            <section>            
                <form id="popis_proizvoda" method = "POST" name="popis_proizvoda" action="popis_proizvoda_pravi.php">

                <div style="display: inline-block;">
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
                </div>
                    <br>
                    <input  type="submit" value="pretrazi" name="pretrazi">
                    <input type="submit" name="svi" value="svi">
                    <input type="submit" name="azuriranje" value="Azuriraj" style=<?php echo $prikazi; ?> >
                </form>       
            </section>
        </div>

    
        <footer>
            <h5> Vrijeme koje sam utroÅ¡ila u rjeÅ¡avanje aktivnog dokumenta je 24h. </h5>   

            <a href="http://jigsaw.w3.org/css-validator/validator?uri=http%3A%2F%2Fbarka.foi.hr%2FWebDiP%2F2016%2Fzadaca_04%2Fmatbodulu%2Fprijava.html&profile=css3&usermedium=all&warning=1&vextwarning=&lang=en">
              <figure>
                <img src="slike/CSS3.png" alt="validacija CSS-a">
                <figcaption> Validacija CSS-a </figcaption>
              </figure> 
            </a>          
        </footer>
    </body>
</html>