<?php

namespace App\Form\Model\Video;

use App\Entity\Video;

class AddGameToVideoModel
{
    private Video $video;

    public function getVideo(): Video
    {
        return $this->video;
    }

    public function setVideo(Video $video): self
    {
        $this->video = $video;
        return $this;
    }
}
