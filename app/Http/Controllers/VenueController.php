<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVenueRequest;
use App\Models\Venue;
use App\Traits\HandlesImageUpload;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class VenueController extends Controller
{
    use HandlesImageUpload;

    public function index(Request $request): View
    {
        $search = $request->string('search')->toString();

        $venues = Venue::query()
            ->withCurrentBookings()
            ->when($search, function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query
                        ->where('name', 'like', "%{$search}%")
                        ->orWhere('address', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(9)
            ->withQueryString();

        return view('admin.venues.index', [
            'venues' => $venues,
            'search' => $search,
        ]);
    }

    public function create(): View
    {
        return view('admin.venues.create');
    }

    public function store(StoreVenueRequest $request): RedirectResponse
    {
        $payload = $request->safe()->except('image');

        if ($request->hasFile('image')) {
            $payload['image_url'] = $this->uploadImage($request->file('image'), 'venue-images');
        }

        Venue::create([
            'id' => (string) Str::uuid(),
            ...$payload,
        ]);

        return redirect()
            ->route('admin.venues.index')
            ->with('status', 'Venue berhasil ditambahkan.');
    }

    public function show(Venue $venue): View
    {
        return view('admin.venues.show', [
            'venue' => $venue,
        ]);
    }

    public function edit(Venue $venue): View
    {
        return view('admin.venues.edit', [
            'venue' => $venue,
        ]);
    }

    public function update(StoreVenueRequest $request, Venue $venue): RedirectResponse
    {
        $payload = $request->safe()->except('image');

        if ($request->hasFile('image')) {
            $this->deleteImageUrl($venue->image_url);
            $payload['image_url'] = $this->uploadImage($request->file('image'), 'venue-images');
        }

        $venue->update($payload);

        return redirect()
            ->route('admin.venues.index')
            ->with('status', 'Venue berhasil diperbarui.');
    }

    public function destroy(Venue $venue): RedirectResponse
    {
        $this->deleteImageUrl($venue->image_url);
        $venue->delete();

        return redirect()
            ->route('admin.venues.index')
            ->with('status', 'Venue berhasil dihapus.');
    }
}
