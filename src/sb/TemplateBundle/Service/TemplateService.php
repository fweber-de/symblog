<?php

namespace sb\TemplateBundle\Service;

use Symfony\Component\HttpFoundation\Response;

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
     * @param string $currentTemplate
     * @param mixed $templates
     */
    public function __construct($container, $currentTemplate, $templates)
    {
        $this->container = $container;
        $this->currentTemplate = $currentTemplate;
        $this->templates = $templates;
        $this->templateFolder = __DIR__ . '/../../../../templates';
    }

    /**
     * Gets the Data for the current template
     *
     * @param string $identifier
     * @return mixed
     * @throws \Exception
     */
    protected function getTemplateData($identifier)
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
}
