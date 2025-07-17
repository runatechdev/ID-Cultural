// Datos simulados — luego reemplazá con datos desde PHP
const artistasPendientes = [
  {
    nombre: "Lucía Gómez",
    disciplina: "Música",
    localidad: "La Banda",
    estado: "Pendiente"
  },
  {
    nombre: "Carlos Ruiz",
    disciplina: "Pintura",
    localidad: "Termas de Río Hondo",
    estado: "Pendiente"
  }
];

const tabla = document.querySelector("#tabla-artistas tbody");

artistasPendientes.forEach((artista, index) => {
  const fila = document.createElement("tr");

  fila.innerHTML = `
    <td>${artista.nombre}</td>
    <td>${artista.disciplina}</td>
    <td>${artista.localidad}</td>
    <td>${artista.estado}</td>
    <td>
      <button class="btn details">Ver</button>
      <button class="btn approve">Aprobar</button>
      <button class="btn reject btn-reject">Rechazar</button>
    </td>
    <td class="motivo-rechazo">—</td>
  `;

  tabla.appendChild(fila);
});

document.addEventListener("DOMContentLoaded", () => {
  const botonesRechazo = document.querySelectorAll(".btn-reject");
  const modal = document.getElementById("modal-rechazo");
  const textarea = document.getElementById("input-motivo");
  const btnCancelar = document.getElementById("btn-cancelar");
  const btnConfirmar = document.getElementById("btn-confirmar");

  let artistaActual = null;
  let filaActual = null;

  botonesRechazo.forEach((boton, index) => {
    boton.addEventListener("click", () => {
      artistaActual = artistasPendientes[index];
      filaActual = tabla.rows[index];
      textarea.value = "";
      modal.classList.remove("hidden");
    });
  });

  btnCancelar.addEventListener("click", () => {
    modal.classList.add("hidden");
  });

  btnConfirmar.addEventListener("click", () => {
    const motivo = textarea.value.trim();
    if (motivo !== "") {
      const celdaMotivo = filaActual.querySelector(".motivo-rechazo");
      celdaMotivo.textContent = motivo;
      alert(`Motivo guardado para ${artistaActual.nombre}:\n"${motivo}"`);
      modal.classList.add("hidden");
    } else {
      alert("Debes escribir un motivo para rechazar.");
    }
  });
});
