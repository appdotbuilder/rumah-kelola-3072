<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreHouseRequest;
use App\Http\Requests\UpdateHouseRequest;
use App\Models\House;
use Illuminate\Http\Request;
use Inertia\Inertia;

class HouseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = House::with(['residents' => function ($query) {
            $query->where('is_active', true);
        }]);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by house type
        if ($request->filled('house_type')) {
            $query->where('house_type', $request->house_type);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('block_number', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%")
                  ->orWhere('owner_name', 'like', "%{$search}%");
            });
        }

        $houses = $query->latest()->paginate(12);

        return Inertia::render('houses/index', [
            'houses' => $houses,
            'filters' => $request->only(['status', 'house_type', 'search']),
            'stats' => [
                'total' => House::count(),
                'available' => House::where('status', 'available')->count(),
                'sold' => House::where('status', 'sold')->count(),
                'reserved' => House::where('status', 'reserved')->count(),
                'maintenance' => House::where('status', 'maintenance')->count(),
            ]
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('houses/create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreHouseRequest $request)
    {
        $house = House::create($request->validated());

        return redirect()->route('houses.show', $house)
            ->with('success', 'Rumah berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(House $house)
    {
        $house->load([
            'residents' => function ($query) {
                $query->latest();
            },
            'payments' => function ($query) {
                $query->with(['createdBy', 'paidBy'])->latest();
            },
            'complaints' => function ($query) {
                $query->with(['reportedBy', 'assignedTo'])->latest();
            }
        ]);

        return Inertia::render('houses/show', [
            'house' => $house,
            'recentPayments' => $house->payments->take(5),
            'recentComplaints' => $house->complaints->take(5),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(House $house)
    {
        return Inertia::render('houses/edit', [
            'house' => $house
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateHouseRequest $request, House $house)
    {
        $house->update($request->validated());

        return redirect()->route('houses.show', $house)
            ->with('success', 'Data rumah berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(House $house)
    {
        $house->delete();

        return redirect()->route('houses.index')
            ->with('success', 'Rumah berhasil dihapus.');
    }
}