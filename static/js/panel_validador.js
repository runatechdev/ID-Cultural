document.addEventListener('DOMContentLoaded', () => {

  // --- ELEMENTOS DEL DOM ---
  const tablaBody = document.getElementById('tabla-pendientes-body');
  const modal = document.getElementById('modal-rechazo');
  const nombreArtistaModal = document.getElementById('nombre-artista-modal');
  const inputMotivo = document.getElementById('input-motivo');
  const btnConfirmarRechazo = document.getElementById('btn-confirmar-rechazo');
  const btnCancelarModal = document.getElementById('btn-cancelar-modal');

  // Variable para saber qué artista estamos procesando
  let idArtistaSeleccionado = null;

  // --- FUNCIONES ---

  /**
   * Muestra el modal para ingresar el motivo del rechazo.
   * @param {string} id - El ID del artista a rechazar.
   * @param {string} nombre - El nombre del artista para mostrar en el modal.
   */
  const abrirModalRechazo = (id, nombre) => {
      idArtistaSeleccionado = id;
      nombreArtistaModal.textContent = nombre;
      inputMotivo.value = '';
      modal.classList.remove('hidden');
      inputMotivo.focus();
  };

  /**
   * Cierra el modal de rechazo.
   */
  const cerrarModal = () => {
      modal.classList.add('hidden');
      idArtistaSeleccionado = null;
  };

  /**
   * Envía la acción (aprobar o rechazar) al servidor.
   * @param {string} accion - Puede ser 'aprobar' o 'rechazar'.
   * @param {string} id - El ID del artista.
   * @param {string} [motivo=''] - El motivo del rechazo (opcional).
   */
  const procesarAccion = async (accion, id, motivo = '') => {
      const url = `/ID-Cultural/backend/controllers/${accion}_perfil.php`;
      const formData = new FormData();
      formData.append('id', id);
      if (accion === 'rechazar') {
          formData.append('motivo', motivo);
      }

      try {
          const response = await fetch(url, { method: 'POST', body: formData });
          const resultado = await response.json();

          if (resultado.status === 'ok') {
              // Si el servidor confirma, eliminamos la fila de la tabla
              const filaParaEliminar = document.querySelector(`tr[data-id='${id}']`);
              if (filaParaEliminar) {
                  filaParaEliminar.remove();
              }
              alert(`El perfil ha sido ${accion === 'aprobar' ? 'aprobado' : 'rechazado'} con éxito.`);
              cerrarModal();
          } else {
              alert(`Error del servidor: ${resultado.message}`);
          }
      } catch (error) {
          console.error(`Error en la acción ${accion}:`, error);
          alert('Error de conexión con el servidor.');
      }
  };

  // --- EVENT LISTENERS ---

  // 1. Usamos DELEGACIÓN DE EVENTOS en la tabla. Un solo listener para todo.
  tablaBody.addEventListener('click', (e) => {
      const id = e.target.closest('tr').dataset.id;
      const nombre = e.target.closest('tr').dataset.nombre;

      // Si se hizo clic en el botón de aprobar
      if (e.target.classList.contains('btn-aprobar')) {
          if (confirm(`¿Estás seguro de que quieres APROBAR el perfil de ${nombre}?`)) {
              procesarAccion('aprobar', id);
          }
      }

      // Si se hizo clic en el botón de rechazar
      if (e.target.classList.contains('btn-rechazar')) {
          abrirModalRechazo(id, nombre);
      }
  });

  // 2. Listener para los botones del modal
  btnCancelarModal.addEventListener('click', cerrarModal);

  btnConfirmarRechazo.addEventListener('click', () => {
      const motivo = inputMotivo.value.trim();
      if (motivo) {
          procesarAccion('rechazar', idArtistaSeleccionado, motivo);
      } else {
          alert('Debes escribir un motivo para el rechazo.');
      }
  });
});