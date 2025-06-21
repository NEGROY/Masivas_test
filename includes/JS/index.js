

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

    fetch('./api.php')
    //fetch('./src/api_data/api.php')
        .then(response => response.json())
        .then(data => {
            const encontrado = data.find(item => item.tk === tk);

            if (encontrado) {
                console.log(`TK encontrado: TK: ${encontrado.tk} Total menos cliente (horas): 
                ${encontrado.total_menos_cliente_horas} HH:MM:SS: ${encontrado.hh_mm_ss}`);
                //COLOCA LA HORA ACTUAL 
                const hora = (encontrado.open_time.match(/\d{2}:\d{2}:\d{2}/) || [])[0] || '';
                document.getElementById('horaActual').value = hora;
                document.getElementById('tiempoAcumulado').value = `${encontrado.hh_mm_ss}`;
                Swal.fire({
                text: "TK encontrado.",
                icon: "success" });
            } else {
            console.warn("⚠️ No se encontró el TK solicitado.");
            Swal.fire({
            text: "No se encontró el TK solicitado.",
            icon: "warning" });
            }
        })
        .catch(error => {
            console.error('Error al consumir API:', error);
            Swal.fire({
            text: "Error al consumir API.",
            icon: "warning" });
        });
}

// PARA CAPTURAR LOS DATOS DE LA CALCULADA DE TIMEPO 
function calcularTiempos() {
    const hrActual = document.getElementById('horaActual').value.trim();
    const tmpAcumu = document.getElementById('tiempoAcumulado').value.trim();
    const areaSlct = document.getElementById('areasxpais').value;
    const fallaID = document.getElementById('falla').value;

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
    
    const select = document.getElementById('areasxpais');
    const areaSlct2 = select.options[select.selectedIndex].text;
    $("#titulos").html(areaSlct2);

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
        data: {fallaID,hrActual,tmpAcumu,areaSlct,condi},
        success: function(data) {
            $("#TB_calcu").html(data);
    } }) 
        return;
}

function mnsjEscala(data){
    //console.log("Datos enviados:", data);
    const escalacionActual = "1/5"; // Puedes ajustar esto según contexto o contador
    const filaActual = "1/4";       // Igual, puede venir del backend
    const horaActualFormateada = new Date().toLocaleTimeString('en-GB'); // hh:mm:ss

    const mensaje = 
    `*## ESCALACION ${data.nivel} ##*
    ${data.nivel}\t${data.nombre}\t${data.telefono}\t${data.tiempo}Hrs\t${data.hr_suma}\n
    *${data.titulo}*
    SE INDICA TIEMPO Y CLIENTES`;


    const wasapp = 
    `*DETALLES DE LA ESCALACIÓN DE FALLA MASIVA*
    Se escala con ${data.nombre}
    ${data.nivel} ${data.nombre} ${data.telefono} ${data.tiempo}hrs ${data.hr_suma}\n
    *FALLA Masiva* :  ${data.fallaID}
    *FALLA General* : F- \n
    *TIEMPO DE LA FALLA MASIVA*: ${data.tmpAcumu}
    ${data.titulo} \n
    *CLIENTES AFECTADOS*: -
    • F6081621 / 1136600001T / ROFRAMA, SOCIEDAD ANÓNIMA
    • F6081728 / 929300001T / INDUSTRIA GUATEMALTECA DE GRANITO, S.A.

    *SEGUIMIENTO*:`;

    // Insertar mensaje en el input
    document.getElementById('notaGenerada').value = mensaje;
    document.getElementById('wasapp').value = wasapp;

    console.log("Mensaje generado:\n", data);    
}


// aestas super seguro de que quieres mandar esta falla al tablero 
// falta validar si la falla ya esta en el tablero y en que escala esta y solo actualizarla 
// que pasa si cambia de escalacion ? manejar un estado de cada una de las fallas, igual almacenar los datos 

function confirmarEscalacion() {
  Swal.fire({
    title: '¿Estás seguro?',
    text: '¿Deseas tomar esta escalación?',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Sí, tomar',
    cancelButtonText: 'Cancelar',
    confirmButtonColor: '#198754',
    cancelButtonColor: '#6c757d'
  }).then((result) => {
    if (result.isConfirmed) {
      Swal.fire({
        icon: 'success',
        title: 'Escalación tomada',
        showConfirmButton: false,
        timer: 1500
      }).then(() => {
        // Recargar la página después de la confirmación
        location.reload();
      });
    }
  });
}

// verdadera funcion para mandar a guardar datos al tablero 
function tablerosave(datos) {
  Swal.fire({
    title: '¿Estás seguro?',
    text: '¿Deseas tomar esta escalación?',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Sí, tomar',
    cancelButtonText: 'Cancelar',
    confirmButtonColor: '#198754',
    cancelButtonColor: '#6c757d'
  }).then((result) => {
    if (result.isConfirmed) {
      // Mostrar loading
      Swal.fire({
        title: 'Procesando...',
        allowOutsideClick: false,
        didOpen: () => {
          Swal.showLoading();
        }
      });

      // Enviar datos por AJAX
      $.ajax({
        url: "./views/crud/escalaciones.php",
        method: "POST",
        data: {datos, condi: "insertb" },
        success: function(response) {
          Swal.fire({
            icon: 'success',
            title: 'Escalación agregada',
            showConfirmButton: false,
            timer: 1500
          }).then(() => {
            console.log("CONSULTA :\n", response);   
           // location.reload();
          });
        },
        error: function() {
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'No se pudo registrar la escalación.',
          });
        }
      });
    }
  });
}



/// FUNCION PARA COPIAR LOS TEXTOS GAYS
function copiarTextoWhatsApp(id_areatxt) {
    const texto = document.getElementById(id_areatxt);
    texto.select();
    texto.setSelectionRange(0, 99999); // Para móviles
    document.execCommand("copy");
    //alert("Texto copiado al portapapeles");
  }
