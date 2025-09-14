<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\PayoutCalculationService;
use App\Models\SystemSetting;
use Illuminate\Support\Facades\Log;

class GeneratePayouts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payouts:generate 
                            {--type=auto : Type of payout generation (auto, weekly, monthly)}
                            {--start-date= : Start date for custom period (Y-m-d format)}
                            {--end-date= : End date for custom period (Y-m-d format)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate payouts for riders and vendors based on completed online orders';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $type = $this->option('type');
        $startDate = $this->option('start-date');
        $endDate = $this->option('end-date');

        // Check if auto generation is enabled
        if ($type === 'auto') {
            $autoGenerationEnabled = SystemSetting::where('setting_key', 'payout_auto_generation_enabled')
                ->first()?->setting_value === 'true';

            if (!$autoGenerationEnabled) {
                $this->info('Automatic payout generation is disabled in system settings.');
                return Command::SUCCESS;
            }

            // Get the schedule type from settings
            $scheduleType = SystemSetting::where('setting_key', 'payout_schedule_type')
                ->first()?->setting_value ?? 'weekly';

            $type = $scheduleType;
        }

        $this->info("Starting payout generation ({$type})...");

        try {
            $payoutService = new PayoutCalculationService();
            $result = null;

            switch ($type) {
                case 'weekly':
                    $result = $payoutService->generateWeeklyPayouts();
                    break;

                case 'monthly':
                    $result = $payoutService->generateMonthlyPayouts();
                    break;

                case 'custom':
                    if (!$startDate || !$endDate) {
                        $this->error('Start date and end date are required for custom payout generation.');
                        return Command::FAILURE;
                    }

                    $start = \Carbon\Carbon::parse($startDate);
                    $end = \Carbon\Carbon::parse($endDate);
                    $result = $payoutService->calculatePayoutsForPeriod($start, $end);
                    break;

                default:
                    $this->error("Invalid payout type: {$type}. Use 'weekly', 'monthly', or 'custom'.");
                    return Command::FAILURE;
            }

            if ($result && $result['success']) {
                $this->info('Payout generation completed successfully!');
                $this->table(
                    ['Metric', 'Count'],
                    [
                        ['Rider payouts created', count($result['rider_payouts'])],
                        ['Vendor payouts created', count($result['vendor_payouts'])],
                        ['Orders processed', $result['orders_processed']],
                        ['Period start', $result['period']['start']],
                        ['Period end', $result['period']['end']],
                    ]
                );

                // Log the successful generation
                Log::info('Payouts generated successfully', [
                    'type' => $type,
                    'rider_payouts' => count($result['rider_payouts']),
                    'vendor_payouts' => count($result['vendor_payouts']),
                    'orders_processed' => $result['orders_processed'],
                    'period' => $result['period']
                ]);

                return Command::SUCCESS;
            } else {
                $this->error('Payout generation failed: ' . ($result['message'] ?? 'Unknown error'));
                return Command::FAILURE;
            }

        } catch (\Exception $e) {
            $this->error('Error during payout generation: ' . $e->getMessage());
            Log::error('Payout generation command failed', [
                'type' => $type,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return Command::FAILURE;
        }
    }
}
