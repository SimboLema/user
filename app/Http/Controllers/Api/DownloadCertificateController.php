<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DownloadCertificateController extends Controller
{
    public function downloadAll()
    {
        try {
            $domains = [
                'pay.suretech.co.tz',
                'suretech.co.tz'
            ];

            $files = [
                'cert.pem',
                'chain.pem',
                'fullchain.pem',
                'privkey.pem',
            ];

            $zipFileName = 'all-certificates.zip';
            $zipPath = storage_path('app/' . $zipFileName);

            if (file_exists($zipPath)) {
                @unlink($zipPath);
            }

            $zip = new \ZipArchive;
            if ($zip->open($zipPath, \ZipArchive::CREATE) !== TRUE) {
                throw new \Exception("Unable to create ZIP at: {$zipPath}");
            }

            $filesAdded = 0;

            foreach ($domains as $domain) {
                foreach ($files as $file) {
                    $path = "/etc/letsencrypt/live/{$domain}/{$file}";
                    if (file_exists($path)) {
                        $zip->addFile($path, "{$domain}/{$file}");
                        $filesAdded++;
                    }
                }
            }

            $zip->close();

            if (!file_exists($zipPath)) {
                throw new \Exception("ZIP file was not created at: {$zipPath}");
            }

            if ($filesAdded === 0) {
                throw new \Exception("No certificate files were added to the ZIP.");
            }

            return response()->download($zipPath)->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            Log::error("Certificate ZIP error: " . $e->getMessage());

            return response()->json([
                "status" => "error",
                "message" => "Something went wrong while creating the ZIP.",
                "details" => $e->getMessage()
            ], 500);
        }
    }
}
