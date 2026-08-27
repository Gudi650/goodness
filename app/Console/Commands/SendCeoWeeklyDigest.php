<?php

namespace App\Console\Commands;

use App\Mail\CeoWeeklyDigest;
use App\Services\CeoWeeklyDigestService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendCeoWeeklyDigest extends Command
{
    protected $signature = 'ceo:send-weekly-digest';

    protected $description = 'Email the CEO the weekly briefing';

    public function handle(CeoWeeklyDigestService $digestService): int
    {
        $ceos = $digestService->recipients();

        if ($ceos->isEmpty()) {
            $this->warn('No CEO user with an email address.');

            return self::FAILURE;
        }

        $digest = $digestService->build();
        $pdfs = $digestService->pdfs();

        foreach ($ceos as $ceo) {
            //for testing purposes, send to myself
            Mail::to('godluckmsangi3@gmail.com')->send(new CeoWeeklyDigest($digest, $pdfs));
            $this->info("Sent to godluckmsangi3@gmail.com");

            //Mail::to($ceo->email)->send(new CeoWeeklyDigest($digest, $pdfs));
            //$this->info("Sent to {$ceo->email}");
        }

        return self::SUCCESS;
    }
}
