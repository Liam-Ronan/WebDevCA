<?php

namespace app\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Creator;
use App\Models\Developer;
use App\Models\Project;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ProjectController extends Controller
{
    //show all projects
    public function index()
    {

        $user = Auth::user();
        $user->authorizeRoles('admin');

        $projects = Project::with('creator')->with('developers')->latest()->filter(request(['tag', 'search']))->paginate(6);

        return view('admin.projects.index')->with('projects', $projects);

        /* return view('admin.projects.index', [ */
        /* Showing a total of 6 projects */
        /*       'projects' => Project::latest()->filter(request(['tag', 'search']))->paginate(6)
        ]); */
    }

    //show single project
    public function show(Project $project)
    {
        return view('admin.projects.show')->with('project', $project);
    }

    //show create form
    public function create()
    {
        $user = Auth::user();
        $user->authorizeRoles('admin');

        $creators = Creator::all();
        $developers = Developer::all();
        return view('admin.projects.create')->with('creators', $creators)->with('developers', $developers);
    }

    //store project data
    public function store(Request $request)
    {

        $user = Auth::user();
        $user->authorizeRoles('admin');

        $request->validate([
            'title' => ['required', Rule::unique('projects', 'title')],
            'tags' => 'required',
            'date_created' => 'required',
            'website' => ['required', 'url'],
            'email' => ['required', 'email'],
            'description' => 'required',
            'creator_id' => 'required',
            'developers' => ['required', 'exists:developers,id']
        ]);


        /* File Upload */
        if ($request->hasFile('image')) {
            $image = $request->file('image')->store('images', 'public');
        }

        $project = Project::create([
            'title' => $request->title,
            'tags' => $request->tags,
            'date_created' => $request->date_created,
            'website' => $request->website,
            'email' => $request->email,
            'description' => $request->description,
            'creator_id' => $request->creator_id,
            'image' => $image
        ]);

        $project->developers()->attach($request->developers);


        return to_route('admin.projects.index')->with('message', 'Project Created successfully');
    }

    //show edit form
    public function edit(Project $project)
    {

        $user = Auth::user();
        $user->authorizeRoles('admin');

        $creators = Creator::all();
        $developers = Developer::all();

        return view('admin.projects.edit', ['project' => $project])->with('creators', $creators)->with('developers', $developers);
    }


    //Update project data
    public function update(Request $request, Project $project)
    {

        $user = Auth::user();
        $user->authorizeRoles('admin');

        $formFields = $request->validate([
            'title' => 'required',
            'tags' => 'required',
            'date_created' => 'required',
            'website' => ['required', 'url'],
            'email' => ['required', 'email'],
            'description' => 'required',
            'creator_id' => 'required',
            'developer_id' => 'required'
        ]);


        /* File Upload */
        if ($request->hasFile('image')) {
            $formFields['image'] = $request->file('image')->store('images', 'public');
        }

        $project->update($formFields);

        return to_route('admin.projects.index')->with('message', 'Project Updated successfully!');
    }


    //Delete Project
    public function destroy(Project $project)
    {

        $user = Auth::user();
        $user->authorizeRoles('admin');

        $project->delete();
        return to_route('admin.projects.index')->with('message', 'Project deleted successfully');
    }
}
