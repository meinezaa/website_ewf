<?php

namespace App\Http\Controllers;

use App\Models\CareerOpening;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class HrdCareerOpeningController extends Controller
{
    public function store(Request $request, string $type): RedirectResponse
    {
        $type = $this->validatedType($type);
        $validated = $this->validateOpening($request, $type);
        $validated['opening_key'] = $type.'-'.Str::slug($validated['title']).'-'.Str::lower(Str::random(6));
        $validated['type'] = $type;
        $validated['category'] = Str::slug($validated['title']);
        $validated['major'] = $type === 'internship' ? $validated['major'] : null;
        $validated['duration'] = $type === 'internship' ? $validated['duration'] : null;

        CareerOpening::query()->create($validated);

        return redirect(route('hrd.dashboard').'#'.$this->viewName($type))
            ->with('hrd_opening_saved', true);
    }

    public function update(Request $request, CareerOpening $opening): RedirectResponse
    {
        $type = $this->validatedType((string) $opening->type);
        $validated = $this->validateOpening($request, $type);
        $validated['category'] = Str::slug($validated['title']);
        $validated['major'] = $type === 'internship' ? $validated['major'] : null;
        $validated['duration'] = $type === 'internship' ? $validated['duration'] : null;
        $opening->update($validated);

        return redirect(route('hrd.dashboard').'#'.$this->viewName($type))
            ->with('hrd_opening_saved', true);
    }

    public function destroy(CareerOpening $opening): RedirectResponse
    {
        $type = $this->validatedType((string) $opening->type);
        $opening->delete();

        return redirect(route('hrd.dashboard').'#'.$this->viewName($type))
            ->with('hrd_opening_deleted', true);
    }

    private function validateOpening(Request $request, string $type): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:180'],
            'major' => [Rule::requiredIf($type === 'internship'), 'nullable', 'string', 'max:180'],
            'location' => ['required', 'string', 'max:180'],
            'work_system' => ['required', 'string', 'max:120'],
            'education' => ['required', 'string', 'max:180'],
            'qualifications' => ['required', 'string', 'max:5000'],
            'description' => ['required', 'string', 'max:10000'],
            'benefits' => ['required', 'string', 'max:3000'],
            'duration' => [Rule::requiredIf($type === 'internship'), 'nullable', 'string', 'max:120'],
            'status' => ['required', Rule::in(['open', 'closed'])],
        ]);
    }

    private function validatedType(string $type): string
    {
        abort_unless(in_array($type, ['professional', 'internship'], true), 404);

        return $type;
    }

    private function viewName(string $type): string
    {
        return $type === 'professional' ? 'career-openings' : 'internship-openings';
    }
}
