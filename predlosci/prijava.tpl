  <section id="sadrzaj"> 
	 <article id="greske"> {$greska} </article>
            <article>
                 <form name="prijava" id="prijava" method="POST" enctype='multipart/form-data' action="prijava.php">
                     <label for="koris_ime"> Korisničko ime: </label>  <input type="text" name="koris_ime" id="koris_ime" autofocus  placeholder="Korisnicko ime" value="{$cookieKorisnicko}"> <br/>
                     <label for="lozinka"> Lozinka:</label>   <input type="password" name="lozinka" id="lozinka"  placeholder="Lozinka"><br/>
                     <label for="chbx_pamti"> Zapamti me? </label> <input type="checkbox" name ="chbx_pamti" id="chbx_pamti" > <br/>
                     <input name="potvrda" id="potvrda" type="submit" value="Prijavi se" class="gumb">            
                </form>
             </article>
        </section>  