<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @copyright   Copyright (c) 2014 X.commerce, Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

namespace Magento\Theme\Block\Html;

/**
 * Html page top menu block
 */
class Topmenu extends \Magento\View\Element\Template
{
    /**
     * Init top menu tree structure
     */
    protected function _construct()
    {
        $this->addData(array(
            'cache_lifetime'    => false,
            'cache_tags'        => array(
                \Magento\Catalog\Model\Category::CACHE_TAG,
                \Magento\Core\Model\Store\Group::CACHE_TAG
            ),
        ));
    }

    /**
     * Recursively prepare top menu data that is specified in $menuTree
     *
     * @param \Magento\Data\Tree\Node $menuTree
     */
    protected function _prepareTree(\Magento\Data\Tree\Node $menuTree)
    {
        $children = $menuTree->getChildren();
        $parentLevel = $menuTree->getLevel();
        $childLevel = is_null($parentLevel) ? 0 : $parentLevel + 1;

        $position = 1;
        $childrenCount = $children->count();

        foreach ($children as $child) {
            $child->setPosition($position);
            $child->setLevel($childLevel);
            $child->setIsFirst($position == 1);
            $child->setIsLast($position == $childrenCount);
            $position++;

            $this->_prepareTree($child);
        }
    }

    /**
     * Returns tree of menu
     *
     * @return \Magento\Data\Tree\Node
     */
    public function getTreeMenu()
    {
        $menu = new \Magento\Data\Tree\Node(array(), 'root', new \Magento\Data\Tree());
        $this->_eventManager->dispatch('page_block_html_topmenu_gethtml_before', array('menu' => $menu));
        $this->_prepareTree($menu);
        $this->_eventManager->dispatch('page_block_html_topmenu_gethtml_after', array('menu' => $menu));
        return $menu;
    }

}
