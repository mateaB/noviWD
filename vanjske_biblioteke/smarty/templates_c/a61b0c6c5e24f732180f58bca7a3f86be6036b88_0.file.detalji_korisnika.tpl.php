<?php /* Smarty version 3.1.22-dev/30, created on 2015-05-12 13:55:10
         compiled from "predlosci/detalji_korisnika.tpl" */ ?>
<?php
/*%%SmartyHeaderCode:17101416965551ea1ed97522_29113510%%*/
if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'a61b0c6c5e24f732180f58bca7a3f86be6036b88' => 
    array (
      0 => 'predlosci/detalji_korisnika.tpl',
      1 => 1431295054,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '17101416965551ea1ed97522_29113510',
  'variables' => 
  array (
    'greska' => 0,
    'ime' => 0,
    'prezime' => 0,
    'adresa' => 0,
    'grad' => 0,
    'email' => 0,
    'lozinka' => 0,
    'telefon' => 0,
    'checkiranMusko' => 0,
    'checkiranZensko' => 0,
    'idGET' => 0,
    'tip' => 0,
    'aktiviranTekst' => 0,
  ),
  'has_nocache_code' => false,
  'version' => '3.1.22-dev/30',
  'unifunc' => 'content_5551ea1ee56e46_25291628',
),false);
/*/%%SmartyHeaderCode%%*/
if ($_valid && !is_callable('content_5551ea1ee56e46_25291628')) {
function content_5551ea1ee56e46_25291628 ($_smarty_tpl) {
?>
<?php
$_smarty_tpl->properties['nocache_hash'] = '17101416965551ea1ed97522_29113510';
?>
 <section id="sadrzaj">  <!--Koristi se kad se ne zna funkcionalnost dijela koji se oce napraviti --> 
    <article id="greske"> <?php echo $_smarty_tpl->tpl_vars['greska']->value;?>
 </article>
    <article>
   <form name="registracija" id="registracija"  method="POST" action="detalji_korisnika.php"> 

   <label for="ime"> Ime: </label>
   <input type="text" name="ime" maxlength="30" size="20" value="<?php echo $_smarty_tpl->tpl_vars['ime']->value;?>
" id="ime" > <!-- size = vidljivi broj znakova na ekranu -->
   <br/>
   <label for="prezime"> Prezime: </label>
   <input type="text" name="prezime" maxlength="50" size="20" value="<?php echo $_smarty_tpl->tpl_vars['prezime']->value;?>
"  id="prezime" >           
   <br/>
   <label for="slika"> Slika: </label>
   <input style="width:20%; margin-left:15%" type="image" name="slika" id="slika" src="img/hhrvoic.png" height ="20%"alt="slika"/>
   <br/>
   <label for="adresa"> Adresa: </label>
   <textarea name="adresa" id="adresa" rows="5" cols="20" ><?php echo $_smarty_tpl->tpl_vars['adresa']->value;?>
</textarea>
   <br/>
   <br/><br/>
   <label for="grad"> Grad: </label>
   <input type="text" name="grad" id="grad" maxlength="30" size="20" value="<?php echo $_smarty_tpl->tpl_vars['grad']->value;?>
">  
   <br/>
   <label for="email">email(korisnicko ime):</label> 
    <input type="text" name="email" id="email" value="<?php echo $_smarty_tpl->tpl_vars['email']->value;?>
" > 
   <br/> 
   <br/>
   <label for="lozinka"> Lozinka(*) :</label> 
   <input type="password" name="lozinka" id="lozinka" value="<?php echo $_smarty_tpl->tpl_vars['lozinka']->value;?>
">
   <br/>      
   <label for="telefon"> Telefon: </label> 
   <input type ="text" name="telefon" id="telefon" value="<?php echo $_smarty_tpl->tpl_vars['telefon']->value;?>
">
   <br/>   
    <label for="radio_musko">Spol:</label>
    <input type="radio" name="spol" id="radio_musko" value="1" <?php if ($_smarty_tpl->tpl_vars['checkiranMusko']->value==true) {?> checked <?php }?> > <span class="radio-lab"> Musko </span>
    &nbsp;&nbsp;
    <input type="radio" name="spol" id="radio_zensko" value="2"  <?php if ($_smarty_tpl->tpl_vars['checkiranZensko']->value==true) {?> checked <?php }?>> <span class="radio-lab"> Zensko </span>
   <br/>
   <br/><br/>
    <input type="hidden" name ="idMijenjanog" id="idMijenjanog" value= "<?php echo $_smarty_tpl->tpl_vars['idGET']->value;?>
" > <!--da php zna kome mijenjat u bazi podatke, stavljeno jer admin more mijenjati vise stvari pa nemre preko session varijable u php-u -->
   <input type="submit" name="promjeni" id="promjeni"  value="Promijeni podatke" class="gumb"> 
   <?php if ($_smarty_tpl->tpl_vars['tip']->value==1) {?> <input type ="submit" name="aktiviraj" id="aktiviraj" value="<?php echo $_smarty_tpl->tpl_vars['aktiviranTekst']->value;?>
" class="gumb"> <?php }?>
   <br/>
</form>
</article>

</section>  <?php }
}
?>