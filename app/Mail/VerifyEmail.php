<?php

namespace App\Mail;

use Crypt;
use App\User;
use App\Model\IdentitasMahasiswa;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class VerifyEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($mhs)
    {
        //
        $this->mhs = $mhs;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
         // generate link berdasarkan email
         $encryptedEmail = Crypt::encrypt($this->mhs->email);
         
         //untuk domain pkm.kemahasiswaan.uny.ac.id/verify?token=xxxx
         $link = route('signup.verify', ['token' => $encryptedEmail]);
         $nama = IdentitasMahasiswa::whereemail($this->mhs->email)->first()->nama;
         
         return $this->subject('Aktivasi Akun PKM')
             ->with('nama',$nama)
             ->with('link', $link)
             ->view('layout.mail.signup');
    }
}
