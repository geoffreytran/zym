<?php
namespace Zym\Bundle\ThemeBundle\Entity;

use Zym\Bundle\FrameworkBundle\Entity\AbstractEntityManager;
use Knp\Component\Pager\Paginator;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * Theme Rule Manager
 *
 * @author    Geoffrey Tran
 * @copyright Copyright (c) 2011 RAPP. (http://www.rapp.com/)
 */
class ThemeRuleManager extends AbstractEntityManager
{
    /**
     * Repository
     *
     * @var ThemeRuleRepository
     */
    protected $repository;

    /*
     * Create an theme rule
     *
     * @param ThemeRule $themeRule
     * @return ThemeRule
     */
    public function createThemeRule(ThemeRule $themeRule)
    {
        parent::createEntity($themeRule);
        return $themeRule;
    }

    /**
     * Delete an theme rule
     *
     * @param ThemeRule $themeRule
     */
    public function deleteThemeRule(ThemeRule $themeRule)
    {
        parent::deleteEntity($themeRule);
    }

    /**
     * Save an theme rule
     *
     * @param ThemeRule $themeRule
     * @param boolean $andFlush
     */
    public function saveThemeRule(ThemeRule $themeRule, $andFlush = true)
    {
        $this->saveEntity($themeRule, $andFlush);
    }

    /**
     * Find an theme rule
     *
     * @param array $criteria
     * @param integer $page
     * @param integer $limit
     * @param array $orderBy
     * @return PaginationInterface
     */
    public function findThemeRules(array $criteria = null, $page = 1, $limit = 50, array $orderBy = null)
    {
        return $this->repository->findThemeRules($criteria, $page, $limit, $orderBy);
    }

    /**
     * Find an theme rule by criteria
     *
     * @param array $criteria
     * @return ThemeRule
     */
    public function findThemeRuleBy(array $criteria)
    {
        return $this->repository->findOneBy($criteria);
    }

    /**
     * Get the theme rules
     *
     * @return array Array of Zym\Bundle\ThemeBundle\Entity\ThemeRule
     */
    public function getRules()
    {
        return $this->repository->findAll();
    }
}