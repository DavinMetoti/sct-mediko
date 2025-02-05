<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Package;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

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

    }

    public function show(Request $request, $id)
    {
        $histories = Invoice::with('package')->where('user_id',auth()->id())->get();

        if ($request->ajax()) {
            $histories = Invoice::with('package')->where('user_id',auth()->id())->get();

            return DataTables::of($histories)
            ->addColumn('formatted_id', function ($row) {
                $formattedDate = Carbon::parse($row->created_at)->format('YmdHi');
                return $formattedDate.'000'.$row->id;
            })
            ->addColumn('status', function ($row) {
                switch ($row->status) {
                    case 'paid':
                        return '<span class="badge bg-success">Dibayar</span>';
                    case 'pending':
                        return '<span class="badge bg-warning text-dark">Menunggu</span>';
                    case 'cancel':
                        return '<span class="badge bg-danger">Dibatalkan</span>';
                    case 'verification':
                        return '<span class="badge bg-primary">Verifikasi</span>';
                    default:
                        return '<span class="badge bg-secondary">Tidak Diketahui</span>';
                }
            })
            ->editColumn('created_at', function ($row) {
                return Carbon::parse($row->created_at)->translatedFormat('d F Y H:i');
            })
            ->addColumn('action', function ($row) {
                $invoiceButton = ($row->status == 'paid' || $row->status == 'verification') ?
                    '<a href="' . route('payment.edit', $row->id) . '" class="btn btn-sm btn-primary ' . ($row->status == 'verification' ? 'disabled' : '') . '">
                        <i class="fas fa-file-invoice"></i>
                    </a>' : '';

                $deleteButton = ($row->status != 'paid' && $row->status != 'verification') ?
                    '<button type="button" class="btn btn-sm btn-danger delete-btn" data-id="' . $row->id . '">
                        <i class="fas fa-times"></i>
                    </button>' : '';

                return $invoiceButton . $deleteButton;
            })
            ->rawColumns(['status','action'])
            ->make(true);
        }

    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'payment_proof' => 'required|string',
            ]);

            $invoice = Invoice::findOrFail($id);

            if ($request->payment_proof) {
                $base64String = $request->payment_proof;

                $fileSizeInBytes = (strlen($base64String) * 3) / 4;
                if ($fileSizeInBytes > 2097152) {
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
        try {
            $invoice = Invoice::findOrFail($id);

            $invoice->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Invoice deleted successfully.'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete invoice. ' . $e->getMessage()
            ], 500);
        }
    }


    public function showPaymentHistory()
    {
        $this->authorize('viewAny', [User::class, 'payment-histories.index']);

        return view('public.payment_history');
    }
}
