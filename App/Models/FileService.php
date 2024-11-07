<?php

namespace Models;

class FileService extends \Core\AUtility
{

    public static string $IMAGES_PATH = __DIR__ . '/../../public/assets/images/';

    /**
     * Singleton instance
     * @var FileService
     */
    private static FileService $instance;

    private function __construct()
    {
    }

    public function saveFormFile(string $formName, string $path, string $fileName = ''): string
    {
        if (!isset($_FILES[$formName])) {
            $this->error('Soubor nebyl nahrán');
            return '';
        }
        $file = $_FILES[$formName];
        if ($file['error'] !== UPLOAD_ERR_OK) {
            $this->error('Nastala chyba při nahrávání souboru');
            return '';
        }

        if (!$fileName) {
            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            $fileName = uniqid('product-img_') . uniqid() . '.' . $ext;
        }
        $fullPath = $path . $fileName;
        if (!move_uploaded_file($file['tmp_name'], $fullPath)) {
            $this->error('Nastala chyba při nahrávání souboru', 'FileService error 3');
            return '';
        }
        return $fileName;
    }

    /**
     * Singleton Getter
     * @return FileService
     */
    public
    static function get(): FileService
    {
        if (!isset(self::$instance))
            self::$instance = new FileService();
        return self::$instance;
    }
}