<?php

namespace App\Services;

use Symfony\Component\Form\Form;

class RegisterImage
{
    private Form $form;

    public function setForm(Form $form)
    {
        $this->form = $form;
    }

    public function saveImage(): string
    {
        $file = $this->form->get('image')->getData();

        $originalFileName = $file->getClientOriginalName();

        $randomString = bin2hex(random_bytes(5));

        $filenameWithoutExtension = pathinfo($originalFileName, PATHINFO_FILENAME);

        $extension = $file->getClientOriginalExtension();

        $randonFileName = $filenameWithoutExtension . '-' . $randomString . '.' .  $extension;

        $file->move('image_directory', $randonFileName);

        return $randonFileName;
    }
}