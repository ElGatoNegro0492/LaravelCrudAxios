<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Movie; // instanciar el modelo Movie
use DB;

class MovieController extends Controller
{
    // El CRUD es un acrónimo que significa Crear, Leer, Actualizar y Borrar (del inglés, Create, Read, Update and Delete).

    public function index()
    {
        // Esta parte se encarga de la parte Read inicial del CRUD
        $movies = Movie::all(); // el equivalente usando la consulta en sql es: select * from movies;
        // usando la consulta de sql directamente, sería: DB::select('select * from movies');
        return view('movies', compact('movies'));
    }

    public function getMovies()
    {
        // se maneja con AXIOS en el frontend por medio del metodo GET
        $movies = Movie::all();
        // $movies = DB::select('select * from movies');
        // la consulta sql es: select * from movies;
        return response()->json($movies);
    }

    public function create()
    {
        // se maneja con AXIOS en el frontend por medio del metodo POST
        $movie = new Movie(); // instanciar el modelo Movie
        $movie->title = request('title');
        $movie->director = request('director');
        $movie->save();
        // $movie = DB::insert('insert into movies (title, director) values (?, ?)', [request('title'), request('director')]);
        // la consulta sql es: insert into movies (title, director) values ('mi maravillosa pelicula', 'Ernesto Gonzales');
        return response()->json(['message' => 'Pelicula Agregada']);
    }

    public function update(Request $request)
    {
        // Validar los datos
        $validated = $request->validate([
            'id' => 'required|exists:movies,id',
            'title' => 'required|string',
            'director' => 'required|string',
            'isPublished' => 'required|boolean',
            'isDeleted' => 'required|boolean',
        ]);

        // Encontrar la película por ID
        $movie = Movie::find($validated['id']);
        if (!$movie) {
            return response()->json(['message' => 'Película no encontrada'], 404);
        }

        // Actualizar los campos
        $movie->title = $validated['title'];
        $movie->director = $validated['director'];
        $movie->isPublished = $validated['isPublished'];
        $movie->isDeleted = $validated['isDeleted'];
        // $movie = DB::update('update movies set title = ?, director = ?, isPublished = ?, isDeleted = ? where id = ?', [$validated['title'], $validated['director'], $validated['isPublished'], $validated['isDeleted'], $validated['id']]);
        // la consulta sql es: update movies set title = 'titulo', director = 'director', isPublished = 1, isDeleted = 0 where id = 1;
        $movie->save();

        return response()->json(['message' => 'Pelicula Actualizada']);
    }

    public function delete(Request $request)
    {
        // Validar los datos
        $validated = $request->validate([
            'id' => 'required|exists:movies,id',
        ]);

        // Encontrar la película por ID
        $movie = Movie::find($validated['id']);

        if (!$movie) {
            return response()->json(['message' => 'Película no encontrada'], 404);
        }

        // Borrar la película
        $movie->delete();
        // $movie = DB::delete('delete from movies where id = ?', [$validated['id']]);
        // la consulta sql es: delete from movies where id = 1;

        return response()->json(['message' => 'Pelicula Eliminada']);
    }
}
