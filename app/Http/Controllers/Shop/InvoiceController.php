<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Order;
use App\Services\InvoicePdfService;
use Illuminate\Http\Response;
use Illuminate\View\View;

class InvoiceController extends Controller
{
    public function show(Order $order): View
    {
        abort_unless($order->user_id === auth()->id(), 403);

        $invoice = Invoice::where('order_id', $order->id)->first();
        abort_if(! $invoice, 404, 'No invoice issued yet.');

        return view('invoices.show', compact('invoice', 'order'));
    }

    public function downloadPdf(Order $order, InvoicePdfService $pdfService): Response
    {
        abort_unless($order->user_id === auth()->id(), 403);

        $invoice = Invoice::where('order_id', $order->id)->first();
        abort_if(! $invoice, 404);

        $pdf = $pdfService->generate($invoice);

        return response($pdf, 200, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $invoice->invoice_number . '.pdf"',
        ]);
    }
}