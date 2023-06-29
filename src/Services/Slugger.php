<?php

namespace App\Services;

use Cocur\Slugify\Slugify;

class Slugger
{
    public function slugify(string $title): string
    {
        $slugify = new Slugify();
        return $slugify->slugify($title);
    }
}