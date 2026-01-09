<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Workspace;
use App\Traits\ApiResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class WorkspaceController extends Controller
{
    use ApiResponses;

    
    public function index() {
        $user = Auth::user();
        $workspaces = $user->workspaces;
        if ($workspaces->isEmpty()) {
            return $this->errorResponse(message: 'No workspaces are made yet');
        }
        return $this->successResponse($workspaces);
    }


    public function store(Request $request) {

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'description' => 'nullable|string',
            'website' => 'nullable|url',
            'logo' => 'nullable|string',
            'visibility' => 'nullable|in:private,public',
        ]);

        $validated['slug'] = Str::slug($request->name) . random_int(200, 900);
        $validated['owner_id'] = $request->user()->id;

        $workspace = Workspace::create($validated);

        return $this->successResponse($workspace, message: "Worspace created successfully", statusCode:201);
    }


    public function show(string $id) {
        $user = Auth::user();
        $workspace = $user->workspaces()->where('id', $id)->first();
        if(! $workspace) {
            return $this->errorResponse(message: 'workspace not found');
        }
        return $this->successResponse($workspace, statusCode:201);
    }


    public function update(Request $request, $id) {
        $user = Auth::user();
        $workspace = $user->workspaces()->where('id', $id)->first();
        if(! $workspace) {
            return $this->errorResponse(message: 'workspace not found');
        }
        $validated = $request->validate([
            'name' => 'nullable|string|max:255',
            'slug' => 'nullable|string|max:255',
            'type' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'website' => 'nullable|url',
            'logo' => 'nullable|string',
            'visibility' => 'nullable|in:private,public',
        ]);

        $workspace->update($validated);
        if (! $workspace->wasChanged()) {
            return $this->errorResponse(message: 'No data provided to update');
        }
        return $this->successResponse($workspace, message: "workspace updated successfully");
    }


    public function destroy(string $id) {
        $user = Auth::user();
        $workspace = $user->workspaces()->where('id', $id)->first();
        if(! $workspace) {
            return $this->errorResponse(message: 'workspace not found');
        }
        $workspace->delete();
        return $this->successResponse(message: 'workspace deleted successfully');
    }
}
