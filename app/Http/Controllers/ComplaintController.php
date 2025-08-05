<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreComplaintRequest;
use App\Http\Requests\UpdateComplaintRequest;
use App\Models\Complaint;
use App\Models\House;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ComplaintController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = Complaint::with(['house', 'reportedBy', 'assignedTo']);

        // Residents can only see their own complaints
        if ($user->isResident()) {
            $query->where('reported_by', $user->id);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Filter by priority
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('house', function ($hq) use ($search) {
                      $hq->where('block_number', 'like', "%{$search}%");
                  });
            });
        }

        $complaints = $query->latest()->paginate(15);

        return Inertia::render('complaints/index', [
            'complaints' => $complaints,
            'filters' => $request->only(['status', 'category', 'priority', 'search']),
            'stats' => [
                'total' => $user->isResident() ? 
                    Complaint::where('reported_by', $user->id)->count() : 
                    Complaint::count(),
                'open' => $user->isResident() ? 
                    Complaint::where('reported_by', $user->id)->whereIn('status', ['open', 'in_progress'])->count() :
                    Complaint::whereIn('status', ['open', 'in_progress'])->count(),
                'resolved' => $user->isResident() ?
                    Complaint::where('reported_by', $user->id)->where('status', 'resolved')->count() :
                    Complaint::where('status', 'resolved')->count(),
                'high_priority' => $user->isResident() ?
                    Complaint::where('reported_by', $user->id)->whereIn('priority', ['high', 'urgent'])->count() :
                    Complaint::whereIn('priority', ['high', 'urgent'])->count(),
            ]
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = auth()->user();
        
        if ($user->isResident()) {
            // For residents, only show houses they live in
            $houses = House::whereHas('residents', function ($query) use ($user) {
                $query->where('user_id', $user->id)->where('is_active', true);
            })->select('id', 'block_number', 'address')->get();
        } else {
            // For staff, show all houses
            $houses = House::select('id', 'block_number', 'address')
                ->orderBy('block_number')
                ->get();
        }

        return Inertia::render('complaints/create', [
            'houses' => $houses,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreComplaintRequest $request)
    {
        $complaint = Complaint::create(array_merge(
            $request->validated(),
            ['reported_by' => auth()->id()]
        ));

        return redirect()->route('complaints.show', $complaint)
            ->with('success', 'Keluhan berhasil diajukan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Complaint $complaint)
    {
        $user = auth()->user();
        
        // Residents can only view their own complaints
        if ($user->isResident() && $complaint->reported_by !== $user->id) {
            abort(403);
        }

        $complaint->load(['house', 'reportedBy', 'assignedTo']);

        $staff = [];
        if ($user->canManageComplaints()) {
            $staff = User::whereIn('role', ['administrator', 'housing_manager'])
                ->select('id', 'name')
                ->orderBy('name')
                ->get();
        }

        return Inertia::render('complaints/show', [
            'complaint' => $complaint,
            'staff' => $staff,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Complaint $complaint)
    {
        $user = auth()->user();
        
        // Residents can only edit their own unresolved complaints
        if ($user->isResident() && 
            ($complaint->reported_by !== $user->id || 
             in_array($complaint->status, ['resolved', 'closed']))) {
            abort(403);
        }

        if ($user->isResident()) {
            $houses = House::whereHas('residents', function ($query) use ($user) {
                $query->where('user_id', $user->id)->where('is_active', true);
            })->select('id', 'block_number', 'address')->get();
        } else {
            $houses = House::select('id', 'block_number', 'address')
                ->orderBy('block_number')
                ->get();
        }

        $staff = [];
        if ($user->canManageComplaints()) {
            $staff = User::whereIn('role', ['administrator', 'housing_manager'])
                ->select('id', 'name')
                ->orderBy('name')
                ->get();
        }

        return Inertia::render('complaints/edit', [
            'complaint' => $complaint,
            'houses' => $houses,
            'staff' => $staff,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateComplaintRequest $request, Complaint $complaint)
    {
        $user = auth()->user();
        
        // Residents can only edit their own unresolved complaints
        if ($user->isResident() && 
            ($complaint->reported_by !== $user->id || 
             in_array($complaint->status, ['resolved', 'closed']))) {
            abort(403);
        }

        $updateData = $request->validated();
        
        // Set resolved date if marking as resolved
        if ($updateData['status'] === 'resolved' && $complaint->status !== 'resolved') {
            $updateData['resolved_date'] = now();
        }

        $complaint->update($updateData);

        return redirect()->route('complaints.show', $complaint)
            ->with('success', 'Keluhan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Complaint $complaint)
    {
        $user = auth()->user();
        
        // Residents can only delete their own complaints
        if ($user->isResident() && $complaint->reported_by !== $user->id) {
            abort(403);
        }

        $complaint->delete();

        return redirect()->route('complaints.index')
            ->with('success', 'Keluhan berhasil dihapus.');
    }
}