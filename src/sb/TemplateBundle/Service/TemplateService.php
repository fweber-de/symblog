<?php

namespace sb\TemplateBundle\Service;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Yaml\Parser;

class TemplateService
{
    protected $container;

    /**
     * @var string
     */
    protected $currentTemplate;

    /**
     * @var mixed
     */
    protected $templates;

    /**
     * @var string
     */
    protected $templateFolder;

    /**
     * @param $container
     * @param $currentTemplate
     */
    public function __construct($container, $currentTemplate)
    {
        $this->container = $container;
        $this->currentTemplate = $currentTemplate;

        //get templates
        $yaml = new Parser();
        $this->templates = $yaml->parse(
            file_get_contents(__DIR__ . '/../../../../app/config/templates.yml')
        )['sb.templates'];

        $this->templateFolder = __DIR__ . '/../../../../templates';
    }

    /**
     * Gets the Data for the current template
     *
     * @param string $identifier
     * @return mixed
     * @throws \Exception
     */
    public function getTemplateData($identifier)
    {
        foreach ($this->templates as $template) {
            if ($template['identifier'] == $identifier) {
                return json_decode(json_encode($template), false);
            }
        }

        throw new \Exception(sprintf('The Template with Identifier %s is not defined!', $identifier));
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
        $template = $this->getTemplateData($this->currentTemplate);
        $parameters['template'] = $template;

        //reconfig twig to use another file path
        $twig = clone $this->container->get('twig');
        $twig->setLoader(new \Twig_Loader_Filesystem($this->templateFolder . '/' . $this->currentTemplate));

        $rendered = $twig->render(
            $viewName,
            $parameters
        );

        return new Response($rendered);
    }

    /**
     * @return mixed
     */
    public function getTemplatesArray()
    {
        return $this->templates;
    }

    /**
     * @return array
     */
    public function getTemplatesObjectArray()
    {
        $out = array();

        foreach ($this->templates as $template) {
            $out[] = json_decode(json_encode($template), false);
        }

        return $out;
    }

    /**
     * @return string
     */
    public function getTemplateFolder()
    {
        return $this->templateFolder;
    }
    
    public function getCurrentTemplate()
    {
        return $this->getTemplateData($this->currentTemplate);
    }
}
