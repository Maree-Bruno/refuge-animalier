<?php

namespace App\Http\Controllers;

use App\Concerns\FilterablePaginate;
use App\Enums\AnimalStatus;
use App\Enums\SuitableFor;
use App\Jobs\ProcessUploadedImage;
use App\Models\Animal;
use App\Models\Coat;
use App\Models\Race;
use App\Models\Specie;
use App\Models\Vaccine;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;

class AnimalController extends Controller
{
    use FilterablePaginate;

    public function index(Request $request)
    {
        $species = Specie::all();
        $races = Race::all();
        $coats = Coat::all();
        $vaccines = Vaccine::all();
        $animals = $this->filterAndPaginate(Animal::class, $request, ['coat', 'race', 'specie', 'vaccines']);
        return Inertia::render('AnimalsIndexView', [
            'title' => 'Animals',
            'animals' => $animals,
            'species' => $species,
            'races' => $races,
            'coats' => $coats,
            'vaccines' => $vaccines,
            'filters' => $request->only(['search', 'orderby', 'dir', 'status']),
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
            'suitable' => 'nullable|array',
            'suitable.*' => 'string|in:'.implode(',', SuitableFor::values()),
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
            'suitable' => $validated['suitable'] ?? [],
            'user_id' => auth()->id(),
            'admission_date' => Carbon::now()->format('d-m-Y'),
            'pictures' => $storedImages,
        ]);
        $vaccines = $request['vaccine_id'];
        if ($vaccines) {
            $animal->vaccines()->sync($vaccines);
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
            'suitable' => 'nullable|array',
            'suitable.*' => 'string|in:'.implode(',', SuitableFor::values()),
            'pictures' => 'nullable|array',
            'pictures.*' => 'image|mimes:jpeg,png,jpg,webp|max:4096',
            'admission_date' => 'nullable|date=>format("d-m-Y")',
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
            'suitable' => $validated['suitable'] ?? [],
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
        $vaccines = $request['vaccine_id'];
        if ($vaccines) {
            $animal->vaccines()->sync($vaccines);
        }

        return back();
    }

    public function show(Animal $animal)
    {
        $otherAnimals = Animal::where('id', '!=', $animal->id)
            ->whereIn('status', [
                AnimalStatus::VALIDATED,
                AnimalStatus::IN_PROGRESS
            ])
            ->inRandomOrder()
            ->limit(3)
            ->get();

        return view('client.animals_show', compact('animal', 'otherAnimals'));
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
            config('images.original_path') . '/' . $filename
        );

        $sizes = ['300x300', '600x600', '900x900'];
        foreach ($sizes as $size) {
            Storage::disk(config('images.disk'))->delete(
                "images/animals/variants/{$size}/{$filename}"
            );
        }

        $pictures = array_values(array_filter($animal->pictures, function($pic) use ($filename) {
            return $pic !== $filename;
        }));

        $animal->update(['pictures' => $pictures]);

        return back();
    }
}
