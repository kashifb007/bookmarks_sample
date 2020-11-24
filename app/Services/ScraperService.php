<?php
/**
 * Class ScraperService
 * @package App\Services
 * @author Kashif <kash@dreamsites.co.uk>
 * @created 25/09/2020
 */

namespace App\Services;

use Goutte\Client;
use GuzzleHttp\Exception\GuzzleException;
use Symfony\Component\DomCrawler\Crawler;
use GuzzleHttp\Client as GuzzleClient;

class ScraperService
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @var GuzzleClient
     */
    private $guzzleClient;

    /**
     * @var string
     */
    private $data;

    /**
     * @var bool
     */
    private $fetchCanonical = false;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->client = new Client();
        $this->guzzleClient = new GuzzleClient([
            'verify' => false
        ]);
    }

    /**
     * Process the URL
     *
     * @param $url
     * @return array
     * @throws GuzzleException
     */
    public function processUrl(string $url): array
    {

        $scrapedData = [];

        try {
            $crawler = $this->client->request('GET', $url);

            if ($crawler->filterXPath('//title')->count() > 0) {
                $scrapedData['title'] = $crawler->filterXPath('//title')->text();
            }

            if ($crawler->filterXpath('//meta[@name="description"]')->count() > 0) {
                $scrapedData['description'] = $crawler->filterXpath('//meta[@name="description"]')->attr('content');
            }

            if ($this->fetchCanonical) {
                if ($crawler->filterXPath('//link[@rel="canonical"]')->count() > 0) {
                    $crawler->filterXPath('//link[@rel="canonical"]')->each(function (Crawler $node) use (&$linkMetaInfo) {
                        $scrapedData['canonicalLink'] = trim($node->attr('href'));
                    });
                }

                if (empty($scrapedData['canonicalLink'])) {
                    $content = $this->guzzleClient->request('GET', $url, ['stream' => true]);
                    $body = $content->getBody();
                    $buffer = '';
                    while (!$body->eof() && (strlen($buffer) < 32768)) {
                        $buffer .= $body->read(1024);
                    }
                    $body->close();

                    // Extract meta tags from the body
                    $scrapedData['canonicalLink'] = $buffer;
                }
            }

            if ($crawler->filterXpath('//meta[@property="og:title"]')->count() > 0) {
                $scrapedData['og_title'] = $crawler->filterXpath('//meta[@property="og:title"]')->attr('content');
            }

            if ($crawler->filterXpath('//meta[@property="og:description"]')->count() > 0) {
                $scrapedData['og_desc'] = $crawler->filterXpath('//meta[@property="og:description"]')->attr('content');
            }

            if ($crawler->filterXpath('//meta[@property="og:image"]')->count() > 0) {
                $scrapedData['og_image'] = $crawler->filterXpath('//meta[@property="og:image"]')->attr('content');
            }

            if ($crawler->filterXpath('//meta[@property="og:url"]')->count() > 0) {
                $scrapedData['og_url'] = $crawler->filterXpath('//meta[@property="og:url"]')->attr('content');
            }

            if ($crawler->filterXpath('//meta[@property="og:type"]')->count() > 0) {
                $scrapedData['og_type'] = $crawler->filterXpath('//meta[@property="og:type"]')->attr('content');
            }
        } catch (GuzzleException $e) {
            return ['response' => $e->getCode(), 'error' => $e->getMessage()];
        }

        return $scrapedData;
    }


}
