<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePaymentRequest;
use App\Http\Requests\UpdatePaymentRequest;
use App\Models\House;
use App\Models\Payment;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Payment::with(['house', 'createdBy', 'paidBy']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by payment type
        if ($request->filled('payment_type')) {
            $query->where('payment_type', $request->payment_type);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->where('due_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('due_date', '<=', $request->date_to);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhere('receipt_number', 'like', "%{$search}%")
                  ->orWhereHas('house', function ($hq) use ($search) {
                      $hq->where('block_number', 'like', "%{$search}%");
                  });
            });
        }

        $payments = $query->latest()->paginate(15);

        return Inertia::render('payments/index', [
            'payments' => $payments,
            'filters' => $request->only(['status', 'payment_type', 'date_from', 'date_to', 'search']),
            'stats' => [
                'total' => Payment::count(),
                'pending' => Payment::where('status', 'pending')->count(),
                'paid' => Payment::where('status', 'paid')->count(),
                'overdue' => Payment::where('status', 'overdue')->count(),
                'total_amount' => Payment::sum('amount'),
                'paid_amount' => Payment::where('status', 'paid')->sum('amount'),
            ]
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $houses = House::select('id', 'block_number', 'address', 'owner_name')
            ->orderBy('block_number')
            ->get();

        return Inertia::render('payments/create', [
            'houses' => $houses,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePaymentRequest $request)
    {
        $payment = Payment::create(array_merge(
            $request->validated(),
            ['created_by' => auth()->id()]
        ));

        return redirect()->route('payments.show', $payment)
            ->with('success', 'Pembayaran berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Payment $payment)
    {
        $payment->load(['house', 'createdBy', 'paidBy']);

        return Inertia::render('payments/show', [
            'payment' => $payment,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Payment $payment)
    {
        $houses = House::select('id', 'block_number', 'address', 'owner_name')
            ->orderBy('block_number')
            ->get();

        return Inertia::render('payments/edit', [
            'payment' => $payment,
            'houses' => $houses,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePaymentRequest $request, Payment $payment)
    {
        $updateData = $request->validated();
        
        // If marking as paid, set paid_date and paid_by
        if ($updateData['status'] === 'paid' && $payment->status !== 'paid') {
            $updateData['paid_date'] = now();
            $updateData['paid_by'] = auth()->id();
        }

        $payment->update($updateData);

        return redirect()->route('payments.show', $payment)
            ->with('success', 'Data pembayaran berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Payment $payment)
    {
        $payment->delete();

        return redirect()->route('payments.index')
            ->with('success', 'Pembayaran berhasil dihapus.');
    }
}