<?php

class ImageService
{
    private $allowedExtensions = ['jpeg', 'jpg', 'png', 'webp'];
    public $maxFileSize; // in bytes
    private $imageQuality; // WebP quality (0-100)

    public function __construct(
        $maxFileSize = 5242880, 
        $imageQuality = 80
    ) {
        $this->maxFileSize = $maxFileSize;
        $this->imageQuality = $imageQuality;
    }

    public function processImage($file, $width, $height, $targetPath)
    {
        $fileExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($fileExtension, $this->allowedExtensions)) {
            throw new \Exception("Invalid image format. Allowed formats are " . implode(', ', $this->allowedExtensions) . ".");
        }
        if ($file['size'] > $this->maxFileSize) {
            throw new \Exception("File size exceeds the allowed limit of " . ($this->maxFileSize / (1024 * 1024)) . " MB.");
        }

        $this->resizeAndConvertToWebP($file['tmp_name'], $targetPath, $width, $height);

        return basename($targetPath); // Return the name of the saved file
    }

    private function resizeAndConvertToWebP($sourcePath, $targetPath, $width, $height)
    {
        $imagick = new Imagick();

        try {
            // Load the source image
            $imagick->readImage($sourcePath);

            // Get the original dimensions
            $originalWidth = $imagick->getImageWidth();
            $originalHeight = $imagick->getImageHeight();

            // Calculate new dimensions while maintaining aspect ratio
            $aspectRatio = $originalWidth / $originalHeight;
            if ($width / $height > $aspectRatio) {
                $newWidth = (int)($height * $aspectRatio);
                $newHeight = $height;
            } else {
                $newWidth = $width;
                $newHeight = (int)($width / $aspectRatio);
            }

            // Resize the image with new dimensions
            $imagick->resizeImage($newWidth, $newHeight, Imagick::FILTER_LANCZOS, 1);

            // Set the format to WebP
            $imagick->setImageFormat('webp');
            $imagick->setImageCompressionQuality($this->imageQuality);

            // Save the image
            if (!$imagick->writeImage($targetPath)) {
                throw new \Exception("Failed to save image as WebP.");
            }
        } catch (\Exception $e) {
            throw new \Exception("Error processing image: " . $e->getMessage());
        } finally {
            // Clear Imagick resources
            $imagick->clear();
            $imagick->destroy();
        }
    }

    // Add or remove allowed file extensions
    public function addAllowedExtension($extension)
    {
        $this->allowedExtensions[] = strtolower($extension);
    }

    public function removeAllowedExtension($extension)
    {
        $this->allowedExtensions = array_diff($this->allowedExtensions, [strtolower($extension)]);
    }
}
