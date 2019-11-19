<?php

namespace App\Listeners;

use App\Events\TestEvent;
use App\Mail\TestMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailer;
use Illuminate\Queue\InteractsWithQueue;

/**+
 * Class TestListener
 *
 * @package App\Listeners
 */
class TestListener implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * @var Mailer
     */
    protected $mailer;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * Handle the event.
     *
     * @param  TestEvent $event
     *
     * @return void
     */
    public function handle(TestEvent $event)
    {
        $mail = new TestMail();
        $mail->subject(sprintf('event creation time: %d / current time: %d', $event->currentTime, time()));

        $this->mailer->send($mail);
    }
}
