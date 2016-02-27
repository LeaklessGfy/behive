<?php
    $dirKernel = $container->getParameter('kernel.root_dir');
    $dirUploads = $dirKernel.'/../web/uploads/';
    $dirCache = $dirKernel.'/../src/AppBundle/Cache/';

    $container->setParameter('upload.dir', $dirUploads);
    $container->setParameter('cache.dir', $dirCache);