<?php

namespace App\Services;

use Symfony\Component\Form\Form;

interface RegisterImageInterface
{
    public function setForm(Form $form): void;

    public function saveImage(): string;
}