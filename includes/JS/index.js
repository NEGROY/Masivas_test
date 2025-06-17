

// funcion para que se muestre las diferentes areas 
function seto(id) {
    document.getElementById("ids").value = id;
    alert("ID del país seleccionado: " + id);
}

// DESASIGNA EL VALOR DE UN SUSUARIO A UNA CAJA
function desig(id){
    condi = "tb_slct_areas"; 
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

