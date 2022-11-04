<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProjectController extends Controller
{
    //show all projects
    public function index() {
        return view('projects.index', [
            'projects' => Project::latest()->filter(request(['tag', 'search']))->paginate(6)
        ]);
    }

    //show single project
    public function show(Project $project) {
        return view('projects.show', [
            'project' => $project
        ]);
    }

    //show create form
    public function create() {
        return view('projects.create');
    }

    //store project data
    public function store(Request $request) {
        $request->validate([
            'title' => ['required', Rule::unique('projects', 'title')],
            'tags'=> 'required',
            'date_created' => 'required',
            'website' => ['required', 'url'],
            'email' => ['required', 'email'],
            'description' => 'required'  
        ]);


        /* File Upload */
        if($request->hasFile('image')) {
            $formFields['image'] = $request->file('image')->store('images', 'public');
        }

        Project::create([
            'uuid' => Str::uuid(),
            'title' => $request->title,
            'tags'=> $request->tags,
            'date_created' => $request->date_created,
            'website' => $request->website,
            'email' => $request->email,
            'description' => $request->description       
        ]);

        return redirect('/')->with('message', 'Project uploaded successfully!');
    }

    //show edit form
    public function edit(Project $project) {
        return view('projects.edit', ['project' => $project]);
    }


     //Update project data
     public function update(Request $request, Project $project) {
        $formFields = $request->validate([
            'title' => 'required',
            'tags'=> 'required',
            'date_created' => 'required',
            'website' => ['required', 'url'],
            'email' => ['required', 'email'],
            'description' => 'required'  
        ]);


        /* File Upload */
        if($request->hasFile('image')) {
            $formFields['image'] = $request->file('image')->store('images', 'public');
        }

        $project->update($formFields);

        return back()->with('message', 'Project Updated successfully!');
    }

    

}
