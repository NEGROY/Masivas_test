

// funcion para que se muestre las diferentes areas 
function seto(id) {
    document.getElementById("ids").value = id;
    alert("ID del país seleccionado: " + id);
}

// Recibe el ID y muestra las areas de escalacion asociadas a ese PAIS
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

// CAPTURA DE DATOS POR UN BOTON A PETICION DEL MASTER 
function buscarDatos() {
    // Obtener valores del formulario
    const pais = document.getElementById('pais').value;
    let falla = document.getElementById('falla').value.trim();

    // Validar entrada de falla vacía
    if (!falla) {
        Swal.fire({
        text: "Por favor, INGRESA una falla",
        icon: "warning"
        });
    return;
    }
    
    // Limpiar espacios y convertir a mayúsculas
    falla = falla.replace(/\s+/g, '').toUpperCase();

     // Asegurarse que empieza con F
    if (!falla.startsWith('F')) {
    falla = 'F' + falla;
    }

    //actualizo la falla 
    document.getElementById('falla').value = falla;
    // Mostrar resultados
    console.log("País seleccionado:", pais);
    console.log("Falla:", falla);

    alert(`Datos capturados:\nPaís: ${pais}\nFalla: ${falla} `);
}

// PARA CAPTURAR LOS DATOS DE LA CALCULADA DE TIMEPO 
function calcularTiempos() {
    const horaActual = document.getElementById('horaActual').value;
    const tiempoAcumulado = document.getElementById('tiempoAcumulado').value;

    console.log("Hora actual:", horaActual);
    console.log("Tiempo acumulado:", tiempoAcumulado);

}

  // PSEUDO API PARA LA BUSQUEDA 
function buscarDatos_api() {
    const tk = document.getElementById('falla').value.trim();
    const resultadoDiv = document.getElementById('resultado');

    if (!tk) {
        Swal.fire({
        text: "Por favor, INGRESA una falla",
        icon: "warning"
        });
    return;
    }
    

    fetch('http://localhost/masiva_test/src/api_data/api.php')
      .then(response => response.json())
      .then(data => {
        const encontrado = data.find(item => item.tk === tk);

        if (encontrado) {
          resultadoDiv.innerHTML = `
            <div class="alert alert-success">
              <strong>TK:</strong> ${encontrado.tk}<br>
              <strong>Total menos cliente (horas):</strong> ${encontrado.total_menos_cliente_horas}
            </div>
          `;
        } else {
          resultadoDiv.innerHTML = '<div class="alert alert-warning">No se encontró el TK solicitado.</div>';
        }
      })
      .catch(error => {
        console.error('Error al consumir API:', error);
        resultadoDiv.innerHTML = '<div class="alert alert-danger">Error al consultar la API.</div>';
      });
}
