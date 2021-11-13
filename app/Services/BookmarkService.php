<?php

namespace App\Services;

use App\Bookmark;
use App\Link;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Database\Eloquent\Collection;
use Validator;
use Illuminate\Support\Facades\DB;
use App\Repositories\BookmarkRepository;

/**
 * Class BookmarkService
 * @package App\Services
 * @author Kashif <kash@dreamsites.co.uk>
 * @created 22/09/2020
 */
class BookmarkService
{
    /**
     * @var ScraperService
     */
    private $scraperService;

    /**
     * @var SiteService
     */
    private $siteService;

    /**
     * @var LogoService
     */
    private $logoService;

    /**
     * @var LinkService
     */
    private $linkService;

    /**
     * @var
     */
    private $count;

    /**
     * @var
     */
    private $selectData;

    /**
     * @var BookmarkRepository
     */
    private $bookmarkRepository;

    /**
     * BookmarkService constructor.
     * @param ScraperService $scraperService
     * @param SiteService $siteService
     * @param LogoService $logoService
     * @param LinkService $linkService
     */
    public function __construct(
        ScraperService $scraperService,
        SiteService $siteService,
        LogoService $logoService,
        LinkService $linkService,
        BookmarkRepository $bookmarkRepository
    )
    {
        $this->scraperService = $scraperService;
        $this->siteService = $siteService;
        $this->logoService = $logoService;
        $this->linkService = $linkService;
        $this->count = 0;
        $this->selectData = '';
        $this->bookmarkRepository = $bookmarkRepository;
    }

    /**
     * @param array $data
     */
    public function updateSite(array $data)
    {
        $linkDatas = Link::whereUrl($data['domain'])->get();

        if (isset($linkDatas)) {
            foreach ($linkDatas as $linkData) {
                $data['site_id'] = $linkData->site_id;
                $data['link_id'] = $linkData->id;
            }
        }

        if (isset($data['site_id'])) {
            //update site
            $siteId = $this->siteService->updateSite($data, false)[0];
        } else {
            //create SITE
            $site = $this->siteService->updateSite($data, true);

            $data['site_id'] = $site[0];

            //create the site LINK
            if ($site[1] === 1) {
                $data['scraped_url_data'] = $this->scraperService->processUrl($data['domain']);
            }

            $oldUrl = $data['url'];
            $data['url'] = $data['domain'];
            $linkData = $this->updateLink($data, true);
            //update the LINK ID in SITEs table
            $data['link_id'] = $linkData['link_id'];
            $siteId = $this->siteService->updateSite($data, false)[0];
            $data['url'] = $oldUrl;
        }

        return $siteId;
    }

    /**
     * @param array $data
     * @param bool $createSite
     * @return array|\Illuminate\Support\MessageBag|int
     */
    public function updateLink(array $data, bool $createSite = false)
    {
        //check if Link exists
        /** @var Link $link */
        $link = Link::whereUrl($data['url'])->first();

        if (isset($link)) {
            //update link if older than 7 days

            $site = $link->site()->get();
            $is_scrape = false;
            foreach ($site as $st) {
                $is_scrape = $st->perform_scrape === 1;
            }

            if (($link->updated_at < date_create('-7 days') || $link->title === 'Untitled' || $link->meta_description === null || $link->og_image === null) && $is_scrape) {
                $data['scraped_url_data'] = $this->scraperService->processUrl($data['url']);
                $data['title'] = $data['title'] ?? $data['scraped_url_data']['title'];
                $data['title'] = $data['title'] ?? $data['scraped_url_data']['og_title'];

                if (isset($data['scraped_url_data']['description'])) {
                    $data['description'] = $data['description'] ?? $data['scraped_url_data']['description'];
                }

                $data['title'] = $data['title'] ?? $data['scraped_url_data']['og_desc'];
                $data['link_id'] = $this->linkService->updateLink($data, $link);
            } else {
                $data['link_id'] = $link->id;
                $data['title'] = $link->title;
                $data['description'] = $link->meta_description;
            }

        } else {
            //create link

            if (stripos($data['url'], 'twitter.com') === false && stripos($data['url'], 'facebook.com') === false) {
                $data['scraped_url_data'] = $this->scraperService->processUrl($data['url']);

                if (!isset($data['title']) && !isset($data['scraped_url_data']['og_title'])) {
                    $data['title'] = 'Untitled';
                } elseif (!isset($data['title'])) {
                    $data['title'] = 'Untitled';
                }

                if (!isset($data['description']) && !isset($data['scraped_url_data']['description'])) {
                    $data['description'] = null;
                }

                $data['title'] = $data['title'] ?? $data['scraped_url_data']['title'];
                $data['title'] = $data['title'] ?? $data['scraped_url_data']['og_title'];

                if (isset($data['scraped_url_data']['description'])) {
                    $data['description'] = $data['description'] ?? $data['scraped_url_data']['description'];
                }

                $data['title'] = $data['title'] ?? $data['scraped_url_data']['og_desc'];
            } else {

                if (!isset($data['title']) && !isset($data['scraped_url_data']['og_title'])) {
                    $data['title'] = 'Untitled';
                } elseif (!isset($data['title'])) {
                    $data['title'] = 'Untitled';
                }

                if (!isset($data['description']) && !isset($data['scraped_url_data']['description'])) {
                    $data['description'] = null;
                }

                if (!isset($data['description'])) {
                    $data['description'] = null;
                }

            }

            $data['link_id'] = $this->linkService->updateLink($data, null, $createSite);
        }
        return $data;
    }

    /**
     * @param array $data
     * @param bool $create
     * @throws GuzzleException
     */
    public function updateBookmark(array $data, bool $create = true)
    {
        try {
            DB::beginTransaction();

            // fetch just the domain
            $data['domain'] = $this->logoService->getHost($data['url']);
            $data['user_id'] = auth()->id();

            $data['site_id'] = $this->updateSite($data);

            if (!is_int($data['site_id'])) {
                return $data['site_id'];
            }

            $linkData = $this->updateLink($data);

            if (!is_int($linkData['link_id'])) {
                return $linkData['link_id'];
            } else {
                $data['link_id'] = $linkData['link_id'];
            }

            $messages = [
                'title.required' => 'Title is required.',
                'title.min' => 'Title must be at least 3 characters.',
                'title.max' => "Title can't be more than 512 characters.",
                'url.required' => 'URL is required.',
                'url.min' => 'URL must be at least 5 characters.',
                'url.unique' => 'This URL has already been saved before.',
                'sort_order.numeric' => 'Sort order must be numeric.'
            ];

            if (isset($data['categories'])) {
                if (is_int($data['categories'])) {
                    $data['categories'] = [$data['categories']];
                }
            } else {
                $data['categories'] = [];
            }

            if (isset($data['tags'])) {
                if (is_int($data['tags'])) {
                    $data['tags'] = [$data['tags']];
                }
            } else {
                $data['tags'] = [];
            }

            $validation = [
                'title' => 'bail|string|min:3|max:512',
                'url' => 'required|string|min:5|max:2000',
                'sort_order' => 'nullable|numeric',
                'user_id' => 'required|integer',
                'categories' => 'array',
                'tags' => 'array',
                'visibility' => 'string'
            ];

            if ($create) {
                $validation['link_id'] = 'required|integer';
            }

            if (!isset($data['title'])) {
                if (isset($linkData['scraped_url_data']['title'])) {
                    $data['title'] = $linkData['scraped_url_data']['title'];
                } else {
                    $data['title'] = $linkData['title'];
                }
            }

            if (!isset($data['description']) && $create) {
                if (isset($linkData['scraped_url_data']['description'])) {
                    $data['description'] = $linkData['scraped_url_data']['description'];
                } elseif (isset($linkData['description'])) {
                    $data['description'] = $linkData['description'];
                } else {
                    $data['description'] = null;
                }
            }

            if (!empty($data['description'])) {
                $validation['description'] = 'string|max:2000';
            }

            $validator = Validator::make($data, $validation, $messages);

            if ($validator->fails()) {
                return $validator->errors();
            }

            if ($create) {
                //create bookmark
                /** @var Bookmark $bookmark */
                $bookmark = Bookmark::create($validator->validated());
            } else {
                //UPDATING THE BOOKMARK
                /** @var Bookmark $bookmark */
                $bookmark = Bookmark::whereId($data['id'])->first();
                $validated = $validator->validated();
                unset($validated['url']);
                unset($validated['tags']);
                unset($validated['categories']);
                $bookmark->update($validated);
            }

            $currentTags = $bookmark->tags()->get();
            $currentTag = [];

            //old tags
            foreach ($currentTags as $tag) {
                $currentTag[] = $tag->id;
            }

            if (isset($data['tags'])) {

                //new tags
                $newTags = $data['tags'];

                // check if we have old tags
                if (count($currentTag) > 0) {

                    // what are the old tags
                    $difference = array_diff($currentTag, $newTags);

                    $bookmark->tags()->detach($difference);

                    // what are the new tags

                    $difference = array_diff($newTags, $currentTag);

                    $bookmark->tags()->attach($difference);
                } else {
                    // no old tags, just whack in the new ones
                    $bookmark->tags()->attach($newTags);
                }
            } elseif (isset($currentTag)) {
                $bookmark->tags()->detach($currentTag);
            }

            $currentCategories = $bookmark->categories()->get();
            $currentCats = [];

            foreach ($currentCategories as $cat) {
                $currentCats[] = $cat->id;
            }

            if (isset($data['categories'])) {

                //new categories
                $newCategories = $data['categories'];

                // check if we have old categories
                if (count($currentCats) > 0) {

                    // what are the old categories
                    $difference = array_diff($currentCats, $newCategories);

                    $bookmark->categories()->detach($difference);

                    // what are the new categories

                    $difference = array_diff($newCategories, $currentCats);

                    $bookmark->categories()->attach($difference);
                } else {
                    // no old categories, just whack in the new ones
                    $bookmark->categories()->attach($newCategories);
                }
            } elseif (isset($currentCats)) {
                $bookmark->categories()->detach($currentCats);
            }

            DB::commit();

            return $bookmark;
        } catch (\Exception $e) {
            //log error and return
            DB::rollBack();
            return [$e->getMessage()];
        }
    }

    public function parseTree($tree, $root = null)
    {
        $return = array();
        # Traverse the tree and search for direct children of the root
        foreach ($tree as $child => $parent) {
            # A direct child is found
            if ($parent == $root) {
                # Remove item from tree (we don't need to traverse this again)
                unset($tree[$child]);
                # Append the child into result array and parse its children
                $return[] = array(
                    'name' => $child,
                    'children' => $this->parseTree($tree, $child)
                );
            }
        }
        return empty($return) ? null : $return;
    }

    public function printSelectTree($tree, $category_names_tree, $username = '', bool $createBookmark = false)
    {
        if (!is_null($tree) && count($tree) > 0) {
            $this->count++;
            foreach ($tree as $node) {
                $spacing = '';
                for ($x = 0; $x < $this->count; $x++) {
                    $thespace = $this->count == 1 ? "&#160&#160&#160" : "&#8618;&#160";
                    $spacing .= $thespace;
                    //echo $spacing;
                }
                if ($createBookmark) {
                    $this->selectData .= '<div class="item" data-value="' . $node['name'] . '">' .
                        '' . $spacing . $category_names_tree[$node["name"]] . '</div>';
                }
                elseif (empty($username)) {
                    $this->selectData .= '<div class="item" data-value="/categories/' . $node['name'] . '/0/show">' .
                    '' . $spacing . $category_names_tree[$node["name"]] . '</div>';

                } else {
                    $this->selectData .= '<div class="item" data-value="/public/' . $username . '/' . $node["name"] . '/0">' .
                        '' . $spacing . $category_names_tree[$node["name"]] . '</div>';
                }
                $this->printSelectTree($node['children'], $category_names_tree, '', $createBookmark);
            }
            $this->count--;
        }
    }

    /**
     * @return mixed
     */
    public function getSelectData()
    {
        return $this->selectData;
    }

    /**
     * @param Bookmark $bookmarks
     * @return string
     */
    public function getMediaData(Collection $bookmarks, array $data): string
    {
        $show_trash = $data['status'] === ['trash'] ? 'trash' : 'live';
        $output = '';
        foreach ($bookmarks as $bookmark) {
            $site_logo = strip_tags($bookmark->link->site->logo);
            $image = strip_tags($bookmark->link->og_image) ?? strip_tags($site_logo);
            $alt_text = strip_tags($bookmark->link->site->rootLink->title);
            $created_at = date_format($bookmark->created_at, "d-M-Y H:i");
            $og_title = strip_tags($bookmark->link->og_title) ?? strip_tags($bookmark->link->title);
            $og_desc = strip_tags($bookmark->link->og_description) ?? strip_tags($bookmark->link->meta_description);
            $your_title = strip_tags($bookmark->title) ?? strip_tags($bookmark->link->og_title);
            $your_desc = strip_tags($bookmark->description) ?? strip_tags($bookmark->link->meta_description);
            $url = strip_tags($bookmark->link->url);
            $short_title = strlen($your_title) > 55 ? substr($your_title, 0, 55) . '...' : $your_title;
            $short_desc = strlen($your_desc) > 90 ? substr($your_desc, 0, 90) . '...' : $your_desc;
            $short_og_title = strlen($og_title) > 55 ? substr($og_title, 0, 55) . '...' : $og_title;
            $short_og_desc = strlen($og_desc) > 90 ? substr($og_desc, 0, 90) . '...' : $og_desc;

            $editUrl = '/bookmarks';

            $output .= '<div class="mdc-card demo-card demo-basic-with-header">' .
                '<div class="demo-card__primary">' .
                '<p class="demo-card__title mdc-typography mdc-typography--headline6">';

            if (!empty($site_logo)) {
                $output .= '<span class="logo" title="' . $alt_text . '"><img src="' . $site_logo . '" alt="' . $alt_text . '" /></span>';
                }

            $output .= '<span title="' . $your_title . '">' . $short_title . '</span></p>' .
                '<p class="demo-card__subtitle mdc-typography mdc-typography--subtitle2" title="' . $your_desc . '">' . $short_desc . '</p>' .
                '</div>' .
                '<div id="card_click" class="mdc-card__primary-action demo-card__primary-action" tabindex="0">' .
                '<div class="mdc-card__media mdc-card__media--16-9 demo-card__media" style="background-image: url(' . $image . ');" title="' . $your_title . '"></div>' .
                '<div class="demo-card__secondary mdc-typography mdc-typography--body2">' . $short_og_title . '' .
                '<p class="show_more">' . $short_og_desc . '</p>' .
                '</div>' .
                '</div>' .
                '<div class="mdc-card__actions">' .
                ' <div class="mdc-card__action-buttons">' .
                '     <button class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored" title="Visit Bookmark" onclick="openInNewTab(\'' . $url . '\');"><span class="mdc-button__ripple"></span>Visit</button>' .
                '     &nbsp;&nbsp;<button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent copy-button" data-clipboard-text="' . $url . '" title="Copy URL to clipboard"><span class="mdc-button__ripple"></span>Copy</button>' .
                '  </div>';

//                ' <div class="mdc-card__action-icons">' .
//                '    <button id="toggle_show" class="mdc-icon-button material-icons mdc-card__action mdc-card__action--icon--unbounded" title="Reveal" data-mdc-ripple-is-unbounded="true">' .
//                '      <i id="expand_more" class="material-icons mdc-icon-button__icon expand_more">expand_more</i>' .
//                '      <i id="expand_less" class="material-icons mdc-icon-button__icon expand_less mdc-icon-button__icon--on">expand_less</i>' .
//                '  </button>' .
//                '  <button id="favourites" class="mdc-icon-button material-icons mdc-card__action mdc-card__action--icon--unbounded" title="Add to Home page" data-mdc-ripple-is-unbounded="true">' .
//                '      <i id="favourite_border" class="material-icons mdc-icon-button__icon favorite_border">favorite_border</i>' .
//                '      <i id="favourite" 		 class="material-icons mdc-icon-button__icon favorite mdc-icon-button__icon--on">favorite</i>' .
//                '  </button>' .
//                '  <button id="share-button" class="mdc-icon-button material-icons mdc-card__action mdc-card__action--icon--unbounded" title="Share" data-mdc-ripple-is-unbounded="true">share</button>' .
//                '  <ul class="mdl-menu mdl-js-menu mdl-menu--top-right mdl-js-ripple-effect" for="share-button">' .
//                '      <li class="mdl-menu__item"><a href="#" title="Share via Email">Email</a></li>' .
//                '      <li class="mdl-menu__item"><a href="#" title="Post about this bookmark on Facebook">Facebook</a></li>' .
//                '     <li class="mdl-menu__item"><a href="#" title="Tweet this bookmark">Twitter</a></li>' .
//                ' </ul>' .

            if ($bookmark->user_id === auth()->id()) {
                $output .= '&nbsp;&nbsp;<button id="edit" class="mdc-icon-button material-icons mdc-card__action mdc-card__action--icon--unbounded" onclick="location.href=\'' . $editUrl . '/' . $bookmark->id . '/edit\'" title="Edit" data-mdc-ripple-is-unbounded="true">' .
                    '     <i class="material-icons mdc-icon-button__icon">create</i>' .
                    ' </button>' .
                    ' <button id="delete" class="mdc-icon-button material-icons mdc-card__action mdc-card__action--icon--unbounded" onclick="location.href=\'' . $editUrl . '/' . $bookmark->id . '/delete\'" title="Delete" data-mdc-ripple-is-unbounded="true">' .
                    '     <i class="material-icons mdc-icon-button__icon">delete</i>' .
                    ' </button>';
            }

//                ' <button id="edit-button" class="mdc-icon-button material-icons mdc-card__action mdc-card__action--icon--unbounded" title="More options" data-mdc-ripple-is-unbounded="true">more_vert</button>' .
//                '  <ul class="mdl-menu mdl-js-menu mdl-menu--top-right mdl-js-ripple-effect" for="edit-button">' .
//                '       <li class="mdl-menu__item"><a href="#">Set Order</a></li>' .
//                '        <li class="mdl-menu__item"><a href="#">Edit</a></li>' .
//                '         <li class="mdl-menu__item"><a href="#">Delete</a></li>' .
//                '      </ul>' .

            $output .= '   </div>' .
                '   </div>' .
                '</div>';
        }
        return $output;
    }

    /**
     * @param int|null $userId
     * @param array|string[] $status
     * @return mixed
     */
    public function fetchBookmarks(int $userId = null, array $data)
    {
        if (isset($data['search_query'])) {
            return $this->bookmarkRepository->searchBookmarks($userId, $data);
        } else {
            return $this->bookmarkRepository->fetchBookmarks($userId, $data);
        }
    }

}
