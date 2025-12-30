<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use Illuminate\Http\JsonResponse;

class ProjectController extends Controller
{
    public function index(): JsonResponse
    {
        $projects = Project::where('status', 'active')->with('photos')->get();
        return response()->json($projects);
    }

    public function donationsOverview()
    {
        return view('admin.donations');
    }
}
