

// funcion para que se muestre las diferentes areas 
$(document).ready(function() {
        $('#pais').on('change', function() {
            const idPais = $(this).val();
            alert(idPais);
            /*if (idPais) {
                $.post('ajax/consultar_areas.php', { pais: idPais }, function(data) {
                    $('#resultado').html(data);
                });
            } else {
                $('#resultado').html('');
            }*/
        });
    });