<?php

namespace sb\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class TemplateController
 * @package sb\AdminBundle\Controller
 */
class TemplateController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function collectionAction()
    {
        $templates = $this->get('sb.templating')->getTemplatesObjectArray();

        return $this->render(
            'sbAdminBundle:Template:collection.html.twig',
            array(
                'templates' => $templates
            )
        );
    }

    public function objectAction(Request $request, $identifier)
    {
        $session = $request->getSession();
        $template = $this->get('sb.templating')->getTemplateData($identifier);

        if ($identifier == 'Default') {
            $session->getFlashBag()->add('error', 'The Default Template cannot be edited!');

            return $this->redirect($this->generateUrl('admin_templates_collection'));
        }

        $finder = new Finder();
        $finder
            ->files()
            ->in($this->get('sb.templating')->getTemplateFolder() . '/' . $identifier)
            ->exclude('css')
            ->exclude('js')
            ->exclude('fonts');

        foreach ($finder as $file) {
            $files[] = $file;
        }

        return $this->render(
            'sbAdminBundle:Template:object.html.twig',
            array(
                'template' => $template,
                'files' => $files
            )
        );
    }

    public function updateFileAction(Request $request, $identifier, $filename)
    {
        $session = $request->getSession();
        $template = $this->get('sb.templating')->getTemplateData($identifier);

        if ($identifier == 'Default') {
            $session->getFlashBag()->add('error', 'The Default Template cannot be edited!');

            return $this->redirect($this->generateUrl('admin_templates_collection'));
        }

        $currentFileFinder = new Finder();
        $currentFileFinder
            ->files()
            ->in($this->get('sb.templating')->getTemplateFolder() . '/' . $identifier)
            ->exclude('css')
            ->exclude('js')
            ->exclude('fonts')
            ->name($filename);

        foreach ($currentFileFinder as $_file) {
            $currentFile = $_file;
        }

        $fs = new Filesystem();
        if (!$fs->exists($currentFile->getRealpath())) {
            $session->getFlashBag()->add('error', 'The requested File does not exist!');

            return $this->redirect($this->generateUrl('admin_templates_collection'));
        }

        $allFiles = new Finder();
        $allFiles
            ->files()
            ->in($this->get('sb.templating')->getTemplateFolder() . '/' . $identifier)
            ->exclude('css')
            ->exclude('js')
            ->exclude('fonts');

        return $this->render(
            'sbAdminBundle:Template:updateFile.html.twig',
            array(
                'template' => $template,
                'currentFile' => $currentFile,
                'fileContent' => trim(trim(file_get_contents($currentFile->getRealpath())), '\t'),
                'files' => $allFiles
            )
        );
    }
}
