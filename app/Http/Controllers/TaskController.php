<?php
namespace App\Http\Controllers;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller {
    public function index() {
        return view('tasks', ['tasks' => Task::latest()->get()]);
    }
    public function store(Request $request) {
        $data = $request->validate(['title' => 'required|max:255']);
        Task::create($data);
        return redirect('/');
    }
    public function destroy(Task $task) {
        $task->delete();
        return redirect('/');
    }
}
