<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\OnboardingResource;
use App\Http\Traits\ApiResponses;
use App\Models\Onboarding;
use Illuminate\Http\Request;

class OnboardingController extends Controller
{
  use ApiResponses;
    public function index()
    {
        $onboardings = Onboarding::with('media')->get();
        return $this->ok('Onboardings retrieved successfully', OnboardingResource::collection($onboardings));

    }


    public function show($id)
    {
        $onboarding = Onboarding::with('media')->findOrFail($id);
        return new OnboardingResource($onboarding);
    }


    public function store(Request $request)
    {
        $this->authorizeAdmin($request);

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'images' => 'nullable|array',
            'images.*' => 'file|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $onboarding = Onboarding::create($data);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $onboarding->addMedia($file)
                    ->toMediaCollection('images');
            }
        }

        return new OnboardingResource($onboarding);
    }


   public function update(Request $request, $id)
{
    $this->authorizeAdmin($request);

    $onboarding = Onboarding::findOrFail($id);

    $data = $request->validate([
        'title' => 'sometimes|required|string|max:255',
        'subtitle' => 'nullable|string|max:255',
        'description' => 'nullable|string',
        'images' => 'nullable|array',
        'images.*' => 'file|mimes:jpg,jpeg,png,webp|max:2048',
    ]);

    $onboarding->update($data);
    $onboarding->refresh();

    if ($request->hasFile('images')) {
        $onboarding->clearMediaCollection('images');
        foreach ($request->file('images') as $file) {
            $onboarding->addMedia($file)->toMediaCollection('images');
        }
        $onboarding->refresh();
    }

    return new OnboardingResource($onboarding->fresh());

}


    public function destroy(Request $request, $id)
    {
        $this->authorizeAdmin($request);

        $onboarding = Onboarding::findOrFail($id);
        $onboarding->delete();

        return response()->json(['message' => 'Onboarding deleted successfully']);
    }

    protected function authorizeAdmin(Request $request)
    {
        $user = $request->user();
        if (!$user || !($user->is_admin ?? false)) {
            abort(403, 'Admin only.');
        }
    }
}
