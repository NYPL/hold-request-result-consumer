<?php

namespace NYPL\HoldRequestResultConsumer\OAuthClient;

use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use NYPL\HoldRequestResultConsumer\Model\Exception\CurlTimedOutException;
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
     * @throws CurlTimedOutException
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
            if (strpos($exception->getMessage(), 'Operation timed out') !== false) {
                throw new CurlTimedOutException(
                    'Curl Timeout Exception from '. $sourceFunction . ' ' . $exception->getMessage(),
                    'Curl Timeout Exception from '. $sourceFunction . ' ' . $exception->getMessage(),
                    $exception->getCode(),
                    null,
                    $exception->getCode(),
                    new ErrorResponse(
                        $exception->getCode(),
                        'curl-timeout-exception',
                        'Curl Timeout Exception from '. $sourceFunction . ' ' . $exception->getMessage()
                    )
                );
            }
            throw $exception;
        }
    }

    /**
     * @param string $url
     * @param array $body
     * @param string $sourceFunction
     * @return \Psr\Http\Message\ResponseInterface
     * @throws CurlTimedOutException
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
            if (strpos($exception->getMessage(), 'Operation timed out') !== false) {
                throw new CurlTimedOutException(
                    'Curl Timeout Exception from '. $sourceFunction . ' ' . $exception->getMessage(),
                    'Curl Timeout Exception from '. $sourceFunction . ' ' . $exception->getMessage(),
                    $exception->getCode(),
                    null,
                    $exception->getCode(),
                    new ErrorResponse(
                        $exception->getCode(),
                        'curl-timeout-exception',
                        'Curl Timeout Exception from '. $sourceFunction . ' ' . $exception->getMessage()
                    )
                );
            }
            throw $exception;
        }
    }
}
