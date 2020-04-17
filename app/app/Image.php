<?php

namespace App;

use Auth, InterventionImage, Storage;
use App\Exceptions\UploadException;
use Illuminate\Http\UploadedFile;
use App\Traits\{OwnershipMethods, TablePaginate};
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use OwnershipMethods, TablePaginate;

    protected $fillable = ['user_id', 'title', 'path', 'description', 'exif'];

    /*
     |--------------------------------------------------------------------------
     | Accessors
     |--------------------------------------------------------------------------
     |
     */
    /**
     * Return the full S3 URL of the image
     *
     * @see fullPath() for retrieving the full S3 URL with image suffix
     * @return string
     */
    public function getUrlAttribute(): string
    {
        return sprintf('%s/%s', config('filesystems.disks.s3.url'), $this->path);
    }

    /*
     |--------------------------------------------------------------------------
     | Relationships
     |--------------------------------------------------------------------------
     |
     */


    /*
     |--------------------------------------------------------------------------
     | Methods
     |--------------------------------------------------------------------------
     |
     */
    /**
     * Add a suffix to a given image path (extension aware).
     *
     * @param string $path
     * @param string $suffix
     *
     * @return string
     */
    public function suffixPath($path, $suffix): string
    {
        return preg_replace(
            '/\.(jpg|jpeg|png|gif)$/i',
            sprintf('_%s$0', $suffix),
            $path
        );
    }

    /**
     * Return the full S3 path of the image (optionally alongside a suffix)
     *
     * @param string  suffix (optional) suffix to use for path evaluation
     *
     * @return string
     */
    public function fullPath($suffix = null): string
    {
        return sprintf(
            '%s/%s',
            config('filesystems.disks.s3.url'),
            $suffix === null ? $this->path : $this->suffixPath($this->path, $suffix)
        );
    }

    /**
     * Delete the resource from S3 bucket.
     *
     * @param string $path
     */
    protected function deleteS3Resource($path): void
    {
        Storage::disk('s3')->delete($path);
        Storage::disk('s3')->delete($this->suffixPath($path, 'preview'));
        Storage::disk('s3')->delete($this->suffixPath($path, 'thumbnail'));
    }

    /**
     * Upload a given image file to S3 storage and return the resulting Image model instance
     *
     * @param UploadedFile $requestImage     request file
     * @param array        $allowedMimeTypes list of mime types to allow for uploading
     *
     * @return \Illuminate\Database\Query\Builder|mixed
     * @throws \App\Exceptions\UploadException
     */
    public static function upload(
        UploadedFile $requestImage,
        array $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif']
    )
    {
        $model = new self();

        $mimeType = $requestImage->getMimeType();

        // Ensure given file type is allowed
        if (!in_array($mimeType, $allowedMimeTypes, true)) {
            throw new UploadException('File type unsupported!');
        }

        // Process image where applicable (reduce resolution to a maximum of 1200x1200 without cutting)
        switch ($mimeType) {
            case 'image/jpeg':
            case 'image/png':
            case 'image/gif':
                // 1. Generate full image
                $procImage = InterventionImage::make($requestImage)
                    ->resize(1200, null, static function ($constraint) {
                        // Maintain original aspect ratio
                        $constraint->aspectRatio();
                        // Prevent unwanted upsizing of the image
                        $constraint->upsize();
                    })
                    ->encode();

                $path = $requestImage->hashName('images');
                Storage::disk('s3')->put($path, $procImage->getEncoded(), 'public');

                // 2. Generate thumbnail image
                $procImage = InterventionImage::make($requestImage)
                    ->fit(150, 150)
                    ->encode();

                Storage::disk('s3')->put(
                    $model->suffixPath($path, 'thumbnail'),
                    $procImage->getEncoded(),
                    'public'
                );

                // 3. Generate preview image
                $procImage = InterventionImage::make($requestImage)
                    ->fit(350, 150)
                    ->encode();

                Storage::disk('s3')->put(
                    $model->suffixPath($path, 'preview'),
                    $procImage->getEncoded(),
                    'public'
                );

                break;
            default:
                $path = $requestImage->storePublicly('images', 's3');
        }

        $model->fill([
            'user_id'     => Auth::user()->id,
            'path'        => $path,
            'title'       => $requestImage->getClientOriginalName(),
            'description' => '',
        ]);

        if (!$model->save()) {
            $model->deleteS3Resource($path);
            throw new UploadException('The image could not be uploaded!');
        }

        return $model;
    }

    public function delete(): ?bool
    {
        $this->deleteS3Resource($this->path);

        return parent::delete();
    }
}
