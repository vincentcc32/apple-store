<?php

namespace App\Jobs;

use App\Mail\checkoutEmail as MailCheckoutEmail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class checkoutEmail implements ShouldQueue
{
    use Queueable;
    private $user;
    private $order;
    /**
     * Create a new job instance.
     */
    public function __construct($user, $order)
    {
        //
        $this->user = $user;
        $this->order = $order;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //
        Mail::to($this->user->email)->send(new MailCheckoutEmail($this->order));
    }
}
