<?php

namespace sb\TemplateBundle\Controller;

use MyProject\Proxies\__CG__\stdClass;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;

const DS = DIRECTORY_SEPARATOR;

class TemplateController extends Controller
{
    /**
     * @var string
     */
    protected $currentTemplate;

    /**
     * @var mixed
     */
    protected $templates;

    public function __construct($currentTemplate, $templates)
    {
        $this->currentTemplate = $currentTemplate;
        $this->templates = $templates;
    }

    /**
     * @param $identifier
     * @return mixed
     * @throws \Exception
     */
    protected function getTemplateData($identifier)
    {
        foreach($this->templates as $template)
        {
            if($template['identifier'] == $identifier) return json_decode(json_encode($template), false);
        }

        throw new \Exception('The Template with Identifier "' . $identifier . '" is not defined!');
    }

    /**
     * Gets the current Template and renders the given view
     *
     * @param string $viewName
     * @param array $parameters
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function renderTemplate($viewName, $parameters = array())
    {
        $templateLocation = __DIR__ . '/../../../../templates/' . $this->currentTemplate;
        $template = $this->getTemplateData($this->currentTemplate);
        $parameters['template'] = $template;

        $twig = clone $this->get('twig');
        $twig->setLoader(new \Twig_Loader_Filesystem($templateLocation));

        $rendered = $twig->render(
            $viewName,
            $parameters
        );

        return new Response($rendered);
    }

    private function getAsset($template, $filename, $type, $contentType = null)
    {
        $folder = __DIR__ . DS . '..' . DS . 'Resources' . DS . 'views' . DS . $template . DS . $type;
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

        throw new FileNotFoundException('File "' . $filename . '" was not found in "' . $folder . '"');
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
