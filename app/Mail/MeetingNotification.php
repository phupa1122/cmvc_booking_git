<?php

namespace App\Mail;

use App\Models\Booking;
use App\Models\Participant;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MeetingNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $booking,$participant;
    

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Booking $booking,Participant $participant)
    {
        $this->booking = $booking;
        $this->participant = $participant;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = 'การแจ้งเตือนการประชุม';

        // ตรวจสอบว่ามีชื่อผู้รับหรือไม่
        $recipientName = $this->booking->user->name; // ดึงชื่อผู้รับจากผู้จอง

        return $this->from('booking.cmvc@gmail.com', 'Booking CMVC')
            ->subject($subject)
            ->view('emails.meeting-notification')
            ->with([
                'booking' => $this->booking,
                'recipientName' => $this->participant->user->name, // ส่งชื่อผู้รับไปที่ view
            ]);
    }
}
