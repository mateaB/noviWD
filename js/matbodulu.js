function zaokruzi(){
	console.log("provjera");
	nazivProizvoda = document.getElementById('naziv');
	opisProizvoda = document.getElementById('opis');
	znakovi = new Array("(", ")", "{", "}", "'", "!", "#", "“", "/");
	datum = document.getElementById('datum');
	vrijeme = document.getElementById('Vrijeme').value;
	kolicina = document.getElementById('kol').value;
	btnPosalji = document.getElementById('slanje');
	kategorija = document.getElementById('kategorija');
	allSelect = document.getElementsByTagName('select');

	lblNaziv = document.getElementById('lblNaziv');

	greskeNaziv = "";
	greskeOpis = "";
	greskeDatum = "";
	greskeVrijeme = "";
	greskeKolicina = "";
	greskeTezina = "";
	greskeKategorija = "";
	greske = document.getElementById("greske");
	btnOsvjezi = document.getElementById('osvjezi');


	usklicnik = "!";
	usklicnikNaziv = "";
	usklicnikOpis = "";
	usklicnikDatum = "";
	usklicnikVrijeme = "";
	usklicnikKol = "";
	usklicnikTezina = "";
	usklicnikKategorija = "";
	usklicnikOpis = " ";
	lblUsklicnik = document.getElementById('usklicnik');
	lblUsklicnik.style.color = "red";
	lblUsklicnikOpis = document.getElementById('usklicnikOpis');
	lblUsklicnikOpis.style.color  = "red";
	lblUsklicnikDatum = document.getElementById('usklicnikDatum');
	lblUsklicnikDatum.style.color = "red";
	lblUsklicnikVrijeme = document.getElementById('usklicnikVrijeme');
	lblUsklicnikVrijeme.style.color = "red";
	lblUsklicnikKol =  document.getElementById('usklicnikKol');
	lblUsklicnikKol.style.color = "red";
	lblUsklicnikTezina = document.getElementById('usklicnikTezina');
	lblUsklicnikTezina.style.color = "red";
	lblUsklicnikKategorija = document.getElementById('usklicnikKategorija');
	lblUsklicnikKategorija.style.color = "red";
	

	datum.addEventListener("keyup", function(event){
		imaGresaka = true;
		greske.innerHTML = greskeNaziv + greskeOpis + greskeDatum+greskeVrijeme + greskeKolicina + greskeTezina  + greskeKategorija;
		console.log("datum radi na klik" + datum.value);	

			if(imaGresaka === true){
				greske.innerHTML = greskeNaziv;
				lblUsklicnikDatum.innerHTML = usklicnik;
			}
			else{
				lblUsklicnikDatum.innerHTML = " ";
			}	

		event.preventDefault();
	}, false);




	function provjeriVrijeme(event){
		greske.innerHTML = greskeNaziv + greskeOpis + greskeDatum+greskeVrijeme + greskeKolicina + greskeTezina  + greskeKategorija;
		greskeVrijeme = "";
		for (var i = 0; i < znakovi.length; i++) {
			if(vrijeme.indexOf(znakovi[i]) !== -1){
				greske.innerHTML += "U vremenu ne smije biti '" + znakovi[i] + "'.";
				event.preventDefault();
			}
		}
	}

	function provjeriKolicinu(event){
		greskeKolicina = "";
		greske.innerHTML = greskeNaziv + greskeOpis + greskeDatum+greskeVrijeme + greskeKolicina + greskeTezina  + greskeKategorija;
		for (var i = 0; i < znakovi.length; i++) {
			if(kolicina.indexOf(znakovi[i]) !== -1){
				greske.innerHTML += "U kolicini ne smije biti '" + znakovi[i] + "',";
				event.preventDefault();
			}
		}

	}


	nazivProizvoda.addEventListener("keyup",function(event) {
			greske.innerHTML = greskeNaziv + greskeOpis + greskeDatum+greskeVrijeme + greskeKolicina + greskeTezina  + greskeKategorija;
			greskeNaziv = " ";
			imaGresaka = false;
			proizvod = nazivProizvoda.value;
			var naziv = lblNaziv.innerHTML;
			if(proizvod.length > 1){
				if(proizvod.length < 5){
				imaGresaka = true;
				greskeNaziv  += ("Naziv mora imati više od 5 elemenata! <br>");
				event.preventDefault();
			}

			prviZnak = proizvod.charAt(0);
			if(prviZnak !== prviZnak.toUpperCase()){
				imaGresaka = true;
				greskeNaziv += "Naziv treba počinjati velikim slovom! <br>";
				event.preventDefault();
			}			

			for (var i = 0; i < znakovi.length; i++) {
				if(proizvod.indexOf(znakovi[i]) !== -1){
					imaGresaka = true;
					greskeNaziv += "U nazivu ne smije biti '" + znakovi[i] + "' <br>";
					event.preventDefault();
				}
			}		
		}
			else{
				greskeNaziv = "";
			}

			if(imaGresaka === true){
				greske.innerHTML = greskeNaziv;
				lblUsklicnik.innerHTML = usklicnik;
			}
			else{
				lblUsklicnik.innerHTML = " ";
			}	
		}
		, false);



	opisProizvoda.addEventListener("keyup", function(event){
		imaGresakaUOpisu = false;
		greske.innerHTML = greskeNaziv + greskeOpis + greskeDatum+greskeVrijeme + greskeKolicina + greskeTezina  + greskeKategorija;
		greskeOpis = " ";
		velikoSlovo = false;
		opis = opisProizvoda.value;
		var poTocki = opis.split('. ');
		if(poTocki.length > 1){
			if(poTocki.length > 3){
			for (var i = 0; i < znakovi.length; i++) {
				if(opis.indexOf(znakovi[i]) !== -1){
					imaGresakaUOpisu = true;
					greskeOpis += "U opisu ne smije biti '" + znakovi[i] + "' <br>";
				}
			}

			for(i=0; i < poTocki.length; i++){
				if(poTocki[i].charAt(0) !== poTocki[i].charAt(0).toUpperCase()){
					velikoSlovo = true;
				}
			}
			}
			else{
				imaGresakaUOpisu = true;
				greskeOpis += "Morate upisati barem 3 rečenice! <br>";
			}
		}
		else{
			greskeOpis = " ";
		}

		if(velikoSlovo === true){
				greskeOpis += ("Rečenica mora početi s velikim slovom!! <br>");
				imaGresakaUOpisu = true;
		}	
			

		if(imaGresakaUOpisu === true){
				greske.innerHTML += greskeOpis;
				lblUsklicnikOpis.innerHTML = usklicnik;
				event.preventDefault();
			}
			else{
				greske.innerHTML += greskeOpis;
				lblUsklicnikOpis.innerHTML = " ";
			}
	}, false);

	kategorija.addEventListener("click", function(event){
		greske.innerHTML = greskeNaziv + greskeOpis + greskeDatum+greskeVrijeme + greskeKolicina + greskeTezina  + greskeKategorija;
		greskeKategorija = "";
		imaGresaka = false;
		for(i=0; i < allSelect.length; i++){
			if(allSelect[i].value >= 0 && allSelect[i].value <= 4){
				console.log("Odabrali ste opciju " + allSelect[i].value);
			}
			else
			{
				imaGresaka = true;
				greske.innerHTML += "Morate odabrati opciju!!";
				event.preventDefault();
			}
		}

		if(imaGresaka === true){
				lblUsklicnik.style.color = "red";
				greske.innerHTML = greske;
				lblUsklicnik.innerHTML = usklicnik;
			}
			else{
				lblUsklicnik.innerHTML = " ";
			}
		event.preventDefault();
	}, false)

/*
	var trajanje=false;
    setTimeout(function(){
        trajanje=true;
    },300000);
           
    btnPosalji.addEventListener("click", function(event){
            if(trajanje === true){
                document.getElementById("osvjezi").style.visibility = 'visible';
                nazivProizvoda.disabled = true;
                opisProizvoda.disabled = true;
                datum.disabled = true;
                vrijeme.disabled = true;
               	kolicina.disabled = true;
                provjeriTezinu.disabled = true;
                kategorija.disabled = true;
               	btnPosalji.disabled = true;   
            }
    });

    btnOsvjezi.addEventListener("click",function(){window.location.reload();});

    */
}


function registracijaJS(){
	console.log("Uključena");
	stranica = document.getElementById('registracija');
	ime = document.getElementById('ime_reg');
	prezime = document.getElementById('prezime_reg');
	korIme = document.getElementById('kor_ime_reg');
	lozinka = document.getElementById('lozinka_reg');
	lozinkaPotvrda = document.getElementById('pot_lozinka_reg');
	btnPredaj = document.getElementById('predaj');
	postojiIme = document.getElementById('postoji');
	razliciteLozinke = document.getElementById('razlicite');
	korIme.disabled = true;

	ime.addEventListener('keyup', function(){
		if(ime.value.length > 0){

			if(prezime.value.length != 0){
			korIme.disabled = false;
			}

		}
		else{
			korIme.disabled = true;
		}
	}, false);

	prezime.addEventListener('keyup', function(){
		if(prezime.value.length > 0){
			if(ime.value.length != 0){
				korIme.disabled = false;
			}
		}
		else{
			korIme.disabled = true;
		}

	}, false);

	lozinkaPotvrda.addEventListener('keyup', function(){
		if(lozinkaPotvrda.value.length > 0 && lozinka.value.length > 0){
				if(lozinkaPotvrda.value === lozinka.value){
					// debugger
					razliciteLozinke.innerHTML = "Potvrda lozinke je ista kao lozinka!";
				}
				else{
					razliciteLozinke.innerHTML = "Potvrda lozinke NIJE ista kao lozinka!";
				}
				 debugger
			}
		else{

			razliciteLozinke = " ";
		}
	}, false);

}

