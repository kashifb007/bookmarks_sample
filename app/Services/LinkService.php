<?php
/**
 * Class LinkService
 * @package App\Services
 * @author Kashif <kash@dreamsites.co.uk>
 * @created 27/09/2020
 */

namespace App\Services;

use App\Link;
use Symfony\Component\HttpFoundation\Response;
use Validator;

class LinkService
{

    /**
     * @param array $data
     * @param Link|null $link
     * @param bool $createSite Create the Site Link
     * @return array|\Illuminate\Support\MessageBag|int
     */
    public function updateLink(array $data, Link $link = null, bool $createSite = false)
    {

        try {

            $validData = [
                'title' => $data['scraped_url_data']['title'] ?? 'Untitled',
                'meta_description' => $data['scraped_url_data']['description'] ?? null,
                'site_id' => $data['site_id'],
                'status_id' => 1,
                'url' => $data['url'],
                'user_id' => $data['user_id']
            ];

            $validData['og_image'] = $data['scraped_url_data']['og_image'] ?? null;
            $validData['og_description'] = $data['scraped_url_data']['og_desc'] ?? null;
            $validData['og_type'] = $data['scraped_url_data']['og_type'] ?? null;
            $validData['og_url'] = $data['scraped_url_data']['og_url'] ?? null;
            $validData['og_title'] = $data['scraped_url_data']['og_title'] ?? null;

            $validData['is_site'] = $createSite === true ? 1 : 0;

            $validator = Validator::make($validData, [
                'title' => 'string',
                'meta_description' => 'nullable',
                'site_id' => 'integer',
                'status_id' => 'integer',
                'og_image' => 'nullable',
                'og_description' => 'nullable',
                'og_type' => 'nullable',
                'og_url' => 'nullable',
                'og_title' => 'nullable',
                'url' => 'string',
                'user_id' => 'integer',
                'is_site' => 'integer'
            ]);

            if ($validator->fails()) {
                return $validator->errors();
            }

            if (isset($link)) {
                $link->update($validator->validated());
            } else {
                /** @var Link $link */
                $link = Link::create($validator->validated());
            }

            return $link->id;

        } catch (\Exception $e) {
            //log error and return
            return [$e->getMessage()];
        }
    }


}
