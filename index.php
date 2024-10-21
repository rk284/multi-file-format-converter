<!-- index.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Multifile Convert Project</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h2>File Conversion Tool</h2>
        <form action="convert.php" method="POST" enctype="multipart/form-data">
            <label for="file">Choose a file to upload:</label>
            <input type="file" name="file" id="file" required>
            
            <label for="conversion">Select Conversion Type:</label>
            <select name="conversion" id="conversion" required>
                <option value="image-to-png">Convert Image to PNG</option>
                <option value="image-to-jpeg">Convert Image to JPEG</option>
                <option value="docx-to-pdf">Convert DOCX to PDF</option>
                <option value="txt-to-pdf">Convert TXT to PDF</option>
            </select>

            <button type="submit">Convert</button>
        </form>

        <?php
        if (isset($_GET['file'])) {
            $convertedFile = htmlspecialchars($_GET['file']);
            echo "<p>Conversion successful! <a href='converted/$convertedFile' download>Download the converted file</a></p>";
        }
        ?>
    </div>
</body>
</html>
