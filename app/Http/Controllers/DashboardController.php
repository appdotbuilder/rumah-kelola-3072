<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use App\Models\House;
use App\Models\Payment;
use App\Models\Resident;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    /**
     * Display the main dashboard.
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        // Dashboard stats based on user role
        $stats = $this->getDashboardStats($user);
        
        // Recent activity based on user role
        $recentActivity = $this->getRecentActivity($user);

        return Inertia::render('dashboard', [
            'stats' => $stats,
            'recentActivity' => $recentActivity,
            'userRole' => $user->role,
        ]);
    }

    /**
     * Get dashboard statistics based on user role.
     */
    protected function getDashboardStats($user): array
    {
        $baseStats = [
            'houses' => [
                'total' => House::count(),
                'available' => House::where('status', 'available')->count(),
                'sold' => House::where('status', 'sold')->count(),
                'reserved' => House::where('status', 'reserved')->count(),
            ],
            'residents' => [
                'total' => Resident::count(),
                'active' => Resident::where('is_active', true)->count(),
            ],
        ];

        if ($user->canManagePayments()) {
            $baseStats['payments'] = [
                'total' => Payment::count(),
                'pending' => Payment::where('status', 'pending')->count(),
                'overdue' => Payment::where('status', 'overdue')->count(),
                'paid_this_month' => Payment::where('status', 'paid')
                    ->whereMonth('paid_date', now()->month)
                    ->count(),
            ];
        }

        if ($user->canManageComplaints()) {
            $baseStats['complaints'] = [
                'total' => Complaint::count(),
                'open' => Complaint::whereIn('status', ['open', 'in_progress'])->count(),
                'high_priority' => Complaint::where('priority', 'high')->count(),
                'resolved_this_month' => Complaint::where('status', 'resolved')
                    ->whereMonth('resolved_date', now()->month)
                    ->count(),
            ];
        }

        if ($user->isResident()) {
            // For residents, show only their house-specific data
            $userHouses = $user->residents()->with('house')->get()->pluck('house.id');
            
            $baseStats['my_payments'] = [
                'pending' => Payment::whereIn('house_id', $userHouses)
                    ->where('status', 'pending')->count(),
                'overdue' => Payment::whereIn('house_id', $userHouses)
                    ->where('status', 'overdue')->count(),
            ];

            $baseStats['my_complaints'] = [
                'open' => Complaint::where('reported_by', $user->id)
                    ->whereIn('status', ['open', 'in_progress'])->count(),
                'total' => Complaint::where('reported_by', $user->id)->count(),
            ];
        }

        return $baseStats;
    }

    /**
     * Get recent activity based on user role.
     */
    protected function getRecentActivity($user): array
    {
        $activity = [];

        if ($user->canManageHouses()) {
            $activity['recent_houses'] = House::with(['residents' => function ($query) {
                $query->where('is_active', true);
            }])
                ->latest()
                ->take(5)
                ->get();
        }

        if ($user->canManagePayments()) {
            $activity['recent_payments'] = Payment::with(['house', 'createdBy'])
                ->latest()
                ->take(5)
                ->get();
        }

        if ($user->canManageComplaints()) {
            $activity['recent_complaints'] = Complaint::with(['house', 'reportedBy'])
                ->latest()
                ->take(5)
                ->get();
        }

        if ($user->isResident()) {
            $userHouses = $user->residents()->with('house')->get()->pluck('house.id');
            
            $activity['my_payments'] = Payment::with('house')
                ->whereIn('house_id', $userHouses)
                ->latest()
                ->take(5)
                ->get();

            $activity['my_complaints'] = Complaint::with(['house', 'assignedTo'])
                ->where('reported_by', $user->id)
                ->latest()
                ->take(5)
                ->get();
        }

        return $activity;
    }
}