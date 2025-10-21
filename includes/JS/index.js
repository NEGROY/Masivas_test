

// **************** funcion para que se muestre las diferentes areas 
function seto(id) {
    document.getElementById("ids").value = id;
    alert("ID del país seleccionado: " + id);
}

// **************** Recibe el ID y muestra las areas de escalacion asociadas a ese PAIS
function desig3 (id, area){
    condi = "tb_areas"; 
    $.ajax({
        url: "./views/crud/escalaciones.php",
        method: "POST",
        data: {id,condi},
        success: function(data) {
            $("#areasxpais").html(data);
    } }) 

    $('#areasxpais').val(area).trigger('change');
}

function desig(id, area) {
    let condi = "tb_areas"; 
    $.ajax({
        url: "./views/crud/escalaciones.php",
        method: "POST",
        data: { id, condi },
        success: function(data) {
            // Reemplaza las opciones del select
            $("#areasxpais").html(data);

            // Asegura que el valor exista antes de seleccionarlo
            if ($("#areasxpais option[value='" + area + "']").length === 0) {
                // Si no existe, puedes agregarlo (opcional)
                $('#areasxpais').append(new Option("Área dinámica", area));
            }
            // Selecciona el valor correcto después de que el select esté poblado
            $('#areasxpais').val(area).trigger('change');

            const select = document.getElementById('areasxpais');
            const areaSlct2 = $("#areasxpais option:selected").text() || "";
            //const areaSlct2 = select.options[select.selectedIndex].text;
            $("#titulos").html(areaSlct2);
        }
    });
}

// **************** recibe datos de falla y lo formatea 
function valdiaFAlla(falla) {
    if (!falla) {
        Swal.fire({
            text: "Por favor, INGRESA una falla",
            icon: "warning",
            timer: 1500
        });
        toggleLoader(0);
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
async function buscarDatos_api() {

  toggleLoader(1);

  const tkEntrada = document.getElementById('falla').value.trim();
  const resultadoDiv = document.getElementById('resultado');
  const botonCalcular = document.getElementById('btnCalcular');
  const campoCierre = document.getElementById('CIERRE');

  const TK = valdiaFAlla(tkEntrada);
  if (!TK) return;

  const url = `http://172.20.97.102:8503/masivas/${tkEntrada}?token=masivas2025`;
  console.log(`Consultando API: ${url}`);

  try {
    const response = await fetch(url);
    const data = await response.json();
    const encontrado = data.data.find(item => item.TK === TK);

    if (!encontrado) {
      console.warn("No se encontró el TK solicitado.");
      return Swal.fire({
        text: "No se encontró el TK solicitado.",
        icon: "warning",
        timer: 2500
      });
    }

    console.log(`TK encontrado: ${encontrado.TK}, Hora open: ${encontrado.OPEN_TIME}, HH:MM:SS: ${encontrado.HH_MM_SS}`);

    const hora = encontrado.OPEN_TIME.split('T')[1] || '';
    document.getElementById('horaActual').value = hora;
    document.getElementById('tiempoAcumulado').value = encontrado.HH_MM_SS;
    document.getElementById('titulo').textContent = encontrado.TITULO;
    document.getElementById('open_time').value = encontrado.OPEN_TIME;

    const esFallaAbierta = !encontrado.CLOSE_TIME || encontrado.CLOSE_TIME.trim() === "";
    console.log(esFallaAbierta );

    validarFallaOpen(esFallaAbierta, campoCierre, botonCalcular, encontrado.CLOSE_TIME); // Actualiza el estado del botón y campo de cierre
    
    Swal.fire({
      text: "TK encontrado.",
      icon: "success",
      timer: 1500,
      showConfirmButton: false
    }).then(() => {
      if (esFallaAbierta) calcularTiempos(1, 'notaGenerada');
      toggleLoader(0);
    });

  } catch (error) {
    console.error('Error al consumir API:', error);
    Swal.fire({
      text: "Error al consumir API.",
      icon: "warning",
      timer: 2500
    });
  }
}

// Funcion para validar si la falla esta abierta o cerrada
function validarFallaOpen(esFallaAbierta, campoCierre, botonCalcular, CLOSE_TIME) {
      if (esFallaAbierta) {
      botonCalcular.disabled = false;
      campoCierre.value = "FALLA ABIERTA";
    } else {
      botonCalcular.disabled = true;
      campoCierre.value = CLOSE_TIME;
    }
}

// Funcion para las vista de solo mensajitos 
async function buscardatos() {
  const tkEntrada = document.getElementById('falla').value.trim();
  const resultadoDiv = document.getElementById('resultado');
  const botonCalcular = document.getElementById('btnCalcular');
  const campoCierre = document.getElementById('CIERRE');

      }

// PARA CAPTURAR LOS DATOS DE LA CALCULADA DE TIMEPO 
async function calcularTiempos(dashboard,txtarea) {
  // esta valiable solo funcional when dashboard  0 
  const tmpAcumu = document.getElementById('tiempoAcumulado').value.trim();
    

    let hrActual = document.getElementById('open_time').value.trim();
    const areaSlct = document.getElementById('areasxpais').value;
    const fallaID = document.getElementById('falla').value;
    const titulo = document.getElementById('titulo').textContent;

    const nivel = document.getElementById('acumulado').value.trim();

    const regexHora = /^([01]\d|2[0-3]):([0-5]\d)(:([0-5]\d))?$/;

    // Validaciones básicas no null 
    if (!hrActual || !nivel || !areaSlct) {
        Swal.fire({
            icon: 'warning',
            title: 'Campos vacíos',
            text: 'Por favor, completa todos los campos antes de continuar.',
            timer: 1500
        });
        return;
    }
      const select = document.getElementById('areasxpais');
      const selectedOption = select.options[select.selectedIndex];
      const areaText = selectedOption.text;
      const areaValue = selectedOption.value;
      $("#titulos").html(areaText);

    /* validamos como esta la HORA PICO
    if (!regexHora.test(hrActual)) {
        Swal.fire({
            icon: 'error',
            title: 'Formato inválido',
            text: `La hora actual ${hrActual} debe tener el formato HH:MM:SS.`,
            timer: 1500
        });
        return;
    } */

    // Si dashboard == 0, actualizar el campo
    if (dashboard === 0 & tmpAcumu != "00:00") {
        const nuevaHora = await restar_Acumualdo(hrActual, tmpAcumu);
        //document.getElementById('horaActual').value = nuevaHora;
        hrActual = nuevaHora ; 
        console.log( hrActual , nuevaHora)
      }

    // prueba para que imprima la tabla
    condi = "TB_calculadora"; 
    $.ajax({
        url: "./views/crud/escalaciones.php",
        method: "POST",
        data: {titulo,fallaID,hrActual,tmpAcumu,areaSlct,condi, dashboard, txtarea, nivel},
        success: function(data) {
        $("#TB_calcu").html(data);
        if( dashboard == 0 ){
            tb_copy(titulo,fallaID,hrActual,tmpAcumu,areaSlct);
        }
    } }) 
    return;
}

// IMPRIME PARA COPIAR la tabla de escalacion 
    function tb_copy(titulo,fallaID,hrActual,tmpAcumu,areaSlct){ 
      condi = "msj_tb"; 
        $.ajax({
            url: "./views/crud/escalaciones.php",
            method: "POST",
            data: {titulo,fallaID,hrActual,tmpAcumu,areaSlct,condi},
            success: function(data) {
            $("#notaGenerada2").html(data);
            console.log("tabla generada",titulo,fallaID,hrActual,tmpAcumu,areaSlct);  
        } }) 
    return;
      } 



// funcion para calcular la hora restando 
async  function restar_Acumualdo(hrActual, tmpAcumu) {
    console.log(hrActual, tmpAcumu);
    // Validar que tmpAcumu tenga el formato correcto H:MM
    const regexTiempo = /^\d{1,2}:\d{2}$/;
    if (!regexTiempo.test(tmpAcumu)) {
       console.log("El tiempo acumulado debe estar en formato H:MM ");
       tmpAcumu = "00:00"; 
       document.getElementById('tmpAcumu').value = tmpAcumu;
       return hrActual;
    }

    // Convertir hrActual a minutos
    const [hrH, hrM] = hrActual.split(':').map(Number);
    const totalActualMin = hrH * 60 + hrM;
    
    // Convertir tmpAcumu a minutos
    const [acumH, acumM] = tmpAcumu.split(':').map(Number);
    const totalAcumuMin = acumH * 60 + acumM;

    // Restar minutos y ajustar en caso de ser negativo (24h clock)
    let nuevaHoraMin = (totalActualMin - totalAcumuMin + 1440) % 1440;

    // Convertir de nuevo a HH:MM
    const nuevaH = String(Math.floor(nuevaHoraMin / 60)).padStart(2, '0');
    const nuevaM = String(nuevaHoraMin % 60).padStart(2, '0');
    const nuevaHora = `${nuevaH}:${nuevaM}:00`;

    console.log(nuevaHoraMin, totalActualMin, totalAcumuMin, nuevaHora );
    return nuevaHora;
}
 
// FUNCION PARA CERRAR LAS FALLAS, ACTUALMENTE MANUAL 
function cerrarMasiva() {
  const fallaID = document.getElementById('falla').value;
  const condi = "cerrarmasiva";
  //VALIDA VACIO
  if (!fallaID) {
    Swal.fire('Error', 'Debes ingresar un ID de falla.', 'warning');
    return;
  }

  Swal.fire({
    title: '¿Estás seguro?',
    text: `¿Deseas cerrar la falla con ID: ${fallaID}?`,
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Sí, cerrar',
    cancelButtonText: 'Cancelar'
  }).then((result) => {
    // envia ajax 
    $.ajax({
        url: "./views/crud/escalaciones.php",
        method: "POST",
        data: { fallaID, condi },
        success: function (data) {
          $("#TB_calcu").html(data);
          Swal.fire('Cerrado', 'La falla fue cerrada correctamente.', 'success').then(() => {
              location.reload(); // Se ejecuta después de cerrar el mensaje
          });
        },
        error: function () {
          Swal.fire('Error', 'Hubo un problema al cerrar la falla.', 'error');
        }
    });
  });

}

function mnsjEscala(data){
    //console.log("Datos enviados:", data);
    const escalacionActual = "1/5"; // Puedes ajustar esto según contexto o contador
    const filaActual = "1/4";       // Igual, puede venir del backend
    const horaActualFormateada = new Date().toLocaleTimeString('en-GB'); // hh:mm:ss
    const tiempo = document.getElementById("tiempoAcumulado").value;

    const mensaje = 
    `*## ESCALACION ${data.nivel} ##*
    ${data.nivel}\t${data.nombre}\t${data.telefono}\t${data.tiempo}Hrs\t${data.hr_suma} Hrs\n
    *${data.titulo}*
    SE INDICA TIEMPO Y CLIENTES  `;

    const wasapp = 
    `*DETALLES DE LA ESCALACIÓN DE FALLA MASIVA*
    Se escala con ${data.nombre}
    ${data.nivel} ${data.nombre} ${data.telefono} ${data.tiempo}hrs ${data.hr_suma}Hrs \n
    *FALLA Masiva* :  ${data.fallaID}
    *FALLA General* : F- \n
    *TIEMPO DE LA FALLA MASIVA*: ${tiempo}
    ${data.titulo} \n
    *CLIENTES AFECTADOS*: -
    • F6081621 / 1136600001T / ROFRAMA, SOCIEDAD ANÓNIMA
    • F6081728 / 929300001T / INDUSTRIA GUATEMALTECA DE GRANITO, S.A.

    *SEGUIMIENTO*:`;

    // Insertar mensaje en el input
    document.getElementById('notaGenerada').value = mensaje;
    document.getElementById('wasapp').value = wasapp;

    tb_copy(data.titulo,data.fallaID,data.hrActual,data.tmpAcumu,data.areaSlct);
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
        success: function(res) {
          res = JSON.parse(res);
          console.log("CONSULTA :\n", res);   
          Swal.fire({
            icon: res.status,
            title: res.message,
          }).then(() => {
            //location.reload();
            window.location.href = window.location.href;
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

// esta se JALA todas las APIS, 
function apiasociados() {
  let tkEntrada = document.getElementById('falla').value.trim();
  const resultadoDiv = document.getElementById('resultado');

  // Validar y formatear
    const TK = valdiaFAlla(tkEntrada);
    if (!TK) return; // Si la validación falla, se detiene la función

    let url = `http://172.20.97.102:8503/masivas/list/${tkEntrada}?token=masivas2025`;
    fetch('http://172.20.97.102:8503/masivas/list/F5875158?token=masivas2025') 
      .then(response => response.json())
      .then(data => {
        const encontrado = data.data.find(item => item.TK === TK);

        if (encontrado) {
            console.log(`TK encontrado: TK: ${encontrado.TK} Total menos cliente (horas): 
            ${encontrado.total_menos_cliente_horas} HH:MM:SS: ${encontrado.HH_MM_SS}`);
            //COLOCA LA HORA ACTUAL 
            //const hora = (encontrado.OPEN_TIME.match(/\d{2}:\d{2}:\d{2}/) || [])[0] || '';
            const hora = encontrado.OPEN_TIME.split('T')[1]; 
            document.getElementById('horaActual').value = hora;
            document.getElementById('tiempoAcumulado').value = `${encontrado.HH_MM_SS}`;
            document.getElementById('titulo').textContent = `${encontrado.TITULO}`;
            Swal.fire({
            text: "TK encontrado.",
            icon: "success",
            timer: 1500 });
        } else {
            console.warn("No se encontró el TK solicitado.");
            Swal.fire({
            text: "No se encontró el TK solicitado.",
            icon: "warning",
            timer: 2500 });
            }
        })
        .catch(error => {
            console.error('Error al consumir API:', error);
            Swal.fire({
            text: "Error al consumir API.",
            icon: "warning",
            timer: 2500 });
        });
}

// funcion para imprimir cada dos horas 
function plusdos(datos, txtarea) {
  let output = "";
  output += "================================================================================\n";
  output += `  ESCALACIÓN:  ${datos.titulo}  (Falla ID: ${datos.fallaID})\n`;
  output += "================================================================================\n";
  output += "| Nivel | Nombre          | Teléfono     | Tiempo | Comentario           | Tipo     | Hr Suma  |\n";
  output += "--------------------------------------------------------------------------------\n";

  // Primera escalación (nivel 1) con datos originales
  output += `| 1     | ${datos.nombre.padEnd(15)} | ${datos.telefono}  | 0     | ${datos.hr_suma} |\n`;

  // Crear objeto Date a partir de hr_suma
  let fecha = new Date(datos.hr_suma);

  for (let i = 0; i < 3; i++) {
    const nivel = i + 2;   // niveles 2, 3, 4
    const tiempo = i + 1;  // tiempos 1, 2, 3

    // Sumar 2 horas (sin reasignar)
    fecha.setHours(fecha.getHours() + 2);

    // Formatear la fecha en YYYY-MM-DD HH:mm:ss
    const hrSuma = fecha.toISOString().replace('T', ' ').substring(0, 19);

    // Agregar fila
    output += `| ${nivel}     | ${datos.nombre.padEnd(15)} | ${datos.telefono}  | ${tiempo}     | ${hrSuma} |\n`;
  }

  output += "================================================================================";

  // Asignar al textarea
  document.getElementById(txtarea).value = output;
}



// -----
  function ajustarAltura(elemento) {
    elemento.style.height = 'auto';
    elemento.style.height = (elemento.scrollHeight) + 'px';
  }

  // PARA HABILITAR LOS BTN 
  function habilitarBuscar() {
    const select = document.getElementById('areasxpais');
    const boton = document.getElementById('btnBuscar');
    boton.disabled = (select.value === '');
    //document.getElementById('btnBuscar').disabled = false;
  }

// PARA IMPRIMIR LA TABLA DE REGISTROS EXTRA +2 HORAS 
function toggleTable(datos) {
  let registrosTabla = []; // GLOBAL
  const tableContainer = document.getElementById('tableContainer'); // <--- aquí

  registrosTabla.push(datos);

  // Crear tabla si no existe
  if (!document.getElementById('tablaExtra')) {
    tableContainer.innerHTML = `
      <table id="tablaExtra" class="table table-sm table-bordered mt-2">
        <thead class="table-secondary">
          <tr>
            <th>#</th>
            <th>Nombre</th>
            <th>Teléfono</th>
            <th>Tiempo</th>
            <th>Hora Suma</th>
            <th>Opciones</th>
          </tr>
        </thead>
        <tbody></tbody>
      </table> `;
  }

    const tbody = document.querySelector("#tablaExtra tbody");

    // --- Fila original ---
    const rowOriginal = document.createElement("tr");
    rowOriginal.innerHTML = `
      <td>0</td>
      <td>${datos.nombre}</td>
      <td>${datos.telefono}</td>
      <td>${datos.tiempo} hrs</td>
      <td>${datos.hr_suma}</td>`;
    tbody.appendChild(rowOriginal);

        // Convertir la fecha inicial a objeto Date
        let fecha = new Date(datos.hr_suma);

      // --- Generar 5 niveles extra sumando 2 horas acumulativamente ---
        for (let i = 0; i < 5; i++) {
            const nivel = i + 1;
          
          // Sumar 2 horas
          fecha.setHours(fecha.getHours() + 2);
          // Formatear la fecha a "YYYY-MM-DD HH:mm:ss"
          const fechaStr = formatearFechaLocal(fecha);
          // Sustituir hr_suma en el objeto datos
          datos.hr_suma = fechaStr;

        // Crear fila
        const rowExtra = document.createElement("tr");
        rowExtra.innerHTML = `
          <td>${nivel}</td>
          <td>${datos.nombre}</td>
          <td>${datos.telefono}</td>
          <td>+2 hrs</td>
          <td>${fechaStr}</td>
          <td>
            <button class="btn btn-outline-success btn-sm rounded-pill px-2" onclick='tablerosave(${JSON.stringify(datos)})'>
            <i class="fa-solid fa-right-long"> </i> </button>
            <button class="btn btn-outline-secondary btn-sm rounded-pill px-2" onclick='mnsjEscala(${JSON.stringify(datos)})'>
            <i class="fa-regular fa-message"> </i> </button>
            <button class="btn btn-outline-secondary btn-sm rounded-pill px-2" onclick='plusdos(${JSON.stringify(datos)} , "notaGenerada")' >
            <i class="fa-regular fa-message"> </i> </button>
          </td> `;
      tbody.appendChild(rowExtra);
    }
  }


  function formatearFechaLocal(fecha) {
  const anio = fecha.getFullYear();
  const mes = String(fecha.getMonth() + 1).padStart(2, '0');
  const dia = String(fecha.getDate()).padStart(2, '0');
  const horas = String(fecha.getHours()).padStart(2, '0');
  const minutos = String(fecha.getMinutes()).padStart(2, '0');
  const segundos = String(fecha.getSeconds()).padStart(2, '0');
  return `${anio}-${mes}-${dia} ${horas}:${minutos}:${segundos}`;
}


// FUNCION DE Calcular para la RECARFA 
function  calcularTiempos2(titulo,fallaID,hrActual,tmpAcumu,areaSlct,txtarea) {
condi = "TB_calculadora"; 
dashboard = 1;
    $.ajax({
        url: "./views/crud/escalaciones.php",
        method: "POST",
        data: {titulo,fallaID,hrActual,tmpAcumu,areaSlct,condi, dashboard, txtarea},
        success: function(data) {
            $("#TB_calcu").html(data);
    } })
}

// funcion para el loader enviar 1 o 0 || 
 function toggleLoader(state) {
    const loader = document.getElementById('global-loader');
    if (!loader) return; // Evita error si el elemento no existe

    if (state === 1) {
        loader.style.display = 'flex'; // Mostrar loader
    } else if (state === 0) {
        loader.style.display = 'none'; // Ocultar loader
    }
}