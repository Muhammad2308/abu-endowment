<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use Illuminate\Http\JsonResponse;

class ProjectController extends Controller
{
    public function index(): JsonResponse
    {
        $projects = Project::with('photos')->get();
        return response()->json($projects);
    }

    public function donationsOverview()
    {
        $projects = Project::with('photos', 'donations')->get();
        return view('admin.projects', compact('projects'));
    }
}
