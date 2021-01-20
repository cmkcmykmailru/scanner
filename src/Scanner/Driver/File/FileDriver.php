<?php


namespace Scanner\Driver\File;

use Scanner\Driver\AbstractDriver;

class FileDriver extends AbstractDriver
{

    public function detect($path): void
    {
        $path = rtrim($path, DIRECTORY_SEPARATOR);
        $files = array_slice(scandir($path), 2);
        $pathWithSeparator = $path . DIRECTORY_SEPARATOR;

        $this->fireStartDetected($path);

        foreach ($files as $file) {
            $filePath = $pathWithSeparator . $file;
            if (is_dir($filePath)) {
                $this->fireNodeDetected(new Directory($filePath));
            } else {
                $this->fireLeafDetected(new File($filePath));
            }
        }

        $this->fireCompleteDetected($path);
    }

}