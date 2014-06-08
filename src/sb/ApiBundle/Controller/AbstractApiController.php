<?php

namespace sb\ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * Class AbstractApiController
 * @package sb\ApiBundle\Controller
 */
abstract class AbstractApiController extends Controller
{

    /**
     * @return object
     */
    public function getSerializer()
    {
        return $this->container->get('jms_serializer');
    }

    /**
     * Sends a JSON Response
     *
     * @param $data
     * @return Response
     */
    public function sendJSONResponse($data, $statusCode = null)
    {
        $serializer = $this->getSerializer();
        $serialized = $serializer->serialize($data, 'json');

        $response = new Response($serialized);
        $response->headers->set('Content-Type', 'application/json');

        if (!is_null($statusCode)) {
            $response->setStatusCode($statusCode);
        } else {
            $response->setStatusCode(200);
        }

        return $response;
    }

    /**
     * Checks if a given String is valid JSON
     *
     * @param $json
     * @return bool
     */
    public function isJSON($json)
    {
        return is_object(json_decode($json));
    }

    /**
     * Sends a 'Exception' in JSON Format
     *
     * @param $message
     * @param $statusCode
     * @return Response
     */
    public function throwJSONException($message, $statusCode)
    {
        $data = array(
            'message' => $message,
            'status' => $statusCode
        );

        return $this->sendJSONResponse($data, $statusCode);
    }

    /**
     * Gets the Requested Object from JSON
     *
     * @param $json
     * @param $classFQCN
     * @return mixed
     * @throws \Symfony\Component\HttpKernel\Exception\BadRequestHttpException
     */
    protected function getObject($json, $classFQCN)
    {
        if (!$this->isJSON($json)) {
            throw new BadRequestHttpException('The Request Body must contain valid JSON!');
        }

        $object = $this->getSerializer()->deserialize($json, $classFQCN, 'json');

        if (!$object instanceof $classFQCN) {
            throw new BadRequestHttpException('Wrong Object Format!');
        }

        return $object;
    }

}
