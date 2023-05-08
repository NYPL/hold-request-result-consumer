<?php

namespace NYPL\HoldRequestResultConsumer\OAuthClient;

use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use NYPL\HoldRequestResultConsumer\Model\Exception\ClientTimeoutException;
use NYPL\HoldRequestResultConsumer\Model\Exception\NonRetryableException;
use NYPL\HoldRequestResultConsumer\Model\Exception\RetryableException;
use NYPL\Starter\APILogger;
use NYPL\Starter\Model\Response\ErrorResponse;

class ClientHelper extends APIClient
{
    /**
     * @param string $url
     * @param string $sourceFunction
     * @return \Psr\Http\Message\ResponseInterface
     * @throws ClientTimeoutException
     * @throws NonRetryableException
     * @throws RetryableException
     * @throws \Exception
     */
    public static function getResponse($url = '', $sourceFunction = '')
    {
        try {
            $response = self::get($url);

            return $response;
        } catch (ServerException $exception) {
            throw new RetryableException(
                'Server Error from ' . $sourceFunction . ' ' . $exception->getMessage(),
                'Server Error from ' . $sourceFunction . ' ' . $exception->getMessage(),
                $exception->getResponse()->getStatusCode(),
                null,
                $exception->getResponse()->getStatusCode(),
                new ErrorResponse(
                    $exception->getResponse()->getStatusCode(),
                    'internal-server-error',
                    'Server Error from ' . $sourceFunction . ' ' . $exception->getMessage()
                )
            );
        } catch (ClientException $exception) {
            throw new NonRetryableException(
                'Client Error from '. $sourceFunction . ' ' . $exception->getMessage(),
                'Client Error from '. $sourceFunction . ' ' . $exception->getMessage(),
                $exception->getResponse()->getStatusCode(),
                null,
                $exception->getResponse()->getStatusCode(),
                new ErrorResponse(
                    $exception->getResponse()->getStatusCode(),
                    'client-error',
                    'Client Error from '. $sourceFunction . ' ' . $exception->getMessage()
                )
            );
        } catch (\Exception $exception) {
            self::checkForTimeOutException($exception, $sourceFunction);
        }
    }

    /**
     * @param string $url
     * @param array $body
     * @param string $sourceFunction
     * @return \Psr\Http\Message\ResponseInterface
     * @throws ClientTimeoutException
     * @throws NonRetryableException
     * @throws \Exception
     */
    public static function patchResponse($url = '', $body = array(), $sourceFunction = '')
    {
        try {
            APILogger::addDebug('patchResponse ', array($url, json_encode($body)));
            $response = self::patch($url, ["body" => json_encode($body)]);
            APILogger::addDebug('Response from patchResponse ', array($response));
            return $response;
        } catch (ServerException $exception) {
            throw new NonRetryableException(
                'Server Error from ' . $sourceFunction . ' ' . $exception->getMessage(),
                'Server Error from ' . $sourceFunction . ' ' . $exception->getMessage(),
                $exception->getResponse()->getStatusCode(),
                null,
                $exception->getResponse()->getStatusCode(),
                new ErrorResponse(
                    $exception->getResponse()->getStatusCode(),
                    'internal-server-error',
                    'Server Error from ' . $sourceFunction . ' ' . $exception->getMessage()
                )
            );
        } catch (ClientException $exception) {
            throw new NonRetryableException(
                'Client Error from '. $sourceFunction . ' ' . $exception->getMessage(),
                'Client Error from '. $sourceFunction . ' ' . $exception->getMessage(),
                $exception->getResponse()->getStatusCode(),
                null,
                $exception->getResponse()->getStatusCode(),
                new ErrorResponse(
                    $exception->getResponse()->getStatusCode(),
                    'client-error',
                    'Client Error from '. $sourceFunction . ' ' . $exception->getMessage()
                )
            );
        } catch (\Exception $exception) {
            self::checkForTimeOutException($exception, $sourceFunction);
        }
    }

    /**
     * @param string $url
     * @param array $body
     * @param string $sourceFunction
     * @return \Psr\Http\Message\ResponseInterface
     * @throws ClientTimeoutException
     * @throws NonRetryableException
     * @throws \Exception
     */
    public static function postResponse($url = '', $body = array(), $sourceFunction = '')
    {
        try {
            APILogger::addDebug('patchResponse ', array($url, json_encode($body)));
            $response = self::post($url, ["body" => json_encode($body)]);
            APILogger::addDebug('Response from patchResponse ', array($response));
            return $response;
        } catch (ServerException $exception) {
            throw new NonRetryableException(
                'Server Error from ' . $sourceFunction . ' ' . $exception->getMessage(),
                'Server Error from ' . $sourceFunction . ' ' . $exception->getMessage(),
                $exception->getResponse()->getStatusCode(),
                null,
                $exception->getResponse()->getStatusCode(),
                new ErrorResponse(
                    $exception->getResponse()->getStatusCode(),
                    'internal-server-error',
                    'Server Error from ' . $sourceFunction . ' ' . $exception->getMessage()
                )
            );
        } catch (ClientException $exception) {
            throw new NonRetryableException(
                'Client Error from '. $sourceFunction . ' ' . $exception->getMessage(),
                'Client Error from '. $sourceFunction . ' ' . $exception->getMessage(),
                $exception->getResponse()->getStatusCode(),
                null,
                $exception->getResponse()->getStatusCode(),
                new ErrorResponse(
                    $exception->getResponse()->getStatusCode(),
                    'client-error',
                    'Client Error from '. $sourceFunction . ' ' . $exception->getMessage()
                )
            );
        } catch (\Exception $exception) {
            self::checkForTimeOutException($exception, $sourceFunction);
        }
    }

    /**
     * @param \Exception $exception
     * @param string $sourceFunction
     * @throws ClientTimeoutException
     * @throws \Exception
     */
    public static function checkForTimeOutException(\Exception $exception, $sourceFunction = '')
    {
        if (strpos($exception->getMessage(), 'Operation timed out') !== false) {
            throw new ClientTimeoutException(
                'Client Timeout Exception from '. $sourceFunction . ' ' . $exception->getMessage(),
                'Client Timeout Exception from '. $sourceFunction . ' ' . $exception->getMessage(),
                $exception->getCode(),
                null,
                $exception->getCode(),
                new ErrorResponse(
                    $exception->getCode(),
                    'client-timeout-exception',
                    'Client Timeout Exception from '. $sourceFunction . ' ' . $exception->getMessage()
                )
            );
        }
        throw $exception;
    }
}
