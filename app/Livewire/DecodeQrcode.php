<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Zxing\QrReader;

class DecodeQrcode extends Component
{
    use WithFileUploads;

    public $photo;
    public $qrCodeData;

    public function decodeQRCode()
    {
        // $this->validate([
        //     'photo' => 'required|image|max:2048', // 2MB Max
        // ]);

        // $tempPath = $this->photo->store('public/temp', 'public');

        // $randomFilename = Str::random(40);

        // $extension = $this->photo->extension();

        // $newPath = 'images/' . $randomFilename . '.' . $extension;

        // Storage::disk('public')->move($tempPath, $newPath);

        // Decode the QR code
        $qrcode = new QrReader('file:///C:/Developer/livewire/vida/storage/app/public/images/qr_vida_file.png');
        $this->qrCodeData = $qrcode->text();

        // Delete the temporary file
        // Storage::disk('public')->delete($tempPath);
    }

    public function render()
    {
        return view('livewire.decode-qrcode');
    }
}
