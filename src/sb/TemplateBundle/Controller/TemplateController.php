<?php

namespace sb\TemplateBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;

/**
 * TemplateController
 * @package sb\TemplateBundle\Controller
 * @author Florian Weber <florian.weber.dd@icloud.com>
 */
class TemplateController extends Controller
{
    private function getAsset($template, $filename, $type, $contentType = null)
    {
        $folder = __DIR__ . '/../../../../templates' . '/' . $template . '/' . $type;
        $finder = new Finder();
        $finder->files()->in($folder);

        foreach ($finder as $file) {
            if (strpos($file->getRealpath(), $filename) !== false) {
                $response = new Response(file_get_contents($file->getRealpath()));

                if (!is_null($contentType)) {
                    $response->headers->set('Content-Type', $contentType);
                }

                return $response;
            }
        }

        throw new FileNotFoundException(sprintf('File "%s" was not found in "%s"', $filename, $folder));
    }

    public function getJsAction($templateName, $fileName)
    {
        return $this->getAsset($templateName, $fileName, 'js', 'application/javascript');
    }

    public function getCssAction($templateName, $fileName)
    {
        return $this->getAsset($templateName, $fileName, 'css', 'text/css');
    }

    public function getFontsAction($templateName, $fileName)
    {
        return $this->getAsset($templateName, $fileName, 'fonts');
    }
}
