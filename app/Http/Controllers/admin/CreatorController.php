<?php

namespace App\Http\Controllers\admin;

use App\Models\Creator;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CreatorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $user = Auth::user();

        if ($user->hasRole('user')) {
            return view('user.creators.index');
        }

        $user->authorizeRoles('admin');

        $creators = Creator::all();

        return view('admin.creators.index')->with('creators', $creators);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = Auth::user();
        $user->authorizeRoles('admin');

        $creators = Creator::all();
        return view('admin.creators.create')->with('creators', $creators);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $user->authorizeRoles('admin');

        $formFields = $request->validate([
            'name' => ['required', Rule::unique('creators', 'name')],
            'address' => 'required',
            'bio' => 'required',
            'portfolio' => ['required', 'url'],
            'email' => ['required', 'email'],
        ]);


        /* File Upload */
        if ($request->hasFile('image')) {
            $formFields['image'] = $request->file('image')->store('images', 'public');
        }

        Creator::create($formFields);

        return to_route('admin.creators.index')->with('message', 'Creator Added successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Creator $creator)
    {
        $user = Auth::user();
        $user->authorizeRoles('admin');

        if (!Auth::id()) {
            return abort(403);
        }

        return view('admin.creators.show')->with('creator', $creator);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Creator $creator)
    {
        $user = Auth::user();
        $user->authorizeRoles('admin');

        return view('admin.creators.edit', ['creator' => $creator]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Creator $creator)
    {
        $user = Auth::user();
        $user->authorizeRoles('admin');

        $formFields = $request->validate([
            'name' => 'required',
            'address' => 'required',
            'bio' => 'required',
            'portfolio' => ['required', 'url'],
            'email' => ['required', 'email'],
        ]);


        /* File Upload */
        if ($request->hasFile('image')) {
            $formFields['image'] = $request->file('image')->store('images', 'public');
        }

        $creator->update($formFields);

        return to_route('admin.creators.index')->with('message', 'Creator Updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Creator $creator)
    {
        $user = Auth::user();
        $user->authorizeRoles('admin');

        $creator->delete();
        return to_route('admin.creators.index')->with('message', 'Creator deleted successfully');
    }
}
