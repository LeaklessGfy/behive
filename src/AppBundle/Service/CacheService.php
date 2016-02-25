<?php

namespace AppBundle\Service;

use Symfony\Component\Filesystem\Exception\IOException;

class CacheService
{
    public function getInCache($id)
    {
        $finder = new \Symfony\Component\Finder\Finder();
        $finder->files()->in(__DIR__.'/../Cache/ApiGiant/')->name($id.'.json');

        $contents = false;
        foreach ($finder as $file) {
            $contents = $file->getContents();
        }

        if($contents) {
            dump("cache");
            return $contents;
        }

        return false;
    }

    public function storeInCache($id, $result)
    {
        $fs = new \Symfony\Component\Filesystem\Filesystem();

        try {
            $fs->dumpFile(__DIR__.'/../Cache/ApiGiant/'.$id.'.json', $result);
        }
        catch(IOException $e) {
        }
    }

    public function getEverythingFromCache()
    {
        $finder = new \Symfony\Component\Finder\Finder();
        $finder->files()->in(__DIR__.'/../Cache/ApiGiant/')->name('game-*.json');

        $contents = array();
        foreach ($finder as $file) {
            $raw = $file->getContents();
            $contents[] = json_decode($raw, true)['results'];
        }

        return $contents;
    }
}