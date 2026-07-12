<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    /**
     * Send a simulated WhatsApp message (Logs to file).
     */
    public function sendMessage(string $phone, string $message): bool
    {
        // Here you would normally integrate with Fonnte, Watzap, or Twilio API
        // Example for Fonnte:
        // Http::withHeaders(['Authorization' => config('services.fonnte.token')])
        //     ->post('https://api.fonnte.com/send', [
        //         'target' => $phone,
        //         'message' => $message,
        //     ]);
        
        Log::channel('single')->info("WhatsApp Message Sent to {$phone}: \n{$message}");
        
        return true;
    }
}
