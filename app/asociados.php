<?php
session_start();
require_once("../config.php");

if (!isset($_SESSION['usuario'])) {
    echo $_SESSION['usuario'] . " No has iniciado sesión. Redirigiendo al login...";  
    header('Location: ' . urlsite . 'index.php');
    exit();
}



  require_once '../includes/phpFun/fun.php';
  require_once '../includes/2incl.php';
  require_once '../views/miscelana/general.php';

    date_default_timezone_set('America/Guatemala');
    $fecha = date('m-d-Y');
    $hora  = date('H:i');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asociados</title>
    <link rel="stylesheet" href="../includes/CSS/asoc.css">
</head>
<body>

<div>
  <?php listarHeader('.'); ?>
</div>

<div class="container my-3">
  <!-- Sección de entrada -->
<div class="container my-3">
  <div class="card border-0 shadow mb-4">
    <div class="card-body bg-light rounded p-4">
      <div class="row g-3 align-items-center">
        
        <!-- Input de falla -->
        <div class="col-lg-6">
          <label for="falla" class="form-label text-muted">Ingresar ID de Falla</label>
          <div class="input-group">
          <form id="formExport" method="POST" action="./expot2.php" target="_blank"  >
            <input type="text" name="tkid" id="fallaIDInput" class="form-control" placeholder="F6144046" value="F5875158">
          </form>
            <button class="btn btn-outline-primary" type="button" id="buscarFalla" onclick="fallamasiva()" style="height: min-content;">Buscar</button>
          </div>
        </div>

              <!-- Información de la falla -->
        <div class="col-lg-6 d-flex justify-content-between align-items-center">
          <div class="p-3 border-start border-4 border-primary bg-white rounded w-100 me-2">
            <h6 class="text-primary mb-1">Falla Seleccionada:</h6>
            <p class="mb-0 fw-semibold text-dark" id="tituloFalla">descarga en excel</p>
          </div>
          <button id="btnExportar" class="btn btn-success shadow-sm" onclick="exportarExcel()" disabled>Exportar Excel</button>
      
        </div>

      </div>
    </div>
  </div>
</div>

  <!-- Sección de listado -->
  <div class="fallas"></div>

</div>
    

<div><?php loader()?></div>
</body>
</html>

<script>
// BOTON PARA MBUSCAR LOS DATOS DE LA FALLA 
function fallamasiva(){
    const fallaID = document.getElementById('fallaIDInput').value.trim();
    const condi = "asocciadas"; 
    console.log(fallaID);

    if (!fallaID) {
      $(".fallas").html('<div class="alert alert-warning">Debe ingresar un ID de falla.</div>');
      return;
    }
    // Mostrar loader global
    document.getElementById('global-loader').style.display = 'flex';

    $.ajax({
        url: "./api_asocia2.php",
        method: "POST",
        data: { fallaID, condi },
        success: function(data) {
            $(".fallas").html(data);
            const btn = document.getElementById("btnExportar");
            btn.disabled = false;
            btn.innerHTML = "Exportar Excel";
        },
        error: function(xhr, status, error) {
            console.error("Error AJAX:", status, error);
            $(".fallas").html('<div class="alert alert-danger">Error al buscar la falla.</div>');
            const btn = document.getElementById("btnExportar");
            btn.disabled = true;
            btn.innerHTML = "Busca una falla...";
        },
        complete: function() {
            // Ocultar loader global después de la petición
            document.getElementById('global-loader').style.display = 'none';
        }
    });
}

// funcion para traer la ifgno 
function guardarInputs(event) {
  event.preventDefault(); // Detener el envío del formulario

  const form = event.target;
  const formData = new FormData(form);
  const tkid = event.target.id;

  // Convertir FormData en un objeto legible
  const datos = Object.fromEntries(formData.entries());
  console.log("Valores del formulario:", tkid , datos);

  $.ajax({
    url: "../views/miscelana/asoc_insert.php",
    method: "POST",
    data: {tkid , datos },
    success: function(data) {
      //$(".fallas").html(data);
      Swal.fire({
        position: "top-end",
        icon: "success",
        title: data,
        showConfirmButton: false,
        timer: 1000
      });
      console.log(data);
    }
  });

  /*return;
  sleep(60000);*/
}

// funcion para exportar a excel  // Exportar Excel
    function exportarExcel() {
      const tkidActual = document.getElementById("fallaIDInput").value;
        if (!tkidActual) return;
      document.getElementById("formExport").submit();
}

// elimanar un registro 
function deletetk(tk) {
  Swal.fire({
    title: '¿Estás seguro?',
    text: '¿Deseas Eliminar este TK?',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Sí, Eliminar',
    cancelButtonText: 'Cancelar',
    confirmButtonColor: '#198754',
    cancelButtonColor: '#6c757d'
  }).then((result) => {
    if (result.isConfirmed) {
      $.ajax({
        url: "../views/crud/escalaciones.php",
        method: "POST",
        data: {tk, condi: "deletetk" },
        success: function(res) {
            Swal.fire({
              icon: 'success',
              title: res,
              showConfirmButton: false,
              timer: 1500
            }).then(() => {
              document.getElementById(tk).hidden = true;
            });
        }
      }); /* ajax fin/*/
    }
  });
}

// FUNCIOON PARA BUSCAR LA WAN 
function buscarWan(tk) {
  const wanInput = document.getElementById('wan_' + tk).value.trim();
  const regexIP = /^(?:\d{1,3}\.){3}\d{1,3}$/;

  
  // Validar que no esté vacío
  if (wanInput === "") {
    Swal.fire({
      icon: "warning",
      title: "Campo vacío",
      text: "Por favor ingresa una IP WAN.",
    });
    return;
  }

  // Validar que sea una IP válida
  if (!regexIP.test(wanInput)) {
    Swal.fire({
      icon: "error",
      title: "Formato inválido",
      text: "La IP WAN no tiene un formato válido.",
    });
    return;
  }

  // Construir URL con la IP ingresada
  const arkurl = `http://10.20.10.81/get_network_data/?wan=${wanInput}`;

  // Hacer la llamada a la API (fetch es asíncrono)
  fetch(arkurl, {
    method: 'GET',
    headers: {
      // Si necesitas autenticación básica, por ejemplo:
      // // user: frt_ark_interno  pass: Uf!8$6mMG0qg
      'Authorization': 'Basic ' + btoa('frt_ark_interno:Uf!8$6mMG0qg')
    }
  })
    .then(response => {
      if (!response.ok) {
        throw new Error('Error en la respuesta de la API');
      }
      return response.json();
    })
    .then(data => {
      //console.log(data);
      console.log(data);
      respuesta(data,tk);
      // Insertar valores en los inputs
      // logica si mas de uno
      //console.log(data.pe);
    })
    .catch(error => {
      Swal.fire({
        icon: "error",
        title: "Error",
        text: "No se pudo obtener datos de la API: " + error.message,
      });
    });
}


function respuesta(data, tk) {
    console.log("Datos recibidos:", data);

    // Convertir el objeto en un array de sus valores
    const registros = Object.values(data);
    const cantidad = registros.length;
    console.log(`Cantidad de registros: ${cantidad}`);

    if (cantidad === 3) {
        inputvalue(tk, data.vrf, data.pe);
    } else {
        console.log("Múltiples registros recibidos:", registros);
        //define el div donde imprime
        const contenedor = document.getElementById("btns_" + tk);
        contenedor.innerHTML = ""; // Limpiar contenido previo
        
          registros.forEach((registro, index) => {
            // Crear un botón
            const boton = document.createElement("button");
            boton.textContent = `VRF: ${registro.vrf}, PE: ${registro.pe}`;
            boton.classList.add("btn-vrf"); // opcional: clase CSS
            boton.onclick = () => inputvalue(tk, registro.vrf, registro.pe);

            // Agregar el botón al contenedor
            contenedor.appendChild(boton);
        });
    }
}



function inputvalue(tk,vrf,pe){
   $('#vrf_' + tk).val(vrf ?? '');
   $('#pee_' + tk).val(pe ?? '');
}
</script>
