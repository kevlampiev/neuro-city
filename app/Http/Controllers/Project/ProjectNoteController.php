<?php

namespace App\Http\Controllers\Project;

use App\Dataservices\Project\ProjectNoteDataservice;
use App\Http\Controllers\Controller;
use App\Http\Requests\Project\ProjectNoteRequest;
use App\Models\Project;
use App\Models\Note;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;


class ProjectNoteController extends Controller
{

    public function create(Request $request, Project $project)
    {
        if (url()->previous() !== url()->current()) session(['previous_url' => url()->previous()]);
        $projectNote = ProjectNoteDataservice::create($request);
        return view('projects.project-note-edit',
            ProjectNoteDataservice::provideProjectNoteEditor($projectNote, $project));
    }

    public function store(ProjectNoteRequest $request, Project $project): RedirectResponse
    {
        
        ProjectNoteDataservice::store($request, $project);
        $route = session('previous_url', route('projects.summary', ['project'=>$project, 'page'=>'notes']));
        return redirect()->to($route);
    }


    public function edit(Request $request, Project $project, Note $projectNote)
    {
        if (url()->previous() !== url()->current()) session(['previous_url' => url()->previous()]);
        ProjectNoteDataservice::edit($request, $project, $projectNote);
        return view('projects.project-note-edit',
            ProjectNoteDataservice::provideProjectNoteEditor($projectNote, $project));
    }

    public function update(ProjectNoteRequest $request, Project $project, Note $projectNote): RedirectResponse
    {
        ProjectNoteDataservice::update($request, $project, $projectNote);
        $route = session('previous_url');
        return redirect()->to($route);
    }


    public function delete(Note $projectNote): RedirectResponse
    {
        ProjectNoteDataservice::delete($projectNote);
        $route = session('previous_url');
        return redirect()->to($route);
    }

}
