<?php
/**
 * Class SiteService
 * @package App\Services
 * @author Kashif <kash@dreamsites.co.uk>
 * @created 25/09/2020
 */

namespace App\Services;

use App\Services\LogoService;
use App\Site;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Validator;
use Symfony\Component\HttpFoundation\Response;

class SiteService
{
    /**
     * @var ScraperService
     */
    private $scraperService;

    /**
     * @var LogoService
     */
    private $logoService;

    /**
     * SiteService constructor.
     * @param LogoService $logoService
     */
    public function __construct(LogoService $logoService, ScraperService $scraperService)
    {
        $this->logoService = $logoService;
        $this->scraperService = $scraperService;
    }

    /**
     * @param array $data
     * @param bool $createSite
     */
    public function updateSite(array $data, bool $createSite = true)
    {
        try {
            if ($createSite) {
                // Create the Site

                try {
                    // scrape the domain
                    $data['scraped_data'] = $this->scraperService->processUrl($data['domain']);
                } catch (\Exception $e) {
                    //log error and return
                    $data['scraped_data'] = null;
                }

                // get the logo data
                $data['logo_data'] = $this->logoService->getSiteLogo($data['url']);

                $validData = [
                    'last_ping' => now(),
                    'last_logo_update' => now(),
                    'status_code' => $data['logo_data']['response'],
                    'status_id' => 1,
                    'link_id' => $data['link_id'] ?? null
                ];

                if (isset($data['logo_data']['icon'])) {
                    $validData['logo'] = $data['logo_data']['icon'];
                } else {
                    $validData['logo'] = null;
                }

                if (!isset($data['logo_data']['iconData']['sha1sum'])) {
                    $validData['logo_sha1sum'] = null;
                } else {
                    $validData['logo_sha1sum'] = $data['logo_data']['iconData']['sha1sum'];
                }

                if (!isset($data['logo_data']['iconData']['bytes'])) {
                    $validData['logo_bytes'] = null;
                } else {
                    $validData['logo_bytes'] = $data['logo_data']['iconData']['bytes'];
                }

                $validation = [
                    'logo' => 'nullable',
                    'logo_sha1sum' => 'nullable',
                    'logo_bytes' => 'nullable',
                    'last_ping' => 'date',
                    'last_logo_update' => 'date',
                    'status_code' => 'integer',
                    'status_id' => 'integer',
                    'link_id' => 'nullable'
                ];

                if ($data['scraped_data'] === null) {
                    $validData['perform_scrape'] = 0;
                    $validation['perform_scrape'] = ['integer'];
                }

                $validator = Validator::make($validData, $validation);

                if ($validator->fails()) {
                    return $validator->errors();
                }

                /** @var Site $site */
                $site = Site::create($validator->validated());
            } else {
                // UPDATE the Site

                /** @var Site $site */
                //$site = Site::whereId($data['site_id'])->whereUpdatedAt('date', '<', date_create('-7 days'))->first();
                $site = Site::whereId($data['site_id'])->first();
                if ($site->updated_at < date_create('-7 days') || $site->link_id === null) {

                    if ($site->perform_scrape === 1) {
                        // scrape the domain
                        $data['scraped_data'] = $this->scraperService->processUrl($data['domain']);
                    }

                    // get the logo data
                    $data['logo_data'] = $this->logoService->getSiteLogo($data['url']);

                    $validData = [
                        'last_ping' => now(),
                        'last_logo_update' => now(),
                        'status_code' => $data['logo_data']['response'],
                        'status_id' => 1,
                        'link_id' => $data['link_id'] ?? null
                    ];

                    if (isset($data['logo_data']['icon'])) {
                        $validData['logo'] = $data['logo_data']['icon'];
                    } else {
                        $validData['logo'] = null;
                    }

                    if (!isset($data['logo_data']['iconData']['sha1sum'])) {
                        $validData['logo_sha1sum'] = null;
                    } else {
                        $validData['logo_sha1sum'] = $data['logo_data']['iconData']['sha1sum'];
                    }

                    if (!isset($data['logo_data']['iconData']['bytes'])) {
                        $validData['logo_bytes'] = null;
                    } else {
                        $validData['logo_bytes'] = $data['logo_data']['iconData']['bytes'];
                    }

                    $validator = Validator::make($validData, [
                        'logo' => 'nullable',
                        'logo_sha1sum' => 'nullable',
                        'logo_bytes' => 'nullable',
                        'last_ping' => 'date',
                        'last_logo_update' => 'date',
                        'status_code' => 'integer',
                        'status_id' => 'integer',
                        'link_id' => 'nullable'
                    ]);

                    if ($validator->fails()) {
                        return $validator->errors();
                    }

                    $site->update($validator->validated());
                }
            }

            return [$site->id, $site->perform_scrape];

        } catch (\Exception $e) {
            //log error and return
            return [$e->getMessage()];
        }
    }

}
