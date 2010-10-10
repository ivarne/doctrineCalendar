function autocomplete1()
    {
        // Definer RegExp objekt som finner ordet fra tekstfelt id="oppslag"
        var ord = new RegExp(window.document.getElementById("oppslag").value , "i");
        // Gå gjennom alle elementene i array ordliste
        var antallRader = window.document.getElementById("ordliste_tabell").rows.length;
        var ref = window.document.getElementById("ordliste_tabell");
        var i; 
        for (i = 1; i < antallRader; i++)
        {
            // og hvis RegExp funksjonen finner ordet 
            if (ord.test(ref.rows[i].cells[0].innerHTML + " " + ref.rows[i].cells[1].innerHTML))
            {
                // skal linjen vises i tabell
                ref.rows[i].style.display = '';
            }
            else
            {
                // skal linjen skjules fra tabell
                ref.rows[i].style.display = 'none';
            }
        }
    }
