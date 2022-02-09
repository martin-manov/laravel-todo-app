<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(): Response
    {
        return \response(['projects' => auth()->user()->projects]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request): Response
    {
        $fields = $request->validate([
            'name' => 'required'
        ]);

        $project = new Project();
        $project->name = $fields['name'];
        $project->user()->associate(auth()->user());
        $project->save();

        return \response(['project' => $project]);
    }
}
