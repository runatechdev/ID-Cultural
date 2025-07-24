// ✅ Inclusión dinámica de HTML
async function includeHTML(id, file) {
  const el = document.getElementById(id);
  if (el) {
    try {
      const res = await fetch(file);
      if (!res.ok) throw new Error("No se pudo cargar el archivo: " + file);
      const html = await res.text();
      el.innerHTML = html;
    } catch (error) {
      console.error("Error al incluir HTML:", error);
    }
  }
}

// ✅ Cargar navbar y footer al iniciar
document.addEventListener("DOMContentLoaded", () => {
  includeHTML("navbar", "/ID-Cultural/src/views/pages/public/components/navbar.html");
  includeHTML("footer", "/ID-Cultural/src/views/pages/public/components/footer.html");

  cargarNoticiasHome();
});

// ✅ Renderizado de noticias en el home
function cargarNoticiasHome() {
  const contenedor = document.getElementById("contenedor-noticias");
  if (!contenedor) return;

  const lista = JSON.parse(localStorage.getItem("noticiasHome") || "[]");
  const ultimas = lista.slice(-3).reverse();

  ultimas.forEach(noticia => {
    const card = document.createElement("div");
    card.classList.add("noticia-card");

    card.innerHTML = `
      ${noticia.imagen ? `<img src="${noticia.imagen}" alt="Imagen de la noticia">` : ""}
      <h3>${noticia.titulo}</h3>
      <p>${noticia.contenido}</p>
      <small>Fecha: ${noticia.fecha}</small>
    `;

    contenedor.appendChild(card);
  });
}
