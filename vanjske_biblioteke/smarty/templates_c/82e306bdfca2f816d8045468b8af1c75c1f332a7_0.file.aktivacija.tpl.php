<?php /* Smarty version 3.1.22-dev/30, created on 2015-05-12 14:38:07
         compiled from "predlosci/aktivacija.tpl" */ ?>
<?php
/*%%SmartyHeaderCode:6673827055551f42f4818f4_83761391%%*/
if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '82e306bdfca2f816d8045468b8af1c75c1f332a7' => 
    array (
      0 => 'predlosci/aktivacija.tpl',
      1 => 1431434266,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '6673827055551f42f4818f4_83761391',
  'variables' => 
  array (
    'greska' => 0,
  ),
  'has_nocache_code' => false,
  'version' => '3.1.22-dev/30',
  'unifunc' => 'content_5551f42f4ed6a1_01465843',
),false);
/*/%%SmartyHeaderCode%%*/
if ($_valid && !is_callable('content_5551f42f4ed6a1_01465843')) {
function content_5551f42f4ed6a1_01465843 ($_smarty_tpl) {
?>
<?php
$_smarty_tpl->properties['nocache_hash'] = '6673827055551f42f4818f4_83761391';
?>

           <section id="sadrzaj">  <!--Koristi se kad se ne zna funkcionalnost dijela koji se oce napraviti --> 
		    <article id="greske"> <?php echo $_smarty_tpl->tpl_vars['greska']->value;?>
 </article>
           </section>  
         <?php }
}
?>