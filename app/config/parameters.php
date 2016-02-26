<?php
    $dir = $container->getParameter('kernel.root_dir').'/../web/uploads/';
    $container->setParameter('upload.dir', $dir);