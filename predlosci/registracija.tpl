<!--NAPOMENA: baza je već prilagođena potrebama projekta(light verzija registracije) pa nema spola,telefona, slike, i ostalih drugih stvari.-->
           <section id="sadrzaj">  <!--Koristi se kad se ne zna funkcionalnost dijela koji se oce napraviti --> 
		    <article id="greske"> {$greska} </article>
                

               <article>
                  <form name="registracija" id="registracija" action="registracija.php" method="POST" enctype='multipart/form-data'>

                   <label for="ime"> Ime: </label>
                   <input type="text" name="ime" maxlength="30" size="20" placeholder="Unesite vaše ime..." id="ime" > <!-- size = vidljivi broj znakova na ekranu -->
                   <br/>
                   <label for="prezime"> Prezime: </label>
                   <input type="text" name="prezime" maxlength="50" size="20" placeholder="Unesite vaše prezime..." id="prezime" >           
                   <br/>
                   <label for="adresa"> Adresa: </label>
                   <textarea name="adresa" id="adresa" rows="5" cols="20" ></textarea>
                   <br/>  
                   <br/><br/>
                   <label for="grad"> Grad: </label>
                   <input type="text" name="grad" id="grad" maxlength="30" size="20"  placeholder="Upisite vas grad...">  
                   <br/>
                   <label for="email">email(korisnicko ime):</label> 
                    <input type="email" name="email" id="email" > 
                   <br/>
                   <label for="lozinka"> Lozinka(*) :</label> 
                   <input type="password" name="lozinka" id="lozinka">
                   <br/>      
                   <br/>
                   
                   <label for="chbx_obav">Zelite li primati obavijesti putem e-poste?:</label>  
                   <input type="checkbox" name ="chbx_obav" id="chbx_obav"> 
                   <br/><br/>
                    <div style="margin-left:40%;width:40"  class="g-recaptcha" data-sitekey="{$siteKey}"></div>
                    <script type="text/javascript"
                    src="https://www.google.com/recaptcha/api.js?hl=eng">
                        
                     </script>
                   <input type="submit" name="potvrda" id="potvrda"  value="Registriraj se" class="gumb"> 
                        &nbsp;&nbsp;&nbsp;&nbsp;
                   <input type="reset" name="reset_botun" id="reset_botun"  value="Ponovni unos" class="gumb" >     
                   <br/>
              
                </form>
               </article>

           </section>  

