<?php

namespace App\Services;

use App\Models\Invoice;
use Mpdf\Mpdf;
use Mpdf\Output\Destination;

class InvoicePdfService
{
    /**
     * Render an invoice as PDF bytes (mPDF, Arabic-shaped, RTL).
     */
    public function generate(Invoice $invoice): string
    {
        $html = view('invoices.pdf', ['invoice' => $invoice])->render();

        $mpdf = new Mpdf([
            'mode'             => 'utf-8',
            'format'           => 'A4',
            'orientation'      => 'P',
            'default_font'     => 'dejavusans',
            'autoScriptToLang' => true,
            'autoLangToFont'   => true,
            'tempDir'          => storage_path('app/mpdf'),
        ]);

        $mpdf->SetDirectionality('rtl');
        $mpdf->WriteHTML($html);

        return $mpdf->Output('', Destination::STRING_RETURN);
    }
}