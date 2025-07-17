document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("loginForm");
  const errorMsg = document.getElementById("mensaje-error");

  if (!form) {
    console.warn("Formulario de login no encontrado");
    return;
  }

  form.addEventListener("submit", async function (e) {
    e.preventDefault();
    console.log("Enviando formulario...");

    const email = document.getElementById("email").value.trim().toLowerCase();
    const password = document.getElementById("password").value.trim();

    const formData = new FormData();
    formData.append("email", email);
    formData.append("password", password);

    try {
      const res = await fetch("/ID-Cultural/backend/controllers/verificar_usuario.php", {
        method: "POST",
        body: formData
      });

      const resultado = await res.json();
      console.log("Respuesta del servidor:", resultado);

      if (resultado.status === "ok") {
        let destino = "";
        switch (resultado.role) {
          case "admin":
            destino = "/ID-Cultural/src/views/pages/admin/dashboard-adm.php";
            break;
          case "editor":
            destino = "/ID-Cultural/src/views/pages/editor/panel_editor.php";
            break;
          case "validador":
            destino = "/ID-Cultural/src/views/pages/validador/panel_validador.php";
            break;
          case "artista":
            destino = "/ID-Cultural/src/views/pages/user/dashboard-user.php";
            break;
          default:
            errorMsg.textContent = "Rol desconocido.";
            errorMsg.hidden = false;
            return;
        }

        console.log("Redirigiendo a:", destino);
        window.location.href = destino;
      } else {
        errorMsg.textContent = resultado.message;
        errorMsg.hidden = false;
      }

    } catch (error) {
      console.error("Error al iniciar sesión:", error);
      errorMsg.textContent = "Error de conexión con el servidor.";
      errorMsg.hidden = false;
    }
  });

  // NAVBAR Y FOOTER
  const navbar = document.getElementById("navbar");
  if (navbar) {
    fetch("/ID-Cultural/components/navbar.php")
      .then(response => response.text())
      .then(data => {
        navbar.innerHTML = data;
        if (window.lucide) lucide.createIcons?.();
      })
      .catch((err) => console.error("Error al cargar el navbar:", err));
  }

  const footer = document.getElementById("footer");
  if (footer) {
    fetch("/ID-Cultural/components/footer.php")
      .then(response => response.text())
      .then(data => {
        footer.innerHTML = data;
      })
      .catch((err) => console.error("Error al cargar el footer:", err));
  }
});
