<?php /* Smarty version 3.1.22-dev/30, created on 2015-05-12 14:44:33
         compiled from "predlosci/error.tpl" */ ?>
<?php
/*%%SmartyHeaderCode:3198190645551f5b1c01835_21337983%%*/
if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'f8d5fc97d3d06f0924d4365f3f2e25ee5aec49e5' => 
    array (
      0 => 'predlosci/error.tpl',
      1 => 1431434501,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '3198190645551f5b1c01835_21337983',
  'variables' => 
  array (
    'greska' => 0,
  ),
  'has_nocache_code' => false,
  'version' => '3.1.22-dev/30',
  'unifunc' => 'content_5551f5b1c5edc0_68228862',
),false);
/*/%%SmartyHeaderCode%%*/
if ($_valid && !is_callable('content_5551f5b1c5edc0_68228862')) {
function content_5551f5b1c5edc0_68228862 ($_smarty_tpl) {
?>
<?php
$_smarty_tpl->properties['nocache_hash'] = '3198190645551f5b1c01835_21337983';
?>

           <section id="sadrzaj">  <!--Koristi se kad se ne zna funkcionalnost dijela koji se oce napraviti --> 
		    <article id="greske"> <?php echo $_smarty_tpl->tpl_vars['greska']->value;?>
 </article>
           </section>  
         <?php }
}
?>