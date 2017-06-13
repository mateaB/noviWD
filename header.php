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

   
<nav>
      <ul>
          <li>
              <a href="index.php"> Početna stranica </a>   
          </li>
          
          <li>  <a href="programiNeregistrirani.php"> Popis programa </a>
          </li>

          <?php 
          $ispisiS = "";
          if(!isset($tipKorisnika)){
              $ispisiS .= "<li>
                             <a href='registracija.php'> Registracija </a>
                           </li>
                          <li>
                            <a href='prijava.php'> Prijava </a>   
                          </li>";
          }
          else{
            $ispisiS .= "
            <li> <a href='vidiKupone.php'> Kuponi </a> 
            </li> 
            <li>
              <a href='mojaEvidencijaDolazaka.php'> Moja evidencija dolazaka </a>
            </li>";

            
            if($tipKorisnika == 2){
            $ispisiS .= "
            <li>
                <a href='nova_vrsta_programa.php'> Nova vrsta programa </a>   
            </li>
            
            <li>
                <a href='azuriraj_vrstu_programa.php'> Azuriraj vrstu programa </a>   
            </li>
            <li>
                <a href='novi_kupon.php'> Novi kupon </a>   
            </li>         
            <li>
                <a href='azurirajKupon.php'> Azuriraj kupon </a> 
            </li>  
            <li>
              <a href='lojalnost.php'> Lojalnost </a>
            </li>
             <li>
              <a href='dnevnik.php'> Dnevnik </a>
            </li>
            <li> <a href='trosenjeBodova.php'> Korisnik - akcija </a>
            </li>
             <li>
              <a href='dodjela_uloga.php'> Dodjela uloga </a>
            </li>
             <li>
              <a href='otkljucavanje_korisnika.php'> Otključavanje korisnika </a>
            </li>
            <li>
              <a href='blokiraj_korisnika.php'> Blokiranje korisnika </a>
            </li> ";
            }

            if($tipKorisnika == 1){
              $ispisiS .= "
            <li>
                <a href='novi_program.php'> Novi program </a>   
            </li>
            
            <li>
                <a href='azurirajProgram.php'> Azuriraj program </a>   
            </li>
                    
            <li>
                <a href='azurirajKupon.php'> Azuriraj kupon </a> 
            </li> 
            <li>
                <a href='novi_trening.php'> Novi trening </a>   
            </li>         
            <li>
                <a href='azurirajTrening.php'> Azuriraj trening </a> 
            </li> 
            <li> <a href='promijenaStatusaTreninga.php'> Status treninga </a>
            </li>
            <li> <a href='novi_kupon_MODERATOR.php'> Novi kupon - DETALJI </a>
            </li>
            
            ";}

            if($tipKorisnika == 3){
              $ispisiS .= "
            <li>
              <a href='lojalnost.php'> Lojalnost </a>
            </li>  
             <li>
              <a href='evidencijaDolazaka.php'> Evidencija dolazaka </a>
            </li>
             <li>
              <a href='kosarica.php'> Kosarica </a>
            </li>
             <li>
              <a href='evidencijaBodova.php'> Stanje bodova </a>
            </li>
            ";}    
          $ispisiS .= "<li> <a href='o_autoru.html'> O autoru </a> </li>
           <div style = 'float: right' >
                <li>
                    <a href='odjava.php'>  Odjava </a>   
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