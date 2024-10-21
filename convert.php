<!-- convert.php -->
<?php
// Check if file was uploaded
if (isset($_FILES['file']) && isset($_POST['conversion'])) {
    $uploadDir = 'uploads/';
    $convertedDir = 'converted/';
    $fileName = basename($_FILES['file']['name']);
    $targetFile = $uploadDir . $fileName;
    $conversionType = $_POST['conversion'];
    $convertedFileName = '';

    // Move the uploaded file to the 'uploads' directory
    if (move_uploaded_file($_FILES['file']['tmp_name'], $targetFile)) {
        switch ($conversionType) {
            case 'image-to-png':
                $convertedFileName = convertImageToPng($targetFile, $convertedDir);
                break;
            case 'image-to-jpeg':
                $convertedFileName = convertImageToJpeg($targetFile, $convertedDir);
                break;
            case 'docx-to-pdf':
                $convertedFileName = convertDocxToPdf($targetFile, $convertedDir);
                break;
            case 'txt-to-pdf':
                $convertedFileName = convertTxtToPdf($targetFile, $convertedDir);
                break;
            default:
                echo "Unsupported conversion type.";
                exit;
        }

        // Redirect back to the index page with the converted file name
        header("Location: index.php?file=" . urlencode($convertedFileName));
        exit;
    } else {
        echo "Failed to upload file.";
    }
}

// Function to convert an image to PNG
function convertImageToPng($filePath, $outputDir) {
    $image = imagecreatefromstring(file_get_contents($filePath));
    $newFileName = $outputDir . pathinfo($filePath, PATHINFO_FILENAME) . '.png';
    imagepng($image, $newFileName);
    imagedestroy($image);
    return basename($newFileName);
}

// Function to convert an image to JPEG
function convertImageToJpeg($filePath, $outputDir) {
    $image = imagecreatefromstring(file_get_contents($filePath));
    $newFileName = $outputDir . pathinfo($filePath, PATHINFO_FILENAME) . '.jpeg';
    imagejpeg($image, $newFileName);
    imagedestroy($image);
    return basename($newFileName);
}

// Function to convert DOCX to PDF using PhpWord library
function convertDocxToPdf($filePath, $outputDir) {
    require 'vendor/autoload.php';
    
    $phpWord = \PhpOffice\PhpWord\IOFactory::load($filePath);

    // Set DomPDF as the PDF rendering engine
    \PhpOffice\PhpWord\Settings::setPdfRendererName(\PhpOffice\PhpWord\Settings::PDF_RENDERER_DOMPDF);
    \PhpOffice\PhpWord\Settings::setPdfRendererPath('vendor/dompdf/dompdf');

    // Define the output file name
    $newFileName = $outputDir . pathinfo($filePath, PATHINFO_FILENAME) . '.pdf';

    // Create a PDF writer and save the file
    $pdfWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'PDF');
    $pdfWriter->save($newFileName);

    return basename($newFileName);
}

// Function to convert TXT to PDF
function convertTxtToPdf($filePath, $outputDir) {
    // Include the autoloader
    require 'vendor/autoload.php'; // Include the FPDF library

    // Define the output file name
    $newFileName = $outputDir . pathinfo($filePath, PATHINFO_FILENAME) . '.pdf';

    // Read the text file
    $text = file_get_contents($filePath);

    // Create a new PDF instance (using only FPDF)
    $pdf = new \FPDF(); // Correct instantiation for FPDF

    // Add a page to the PDF
    $pdf->AddPage();
    $pdf->SetFont('Arial', '', 12); // Use Arial instead of Helvetica

    // Write the text to the PDF
    $pdf->MultiCell(0, 10, $text);

    // Save the PDF file
    $pdf->Output('F', $newFileName); // Save as file

    return basename($newFileName);
}
?>
