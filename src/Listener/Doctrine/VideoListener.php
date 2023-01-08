<?php

namespace App\Listener\Doctrine;

use App\Entity\Video;

class VideoListener
{
    public function prePersist(Video $video): void
    {
        $video
            ->setCreationDateTime(new \DateTime())
            ->setLastUpdateDateTime(new \DateTime())
        ;
    }

    public function preUpdate(Video $video): void
    {
        $video
            ->setLastUpdateDateTime(new \DateTime())
        ;
    }
}
