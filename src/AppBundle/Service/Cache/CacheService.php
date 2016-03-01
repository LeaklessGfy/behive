<?php

namespace AppBundle\Service\Cache;

use Symfony\Component\Filesystem\Exception\IOException;

class CacheService
{
    private $dirBase;

    public function __construct()
    {
        $this->dirBase = __DIR__ . "/";
    }

    public function getInCache($id, $subDir = "raw")
    {
        $dir = $this->dirBase.$subDir."/";

        $finder = new \Symfony\Component\Finder\Finder();
        $finder->files()->in($dir)->name($id.'.json');

        $contents = false;
        foreach ($finder as $file) {
            $contents = $file->getContents();
        }

        return $contents;
    }

    public function storeInCache($id, $result, $subDir = "raw")
    {
        $fs = new \Symfony\Component\Filesystem\Filesystem();
        $dir = $this->dirBase.$subDir."/";

        try {
            $fs->dumpFile($dir.$id.'.json', $result);
        }
        catch(IOException $e) {
        }
    }

    public function getEverythingFromCache($name, $subDir = "raw")
    {
        $dir = $this->dirBase.$subDir."/";

        $finder = new \Symfony\Component\Finder\Finder();
        $finder->files()->in($dir)->name($name);

        $contents = array();
        foreach ($finder as $file) {
            $contents[] = $file->getContents();
        }

        return $contents;
    }
}
