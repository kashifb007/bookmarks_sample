<?php
/**
 * Class CategoryService
 * @package App\Services
 * @author Kashif <kash@dreamsites.co.uk>
 * @created 06/10/2020
 */

namespace App\Services;

use App\Repositories\CategoryRepository;

class CategoryService
{

    /**
     * @var CategoryRepository
     */
    private $categoryRepository;

    /**
     * CategoryService constructor.
     * @param CategoryRepository $categoryRepository
     */
    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * @param int|null $userId
     * @param array|string[] $status
     */
    public function fetchCategories(int $userId = null, array $status = ['live'])
    {
        return $this->categoryRepository->fetchCategories($userId, $status);
    }

}
