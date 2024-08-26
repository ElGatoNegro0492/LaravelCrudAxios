<!-- Botón para abrir el modal -->
<button data-target="modalcreate" class="bg-blue-500 text-white px-4 py-2 rounded">
    Agregar
</button>

<!-- Modal -->
<div id="modalcreate" class="modal hidden fixed inset-0 bg-gray-800 bg-opacity-50 items-center justify-center">
    <div class="modal-content bg-white rounded-lg shadow-lg max-w-lg w-full p-4">
        <div class="modal-header flex justify-between items-center border-b mb-2">
            <div class="w-full">
                <h2 class="text-xl font-semibold">Agregar película</h2>
            </div>
            <div class="w-full">
                <div class="resultado"></div>
            </div>
        </div>
        <div class="modal-body mb-4">
            <form id="createMovieForm" onsubmit="createMovie(event)">
                @csrf
                <div class="mb-4">
                    <label for="title" class="block text-sm font-medium text-gray-700">Título</label>
                    <input type="text" name="title" id="title" class="form-input mt-1 block w-full" required>
                </div>
                <div class="mb-4">
                    <label for="director" class="block text-sm font-medium text-gray-700">Director</label>
                    <input type="text" name="director" id="director" class="form-input mt-1 block w-full" required>
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

<script>
    // Función para crear una película
    function createMovie(event) {
        event.preventDefault();

        const form = event.target;
        const title = form.querySelector('#title').value;
        const director = form.querySelector('#director').value;
        const resultado = document.querySelector('.modal .resultado'); // Ajustado para buscar dentro del modal

        axios.post('/createMovie', {
            title,
            director
        }).then(response => {
            if (resultado) {
                resultado.classList.add('text-green-500');
                resultado.innerHTML = response.data.message;
                form.reset(); // Limpiar el formulario
                updateMovieTable(); // Actualizar la tabla


                setTimeout(() => {
                    // Ocultar el modal
                    const modal = document.getElementById('modalcreate');
                    modal.classList.add('hidden'); // Ocultar el modal
                    modal.classList.remove('flex'); // Ocultar el modal
                    resultado.innerHTML = '';
                }, 3000);
            } else {
                console.error('Elemento .resultado no encontrado');
            }
        }).catch(error => {
            if (resultado) {
                resultado.classList.add('text-red-500');
                resultado.innerHTML = error.response.data.error || 'Hubo un error';
            } else {
                console.error('Elemento .resultado no encontrado');
            }
        });
    }

    // Función para mostrar el modal al hacer clic en el botón
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

    // Función para cerrar el modal
    document.querySelectorAll('.close-modal').forEach(button => {
        button.addEventListener('click', () => {
            const modal = button.closest('.modal');
            if (modal) {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }
        });
    });
</script>