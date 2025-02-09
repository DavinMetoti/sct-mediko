<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Package;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ListPakcageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny', [User::class, 'list-package.index']);

        $userId = auth()->id();

        $packages = Package::with(['questions'])
            ->leftJoin('invoices', function ($join) use ($userId) {
                $join->on('invoices.package_id', '=', 'packages.id')
                    ->where('invoices.user_id', '=', $userId);
            })
            ->whereDate('packages.expires_at', '>=', now()) // Hanya tampilkan yang masih berlaku
            ->select(
                'packages.*',
                'invoices.status as invoice_status',
                DB::raw('COALESCE(invoices.status, "Unpurchased") as final_status')
            )
            ->get();


        // $packages = Package::with(['questions'])
        //     ->get();

        return view('public.list_package', [
            "packages" => $packages
        ]);
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
