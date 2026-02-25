<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

final class TinyMCEController extends AbstractController
{
    #[Route('/tinymce/upload', name: 'app_tinymce_upload', methods: ['POST'])]
    public function upload(Request $request): JsonResponse
    {
        $file = $request->files->get('file');
        if (!$file) {
            return new JsonResponse(['error' => 'No file uploaded'], Response::HTTP_BAD_REQUEST);
        }

        $uploadDir = $this->getParameter('kernel.project_dir') . '/public/uploads/tinymce';
        if (!is_dir($uploadDir)) {
            if (!mkdir($uploadDir, 0755, true) && !is_dir($uploadDir)) {
                return new JsonResponse(['error' => 'Could not create upload directory'], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }

        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeName = preg_replace('/[^a-zA-Z0-9_\-]/', '_', $originalName);
        $extension = $file->guessExtension() ?: 'bin';
        $filename = $safeName . '-' . uniqid() . '.' . $extension;

        try {
            $file->move($uploadDir, $filename);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Could not move file: ' . $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $url = '/uploads/tinymce/' . $filename;
        return new JsonResponse(['location' => $url]);
    }
}
