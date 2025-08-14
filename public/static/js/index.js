document.addEventListener('DOMContentLoaded', () => {
    
    const contenedorNoticias = document.getElementById('contenedor-noticias');

    async function cargarNoticias() {
        if (!contenedorNoticias) return;

        contenedorNoticias.innerHTML = '<p class="text-center">Cargando noticias...</p>';
        try {
            const response = await fetch(`${BASE_URL}api/get_noticias.php`);
            if (!response.ok) throw new Error('No se pudieron cargar las noticias.');
            
            const noticias = await response.json();
            contenedorNoticias.innerHTML = '';

            if (noticias.length === 0) {
                contenedorNoticias.innerHTML = '<p class="text-center">No hay noticias para mostrar en este momento.</p>';
                return;
            }

            noticias.forEach(noticia => {
                const contenidoCorto = noticia.contenido.length > 100 
                    ? noticia.contenido.substring(0, 100) + '...' 
                    : noticia.contenido;

                const imagenSrc = noticia.imagen_url 
                    ? `${BASE_URL}${noticia.imagen_url}`
                    : 'https://placehold.co/600x400/efc892/FFFFFF?text=ID+Cultural';

                const fecha = new Date(noticia.fecha_creacion).toLocaleDateString('es-AR', {
                    year: 'numeric', month: 'long', day: 'numeric'
                });

                const cardHTML = `
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100 news-card">
                            <img src="${imagenSrc}" class="card-img-top" alt="${noticia.titulo}">
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title">${noticia.titulo}</h5>
                                <p class="card-text">${contenidoCorto}</p>
                                <a href="#" class="btn btn-primary mt-auto">Leer más</a>
                            </div>
                            <div class="card-footer text-muted">
                                Publicado el ${fecha}
                            </div>
                        </div>
                    </div>
                `;
                contenedorNoticias.innerHTML += cardHTML;
            });

        } catch (error) {
            contenedorNoticias.innerHTML = '<p class="text-center text-danger">Error al cargar las noticias.</p>';
        }
    }

    cargarNoticias();
});
