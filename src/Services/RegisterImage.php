<?php

namespace App\Services;

use Symfony\Component\Form\Form;

/**
 * Gestion des images depuis un formulaire d'ajout d'article
 */
class RegisterImage implements RegisterImageInterface
{
    private Form $form;

    public function setForm(Form $form): void
    {
        $this->form = $form;
    }

    public function saveImage(): string
    {
        // Récupération du fichier image
        $file = $this->form->get('image')->getData();

        // Récuépration du nom original du fichier
        $originalFileName = $file->getClientOriginalName();

        // Nettoyage du nom du fichier (s'il y a des espaces, des points, des caractères spéciaux etc.)
        $cleanedFileName = preg_replace('/[^a-zA-Z0-9]+/', '-', strtolower($originalFileName));

        // Génération d'une chaîne de caractère aléatoire
        $randomString = bin2hex(random_bytes(5));

        // Récupération du nom du fichier sans l'extension
        $filenameWithoutExtension = pathinfo($cleanedFileName, PATHINFO_FILENAME);

        // Récupération de l'extension
        $extension = $file->getClientOriginalExtension();

        // Concaténation du nom du fichier avec la chaîne de caractères alénatoire et l'extension.
        $randonFileName = $filenameWithoutExtension . '-' . $randomString . '.' .  $extension;

        // Déplacement du fichier vers le répertoire /public/image_directory
        $file->move('image_directory', $randonFileName);

        // Renvoi du nom du fichier final afin de le préparer pour son insertion dans la base de données.
        return $randonFileName;
    }
}