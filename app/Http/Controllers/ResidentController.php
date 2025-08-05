<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreResidentRequest;
use App\Http\Requests\UpdateResidentRequest;
use App\Models\House;
use App\Models\Resident;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ResidentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Resident::with(['house', 'user']);

        // Filter by active status
        if ($request->filled('is_active')) {
            $query->where('is_active', $request->boolean('is_active'));
        }

        // Filter by relationship
        if ($request->filled('relationship')) {
            $query->where('relationship', $request->relationship);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhereHas('house', function ($hq) use ($search) {
                      $hq->where('block_number', 'like', "%{$search}%");
                  });
            });
        }

        $residents = $query->latest()->paginate(15);

        return Inertia::render('residents/index', [
            'residents' => $residents,
            'filters' => $request->only(['is_active', 'relationship', 'search']),
            'stats' => [
                'total' => Resident::count(),
                'active' => Resident::where('is_active', true)->count(),
                'owners' => Resident::where('relationship', 'owner')->count(),
                'tenants' => Resident::where('relationship', 'tenant')->count(),
            ]
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $houses = House::select('id', 'block_number', 'address')
            ->orderBy('block_number')
            ->get();

        $users = User::where('role', 'resident')
            ->select('id', 'name', 'email')
            ->orderBy('name')
            ->get();

        return Inertia::render('residents/create', [
            'houses' => $houses,
            'users' => $users,
            'selectedHouseId' => $request->house_id,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreResidentRequest $request)
    {
        $resident = Resident::create($request->validated());

        return redirect()->route('residents.show', $resident)
            ->with('success', 'Data penghuni berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Resident $resident)
    {
        $resident->load(['house', 'user']);

        return Inertia::render('residents/show', [
            'resident' => $resident,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Resident $resident)
    {
        $houses = House::select('id', 'block_number', 'address')
            ->orderBy('block_number')
            ->get();

        $users = User::where('role', 'resident')
            ->select('id', 'name', 'email')
            ->orderBy('name')
            ->get();

        return Inertia::render('residents/edit', [
            'resident' => $resident,
            'houses' => $houses,
            'users' => $users,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateResidentRequest $request, Resident $resident)
    {
        $resident->update($request->validated());

        return redirect()->route('residents.show', $resident)
            ->with('success', 'Data penghuni berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Resident $resident)
    {
        $resident->delete();

        return redirect()->route('residents.index')
            ->with('success', 'Data penghuni berhasil dihapus.');
    }
}