<?php

namespace App\Services;

use Cloudinary\Cloudinary;

class CloudinaryService
{
    protected $cloudinary;

    public function __construct()
    {
        $this->cloudinary = new Cloudinary([
            'cloud' => [
                'cloud_name' => 'dbort384t',
                'api_key'    => '791992577461963',
                'api_secret' => 'LHkRMfHCNOPZeXETuZx7eyjRaxY'
            ],
        ]);
    }

    public function upload($file, $folder = 'laravel')
    {
        // Subida correcta usando uploadApi()
        $result = $this->cloudinary->uploadApi()->upload(
            $file->getRealPath(),
            [
                'folder' => $folder
            ]
        );

        return $result['secure_url']; // URL pública
    }
}
