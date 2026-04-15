<?php

namespace App\Livewire\Hemodialysis;

use App\Services\HemoServices;
use Livewire\Component;
use Livewire\WithFileUploads;
use Zxing\QrReader;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Imagick\Driver;

class HemoUploadFileModal extends Component
{
    use WithFileUploads;

    public $showModal = false;
    public $images = [];
    public $qrCodeData = [];
    public $qrCodeNotReadData = [];
    public $uploadProgress = 0;  // New property for upload progress

    private $hemoServices;

    public function boot(HemoServices $hemoServices)
    {
        $this->hemoServices = $hemoServices;
    }
    public function openModal()
    {
        $this->qrCodeNotReadData = [];
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
    }
    private function ImageCropPlaceLevel1($crop_path): string
    {
        // $manager0 = new ImageManager(new Driver());
        // $img0 = $manager0->read($absolutePath);  // get actual image
        // $img0->crop(400, 200, 0, 0); // crop image
        // $cropPath = 'ex_crop_' . basename($path);
        // $crop_path  = public_path('storage/images/qrcode/' . $cropPath);
        // $img0->save($crop_path);

        $manager2 = new ImageManager(new Driver());
        $img2 = $manager2->read($crop_path);  // get actual image
        $img2->crop(600, 600, 600, 0); // crop image
        $img2->place($crop_path, 'top', 0, 200, 100);

        $topPath = 'ex_top_' . basename($crop_path);
        $top_path  = public_path('storage/images/qrcode/' . $topPath);
        $img2->save($top_path);
        $qrcode = new QrReader($top_path); // reading qr-code
        return (string) $qrcode->text() ?? '';
    }

    private function ImageCropPlaceLevel2(string $crop_path): string
    {

        $manager2 = new ImageManager(new Driver());
        $img2 = $manager2->read($crop_path);  // get actual image
        $img2->crop(800, 800, 800, 0); // crop image
        $img2->place($crop_path, 'top', 0, 300, 100);

        $topPath = 'ex_a_top_' . basename($crop_path);
        $top_path  = public_path('storage/images/qrcode/' . $topPath);
        $img2->save($top_path);
        $qrcode = new QrReader($top_path); // reading qr-code
        return (string) $qrcode->text() ?? '';
    }
    private function ImageCropPlaceLevel3(string $crop_path): string
    {

        $manager2 = new ImageManager(new Driver());
        $img2 = $manager2->read($crop_path);  // get actual image
        $img2->crop(1000, 1000, 1000, 0); // crop image
        $img2->place($crop_path, 'top', 0, 300, 100);

        $topPath = 'ex_b_top_' . basename($crop_path);
        $top_path  = public_path('storage/images/qrcode/' . $topPath);
        $img2->save($top_path);
        $qrcode = new QrReader($top_path); // reading qr-code
        return (string) $qrcode->text() ?? '';
    }

    private function ImageCropPlaceLevel4(string $crop_path): string
    {

        $manager2 = new ImageManager(new Driver());
        $img2 = $manager2->read($crop_path);  // get actual image
        $img2->crop(200, 200, 300, 0); // crop image
        $img2->place($crop_path, 'top', 0, 0, 100);

        $topPath = 'ex_c_top_' . basename($crop_path);
        $top_path  = public_path('storage/images/qrcode/' . $topPath);
        $img2->save($top_path);
        $qrcode = new QrReader($top_path); // reading qr-code
        return (string) $qrcode->text() ?? '';
    }
    private function ImageCrop(string $absolutePath, $path, int $width, int $height, string $prefix): string
    {
        $manager0 = new ImageManager(new Driver());
        $img0 = $manager0->read($absolutePath);  // get actual image
        $img0->crop($width, $height, 0, 0); // crop image
        $cropPath0 = $prefix . '_' . basename($path);
        $crop_path0  = public_path('storage/images/qrcode/' . $cropPath0);
        $img0->save($crop_path0);
        return $crop_path0;
    }
    public function uploadImages()
    {
        $this->qrCodeNotReadData = [];
        $this->validate([
            'images.*' => 'image|max:1024', // 1MB Max per image
        ]);

        foreach ($this->images as $list) {
            $path = $list->store('images', 'custom_local');
            $absolutePath = (string) public_path('storage/' . $path);

            $crop_path = $this->ImageCrop($absolutePath, $path, 400, 200, 'crop_');
            $qrcode = new QrReader($crop_path); // reading qr-code
            $codeGenerate  = $qrcode->text() ?? '';

            if ($codeGenerate == '') {
                // level 1 
                $crop_path_1 = $this->ImageCrop($absolutePath, $path, 400, 300, 'crop1_');
                $qrcode = new QrReader($crop_path_1); // reading qr-code
                $codeGenerate  = $qrcode->text() ?? '';
            }

            if ($codeGenerate == '') {
                // level 1 -1
                $crop_path_2 = $this->ImageCrop($absolutePath, $path, 400, 400, 'crop2_');
                $qrcode = new QrReader($crop_path_2); // reading qr-code
                $codeGenerate  = $qrcode->text() ?? '';
            }



            if ($codeGenerate == '') {
                // level 1
                $codeGenerate = $this->ImageCropPlaceLevel1($crop_path);
            }

            if ($codeGenerate == '') {
                // level 2
                $codeGenerate = $this->ImageCropPlaceLevel2($crop_path);
            }

            if ($codeGenerate == '') {
                // level 3
                $codeGenerate = $this->ImageCropPlaceLevel3($crop_path);
            }

            if ($codeGenerate == '') {
                // level 4
                $codeGenerate = $this->ImageCropPlaceLevel4($crop_path_1);
            }

            // save in qrcode folder
            if ($codeGenerate) {
                $this->qrCodeData[] = [
                    'code' => $codeGenerate,
                    'filename' => basename($path),
                    'filepath' =>  $path
                ];
            }
        }

        // Reading
        $gotReadDoc = false;

        foreach ($this->qrCodeData as $list) {
            $gotReadDoc = true;
            $gotInsert =  $this->hemoServices->UpdateQRFile($list['code'], $list['filename'], $list['filepath']);
            if ($gotInsert) {
                $this->qrCodeNotReadData[] = [
                    'code' =>  $list['code'],
                    'status' => true
                ];
            } else {
                $this->qrCodeNotReadData[] = [
                    'code' =>  $list['code'],
                    'status' => false
                ];
            }
        }

        if ($gotReadDoc == false) {
            $this->qrCodeNotReadData[] = [
                'code' => 'No file',
                'status' => false
            ];
        }

        $this->reset(['qrCodeData', 'images']);
        $this->closeModal();
        $this->dispatch('refresh-list');
    }

    public function render()
    {
        return view('livewire.hemodialysis.hemo-upload-file-modal');
    }
}
