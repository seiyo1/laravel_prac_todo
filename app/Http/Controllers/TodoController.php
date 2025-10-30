<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Todo;

class TodoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user_id = auth()->id();
        return response()->json(Todo::where('user_id', $user_id)->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);
        $data['user_id'] = auth()->id();
        $data['completed'] = false;
        $todo = Todo::create($data);
        return response()->json($todo, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user_todos = Todo::where('user_id', auth()->id())->findOrFail($id);
        return response()->json($user_todos);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $data = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|nullable|string',
            'completed' => 'sometimes|boolean',
        ]);
        $todo = Todo::where('user_id', auth()->id())->findOrFail($id);
        $todo->update($data);
        return response()->json($todo);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $todo = Todo::where('user_id', auth()->id())->findOrFail($id);
        $todo->delete();
        return response()->json(null, 204);
    }
}
