<?php

namespace App\Console\Commands;

use App\LineBot\LineBotApi;
use App\Models\Assignment;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use LINE\LINEBot\MessageBuilder\TextMessageBuilder;

class DeadlineReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'deadline:reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(LineBotApi $bot)
    {
        $assignments = Assignment::where('isReminded', false)->get();
        $now = Carbon::now();
        foreach ($assignments as $assignment) {
            if ($now->addHours(5)->gte($assignment->deadline)) {
                try {
                    $assignment->isReminded = true;
                    $assignment->save();
                    $bot->pushMultipleMessage(
                        env('GROUP_ID'),
                        new TextMessageBuilder(
                            'Deadline tugas ' . $assignment->title . ' bentar lagi lhooo (~‾▿‾)~',
                            'Jangan lupa dikumpulin jam ' . Carbon::parse($assignment->deadline)->isoFormat('H:mm') . ' (｢`･ω･)｢'
                        )
                    );
                    // $this->info('Push reminder successfully send');
                } catch (\Exception $e) {
                    Log::info($e);
                }
            }
        }
        return 0;
    }
}
