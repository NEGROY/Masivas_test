

// **************** funcion para que se muestre las diferentes areas 
function seto(id) {
    document.getElementById("ids").value = id;
    alert("ID del país seleccionado: " + id);
}

// **************** Recibe el ID y muestra las areas de escalacion asociadas a ese PAIS
function desig(id){
    condi = "tb_areas"; 
    $.ajax({
        url: "./views/crud/escalaciones.php",
        method: "POST",
        data: {id,condi},
        success: function(data) {
            $("#areasxpais").html(data);
    } }) 
}

// **************** recibe datos de falla y lo formatea 
function valdiaFAlla(falla) {
    if (!falla) {
        Swal.fire({
            text: "Por favor, INGRESA una falla",
            icon: "warning"
        });
        return null;
    }
    falla = falla.replace(/\s+/g, '').toUpperCase();

    if (!falla.startsWith('F')) {
        falla = 'F' + falla;
    }
    document.getElementById('falla').value = falla;

    return falla; // Retorna el valor ya validado y formateado
}

// **************** PSEUDO API PARA LA BUSQUEDA 
function buscarDatos_api() {
    let tkEntrada = document.getElementById('falla').value.trim();
    const resultadoDiv = document.getElementById('resultado');

    // Validar y formatear
    const tk = valdiaFAlla(tkEntrada);
    if (!tk) return; // Si la validación falla, se detiene la función

    fetch('http://localhost/masiva_test/src/api_data/api.php')
        .then(response => response.json())
        .then(data => {
            const encontrado = data.find(item => item.tk === tk);

            if (encontrado) {
                resultadoDiv.innerHTML = `
                    <div class="alert alert-success">
                        <strong>TK:</strong> ${encontrado.tk}<br>
                        <strong>Total menos cliente (horas):</strong> ${encontrado.total_menos_cliente_horas}<br>
                        <strong>Total menos cliente (horas):</strong> ${encontrado.hh_mm_ss}
                    </div>
                `;
                document.getElementById('tiempoAcumulado').value = `${encontrado.hh_mm_ss}`;
            } else {
                resultadoDiv.innerHTML = `
                    <div class="alert alert-warning">No se encontró el TK solicitado.</div>
                `;
            }
        })
        .catch(error => {
            console.error('Error al consumir API:', error);
            resultadoDiv.innerHTML = `
                <div class="alert alert-danger">Error al consultar la API.</div>
            `;
        });
}


// PARA CAPTURAR LOS DATOS DE LA CALCULADA DE TIMEPO 
function calcularTiempos() {
    const hrActual = document.getElementById('horaActual').value.trim();
    const tmpAcumu = document.getElementById('tiempoAcumulado').value.trim();
    const areaSlct = document.getElementById('areasxpais').value;

    const regexHora = /^([01]\d|2[0-3]):([0-5]\d):([0-5]\d)$/; 

    // Validaciones básicas no null 
    if (!hrActual || !tmpAcumu || !areaSlct) {
        Swal.fire({
            icon: 'warning',
            title: 'Campos vacíos',
            text: 'Por favor, completa todos los campos antes de continuar.'
        });
        return;
    }

    // validamos como esta la HORA PICO
    if (!regexHora.test(hrActual)) {
        Swal.fire({
            icon: 'error',
            title: 'Formato inválido',
            text: 'La hora actual debe tener el formato HH:MM:SS.'
        });
        return;
    }
    /*console.log("Hora actual:", hrActual);
    console.log("Tiempo acumulado:", tmpAcumu);
    console.log("areaSeleccionada:", areaSlct);*/

    // prueba para que imprima la tabla
    condi = "TB_calculadora"; 
    $.ajax({
        url: "./views/crud/escalaciones.php",
        method: "POST",
        data: {hrActual,tmpAcumu,areaSlct,condi},
        success: function(data) {
            $("#TB_calcu").html(data);
    } }) 
        return;
}
