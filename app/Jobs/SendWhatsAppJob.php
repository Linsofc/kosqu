<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SendWhatsAppJob implements ShouldQueue
{
    use Queueable;

    protected string $target;
    protected string $message;
    protected ?int $id_penghuni;

    /**
     * Create a new job instance.
     */
    public function __construct(string $target, string $message, ?int $id_penghuni = null)
    {
        $this->target = $target;
        $this->message = $message;
        $this->id_penghuni = $id_penghuni;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $token = \App\Models\Setting::get('fonnte_token');
        
        if (empty($token)) {
            return;
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => $token,
            ])->post('https://api.fonnte.com/send', [
                'target' => $this->target,
                'message' => $this->message,
                'countryCode' => '62',
            ]);

            $body = $response->json();
            $isSuccess = $response->successful() && isset($body['status']) && $body['status'] === true;

            \App\Models\WhatsappLog::create([
                'id_penghuni' => $this->id_penghuni,
                'no_hp' => $this->target,
                'pesan' => $this->message,
                'status' => $isSuccess ? 'Success' : 'Failed',
                'response_api' => json_encode($body),
            ]);
            
        } catch (\Exception $e) {
            Log::error('Fonnte API Job Error: ' . $e->getMessage());

            \App\Models\WhatsappLog::create([
                'id_penghuni' => $this->id_penghuni,
                'no_hp' => $this->target,
                'pesan' => $this->message,
                'status' => 'Failed',
                'response_api' => json_encode(['error' => $e->getMessage()]),
            ]);
        }
    }
}
