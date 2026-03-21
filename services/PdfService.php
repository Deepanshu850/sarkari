<?php

namespace App\Services;

use Dompdf\Dompdf;
use Dompdf\Options;

class PdfService {
    public function generateBlueprintPdf(array $blueprint, array $days, array $user): string {
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', false);
        $options->set('defaultFont', 'sans-serif');

        $dompdf = new Dompdf($options);

        ob_start();
        $data = compact('blueprint', 'days', 'user');
        extract($data);
        require __DIR__ . '/../views/pdf/blueprint-template.php';
        $html = ob_get_clean();

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $filename = 'blueprint_' . $blueprint['id'] . '_' . time() . '.pdf';
        $filepath = STORAGE_PATH . '/pdfs/' . $filename;
        file_put_contents($filepath, $dompdf->output());

        return 'storage/pdfs/' . $filename;
    }
}
