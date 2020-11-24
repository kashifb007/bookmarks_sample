<?php
/**
 * Class TagService
 * @package App\Services
 * @author Kashif <kash@dreamsites.co.uk>
 * @created 06/10/2020
 */

namespace App\Services;

use App\Repositories\TagRepository;

class TagService
{
    /**
     * @var TagRepository
     */
    private $tagRepository;


    public function __construct(TagRepository $tagRepository)
    {
        $this->tagRepository = $tagRepository;
    }

    /**
     * @param int|null $userId
     * @param array|string[] $status
     * @return mixed
     */
    public function fetchBookmarks(int $userId = null, array $data)
    {
        return $this->tagRepository->searchTagBookmarks($userId, $data);
    }
}
