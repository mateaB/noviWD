 <section id="sadrzaj">  
    <article id="greske"> {$greska} </article>
    <article>
   <form name="registracija" id="registracija"  method="POST" action="detalji_korisnika.php"> 

   <label for="ime"> Ime: </label>
   <input type="text" name="ime" maxlength="30" size="20" value="{$ime}" id="ime" > <!-- size = vidljivi broj znakova na ekranu -->
   <br/>
   <label for="prezime"> Prezime: </label>
   <input type="text" name="prezime" maxlength="50" size="20" value="{$prezime}"  id="prezime" >           
   <br/>
   <label for="slika"> Slika: </label>
   <input style="width:20%; margin-left:15%" type="image" name="slika" id="slika" src="img/hhrvoic.png" height ="20%"alt="slika"/>
   <br/>
   <label for="grad"> Grad: </label>
   <input type="text" name="grad" id="grad" maxlength="30" size="20" value="{$grad}">  
   <br/>
   <label for="adresa"> Adresa: </label>
   <textarea name="adresa" id="adresa" rows="5" cols="20" >{$adresa}</textarea>
   <br/>
   <br/><br/>
   <label for="email">email(korisnicko ime):</label> 
    <input type="text" name="email" id="email" value="{$email}" > 
   <br/> 
   <br/>
   <label for="lozinka"> Lozinka(*) :</label> 
   <input type="password" name="lozinka" id="lozinka" value="{$lozinka}">  
   <br/>
   <br/><br/>
    <input type="hidden" name ="idMijenjanog" id="idMijenjanog" value= "{$idGET}" > <!--da php zna kome mijenjat u bazi podatke, stavljeno jer admin more mijenjati vise stvari pa nemre preko session varijable u php-u -->
   <input type="submit" name="promjeni" id="promjeni"  value="Promijeni podatke" class="gumb"> 
   {if $tip==1 && $nisamja==1} <input type ="submit" name="aktiviraj" id="aktiviraj" value="{$aktiviranTekst}" class="gumb"> {/if}
   <br/>
</form>
</article>

</section>  