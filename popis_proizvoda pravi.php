<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<?php 
session_start();

header('Content-Type: text/html; charset=utf-8');
$ispisi = "";

                if (isset($_POST["pretrazi"])) {
                    $koliko = 0;

                    if(!empty($_POST["pretraziBroj"])){
                        $koliko = $_POST["pretraziBroj"];

                        if($koliko > 0){    
                            include("baza.class.php");
                            $db = new Baza();
                            $db->spojiDB();
                            $upit = "SELECT * FROM  `program` LIMIT " . $koliko . ";";
                            $odgovor = $db->selectDB($upit);  

                            $ispisi .=  "<table><tr><th>ID</th><th>Name</th></tr>";
                            while (list($id, $naziv, $opis) = $odgovor->fetch_array())                         {
                                $ispisi .= "<tr><td>" . $id . "</td>
                                <td>" . $naziv . " " . $opis . "</td></tr>";
                            }
                            $ispisi .= "</table>";
                        } else {
                            $ispisi = "0 results";
                        }              
                        $db->zatvoriDB();
                    }
                    else {
                        $ispisi = "Nema zapisa u bazi!";
                    }
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

            table thead {
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
    

          <form id="popis_proizvoda" method = "POST" name="popis_proizvoda" action="prijava.php">
            <label for="pretraziBroj"> Broj programa: </label>
            <input type="text" name="pretraziBroj" id="pretraziBroj"> 
            <input  type="submit" value="pretrazi" name="pretrazi">
            <?php
            echo $ispisi;
            ?>      
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