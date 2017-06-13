<?php

session_start();
header('Content-Type: text/html; charset=utf-8');
$korisnik = $_SESSION['korisnik_id'];
$korisnikIme = $_SESSION['korisnickoIme'];
include_once './baza.class.php';
$baza = new Baza();
$baza->spojiDB();
$greska = "";

//dnevnik
$korisnikIme = $_SESSION['korisnickoIme'];
$radnja = "Korisnik " . $korisnikIme . " se odjavio!";
$datum = date("Y-m-d H:i:s");
$dnevnik = "INSERT INTO `dnevnik`(  `korisnik`, `datum`, `Opis`, `tip_akcije`)  values  ('$korisnik','$datum ',' $radnja ', 2)";
$baza->selectDB($dnevnik);

session_unset();
session_destroy();
header("Location:prijava.php");
?>