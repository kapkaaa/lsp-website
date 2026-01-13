<?php

namespace App\Http\Controllers\Cashier;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\ProductDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class POSController extends Controller
{
    public function index()
    {
        return view('cashier.pos.index');
    }

    public function searchProduct(Request $request)
    {
        $query = $request->get('query');
        
        $products = ProductDetail::with(['product.brand', 'product.type', 'size', 'color'])
            ->where('status', 'available')
            ->where('stock', '>', 0)
            ->where(function($q) use ($query) {
                $q->where('barcode', 'like', "%{$query}%")
                  ->orWhereHas('product', function($q2) use ($query) {
                      $q2->where('name', 'like', "%{$query}%");
                  });
            })
            ->limit(10)
            ->get();

        $results = $products->map(function($detail) {
            return [
                'id' => $detail->id,
                'barcode' => $detail->barcode,
                'name' => $detail->getFullName(),
                'price' => $detail->product->selling_price,
                'stock' => $detail->stock,
                'size' => $detail->size->name,
                'color' => $detail->color->name
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $results
        ]);
    }

    public function process(Request $request)
    {
        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_detail_id' => 'required|exists:product_details,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'payment_method' => 'required|in:cash,debit,credit,qris',
            'cash_received' => 'nullable|numeric|min:0'
        ]);

        try {
            DB::beginTransaction();

            // Calculate total
            $total = 0;
            foreach ($validated['items'] as $item) {
                $total += $item['quantity'] * $item['unit_price'];
            }

            // Create transaction
            $transaction = Transaction::create([
                'user_id' => auth()->id(),
                'transaction_code' => Transaction::generateTransactionCode(),
                'total' => $total,
                'payment_method' => $validated['payment_method'],
                'transaction_status' => 'completed',
                'cash_received' => $validated['cash_received'] ?? 0,
                'change_given' => 0
            ]);

            // Calculate change if cash payment
            if ($validated['payment_method'] === 'cash') {
                $transaction->calculateChange();
                $transaction->save();
            }

            // Create transaction details and update stock
            foreach ($validated['items'] as $item) {
                $productDetail = ProductDetail::findOrFail($item['product_detail_id']);

                // Check stock availability
                if ($productDetail->stock < $item['quantity']) {
                    throw new \Exception("Insufficient stock for {$productDetail->getFullName()}");
                }

                // Create transaction detail
                TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'product_detail_id' => $item['product_detail_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'subtotal' => $item['quantity'] * $item['unit_price']
                ]);

                // Decrease stock
                $productDetail->decreaseStock($item['quantity']);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Transaction completed successfully',
                'data' => [
                    'transaction_id' => $transaction->id,
                    'transaction_code' => $transaction->transaction_code,
                    'total' => $transaction->total,
                    'cash_received' => $transaction->cash_received,
                    'change_given' => $transaction->change_given
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function printReceipt($id)
    {
        $transaction = Transaction::with([
            'user',
            'transactionDetails.productDetail.product',
            'transactionDetails.productDetail.size',
            'transactionDetails.productDetail.color'
        ])->findOrFail($id);

        $pdf = Pdf::loadView('cashier.pos.receipt', compact('transaction'));
        
        return $pdf->stream('receipt-' . $transaction->transaction_code . '.pdf');
    }

    public function getTransactionHistory(Request $request)
    {
        $transactions = Transaction::with('transactionDetails')
            ->where('user_id', auth()->id())
            ->whereDate('created_at', today())
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $transactions
        ]);
    }
}