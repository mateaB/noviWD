<?php /* Smarty version 3.1.22-dev/30, created on 2015-05-12 14:13:59
         compiled from "predlosci/registracija.tpl" */ ?>
<?php
/*%%SmartyHeaderCode:5338845035551ee8783bdf7_66554898%%*/
if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'd831a80f609a9a5a5621085feca322dd7fe64211' => 
    array (
      0 => 'predlosci/registracija.tpl',
      1 => 1431432745,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '5338845035551ee8783bdf7_66554898',
  'variables' => 
  array (
    'greska' => 0,
    'siteKey' => 0,
  ),
  'has_nocache_code' => false,
  'version' => '3.1.22-dev/30',
  'unifunc' => 'content_5551ee878a44a5_52619402',
),false);
/*/%%SmartyHeaderCode%%*/
if ($_valid && !is_callable('content_5551ee878a44a5_52619402')) {
function content_5551ee878a44a5_52619402 ($_smarty_tpl) {
?>
<?php
$_smarty_tpl->properties['nocache_hash'] = '5338845035551ee8783bdf7_66554898';
?>

           <section id="sadrzaj">  <!--Koristi se kad se ne zna funkcionalnost dijela koji se oce napraviti --> 
		    <article id="greske"> <?php echo $_smarty_tpl->tpl_vars['greska']->value;?>
 </article>
                

               <article>
                  <form name="registracija" id="registracija" action="registracija.php" method="POST" enctype='multipart/form-data'>
                   <!-- koristi se i id i name, id za css a name za kontrolu s javascriptom tada smo dali DOM ime ilitiga  ta oznaka je ujedno i javascript objekt
                   The name is used when sending data in a form submission.
                   With the HTML DOM, JavaScript can access and change all the elements of an HTML document..-->
                   <!--name Specifies a name used to identify the form (for DOM usage: document.forms.name).-->

                   <label for="ime"> Ime: </label>
                   <input type="text" name="ime" maxlength="30" size="20" placeholder="Unesite vaše ime..." id="ime" > <!-- size = vidljivi broj znakova na ekranu -->
                   <br/>
                   <label for="prezime"> Prezime: </label>
                   <input type="text" name="prezime" maxlength="50" size="20" placeholder="Unesite vaše prezime..." id="prezime" >           
                   <br/>
                   <label for="reg-slika"> Slika: </label>
                   <input type="file" name="reg-slika" id="reg-slika"/>
                   <br/>
                   <label for="adresa"> Adresa: </label>
                   <textarea name="adresa" id="adresa" rows="5" cols="20" ></textarea>
                   <br/>
                   <label for="izbornik-zupanija"> Županija: </label>
                   <select name="izbornik_zupanija" id="izbornik-zupanija"> <!-- kada bi bilo multiple(višestruk odabir) name mora biti polje tipa name="zupanije[]"-->
                       <option value="0">Bjelovarsko-bilogorska</option>
                       <option value="1" selected>Varaždinska</option>
                       <option value="1">Dubrovačko-neretvanska</option>
                       <option value="2">Istarska</option>
                       <option value="3">Karlovačka</option>
                       <option value="4">Koprivničko-križevačka</option>
                       <option value="5">Krapinsko-zagorska</option>
                       <option value="6">Ličko-senjska</option>
                       <option value="7">Međimurska</option>
                       <option value="8">Osječko-baranjska</option>
                       <option value="9">Požeško-slavonska</option>
                       <option value="10">Primorsko-goranska</option>
                       <option value="11">Sisačko-moslavačka</option>
                       <option value="12">Splitsko-dalmatinska</option>
                       <option value="13">Virovitičko-podravksa"</option>
                       <option value="14">Vukovarsko-srijemska</option>
                       <option value="15">Zadarska</option>
                       <option value="16">Zagrebačka</option>
                       <option value="17">Šibensko-kninska</option>
                       <option value="18">Grad Zagreb</option>
                   </select>
                   
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
                   <label for="telefon"> Telefon: </label> 
                   <input type ="text" name="telefon" id="telefon" placeholder="[format tipa XXX XXXXXXX]">
                   <br/>
                   <label for="bday">Datum rođenja:</label> 
                   <input type="date" name="bday" id="bday">
                   <br/>
                   <div id="radiogaga">
                    <label for="radio_musko">Spol:</label>
                    <input type="radio" name="spol" id="radio_musko" value="1"> <span class="radio-lab"> Musko </span>
                    &nbsp;&nbsp;
                    <input type="radio" name="spol" id="radio_zensko" value="2"> <span class="radio-lab"> Zensko </span>
                   </div>
                   <br/>
                   
                   <label for="chbx_obav">Zelite li primati obavijesti putem e-poste?:</label>  
                   <input type="checkbox" name ="chbx_obav" id="chbx_obav"> 
                   <br/><br/>
                    <div style="margin-left:40%;width:40"  class="g-recaptcha" data-sitekey="<?php echo $_smarty_tpl->tpl_vars['siteKey']->value;?>
"></div>
                    <?php echo '<script'; ?>
 type="text/javascript"
                    src="https://www.google.com/recaptcha/api.js?hl=eng">
                        
                     <?php echo '</script'; ?>
>
                   <input type="submit" name="potvrda" id="potvrda"  value="Registriraj se" class="gumb"> 
                        &nbsp;&nbsp;&nbsp;&nbsp;
                   <input type="reset" name="reset_botun" id="reset_botun"  value="Ponovni unos" class="gumb" >     
                   <br/>
              
                </form>
               </article>

           </section>  

<?php }
}
?>