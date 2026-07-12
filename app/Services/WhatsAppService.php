<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    /**
     * Send a WhatsApp message using Fonnte API.
     */
    public function sendMessage(string $phone, string $message): bool
    {
        try {
            $response = \Illuminate\Support\Facades\Http::withHeaders([
                'Authorization' => config('services.fonnte.token')
            ])->post('https://api.fonnte.com/send', [
                'target' => $phone,
                'message' => $message,
            ]);

            if ($response->successful()) {
                Log::channel('single')->info("WhatsApp Message Sent to {$phone} via Fonnte.");
                return true;
            }

            Log::channel('single')->error("Failed to send WhatsApp message to {$phone}. Error: " . $response->body());
            return false;
        } catch (\Exception $e) {
            Log::channel('single')->error("Exception when sending WhatsApp message to {$phone}. Error: " . $e->getMessage());
            return false;
        }
    }
}
