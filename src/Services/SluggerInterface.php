<?php 

namespace App\Services;

interface SluggerInterface
{
    public function slugify(string $title): string;
}