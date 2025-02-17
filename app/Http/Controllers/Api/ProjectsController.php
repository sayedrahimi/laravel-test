<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Http\Resources\ProjectsResource;
use App\Models\Projects;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProjectsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $projects = Projects::orderBy('id', 'desc')->get();

        return ProjectsResource::collection($projects);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProjectRequest $request)
    {

        $image_path = $request->file('image')->store('projects', 'public');
        Projects::create([
            'name' => $request->name,
            'image' => $request->$image_path,
        ]);

        return "Project Create successfully";
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $project = Projects::findOrFail($id);
        return new ProjectsResource($project);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $project = Projects::findOrFail($id);
        Storage::disk('public')->delete($project->image);
        $project->delete();

        return "Project deleted successfully";
    }

    public function editProject(UpdateProjectRequest $request, string $id){

        $project = Projects::findOrFail($id);
        if($request->hasFile('image')){
            $image_path = $request->file('image')->store('projects', 'public');
            $project->update([
                'name' => $request->name,
                'image' => $image_path,
            ]);
        }else{
            $project->update([
                'name' => $request->name
            ]);
        }
        return "Project Updated successfully";
    }

}
