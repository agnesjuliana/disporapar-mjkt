<?php

namespace App\Http\Controllers;

use App\Models\Venue;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class VenueController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $search = $request->string('search')->toString();

        $venues = Venue::query()
            ->with([
                'venueBookings' => function ($query) {
                    $query
                        ->whereIn('status', ['PENDING', 'APPROVED'])
                        ->where('booking_start', '<=', now())
                        ->where('booking_end', '>=', now())
                        ->orderByRaw("case when status = 'APPROVED' then 0 else 1 end")
                        ->orderBy('booking_end');
                },
            ])
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

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.venues.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        Venue::create([
            'id' => (string) Str::uuid(),
            ...$this->validatedVenue($request),
        ]);

        return redirect()
            ->route('admin.venues.index')
            ->with('status', 'Venue berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Venue $venue): View
    {
        return view('admin.venues.show', [
            'venue' => $venue,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Venue $venue): View
    {
        return view('admin.venues.edit', [
            'venue' => $venue,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Venue $venue): RedirectResponse
    {
        $venue->update($this->validatedVenue($request));

        return redirect()
            ->route('admin.venues.index')
            ->with('status', 'Venue berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Venue $venue): RedirectResponse
    {
        $venue->delete();

        return redirect()
            ->route('admin.venues.index')
            ->with('status', 'Venue berhasil dihapus.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validatedVenue(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'capacity' => ['required', 'integer', 'min:0'],
            'description' => ['required', 'string', 'max:255'],
            'lowest_price' => ['required', 'numeric', 'min:0'],
            'highest_price' => ['required', 'numeric', 'min:0', 'gte:lowest_price'],
            'available_from' => ['required', 'date'],
            'available_to' => ['required', 'date', 'after_or_equal:available_from'],
        ]);
    }
}
