<?php /* Smarty version 3.1.22-dev/30, created on 2015-05-12 13:56:02
         compiled from "predlosci/popis_korisnika.tpl" */ ?>
<?php
/*%%SmartyHeaderCode:19085635725551ea52554048_04391718%%*/
if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'f927b8ebef0f2b8f9eea6cde0e925520112a498b' => 
    array (
      0 => 'predlosci/popis_korisnika.tpl',
      1 => 1431431751,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '19085635725551ea52554048_04391718',
  'variables' => 
  array (
    'ispis' => 0,
  ),
  'has_nocache_code' => false,
  'version' => '3.1.22-dev/30',
  'unifunc' => 'content_5551ea525aff87_87774596',
),false);
/*/%%SmartyHeaderCode%%*/
if ($_valid && !is_callable('content_5551ea525aff87_87774596')) {
function content_5551ea525aff87_87774596 ($_smarty_tpl) {
?>
<?php
$_smarty_tpl->properties['nocache_hash'] = '19085635725551ea52554048_04391718';
?>
 <section id="sadrzaj"> 
		    <div id="greske">  <?php echo $_smarty_tpl->tpl_vars['ispis']->value;?>
 </div>
</section>  <?php }
}
?>