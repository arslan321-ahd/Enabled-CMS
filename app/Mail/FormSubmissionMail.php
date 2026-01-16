<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class FormSubmissionMail extends Mailable
{
    use Queueable, SerializesModels;
    public $form;
    public $fields;
    public $recipientType;
    public function __construct($form, $fields, $recipientType = 'admin')
    {

        $this->form = $form;
        $this->fields = $fields;
        $this->recipientType = $recipientType;
    }
    public function build()
    {
        return $this->subject(
            $this->recipientType === 'admin'
                ? 'New Form Submission: ' . $this->form->title
                : $this->form->title
        )
            ->view('emails.form_submission');
    }
}