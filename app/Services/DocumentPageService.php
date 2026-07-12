<?php

namespace App\Services;

class DocumentPageService
{
    /**
     * Get the number of pages in a document.
     * Supports PDF, DOCX, PPTX. Falls back to 1 for images/others.
     */
    public function getPageCount(string $filePath, string $mimeType): int
    {
        if (!file_exists($filePath)) {
            return 0;
        }

        try {
            if ($mimeType === 'application/pdf') {
                return $this->getPdfPageCount($filePath);
            }

            if (in_array($mimeType, [
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'application/vnd.openxmlformats-officedocument.presentationml.presentation'
            ])) {
                return $this->getOfficePageCount($filePath, $mimeType);
            }
        } catch (\Exception $e) {
            // Log error or ignore and fall back to 1 page
            \Illuminate\Support\Facades\Log::error("Failed to parse page count for {$filePath}: " . $e->getMessage());
        }

        return 1;
    }

    private function getPdfPageCount(string $path): int
    {
        $fp = @fopen($path, 'rb');
        if (!$fp) {
            return 1;
        }

        $max = 0;
        while (!feof($fp)) {
            $line = fgets($fp, 1024);
            if (preg_match('/\/Count\s+(\d+)/', $line, $matches)) {
                $max = max($max, (int) $matches[1]);
            }
        }
        fclose($fp);

        if ($max > 0) {
            return $max;
        }

        // Fallback: search count in the entire file content
        $content = @file_get_contents($path);
        if ($content && preg_match_all('/\/Count\s+(\d+)/', $content, $matches)) {
            return (int) max($matches[1]);
        }

        return 1;
    }

    private function getOfficePageCount(string $path, string $mime): int
    {
        $zip = new \ZipArchive();
        if ($zip->open($path) === true) {
            if (($index = $zip->locateName('docProps/app.xml')) !== false) {
                $data = $zip->getFromIndex($index);
                $zip->close();

                $xml = @simplexml_load_string($data);
                if ($xml) {
                    if ($mime === 'application/vnd.openxmlformats-officedocument.wordprocessingml.document') {
                        if (isset($xml->Pages)) {
                            return (int) $xml->Pages;
                        }
                    } else {
                        if (isset($xml->Slides)) {
                            return (int) $xml->Slides;
                        }
                    }
                }
            }
            $zip->close();
        }

        return 1;
    }
}
