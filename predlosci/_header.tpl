<nav>
      <ul>
          <li>
              <a href="index.html"> Početna stranica </a>   
          </li>
          <li>
              <a href="prijava.html"> Prijava </a>   
          </li>
          <li>  <a href="programiNeregistrirani.php"> Popis programa </a>
          </li>

          <?php 
          $ispisi = "";
          if(!isset($tipKorisnika)){
              $ispisi .= "<li> <a href='registracija.php'>  </a> </li>";
          }
          else{
            $ispisi .= "<li> <a href='vidiKupone.php'> Kuponi </a> </li>";
            
            if($tipKorisnika == 2){
            $ispisi .= "
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
            ";}

            if($tipKorisnika == 1){
              $ispisi .= "
            <li>
                <a href='novi_program.php'> Novi program </a>   
            </li>
            
            <li>
                <a href='azurirajProgram.php'> Azuriraj program </a>   
            </li>
            <li>
                <a href='novi_kupon.php'> Novi kupon </a>   
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
            ";}

            if($tipKorisnika == 3){
              $ispisi .= "
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
          $ispisi .= " <li>
                    <a href='odjava.php'>  Odjava </a>   
                </li> 
                <li>
                    $korisnickoIme;
                </li>
            </div>";
          }
          echo $ispisi;
           ?>
      </ul>      
    </nav>