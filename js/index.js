document.addEventListener('DOMContentLoaded', function() {
    // ===== MENÚ HAMBURGUESA =====
const menuHamburguesa = document.querySelector(".menu-hamburguesa");
const menuNavegacion = document.querySelector(".menu-navegacion");

// Activar/desactivar el menú hamburguesa al hacer clic
if (menuHamburguesa && menuNavegacion) {
    menuHamburguesa.addEventListener("click", () => {
        menuHamburguesa.classList.toggle("activo");
        menuNavegacion.classList.toggle("activo");
    });
}



    // ===== CARRUSEL DE IMÁGENES =====
    const bannerPrincipal = document.querySelector('.banner-principal');
    
    if (bannerPrincipal) {
        // Crear estructura del carrusel
        bannerPrincipal.innerHTML = `
            <div class="carrusel">
                <div class="carrusel-contenedor">
                    <div class="carrusel-slides">
                        <div class="carrusel-slide activo">
                            <img src="media/Fondo1.webp" alt="Gastronomía Francesa 1">
                        </div>
                        <div class="carrusel-slide">
                            <img src="media/Fondo2.webp" alt="Gastronomía Francesa 2">
                        </div>
                        <div class="carrusel-slide">
                            <img src="media/Fondo3.webp" alt="Gastronomía Francesa 3">
                        </div>
                        <div class="carrusel-slide">
                            <img src="media/Fondo4.webp" alt="Gastronomía Francesa 3">
                        </div>
                        <div class="carrusel-slide">
                            <img src="media/Fondo5.webp" alt="Gastronomía Francesa 3">
                        </div>
                    </div>
                    <button class="carrusel-btn carrusel-prev">‹</button>
                    <button class="carrusel-btn carrusel-next">›</button>
                    <div class="carrusel-indicadores">
                        <span class="indicador activo" data-slide="0"></span>
                        <span class="indicador" data-slide="1"></span>
                        <span class="indicador" data-slide="2"></span>
                        <span class="indicador" data-slide="3"></span>
                        <span class="indicador" data-slide="4"></span>
                    </div>
                </div>
            </div>
        `;

        // Variables del carrusel
        let slideActual = 0;
        const slides = document.querySelectorAll('.carrusel-slide');
        const indicadores = document.querySelectorAll('.indicador');
        const btnPrev = document.querySelector('.carrusel-prev');
        const btnNext = document.querySelector('.carrusel-next');
        const totalSlides = slides.length;

        // Función para mostrar slide específico
        function mostrarSlide(n) {
            // Remover clase activa de todos los slides e indicadores
            slides.forEach(slide => slide.classList.remove('activo'));
            indicadores.forEach(ind => ind.classList.remove('activo'));
            
            // Ajustar índice si está fuera de rango
            slideActual = (n + totalSlides) % totalSlides;
            
            // Agregar clase activa al slide e indicador actual
            slides[slideActual].classList.add('activo');
            indicadores[slideActual].classList.add('activo');
        }

        // Event listeners para botones
        if (btnNext && btnPrev) {
            btnNext.addEventListener('click', () => {
                mostrarSlide(slideActual + 1);
            });

            btnPrev.addEventListener('click', () => {
                mostrarSlide(slideActual - 1);
            });
        }

        // Event listeners para indicadores
        indicadores.forEach((indicador, index) => {
            indicador.addEventListener('click', () => {
                mostrarSlide(index);
            });
        });

        // Cambio automático cada 5 segundos
        setInterval(() => {
            mostrarSlide(slideActual + 1);
        }, 5000);

        // Pausar carrusel al hacer hover
        const carrusel = document.querySelector('.carrusel');
        let intervaloAutomatico;

        function iniciarAutoplay() {
            intervaloAutomatico = setInterval(() => {
                mostrarSlide(slideActual + 1);
            }, 5000);
        }

        function pausarAutoplay() {
            clearInterval(intervaloAutomatico);
        }

        carrusel.addEventListener('mouseenter', pausarAutoplay);
        carrusel.addEventListener('mouseleave', iniciarAutoplay);

        // Iniciar autoplay
        iniciarAutoplay();
    }
});


document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('modal-platillo');
    const botonesVerDetalles = document.querySelectorAll('.boton-ver-detalles');
    const botonCerrarModal = document.querySelector('.boton-cerrar-modal');
    const formularioComentario = document.getElementById('formulario-comentario');
    const imagenModal = document.getElementById('imagen-modal'); // Elemento de la imagen
    const sinImagen = document.getElementById('sin-imagen'); // Mensaje cuando no hay imagen

    // Función para cargar comentarios
    function cargarComentarios(idPlatillo) {
        const listaComentarios = document.getElementById('lista-comentarios');
        listaComentarios.innerHTML = '<p>Cargando comentarios...</p>';
        
        fetch(`obtener_comentarios.php?id_platillo=${idPlatillo}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    if (data.comentarios.length > 0) {
                        listaComentarios.innerHTML = '';
                        data.comentarios.forEach(comentario => {
                            const comentarioHTML = `
                                <div class="comentario">
                                    <div class="info-usuario">
                                        <span class="nombre-usuario">${comentario.nombre_usuario}</span>
                                        <span class="carrera-usuario">${comentario.carrera_usuario}</span>
                                        <span class="fecha-comentario">${new Date(comentario.fecha_creacion).toLocaleDateString()}</span>
                                    </div>
                                    <p class="texto-comentario">${comentario.texto}</p>
                                </div>
                            `;
                            listaComentarios.innerHTML += comentarioHTML;
                        });
                    } else {
                        listaComentarios.innerHTML = '<p>No hay comentarios aún. ¡Sé el primero en comentar!</p>';
                    }
                } else {
                    listaComentarios.innerHTML = '<p>Error al cargar comentarios.</p>';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                listaComentarios.innerHTML = '<p>Error al cargar comentarios.</p>';
            });
    }

    // Abrir modal
    botonesVerDetalles.forEach(boton => {
        boton.addEventListener('click', function() {
            const idPlatillo = this.getAttribute('data-platillo');
            const nombre = this.getAttribute('data-nombre');
            const descripcion = this.getAttribute('data-descripcion');
            const ingredientes = this.getAttribute('data-ingredientes');
            const tipo = this.getAttribute('data-tipo');
            const imagen = this.getAttribute('data-imagen'); // Nueva propiedad para la imagen

            // Llenar modal con datos
            document.getElementById('titulo-modal').textContent = nombre;
            document.getElementById('descripcion-modal').textContent = descripcion;
            document.getElementById('ingredientes-modal').textContent = ingredientes;
            document.getElementById('tipo-modal').textContent = tipo;
            
            // Configurar imagen
            if (imagen && imagen.trim() !== '') {
                imagenModal.style.display = 'block';
                sinImagen.style.display = 'none';
                imagenModal.src = imagen; // Asignar la imagen al modal
            } else {
                imagenModal.style.display = 'none';
                sinImagen.style.display = 'block';
            }

            // Configurar campo hidden para comentarios - CON VERIFICACIÓN
            const inputPlatilloComentario = document.getElementById('id-platillo-comentario');
            if (inputPlatilloComentario) {
                inputPlatilloComentario.value = idPlatillo;
            }

            // Cargar comentarios
            cargarComentarios(idPlatillo);

            // Mostrar modal
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        });
    });

    // Cerrar modal
    botonCerrarModal.addEventListener('click', function() {
        modal.style.display = 'none';
        document.body.style.overflow = 'auto';
    });

    // Cerrar modal al hacer clic fuera
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
        }
    });

    // Manejar envío de comentarios
    if (formularioComentario) {
        formularioComentario.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const idPlatillo = document.getElementById('id-platillo-comentario').value;
            
            fetch('procesar_comentario.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Comentario enviado correctamente');
                    document.getElementById('texto-comentario').value = '';
                    // Recargar comentarios
                    cargarComentarios(idPlatillo);
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al enviar el comentario');
            });
        });
    }
});
