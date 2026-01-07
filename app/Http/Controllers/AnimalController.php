<?php

namespace App\Http\Controllers;

use App\Concerns\FilterablePaginate;
use App\Enums\AnimalStatus;
use App\Jobs\ProcessUploadedImage;
use App\Models\Animal;
use App\Models\Coat;
use App\Models\Race;
use App\Models\Specie;
use App\Models\SuitableType;
use App\Models\Vaccine;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;

class AnimalController extends Controller
{
    use FilterablePaginate;

    public function index(Request $request)
    {
        $species = Specie::select('id', 'name')->get();
        $races = Race::select('id', 'name', 'specie_id')->get();
        $coats = Coat::select('id', 'name')->get();
        $vaccines = Vaccine::select('id', 'name')->get();
        $suitableTypes = SuitableType::select('id', 'name')->get();


        $animals = $this->filterAndPaginate(
            Animal::class,
            $request,
            ['coat', 'race', 'specie', 'vaccines', 'suitableTypes'], 'animal_search'
        );
        $animals->through(fn($animal) => $animal->loadMissing([
            'suitableTypes', 'vaccines', 'notes' => fn($q) => $q->latest()->limit(5)
        ]));

        return Inertia::render('AnimalsIndexView', [
            'title' => 'Animals',
            'animals' => $animals,
            'species' => $species,
            'races' => $races,
            'coats' => $coats,
            'vaccines' => $vaccines,
            'allSuitableTypes' => $suitableTypes,
            'filters' => $request->only(['animal_search', 'orderby', 'dir', 'status']),
            'can' => [
                'publish' => Auth::user()->can('publish', Animal::class),
            ]
        ]);
    }

    public function create()
    {
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'age' => 'required|integer|min:0',
            'chip' => 'required|string|max:50',
            'sex' => 'required|in:male,female',
            'specie_id' => 'required|exists:species,id',
            'race_id' => 'nullable|exists:races,id',
            'coat_id' => 'required|exists:coats,id',
            'description' => 'required|string',
            'status' => 'required|in:'.implode(',', AnimalStatus::values()),
            'outside' => 'boolean',
            'published' => 'boolean',
            'suitable_type_ids' => 'nullable|array',
            'suitable_type_ids.*' => 'exists:suitable_types,id',
            'pictures' => 'nullable|array',
            'pictures.*' => 'image|mimes:jpeg,png,jpg,webp|max:4096',
            'vaccine_id' => 'nullable|array',
            'vaccine_id.*' => 'exists:vaccines,id',
        ]);

        $storedImages = [];

        if ($request->hasFile('pictures')) {
            foreach ($request->file('pictures') as $image) {
                $filename = Str::uuid().'.webp';

                $originalPath = Storage::disk(
                    config('images.disk')
                )->putFileAs(
                    config('images.original_path'),
                    $image,
                    $filename
                );

                if ($originalPath) {
                    $storedImages[] = $filename;
                    ProcessUploadedImage::dispatch(
                        $originalPath,
                        $filename,
                        'images'
                    );
                }
            }
        }

        $animal = Animal::create([
            'name' => $validated['name'],
            'age' => $validated['age'],
            'chip' => $validated['chip'],
            'sex' => $validated['sex'],
            'race_id' => $validated['race_id'],
            'coat_id' => $validated['coat_id'],
            'description' => $validated['description'],
            'status' => $validated['status'],
            'outside' => $validated['outside'] ?? false,
            'published' => $validated['published'] ?? false,
            'user_id' => auth()->id(),
            'admission_date' => Carbon::now()->format('d-m-Y'),
            'pictures' => $storedImages,
        ]);

        if (!empty($validated['suitable_type_ids'])) {
            $animal->suitableTypes()->sync($validated['suitable_type_ids']);
        }

        if (!empty($request['vaccine_id'])) {
            $animal->vaccines()->sync($request['vaccine_id']);
        }

        return back();
    }

    public function update(Request $request, Animal $animal)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'age' => 'required|integer|min:0',
            'chip' => 'required|string|max:50',
            'sex' => 'required|in:male,female',
            'specie_id' => 'required|exists:species,id',
            'race_id' => 'nullable|exists:races,id',
            'coat_id' => 'required|exists:coats,id',
            'description' => 'required|string',
            'status' => 'required|in:'.implode(',', AnimalStatus::values()),
            'outside' => 'boolean',
            'published' => 'boolean',
            'suitable_type_ids' => 'nullable|array',
            'suitable_type_ids.*' => 'exists:suitable_types,id',
            'pictures' => 'nullable|array',
            'pictures.*' => 'image|mimes:jpeg,png,jpg,webp|max:4096',
            'admission_date' => 'nullable|date_format:d-m-Y',
            'vaccine_id' => 'nullable|array',
            'vaccine_id.*' => 'exists:vaccines,id',
        ]);

        $animal->update([
            'name' => $validated['name'],
            'age' => $validated['age'],
            'chip' => $validated['chip'],
            'sex' => $validated['sex'],
            'race_id' => $validated['race_id'],
            'coat_id' => $validated['coat_id'],
            'description' => $validated['description'],
            'status' => $validated['status'],
            'outside' => $validated['outside'] ?? false,
            'published' => $validated['published'] ?? false,
        ]);

        if ($request->hasFile('pictures')) {
            $storedImages = is_array($animal->pictures) ? $animal->pictures : [];

            foreach ($request->file('pictures') as $image) {
                $filename = Str::uuid().'.webp';

                $originalPath = Storage::disk(
                    config('images.disk')
                )->putFileAs(
                    config('images.original_path'),
                    $image,
                    $filename
                );

                if ($originalPath) {
                    $storedImages[] = $filename;
                    ProcessUploadedImage::dispatch(
                        $originalPath,
                        $filename,
                        'images'
                    );
                }
            }

            $animal->update(['pictures' => $storedImages]);
        }

        if (isset($validated['suitable_type_ids'])) {
            $animal->suitableTypes()->sync($validated['suitable_type_ids']);
        }

        if (!empty($request['vaccine_id'])) {
            $animal->vaccines()->sync($request['vaccine_id']);
        }

        return back();
    }

    public function show(Animal $animal)
    {

    }

    public function edit(Animal $animal)
    {

    }

    public function destroy(Animal $animal)
    {
        if (!empty($animal->pictures)) {
            foreach ($animal->pictures as $picture) {
                Storage::disk(config('images.disk'))->delete(
                    config('images.original_path').'/'.$picture
                );
                $sizes = ['300x300', '600x600', '900x900'];
                foreach ($sizes as $size) {
                    Storage::disk(config('images.disk'))->delete(
                        "images/animals/variants/{$size}/{$picture}"
                    );
                }
            }
        }
        $animal->vaccines()->detach();
        $animal->suitableTypes()->detach();
        $animal->delete();

        return back();
    }

    public function deleteImage(Request $request, Animal $animal)
    {
        $validated = $request->validate([
            'filename' => 'required|string'
        ]);

        $filename = $validated['filename'];

        if (!is_array($animal->pictures) || !in_array($filename, $animal->pictures)) {
            return back()->withErrors(['message' => 'Image non trouvée']);
        }

        Storage::disk(config('images.disk'))->delete(
            config('images.original_path').'/'.$filename
        );

        $sizes = ['300x300', '600x600', '900x900'];
        foreach ($sizes as $size) {
            Storage::disk(config('images.disk'))->delete(
                "images/animals/variants/{$size}/{$filename}"
            );
        }

        $pictures = array_values(array_filter($animal->pictures, function ($pic) use ($filename) {
            return $pic !== $filename;
        }));

        $animal->update(['pictures' => $pictures]);

        return back();
    }
}
