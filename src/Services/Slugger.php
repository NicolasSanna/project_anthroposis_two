<?php

namespace App\Services;

use Cocur\Slugify\Slugify;

class Slugger implements SluggerInterface
{
    private Slugify $slugify;

    public function __construct(Slugify $slugify)
    {
        $this->slugify = $slugify;
    }

    public function slugify(string $title): string
    {
        return $this->slugify->slugify($title);
    }
}