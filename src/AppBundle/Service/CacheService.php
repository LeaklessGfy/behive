<?php

namespace AppBundle\Service;

use Symfony\Component\Filesystem\Exception\IOException;

class CacheService
{
    private $dir;

    public function __construct()
    {
        $this->dir = __DIR__ . '/../Cache/ApiGiant/';
    }

    public function getInCache($id)
    {
        $finder = new \Symfony\Component\Finder\Finder();
        $finder->files()->in($this->dir)->name($id.'.json');

        $contents = false;
        foreach ($finder as $file) {
            $contents = $file->getContents();
        }

        if($contents) {
            dump("cache");
        }

        return $contents;
    }

    public function storeInCache($id, $result)
    {
        $fs = new \Symfony\Component\Filesystem\Filesystem();

        try {
            $fs->dumpFile($this->dir.$id.'.json', $result);
        }
        catch(IOException $e) {
        }
    }

    public function getEverythingFromCache()
    {
        $finder = new \Symfony\Component\Finder\Finder();
        $finder->files()->in($this->dir)->name('game-*.json');

        $contents = array();
        foreach ($finder as $file) {
            $raw = $file->getContents();
            $contents[] = json_decode($raw, true)['results'];
        }

        return $contents;
    }
}
