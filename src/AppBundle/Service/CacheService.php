<?php

namespace AppBundle\Service;

use Symfony\Component\Filesystem\Exception\IOException;

class CacheService
{
    private $dirBase;

    public function __construct($dirBase)
    {
        $this->dirBase = $dirBase;
    }

    public function getInCache($id, $api)
    {
        $finder = new \Symfony\Component\Finder\Finder();
        $finder->files()->in($this->dirBase.$api)->name($id.'.json');

        $contents = false;
        foreach ($finder as $file) {
            $contents = $file->getContents();
        }

        if($contents) {
            dump("cache");
        }

        return $contents;
    }

    public function storeInCache($id, $result, $api)
    {
        $fs = new \Symfony\Component\Filesystem\Filesystem();

        try {
            $fs->dumpFile($this->dirBase.$api.$id.'.json', $result);
        }
        catch(IOException $e) {
        }
    }

    public function getEverythingFromCache($name, $api)
    {
        $finder = new \Symfony\Component\Finder\Finder();
        $finder->files()->in($this->dirBase.$api)->name($name);

        $contents = array();
        foreach ($finder as $file) {
            $contents[] = $file->getContents();
        }

        return $contents;
    }
}
