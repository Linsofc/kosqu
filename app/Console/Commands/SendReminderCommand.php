<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Setting;
use App\Models\Penghuni;
use App\Services\FonnteService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class SendReminderCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reminder:send-daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send automated daily WhatsApp reminders for upcoming or overdue payments';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting automated reminders check...');

        $enabled = Setting::get('wa_reminder_enabled');
        if ($enabled !== 'true') {
            $this->warn('Automated reminders are disabled in settings. Exiting.');
            return;
        }

        $daysBefore = (int) Setting::get('wa_reminder_days_before', 7);
        $this->info("Reminder rule: Send H-{$daysBefore}");

        $today = Carbon::today();
        
        // For resiliency, instead of matching exact days, we check if it falls within the window 
        // and hasn't been sent yet for this cycle.
        
        $penghunis = Penghuni::where('status', 'Aktif')->whereNotNull('tgl_jatuh_tempo')->get();
        $fonnte = new FonnteService();
        $count = 0;

        foreach ($penghunis as $p) {
            $tempo = Carbon::parse($p->tgl_jatuh_tempo)->startOfDay();
            $diffInDays = (int) $today->diffInDays($tempo, false); // Negative if overdue

            $shouldSend = false;

            if ($diffInDays >= 0 && $diffInDays <= $daysBefore) {
                // --- MASA MENDATANG (Upcoming Window) ---
                if (!$p->last_reminder_sent_at) {
                    $shouldSend = true;
                } else {
                    $lastSent = Carbon::parse($p->last_reminder_sent_at)->startOfDay();
                    // Jika last sent lebih lama dari (tempo - 15 hari), itu berarti reminder untuk bulan lalu
                    if ($lastSent < $tempo->copy()->subDays($daysBefore + 5)) {
                        $shouldSend = true;
                    }
                }
            } elseif ($diffInDays < 0) {
                // --- MASA TERLAMBAT (Overdue Window) ---
                if (!$p->last_reminder_sent_at) {
                    $shouldSend = true;
                } else {
                    $lastSent = Carbon::parse($p->last_reminder_sent_at)->startOfDay();
                    // Belum pernah dikirim semenjak masuk masa overdue
                    if ($lastSent < $tempo) {
                        $shouldSend = true;
                    } 
                    // Atau, sudah dikirim tapi ulangi setiap 3 hari agar terus diingatkan
                    elseif ($lastSent <= $today->copy()->subDays(3)) {
                        $shouldSend = true;
                    }
                }
            }

            if ($shouldSend) {
                if ($count > 0) {
                    $this->info("Sleeping for 30 seconds to prevent spam detection...");
                    sleep(30);
                }
                $this->info("Sending message to {$p->nama} (H {$diffInDays})...");
                $result = $fonnte->sendReminder($p);
                
                if ($result['success']) {
                    $this->info('Success.');
                    $p->update(['last_reminder_sent_at' => $today->toDateString()]);
                } else {
                    $this->error('Failed: ' . $result['message']);
                }
                $count++;
            }
        }

        $this->info("Finished sending {$count} reminders.");
        Log::info("Automated reminders completed. Sent: {$count}");
    }
}
