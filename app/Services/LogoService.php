<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class LogoService
 * @package App\Services
 * @author Kashif <kash@dreamsites.co.uk>
 * @created 22/09/2020
 */
class LogoService
{
    /**
     * @var Client
     */
    private $client;

    /**
     * API host URL
     * @var string
     */
    private $baseUri = 'https://logo.preview1.co';

    /**
     * Default test url
     * @var string
     */
    private $testUrl = 'instagram.com';

    /**
     * @return Client
     */
    public function newClient(): Client
    {
        $this->client = new Client([
            'base_uri' => $this->baseUri,
            'verify' => false
        ]);

        return $this->client;
    }

    /**
     * Return domain from url.
     *
     * @param string $address
     * @param bool $returnProtocol
     * @return string
     */
    public function getHost(string $address, bool $returnProtocol = true): string
    {
        $domain = parse_url($address, PHP_URL_HOST);
        $scheme = parse_url($address, PHP_URL_SCHEME);

        if ($returnProtocol) {
            return $scheme . '://' . $domain;
        } else {
            return $domain;
        }
    }

    /**
     * Return logo data from a url.
     *
     * @param string $siteUrl
     * @return array
     */
    public function getSiteLogo(string $siteUrl = ''): array
    {
        try {
            $url = empty($siteUrl) ? $this->testUrl : $this->getHost($siteUrl, false);

            $http = $this->newClient();

            $res = $http->request('GET', $this->baseUri . '/allicons.json?url=' . $url, []);

            if ($res->getStatusCode() === Response::HTTP_OK) {

                $arrayContent = json_decode($res->getBody()->getContents(), true);

                if (isset($arrayContent) && isset($arrayContent['icons'])) {
                    if (count($arrayContent['icons']) > 0) {
                        $iconData = $arrayContent['icons'][0];
                        $icon = $iconData['url'];
                    } else {
                        $iconData = null;
                        $icon = null;
                    }
                } else {
                    $iconData = null;
                    $icon = null;
                }

                return ['response' => Response::HTTP_OK, 'icon' => $icon, 'iconData' => $iconData, 'url' => $url];
            } else {
                return ['response' => $res->getStatusCode(), 'url' => $url];
            }

        } catch (GuzzleException $e) {
            return ['response' => $e->getCode(), 'error' => $e->getMessage()];
        }
    }


}
