@include("header")


<div class="container mx-auto">
    <div class="w-12 actions">
        @include("create")
    </div>
    <div class="w-full flex justify-center">
        <table id="moviesTable" class="table-auto text-left w-full">
            <thead>
                <tr>
                    <th class="px-4">ID</th>
                    <th class="px-4">Titulo</th>
                    <th class="px-4">Director</th>
                    <th class="px-4">Publicado</th>
                    <th class="px-4">Eliminado</th>
                    <th class="px-4">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($movies as $movie)
                <tr>
                    <td class="px-4">{{ $movie->id }}</td>
                    <td class="px-4">{{ $movie->title }}</td>
                    <td class="px-4">{{ $movie->director }}</td>
                    <td class="px-4">{{ $movie->published ? "Si" : "No" }}</td>
                    <td class="px-4">{{ $movie->deleted ? "Si" : "No" }}</td>
                    <td class="px-4">
                        <button data-target="modalupdate_{{ $movie->id }}" class="bg-blue-500 text-white px-4 py-2 rounded">Editar</button>
                        <div id="modalupdate_{{ $movie->id }}" class="modal hidden fixed inset-0 bg-gray-800 bg-opacity-50 items-center justify-center">
                            <div class="modal-content bg-white rounded-lg shadow-lg max-w-lg w-full p-4">
                                <div class="modal-header flex justify-between items-center border-b mb-2">
                                    <div class="w-full">
                                        <h2 class="text-xl font-semibold">Editar Película "{{$movie->title}}"</h2>
                                    </div>
                                    <div class="w-full">
                                        <div class="resultado"></div>
                                    </div>
                                </div>
                                <div class="modal-body mb-4">
                                    <form onsubmit="editMovie(event)">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $movie->id }}">
                                        <div class="mb-4">
                                            <label for="title" class="block text-sm font-medium text-gray-700">Título</label>
                                            <input type="text" name="title" id="title" class="form-input mt-1 block w-full" value="{{$movie->title}}" required>
                                        </div>
                                        <div class="mb-4">
                                            <label for="director" class="block text-sm font-medium text-gray-700">Director</label>
                                            <input type="text" name="director" id="director" class="form-input mt-1 block w-full" value="{{$movie->director}}" required>
                                        </div>
                                        <div class="mb-4">
                                            <label for="published" class="block text-sm font-medium text-gray-700">Publicado</label>
                                            <select name="published" id="published" class="form-select mt-1 block w-full">
                                                <option value="1" {{ $movie->isPublished ? 'selected' : '' }}>Si</option>
                                                <option value="0" {{ !$movie->isPublished ? 'selected' : '' }}>No</option>
                                            </select>
                                        </div>
                                        <div class="mb-4">
                                            <label for="deleted" class="block text-sm font-medium text-gray-700">Eliminado</label>
                                            <select name="deleted" id="deleted" class="form-select mt-1 block w-full">
                                                <option value="1" {{ $movie->isDeleted ? 'selected' : '' }}>Si</option>
                                                <option value="0" {{ !$movie->isDeleted ? 'selected' : '' }}>No</option>
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


                        <button data-target="modaldelete_{{ $movie->id }}" class="bg-red-500 text-white px-4 py-2 rounded">Eliminar</button>
                        <div id="modaldelete_{{ $movie->id }}" class="modal hidden fixed inset-0 bg-gray-800 bg-opacity-50 items-center justify-center">
                            <div class="modal-content bg-white rounded-lg shadow-lg max-w-lg w-full p-4">
                                <div class="modal-header items-center mb-4 w-full">
                                    <h2 class="w-full text-xl font-semibold">¿Está seguro de querer eliminar la película "{{$movie->title}}"?</h2>
                                    <div class="w-full">
                                        <div class="resultado"></div>
                                    </div>
                                </div>
                                <div class="modal-body mb-4 mt-4">
                                    <div class="w-full flex">
                                        <form onsubmit="deleteMovie(event)" class="w-1/2">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $movie->id }}">
                                            <div class="flex justify-center">
                                                <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded">Eliminar</button>
                                            </div>
                                        </form>
                                        <div class="modal-footer flex justify-center w-1/2">
                                            <button class="close-modal bg-gray-500 text-white px-4 py-2 rounded">Cancelar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>


<script>
    function editMovie(event) {
        event.preventDefault();

        const form = event.target;
        const id = form.querySelector('input[name="id"]').value;
        const title = form.querySelector('input[name="title"]').value;
        const director = form.querySelector('input[name="director"]').value;
        const published = form.querySelector('select[name="published"]').value;
        const deleted = form.querySelector('select[name="deleted"]').value;
        const resultado = document.querySelector(`#modalupdate_${id} .resultado`);

        axios.post('/updateMovie', {
            id,
            title,
            director,
            isPublished: published,
            isDeleted: deleted
        }).then(response => {
            if (resultado) {
                resultado.classList.add('text-green-500');
                resultado.innerHTML = response.data.message;
                updateMovieTable();
                setTimeout(() => {
                    resultado.innerHTML = '';
                }, 3000);
            }
        }).catch(error => {
            if (resultado) {
                resultado.classList.add('text-red-500');
                resultado.innerHTML = error.response.data.message || 'Error desconocido';
            }
        });
    }

    function deleteMovie(event) {
        event.preventDefault();

        const form = event.target;
        const id = form.querySelector('input[name="id"]').value;
        const resultado = document.querySelector(`#modaldelete_${id} .resultado`);

        axios.post('/deleteMovie', {
            id
        }).then(response => {
            if (resultado) {
                resultado.classList.add('text-green-500');
                resultado.innerHTML = response.data.message;
                updateMovieTable();
                setTimeout(() => {
                    resultado.innerHTML = '';
                }, 3000);
            }
        }).catch(error => {
            if (resultado) {
                resultado.classList.add('text-red-500');
                resultado.innerHTML = error.response.data.message || 'Error desconocido';
            }
        });
    }
</script>

@include("footer")