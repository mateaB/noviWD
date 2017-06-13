<?php /* Smarty version 3.1.22-dev/30, created on 2015-05-12 13:55:13
         compiled from "predlosci/prijava.tpl" */ ?>
<?php
/*%%SmartyHeaderCode:3368549775551ea2114e4a7_95194050%%*/
if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'f4dcb486bf37f3025c74b9b62ccc504e972eabca' => 
    array (
      0 => 'predlosci/prijava.tpl',
      1 => 1431278411,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '3368549775551ea2114e4a7_95194050',
  'variables' => 
  array (
    'greska' => 0,
    'cookieKorisnicko' => 0,
  ),
  'has_nocache_code' => false,
  'version' => '3.1.22-dev/30',
  'unifunc' => 'content_5551ea211aeb98_43617093',
),false);
/*/%%SmartyHeaderCode%%*/
if ($_valid && !is_callable('content_5551ea211aeb98_43617093')) {
function content_5551ea211aeb98_43617093 ($_smarty_tpl) {
?>
<?php
$_smarty_tpl->properties['nocache_hash'] = '3368549775551ea2114e4a7_95194050';
?>
  <section id="sadrzaj"> 
	 <article id="greske"> <?php echo $_smarty_tpl->tpl_vars['greska']->value;?>
 </article>
            <article>
                 <form name="prijava" id="prijava" method="POST" enctype='multipart/form-data' action="prijava.php">
                     <label for="koris_ime"> Korisničko ime: </label>  <input type="text" name="koris_ime" id="koris_ime" autofocus  placeholder="Korisnicko ime" value="<?php echo $_smarty_tpl->tpl_vars['cookieKorisnicko']->value;?>
"> <br/>
                     <label for="lozinka"> Lozinka:</label>   <input type="password" name="lozinka" id="lozinka"  placeholder="Lozinka"><br/>
                     <label for="chbx_pamti"> Zapamti me? </label> <input type="checkbox" name ="chbx_pamti" id="chbx_pamti" > <br/>
                     <input name="potvrda" id="potvrda" type="submit" value="Prijavi se" class="gumb">            
                </form>
             </article>
        </section>  <?php }
}
?>