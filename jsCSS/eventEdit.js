/* 
 * Dette er et javascript som legger til egen funksjonalitet i
 * grensesnittet for å redigere hendelser
 */
$(document).ready(function() {
    $('span.foreginKeyEdit').each(function(){
        this.innerHTML = '<a target="_blank" href="test" >Ny taler</a>';
        $(this).show();
    })
})