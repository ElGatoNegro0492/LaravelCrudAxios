<script>
    // Obtener todos los botones que abren modales
    const openModalButtons = document.querySelectorAll('[data-target]');

    // Obtener todos los botones de cerrar modal
    const closeModalButtons = document.querySelectorAll('.close-modal');

    // Función para abrir el modal
    openModalButtons.forEach(button => {
        button.addEventListener('click', () => {
            const modalId = button.getAttribute('data-target');
            const modal = document.getElementById(modalId);
            modal.classList.remove('hidden');
            modal.classList.add('flex'); // Mostrar el modal
        });
    });

    // Función para cerrar el modal
    closeModalButtons.forEach(button => {
        button.addEventListener('click', () => {
            const modal = button.closest('.modal');
            modal.classList.add('hidden'); // Ocultar el modal
            modal.classList.remove('flex');
        });
    });

    // Cerrar el modal al hacer clic fuera del contenido
    window.addEventListener('click', (event) => {
        const modals = document.querySelectorAll('.modal.flex');
        modals.forEach(modal => {
            if (event.target === modal) {
                modal.classList.add('hidden'); // Ocultar el modal
                modal.classList.remove('flex');
            }
        });
    });

    function updateMovieTable() {
        axios.get('/getMovies') // Asegúrate de tener una ruta que devuelva los datos de las películas
            .then(response => {
                const movies = response.data; // Suponiendo que response.data es una lista de películas
                const tableBody = document.querySelector('#moviesTable tbody');
                tableBody.innerHTML = ''; // Limpiar el contenido actual de la tabla

                movies.forEach(movie => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                    <td class="px-4">${movie.id}</td>
                    <td class="px-4">${movie.title}</td>
                    <td class="px-4">${movie.director}</td>
                    <td class="px-4">${movie.published ? "Si" : "No"}</td>
                    <td class="px-4">${movie.deleted ? "Si" : "No"}</td>
                    <td class="px-4">
                        <button data-target="modalupdate_${movie.id}" class="bg-blue-500 text-white px-4 py-2 rounded">Editar</button>
                        <div id="modalupdate_${movie.id}" class="modal hidden fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center">
                            <div class="modal-content bg-white rounded-lg shadow-lg max-w-lg w-full p-4">
                                <div class="modal-header flex justify-between items-center border-b mb-2">
                                    <div class="w-full">
                                        <h2 class="text-xl font-semibold">Editar Película "${movie.title}"</h2>
                                    </div>
                                    <div class="w-full">
                                        <div class="resultado"></div>
                                    </div>
                                </div>
                                <div class="modal-body mb-4">
                                    <form onsubmit="editMovie(event)">
                                        @csrf
                                        <input type="hidden" name="id" value="${movie.id}">
                                        <div class="mb-4">
                                            <label for="title" class="block text-sm font-medium text-gray-700">Título</label>
                                            <input type="text" name="title" id="title" class="form-input mt-1 block w-full" value="${movie.title}" required>
                                        </div>
                                        <div class="mb-4">
                                            <label for="director" class="block text-sm font-medium text-gray-700">Director</label>
                                            <input type="text" name="director" id="director" class="form-input mt-1 block w-full" value="${movie.director}" required>
                                        </div>
                                        <div class="mb-4">
                                            <label for="published" class="block text-sm font-medium text-gray-700">Publicado</label>
                                            <select name="published" id="published" class="form-select mt-1 block w-full">
                                                <option value="1" ${movie.isPublished ? 'selected' : ''}>Si</option>
                                                <option value="0" ${!movie.isPublished ? 'selected' : ''}>No</option>
                                            </select>
                                        </div>
                                        <div class="mb-4">
                                            <label for="deleted" class="block text-sm font-medium text-gray-700">Eliminado</label>
                                            <select name="deleted" id="deleted" class="form-select mt-1 block w-full">
                                                <option value="1" ${movie.isDeleted ? 'selected' : ''}>Si</option>
                                                <option value="0" ${!movie.isDeleted ? 'selected' : ''}>No</option>
                                            </select>
                                        </div>
                                        <div class="flex justify-end">
                                            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Guardar</button>
                                        </div>
                                    </form>
                                </div>
                                <div class="modal-footer flex justify-end border-t pt-2">
                                    <button class="close-modal bg-gray-500 text-white px-4 py-2 rounded">Cerrar</button>
                                </div>
                            </div>
                        </div>

                        <button data-target="modaldelete_${movie.id}" class="bg-red-500 text-white px-4 py-2 rounded">Eliminar</button>
                        <div id="modaldelete_${movie.id}" class="modal hidden fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center">
                            <div class="modal-content bg-white rounded-lg shadow-lg max-w-lg w-full p-4">
                                <div class="modal-header flex justify-between items-center border-b mb-2">
                                    <div class="w-full">
                                        <h2 class="text-xl font-semibold">¿Está seguro de querer eliminar la película "${movie.title}"?</h2>
                                    </div>
                                    <div class="w-full">
                                        <div class="resultado"></div>
                                    </div>
                                </div>
                                <div class="modal-body mb-4">
                                    <form onsubmit="deleteMovie(event)">
                                        @csrf
                                        <input type="hidden" name="id" value="${movie.id}">
                                        <div class="flex justify-end">
                                            <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded">Eliminar</button>
                                        </div>
                                        <div class="modal-footer flex justify-end pt-2">
                                            <button class="close-modal bg-gray-500 text-white px-4 py-2 rounded">Cancelar</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </td>
                `;

                    tableBody.appendChild(row);
                });

                // Reagregar los manejadores de eventos para los botones de los modales
                document.querySelectorAll('[data-target]').forEach(button => {
                    button.addEventListener('click', () => {
                        const targetId = button.getAttribute('data-target');
                        const modal = document.getElementById(targetId);
                        if (modal) {
                            modal.classList.remove('hidden');
                            modal.classList.add('flex');
                        }
                    });
                });

                document.querySelectorAll('.close-modal').forEach(button => {
                    button.addEventListener('click', () => {
                        const modal = button.closest('.modal');
                        if (modal) {
                            modal.classList.add('hidden');
                            modal.classList.remove('flex');
                        }
                    });
                });
            })
            .catch(error => {
                console.error('Error al actualizar la tabla de películas:', error);
            });
    }
</script>

</body>

</html>