<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $id = $request->query('id');
        $package = Package::findOrFail($id);

        $invoice = Invoice::firstOrCreate(
            [
                'user_id' => auth()->id(),
                'package_id' => $id
            ],
            [
                'status' => Invoice::STATUS_PENDING,
                'payment_proof' => null,
            ]
        );

        $invoiceId = $invoice->id;

        return view('public.payment', compact('package', 'invoiceId','invoice'));
    }



    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
        // Show payment details here
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'payment_proof' => 'required|string', // Pastikan input berupa string base64
            ]);

            $invoice = Invoice::findOrFail($id);

            if ($request->payment_proof) {
                $base64String = $request->payment_proof;

                $fileSizeInBytes = (strlen($base64String) * 3) / 4;
                if ($fileSizeInBytes > 2097152) { // 2MB limit
                    return response()->json([
                        'success' => false,
                        'message' => 'Ukuran file terlalu besar. Maksimal 2MB.'
                    ], 400);
                }

                $matches = [];
                preg_match('/^data:image\/(\w+);base64,/', $base64String, $matches);
                $extension = isset($matches[1]) ? $matches[1] : 'png';

                $base64String = preg_replace('/^data:image\/\w+;base64,/', '', $base64String);
                $imageData = base64_decode($base64String);

                $fileName = 'payment_proof_' . time() . '.' . $extension;
                $filePath = 'payment_proofs/' . $fileName;

                Storage::disk('public')->put($filePath, $imageData);

                $invoice->update([
                    'status' => Invoice::STATUS_VERIFICATION,
                    'payment_proof' => 'storage/' . $filePath,
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Invoice berhasil diperbarui.',
                'data'    => $invoice
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal.',
                'errors'  => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui invoice.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }


    public function destroy($id)
    {
        // Destroy payment details here
    }
}
