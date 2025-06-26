<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProjectController extends Controller
{





    public function index()
    {
        $projects = Project::withCount(['tasks', 'tasks as completed_tasks_count' => function ($query) {
            $query->where('status', 'completed');
        }])->get();

        return view('projects.index', compact('projects'));
    }


    public function create()
    {
        return view('projects.create');
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            return back()->withErrors($validator)->withInput();
        }

        $project = Project::create($request->only(['name', 'description']));


        if ($request->ajax()) {
            return response()->json(['success' => true, 'project' => $project]);
        }

        return redirect()->route('projects.index')->with('success', 'Project created successfully!');
    }


    public function show(Project $project)
    {
        $project->load(['tasks' => function ($query) {
            $query->orderBy('created_at', 'desc');
        }]);

        return view('projects.show', compact('project'));
    }


    public function edit(Project $project)
    {
        return view('projects.edit', compact('project'));
    }


    public function update(Request $request, Project $project)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            return back()->withErrors($validator)->withInput();
        }

        $oldData = $project->toArray();
        $project->update($request->only(['name', 'description']));

        if ($request->ajax()) {
            return response()->json(['success' => true, 'project' => $project]);
        }

        return redirect()->route('projects.index')->with('success', 'Project updated successfully!');
    }


    public function destroy(Project $project)
    {

        $project->delete();

        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('projects.index')->with('success', 'Project deleted successfully!');
    }
}