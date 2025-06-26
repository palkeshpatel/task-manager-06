<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{




    public function index(Request $request)
    {
        $query = Task::with('project');

        if ($request->has('search') && $request->search) {
            $query->search($request->search);
        }



        if ($request->has('project_id') && $request->project_id) {
            $query->where('project_id', $request->project_id);
        }

        $tasks = $query->orderBy('created_at', 'desc')->paginate(10);
        $projects = Project::all();

        return view('tasks.index', compact('tasks', 'projects'));
    }


    public function create()
    {
        $projects = Project::all();
        $users = User::all();
        return view('tasks.create', compact('projects', 'users'));
    }


    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'project_id' => 'required|exists:projects,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date|after_or_equal:today',
        ]);


        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            return back()->withErrors($validator)->withInput();
        }

        $task = Task::create($request->only(['project_id', 'title', 'description', 'due_date']));

        DB::table('pivot_project_users')->where('task_id', $task->id)->delete();
        foreach ($request->user_id as $user) {
            DB::table('pivot_project_users')->insert([
                'user_id' => $user,
                'task_id' => $task->id
            ]);
        }

        if ($request->ajax()) {
            $task->load('project');
            return response()->json(['success' => true, 'task' => $task]);
        }

        return redirect()->route('tasks.index')->with('success', 'Task created successfully!');
    }

    public function show(Task $task)
    {
        $task->load('project');
        return view('tasks.show', compact('task'));
    }


    public function edit(Task $task)
    {
        $projects = Project::all();
        $users = User::all();
        return view('tasks.edit', compact('task', 'projects', 'users'));
    }


    public function update(Request $request, Task $task)
    {

        $validator = Validator::make($request->all(), [
            'project_id' => 'required|exists:projects,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:pending,completed',
            'due_date' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            return back()->withErrors($validator)->withInput();
        }

        $oldData = $task->toArray();
        $task->update($request->only(['project_id', 'title', 'description', 'status', 'due_date']));

        DB::table('pivot_project_users')->where('task_id', $task->id)->delete();
        foreach ($request->user_id as $user) {
            DB::table('pivot_project_users')->insert([
                'user_id' => $user,
                'task_id' => $task->id
            ]);
        }

        if ($request->ajax()) {
            $task->load('project');
            return response()->json(['success' => true, 'task' => $task]);
        }

        return redirect()->route('tasks.index')->with('success', 'Task updated successfully!');
    }


    public function destroy(Task $task)
    {

        $task->delete();

        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('tasks.index')->with('success', 'Task deleted successfully!');
    }
}