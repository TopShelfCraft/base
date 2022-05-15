<?php
namespace TopShelfCraft\base\controllers\web;

use Craft;
use yii\web\Response;

trait WebControllerTrait
{

	abstract function asErrorJson(string $error): Response;
	abstract function asJson($data);
	abstract function redirectToPostedUrl($object = null, string $default = null): Response;

    protected function returnErrorResponse(string $errorMessage, array $routeParams = []): ?Response
    {

        if (Craft::$app->getRequest()->getAcceptsJson())
        {
            return $this->asErrorJson($errorMessage);
        }

        Craft::$app->getSession()->setError($errorMessage);

        Craft::$app->getUrlManager()->setRouteParams([
                'errorMessage' => $errorMessage,
            ] + $routeParams);

        return null;

    }

    protected function returnSuccessResponse($returnUrlObject = null): Response
    {

        if (Craft::$app->getRequest()->getAcceptsJson())
        {
            return $this->asJson(['success' => true]);
        }

        return $this->redirectToPostedUrl($returnUrlObject, Craft::$app->getRequest()->getReferrer());

    }

	/**
	 * Runs the given function and returns an appropriate response.
	 */
    protected function runAndReturn(callable $function): ?Response
    {
        try
        {
            $returnUrlObject = $function();
            return $this->returnSuccessResponse($returnUrlObject);
        }
        catch (\Exception $e)
        {
            return $this->returnErrorResponse($e->getMessage());
        }
    }

}
