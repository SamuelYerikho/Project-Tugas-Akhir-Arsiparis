<?php

namespace App\Services;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class OcrService
{
    /**
     * Cek apakah FastAPI hidup.
     */
    public function health(): bool
    {
        try {
            return Http::timeout(5)
                ->get(config('services.ocr.url') . '/health')
                ->json('status') === 'ok';
        } catch (ConnectionException) {
            return false;
        }
    }

    /**
     * Kirim satu file ke FastAPI dan kembalikan hasil OCR.
     *
     * Kode exception = status HTTP kalau FastAPI menolak (415, 413, 422, ...).
     * Kode 0 = gangguan sementara (FastAPI mati, timeout, 5xx).
     *
     * @return array{filename:string,seconds:float,n_pages:int,text:string,pages:array}
     */
    public function extract(string $path, string $filename): array
    {
        if (! is_file($path)) {
            throw new RuntimeException("File tidak ditemukan: {$path}", 404);
        }

        $handle = fopen($path, 'r');

        try {
            $response = Http::timeout((int) config('services.ocr.timeout'))
                ->connectTimeout(10)
                ->attach('file', $handle, $filename)
                ->post(config('services.ocr.url') . '/ocr');
        } catch (ConnectionException $e) {
            throw new RuntimeException('Layanan OCR tidak dapat dihubungi: ' . $e->getMessage(), 0, $e);
        } finally {
            if (is_resource($handle)) {
                fclose($handle);
            }
        }

        if ($response->failed()) {
            $detail = $response->json('detail') ?? $response->body();
            if (is_array($detail)) {
                $detail = json_encode($detail);
            }

            // 4xx = file bermasalah (jangan dicoba ulang), 5xx = anggap sementara
            $code = $response->clientError() ? $response->status() : 0;

            throw new RuntimeException("OCR gagal ({$response->status()}): {$detail}", $code);
        }

        return $response->json();
    }
}