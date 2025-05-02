<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Todo;
use Illuminate\Support\Facades\Auth;

class TodoController extends Controller
{
    // public function index()
    // {
    //     // $todos = Todo::all();
    //     $todos = Todo::where('user_id', Auth::id())->orderBy('created_at', 'desc')->get();
    //     // dd($todos);

    //     return view('todo.index', compact('todos'));
    // }
    public function index()
    {
        $todos = Todo::where('user_id', Auth::id())
            // ->orderBy('is_done', 'asc')
            ->orderBy('created_at', 'desc')
            ->get();

        $todosCompleted = Todo::where('user_id', Auth::id())
            ->where('is_done', true)
            ->count();

        return view('todo.index', compact('todos', 'todosCompleted'));
    }




    public function create()
    {
        return view('todo.create');
    }

    public function edit(Todo $todo)
    {
        if (Auth::user()->id == $todo->user_id) {
            // dd($todo);
            return view('todo.edit', compact('todo'));
        } else {
            // abort(403);
            // abort(403, 'Not authorized');

            return redirect()->route('todo.index')->with('danger', 'You are not authorized to edit this todo!');
        }
    }

    public function update(Request $request, Todo $todo)
    {
        $request->validate([
            'title' => 'required|max:255',
        ]);

        // Practical
        // $todo->title = $request->title;
        // $todo->save();

        // Eloquent Way - Readable
        $todo->update([
            'title' => ucfirst($request->title),
        ]);

        return redirect()->route('todo.index')->with('success', 'Todo updated successfully!');
    }


    public function complete(Todo $todo)
    {
        if (Auth::id() == $todo->user_id) {
            $todo->update(['is_done' => true]);
            return redirect()->route('todo.index')->with('success', 'Todo completed successfully!');
        } else {
            return redirect()->route('todo.index')->with('error', 'You are not authorized to complete this todo!');
        }
    }

    public function uncomplete(Todo $todo)
    {
        if (Auth::id() == $todo->user_id) {
            $todo->update(['is_done' => false]);
            return redirect()->route('todo.index')->with('success', 'Todo uncompleted successfully!');
        } else {
            return redirect()->route('todo.index')->with('error', 'You are not authorized to uncomplete this todo!');
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $todo = Todo::create([
            'title' => ucfirst($request->title),
            'user_id' => Auth::id(),
        ]);
        return redirect()->route('todo.index')->with('success', 'Todo created successfully.');
    }

    public function destroy(Todo $todo)
    {
        if (Auth::id() == $todo->user_id) {
            $todo->delete();
            return redirect()->route('todo.index')->with('success', 'Todo deleted successfully!');
        } else {
            return redirect()->route('todo.index')->with('danger', 'You are not authorized to delete this todo!');
        }
    }

    public function destroyCompleted()
    {
        $todosCompleted = Todo::where('user_id', Auth::id())
            ->where('is_done', true)
            ->get();

        foreach ($todosCompleted as $todo) {
            $todo->delete();
        }

        return redirect()->route('todo.index')->with('success', 'All completed todos deleted successfully!');
    }
}
