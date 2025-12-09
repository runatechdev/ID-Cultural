document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("loginForm");
  const errorMsg = document.getElementById("mensaje-error");
  const emailInput = document.getElementById("email");
  const passwordInput = document.getElementById("password");

  if (!form) {
    console.warn("Formulario de login no encontrado");
    return;
  }

  // Ocultar mensajes de error cuando el usuario empiece a escribir
  function hideErrorMessage() {
    if (errorMsg) {
      errorMsg.style.display = "none";
    }
  }

  // Agregar event listeners para ocultar errores al escribir
  if (emailInput) emailInput.addEventListener("input", hideErrorMessage);
  if (passwordInput) passwordInput.addEventListener("input", hideErrorMessage);

  form.addEventListener("submit", async function (e) {
    e.preventDefault();
    console.log("Enviando formulario...");

    const email = document.getElementById("email").value.trim().toLowerCase();
    const password = document.getElementById("password").value.trim();

    // Ocultar mensajes previos
    hideErrorMessage();

    const formData = new FormData();
    formData.append("email", email);
    formData.append("password", password);

    try {
      const res = await fetch("/api/auth.php?action=login", {
        method: "POST",
        body: formData
      });

      const resultado = await res.json();
      console.log("Respuesta del servidor:", resultado);

      if (resultado.status === "ok") {
        // Redirige usando la URL que viene del backend
        console.log("Redirigiendo a:", resultado.redirect);
        window.location.href = resultado.redirect;
      } else {
        if (errorMsg) {
          errorMsg.textContent = resultado.message;
          errorMsg.style.display = "block";
        }
      }

    } catch (error) {
      console.error("Error al iniciar sesión:", error);
      if (errorMsg) {
        errorMsg.textContent = "Error de conexión con el servidor.";
        errorMsg.style.display = "block";
      }
    }
  });
});