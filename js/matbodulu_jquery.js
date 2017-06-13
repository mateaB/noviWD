$(document).ready(function () {
    $("kor_ime_reg").attr("disabled", true);
	$("#kor_ime_reg").focusout(function (event) {
        var ime = $("#ime").val();
        var prezime = $("#prezime").val();
        var korime = $("#korIme").val();
        var korime_provjera;
        $.ajax({
            url: 'http://barka.foi.hr/WebDiP/2016/materijali/zadace/dz3/korisnikImePrezime.php',
            data: {ime: ime, prezime: prezime},
            type: 'GET',
            dataType: 'xml',
            success: function (xml) {
                $(xml).find('korisnik').each(function () {
                    if (kor_ime_reg === $(this).find('korisnicko_ime').text()) {
                        alert("Korisničko ime već postoji");
                        $("#lozinka_reg").attr("disabled", true);
                        $("#pot_lozinka_reg").attr("disabled", true);
                    }
                    if (kor_ime_reg !== $(this).find('korisnicko_ime').text()){
                        alert("Korisnik ne postoji");
                        $("#lozinka_reg").removeAttr("disabled");
                        $("#pot_lozinka_reg").removeAttr("disabled");
                    }
                });
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(textStatus);
            }
        });
    });
 });