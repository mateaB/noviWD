<?php
session_start();

header('Content-Type: text/html; charset=utf-8');

if(isset($_SESSION['korisnik_id'])){
$korisnikIme = $_SESSION['korisnickoIme'];
$korisnik = $_SESSION['korisnik_id'];
$tipKorisnika = $_SESSION['tip_korisnika'];
}

?>

<!DOCTYPE html>
<html lang="hr">
	<head>
		<title> Početna stranica </title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="naslov" content="Početna stranica">
        <meta name="kljucne-rijeci" content="zadaca_1, početna, index">
        <meta name="datum_izrade" content="09.03.2017.">
        <meta name="autor" content="matea bodulušić">
        <link rel="stylesheet" type="text/css" href="css/osnova.css">
  </head>
    
	<body>
		<header>
        <figure style="margin: 0px">
            <figcaption class="naslov"> Početna </figcaption>
            <img src="slike/skok.jpg" class="prvaSlika" alt="Logo skok" usemap="#mapa1"/>
            <map name="mapa1">
                <area href="index.php" alt="index" shape="rect" target="_blank" coords="0,0,200,200"/>
                <area href="#o_teretani" alt="o_teretani" shape="rect" target="_parent" coords="200,0,400,200"/>
            </map>
        </figure>
    </header>

   <?php
    include 'nav.php';
   ?>



    

        	<section id="o_meni">

        		<h1> Osnovni podaci o meni </h1>
				<ul>
						<li>
							Ime: Matea					
						</li>

						<li>
							Prezime: Bodulušić			
						</li>

						<li>
							E-mail adresa: matbodulu@foi.hr
						</li>
						<li>
							Broj indeksa: 43126
						</li>
					</ul>
				<figure>
		        	<img  src="slike/ja.jpg" alt="moja slika"/>
		        	<figcaption> Moja slika </figcaption>
		    	</figure>   
	   		</section>
            
	     <section id="proizvodi">
	     	<h2> 5 stvari koje volim </h2>
				<article >
					<h3> 
						Jabuke
					</h3>
					<p> Jabuka je najrasprostranjenija vrsta voća; osvježavajuća, kiselo-slatkog okusa i svojstvene arome. Plod jabuke bogat je hranjivim sastojcima čija količina ovisi o vrsti te o načinu uzgoja, a gotovo svi potrebni nutrijenti prisutni su barem u minimalnim količinama. Jede se sirova, ali i kao dodatak slatkim i slanim jelima. </p>
					<figure>
						<img src="slike/jabuka.jpg">
							<figcaption> Jabuka </figcaption>
					</figure>
				</article>

				<article class="quinoa">
					<h3>  Quinoa </h3>
						<p> Quinoa je jedna od najstarijih žitarica na svijetu. Svjetska zdravstvena organizacija (FAO) uspoređuje hranjivost i nutritivni sastav s namirnicom kao što je mlijeko. Kuhana quinoa je zanimljiv dodatak salatama, juhama, prilozima, povrtnim i mesnim jelima, a upotrebljava se i u pripremi kolača i kruha. Ja ju pripremam "na salatu".
						</p>
						<figure>
							<img src="slike/quinoa.jpg">
							<figcaption> Quinoa </figcaption>
						</figure>
				</article>

				<article>
					<h3>  Kava	</h3>
						<p>
						Kava je tropska biljka, grm ili stablo s plodovima crvenih bobica koje nalikuju trešnji. Potiče koncentraciju, kognitivne procese, ubrzava procese obrade informacija u mozgu, ublažava glavobolju i podiže raspoloženje. Ja volim kavu sa svim dodacima, ali najčešće pijem tursku.  
						</p>
						<figure>
							<img src="slike/kava.jpg">
							<figcaption> Kava </figcaption>
						</figure>
				</article>
				<article class="banane">
					<h3>  Banane </h3>
						<p>
						Jedem ih kao međuobrok (samu ili kao dio voćne salate), dodatak smoothie-u, ali volim ih i u kolacima. To je definitivno moje najdraže voće.
						</p>
						<figure>
							<img src="slike/banane.jpg">
							<figcaption> Banane </figcaption>
						</figure>
				</article>
				<article class="voda">
					<h3> Voda </h3>	
						<p>
							Voda je obična tekućina, bez boje okusa i mirisa. Smatram ju najpotrebnijim, njaboljim i najzdravijim napitkom. Potrebno je piti tijekom cijeloga dana i izbjeći pojavu žeđi odnosno dehidraciju. 
						</p>
						<figure>
							<img src="slike/voda.jpg">
							<figcaption> Voda </figcaption>
						</figure>
				</article>
			</section> 

		<?php 
			include 'footer.php';
		 ?>
	</body>
</html>