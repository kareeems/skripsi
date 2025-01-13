<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Instalment;
use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Snap;

class PaymentController extends Controller
{
    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
    }

    public function createCharge(Request $request)
    {
        $rules = [
            'user_id' => 'required|exists:users,id',
            'payment_type' => 'required|string|in:instalment',
            'referensi_id' => 'required|array|min:1',
            'referensi_id.*' => 'integer',
            'amount' => 'required|integer',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|email',
            'phone' => 'nullable|string|min:10',
        ];

        // Validasi berdasarkan payment_type
        if ($request->payment_type === 'instalment') {
            $rules['referensi_id.*'] = 'exists:instalments,id';
        }

        // Validasi request
        $request->validate($rules);

        $paymentType = $request->payment_type;
        $paymentReference = null;
        if ($paymentType != 'instalment') {
            $paymentReference = $request->referensi_id[0];
        }

        $payment = Payment::create([
            'user_id' => $request->user_id,
            'reference_id' => $paymentReference,
            'reference_type' => $paymentType,
            'invoice_number' => uniqid('INV-'),
            'amount' => $request->amount,
        ]);

        // Jika payment_type adalah instalment
        if ($paymentType === 'instalment') {
            // Validasi bahwa setiap referensi_id merujuk pada instalmen yang valid
            $instalments = Instalment::whereIn('id', $request->referensi_id)->get();
            if ($instalments->count() !== count($request->referensi_id)) {
                $payment->update(['status' => 'failed']);
                return response()->json(['message' => 'Beberapa instalmen tidak valid'], 400);
            }

            // Menambahkan relasi banyak ke banyak dengan instalmen menggunakan pivot table
            foreach ($instalments as $instalment) {
                $payment->instalments()->attach($instalment->id, ['amount' => $instalment->total]);
            }
        }

        $params = [
            'transaction_details' => [
                'order_id' => $payment->invoice_number,
                'gross_amount' => $payment->amount,
            ],
            'customer_details' => [
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'phone' => $request->phone,
            ],
        ];

        $snapToken = Snap::getSnapToken($params);

        return response()->json([
            'params' => $params,
            'token' => $snapToken,
            'payment' => $payment,
        ]);
    }

    public function callback(Request $request)
    {
        $serverKey = config('midtrans.server_key');
        $signatureKey = hash('sha512', $request->order_id . $request->status_code . $request->gross_amount . $serverKey);

        if ($signatureKey !== $request->signature_key) {
            return response()->json(['message' => 'Invalid signature key'], 403);
        }

        $payment = Payment::where('invoice_number', $request->order_id)->first();

        if (!$payment) {
            return response()->json(['message' => 'Payment not found'], 404);
        }

        if ($request->transaction_status === 'settlement') {
            $payment->update([
                'status' => 'paid',
                'paid_at' => now(),
                'payment_method' => $request->payment_type,
                'callback_data' => $request->all(),
            ]);

            // Update instalment and related transaction
            $this->updateInstalmentStatus($payment->reference_id);
        }

        return response()->json(['message' => 'Payment updated']);
    }

    private function updateInstalmentStatus($instalmentId)
    {
        $instalment = Instalment::findOrFail($instalmentId);

        // Update paid_at pada instalment
        $instalment->update(['paid_at' => now()]);

        // Perbarui status transaksi terkait
        $this->updateTransactionStatus($instalment->transaction);

        return $instalment;
    }

    private function updateTransactionStatus($transaction)
    {
        $totalInstalments = $transaction->instalments()->count();
        $paidInstalments = $transaction->instalments()->whereNotNull('paid_at')->count();

        if ($paidInstalments === 0) {
            $transaction->update(['status' => 'unpaid']);
        } elseif ($paidInstalments < $totalInstalments) {
            $transaction->update(['status' => 'partial']);
        } else {
            $transaction->update(['status' => 'paid']);
        }

        return $transaction;
    }

    public function index(Request $request)
    {
        $query = Payment::with(['user', 'instalment']);

        // Filter by search
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('invoice_number', 'like', '%' . $request->search . '%')
                ->orWhere('payment_method', 'like', '%' . $request->search . '%');
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by created_at range
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        // Paginate the results
        $payments = $query->paginate(10);

        return view('payment.index', compact('payments'));
    }
}
