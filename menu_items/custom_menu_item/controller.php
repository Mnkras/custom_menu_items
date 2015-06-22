<?php

namespace Concrete\Package\CustomMenuItems\MenuItem\CustomMenuItem;

use HtmlObject\Link;

class Controller extends \Concrete\Core\Application\UserInterface\Menu\Item\Controller
{
    public $menuItem;

    public function getMenuItemLinkElement()
    {
        $a = new Link();

        $page = \Page::getByID($this->menuItem->getLink());

        $a->setValue(h(t($page->getCollectionName())));
        $a->href($page->getCollectionLink());

        $a->style('line-height: 14px;padding-top: 16px;width: '.(strlen($page->getCollectionName()) * 6.5 + 30).'px;');

        return $a;
    }

    public function displayItem()
    {
        $page = \Page::getByID($this->menuItem->getLink());
        if (is_object($page) && !$page->isError()) {
            $tcp = new \Permissions($page);
            if ($tcp->canRead()) {
                return true;
            }
        }

        return false;
    }
}
