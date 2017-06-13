<?php /* Smarty version 3.1.22-dev/30, created on 2015-05-12 13:52:05
         compiled from "predlosci/_header.tpl" */ ?>
<?php
/*%%SmartyHeaderCode:16720863205551e965c3ff83_09352540%%*/
if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'e5e8234d37cf34c4f62485490732367d44f4c875' => 
    array (
      0 => 'predlosci/_header.tpl',
      1 => 1431298223,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '16720863205551e965c3ff83_09352540',
  'variables' => 
  array (
    'korisnickoIme' => 0,
    'Ime' => 0,
    'Prezime' => 0,
    'zadnjaPrijava' => 0,
    'tip' => 0,
    'id' => 0,
  ),
  'has_nocache_code' => false,
  'version' => '3.1.22-dev/30',
  'unifunc' => 'content_5551e965cde361_94616363',
),false);
/*/%%SmartyHeaderCode%%*/
if ($_valid && !is_callable('content_5551e965cde361_94616363')) {
function content_5551e965cde361_94616363 ($_smarty_tpl) {
?>
<?php
$_smarty_tpl->properties['nocache_hash'] = '16720863205551e965c3ff83_09352540';
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Informacije o kolegiju</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="application-name" content="Registracija">
        <meta name="author" content="Hrvoje Hrvoić">
        <meta name="description" content="Datum izrade: 20.3.2015"> <!--/*??*/-->
         <link rel="stylesheet" type="text/css" href="css/hhrvoic.css"/>
        <link rel="stylesheet" media= "screen and (max-width: 450px)" href="css/hhrvoic_mobitel.css" />
        <link rel="stylesheet" media="screen and (min-width: 450px) and (max-width: 800px) " href="css/hhrvoic_tablet.css" />
        <link rel="stylesheet" media="screen and (min-width: 800px) and (max-width: 1000px) " href="css/hhrvoic_pc.css" />
        <link rel="stylesheet" media="screen and (min-width: 1000px) " href="css/hhrvoic_tv.css" />
    </head> 
    <body> <!-- tu bi moglo se staviti dio za prijavu pošto te mora roknut na prijavu ako nisi prijavljen a header se uvijek učitava -->
   
        <div id="cijela">
                  <header id="zaglavlje">  <!--novi tag u html 5 prije se koristio div za sve -->
                  <div id="podaci">
                      <ul>
                          <?php if (isset($_smarty_tpl->tpl_vars['korisnickoIme']->value)) {?> 
                          <li> Prijavljeni ste kao: <?php echo $_smarty_tpl->tpl_vars['Ime']->value;?>
 <?php echo $_smarty_tpl->tpl_vars['Prezime']->value;?>
 </li> 
                          <li> Vrijeme zadnje prijave:<?php echo $_smarty_tpl->tpl_vars['zadnjaPrijava']->value;?>
</li>   
                          <li> <a  href='odjava.php ' class="mail-link"> Odjava </a> </li>  
                          <?php }?>
                      </ul>
                 </div>
                 Hrvoje Hrvoić <br/> 
                 <img src="img/logo.png" alt="foi_logo">  <!--Relativna putanja(preporuceno) -->
                
                
            </header>
            
            <nav id="meni"> 
                <ul>
                <li>  <a href="index.php">Početna</a> </li>
                 <?php if (!isset($_smarty_tpl->tpl_vars['korisnickoIme']->value)) {?> <!-- neprijavljeni-->
                <li>  <a href="prijava.php">Prijava</a> </li>
                <li>  <a href="registracija.php">Registracija</a> </li>
                <?php } else { ?> <!-- prijavljeni-->
                <?php if ($_smarty_tpl->tpl_vars['tip']->value==1) {?>   <li>  <a href="popis_korisnika.php">Popis Korisnika</a> </li> <?php }?>
                <li>  <a href="detalji_korisnika.php?id=<?php echo $_smarty_tpl->tpl_vars['id']->value;?>
">Vaš profil</a> </li>
                <li>  <a href="http://foi.unizg.hr" target="_blank"> FOI WEB </a> </li>
                <?php }?>
                </ul>
            </nav> 

<?php }
}
?>