<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Package;
use Illuminate\Http\Request;

class ListInvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny', [User::class, 'list-package.index']);

        $invoices_verification = Invoice::with(['package','user'])->where('status', 'verification')->get();
        $invoices_paid = Invoice::with(['package','user'])->where('status', 'paid')->get();
        $invoices_cancel = Invoice::with(['package','user'])->where('status', 'cancel')->get();

        foreach ($invoices_verification as $invoice) {
            $invoice->formatted_id = $this->formatInvoiceId($invoice->id, $invoice->created_at);
        }

        foreach ($invoices_paid as $invoice) {
            $invoice->formatted_id = $this->formatInvoiceId($invoice->id, $invoice->created_at);
        }

        foreach ($invoices_cancel as $invoice) {
            $invoice->formatted_id = $this->formatInvoiceId($invoice->id, $invoice->created_at);
        }

        return view('admin.list_invoices', [
            'invoices_verification' => $invoices_verification,
            'invoices_paid' => $invoices_paid,
            'invoices_cancel' => $invoices_cancel
        ]);
    }

    public function formatInvoiceId($id, $createdAt)
    {
        $formattedDate = \Carbon\Carbon::parse($createdAt)->format('YmdHi');
        return $formattedDate .'000'. $id;
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $invoice = Invoice::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|string|in:verification,paid,cancel',
        ]);

        $invoice->status = $validated['status'];
        $invoice->save();

        $package_id = $invoice->package_id;

        if ($validated['status'] == 'paid') {
            $package = Package::find($package_id);

            if ($package) {
                $package->users()->syncWithoutDetaching([$invoice->user_id]);
            }
        } elseif ($validated['status'] == 'cancel') {
            $package = Package::find($package_id);

            if ($package) {
                $package->users()->detach($invoice->user_id);
            }
        }

        return response()->json([
            'message' => 'Invoice status updated successfully.',
            'invoice' => $invoice
        ]);
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function showHistoryPayment()
    {
        return view('public.payment_history');
    }
}
