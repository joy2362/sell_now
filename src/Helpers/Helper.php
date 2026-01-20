<?php
if (!function_exists('redirect')) {
    function redirect(string $url): void
    {
        header("Location: $url");
        exit;
    }
}

if (!function_exists('uploadFile')) {
    /**
     * Upload a file safely
     *
     * @param array|null $file The $_FILES array element
     * @param string $prefix Optional prefix for the filename
     * @param string $uploadDir Absolute path to upload directory
     * @return string|null Relative path to uploaded file or null on failure
     */
    function uploadFile(?array $file, string $prefix = '', string $uploadDir = ''): ?string
    {
        if (!$file || $file['error'] !== 0) {
            return null;
        }

        // Ensure $uploadDir ends with a slash
        $uploadDir = rtrim($uploadDir, '/') . '/';

        // Create directory if it doesn't exist
        if (!is_dir($uploadDir) && !mkdir($uploadDir, 0755, true) && !is_dir($uploadDir)) {
            throw new RuntimeException("Failed to create directory: $uploadDir");
        }

        // Sanitize filename
        $name = time() . '_' . $prefix . preg_replace('/[^a-zA-Z0-9_\.-]/', '_', basename($file['name']));

        $destination = $uploadDir . $name;

        if (!move_uploaded_file($file['tmp_name'], $destination)) {
            return null;
        }

        // Return relative path
        return 'uploads/' . $name;
    }
}
