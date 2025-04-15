// Versión optimizada
button.addEventListener("click", (event) => {
  // Prevenir comportamiento por defecto si es un botón de formulario
  event.preventDefault();

  // Validar campos
  if (validarCampos()) {
    // Procesar formulario
    procesarFormulario();
  }
});

// Función de validación
function validarCampos() {
  const campos = document.querySelectorAll("input[required], select[required]");
  let esValido = true;

  campos.forEach((campo) => {
    if (!campo.value.trim()) {
      campo.classList.add("error");
      esValido = false;
    } else {
      campo.classList.remove("error");
    }
  });

  return esValido;
}

// Función de procesamiento
function procesarFormulario() {
  const datos = new FormData(document.getElementById("formulario"));

  fetch("procesar.php", {
    method: "POST",
    body: datos,
  })
    .then((respuesta) => respuesta.json())
    .then((resultado) => {
      if (resultado.success) {
        mostrarMensaje("Formulario enviado con éxito", "success");
      } else {
        mostrarMensaje(resultado.mensaje, "error");
      }
    })
    .catch((error) => {
      mostrarMensaje("Error en el envío", "error");
      console.error("Error:", error);
    });
}

// Función de mensajes
function mostrarMensaje(texto, tipo) {
  const contenedorMensaje = document.getElementById("mensaje-contenedor");
  contenedorMensaje.textContent = texto;
  contenedorMensaje.className = `mensaje ${tipo}`;
}
