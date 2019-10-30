<?php

namespace Coderello\FaviconGenerator;

use GuzzleHttp\Client;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use LogicException;
use Spatie\TemporaryDirectory\TemporaryDirectory;
use ZipArchive;

class FaviconManipulator implements Renderable
{
    const API_KEY = '87d5cd739b05c00416c4a19cd14a8bb5632ea563';

    protected $client;

    protected $http;

    public $config;

    public function __construct(RealFaviconGeneratorClient $client, Client $http, array $config = [])
    {
        $this->client = $client;

        $this->http = $http;

        $this->config = $config;
    }

    protected function disk()
    {
        return Storage::disk($this->config('disk_name'));
    }

    protected function iconsPath()
    {
        return trim($this->config('icons_path', 'icons'), '/');
    }
    
    protected function config($key, $default = null)
    {
        return Arr::get($this->config, $key, $default);
    }

    public function drop()
    {
        File::deleteDirectory(
            $this->disk()->path($this->iconsPath())
        );

        $this->disk()->createDir($this->iconsPath());

        return $this;
    }

    protected function getPayload(string $base64image, array $payload = [])
    {
        return array_merge_recursive([
            'favicon_generation' => array_merge_recursive(
                [
                    'api_key' => self::API_KEY,
                ],
                $this->config('favicon_generation', []), [
                    'master_picture' => [
                        'type' => 'inline',
                        'content' => $base64image,
                    ],
                    'files_location' => [
                        'type' => 'path',
                        'path' => $this->disk()->url($this->iconsPath()),
                    ],
                    'versioning' => [
                        'param_name' => 'v',
                        'param_value' => md5(time()),
                    ],
                    'settings' => [
                        'error_on_image_too_small' => true,
                        'readme_file' => false,
                        'html_code_file' => true,
                        'use_path_as_is' => false,
                    ],
                ]
            ),
        ], $payload);
    }

    protected function getPackageUrl(array $response)
    {
        return Arr::get($response, 'favicon_generation_result.favicon.package_url');
    }

    protected function downloadAndImportPackage(string $packageUrl)
    {
        $packageContent = $this->http
            ->get($packageUrl)
            ->getBody()
            ->getContents();

        $directory = (new TemporaryDirectory)->create();

        $archivePath = $directory->path('icons.zip');

        File::put($archivePath, $packageContent);

        $zip = new ZipArchive;

        $zip->open($archivePath);

        $this->drop();

        $zip->extractTo($this->disk()->path($this->iconsPath()));

        $zip->close();

        $directory->delete();

        return $this;
    }

    protected function imageToBase64($image)
    {
        if ($image instanceof \Symfony\Component\HttpFoundation\File\File) {
            return base64_encode(file_get_contents($image->getPath()));
        } elseif (filter_var($image, FILTER_VALIDATE_URL) !== false) {
            return base64_encode(file_get_contents($image));
        } elseif (is_string($image) && Str::startsWith($image, '/') && is_file($image)) {
            return base64_encode(file_get_contents($image));
        } elseif (is_string($image) && ! Str::startsWith($image, '/') && is_file(storage_path('../'.$image))) {
            return base64_encode(file_get_contents(storage_path('../'.$image)));
        } elseif (is_string($image) && base64_decode($image, true) !== false) {
            return $image;
        }

        throw new \InvalidArgumentException('An image should be a valid non-empty value.');
    }

    public function generate($sourceImage = null, array $payload = [])
    {
        $base64image = $this->imageToBase64(
            $sourceImage ?? $this->config('master_picture')
        );

        $response = $this->client->favicon(
            $this->getPayload($base64image, $payload)
        );

        if (Arr::get($response, 'favicon_generation_result.result.status') === 'error') {
            throw new \RuntimeException(
                Arr::get($response, 'favicon_generation_result.result.error_message')
            );
        }

        $this->downloadAndImportPackage(
            $this->getPackageUrl($response)
        );

        return $this;
    }

    public function render()
    {
        try {
            return $this->disk()->get($this->iconsPath().'/html_code.html');
        } catch (FileNotFoundException $e) {
            throw new LogicException('Favicon is not generated yet.');
        }
    }
}
