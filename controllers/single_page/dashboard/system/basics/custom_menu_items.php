<?php
namespace Concrete\Package\CustomMenuItems\Controller\SinglePage\Dashboard\System\Basics;

use \Concrete\Core\Page\Controller\DashboardPageController;

class CustomMenuItems extends DashboardPageController
{
    public function view($message = false)
    {
        if ($message) {
            switch($message) {
                case 'deleted':
                    $this->set('message', t('Menu Item Deleted'));
                    break;

                case 'added':
                    $this->set('message', t('Menu Item Added'));
                    break;

                case 'no':
                    $this->set('message', t('Invalid Page'));
                    break;

                case 'exists':
                    $this->set('message', t('This page is already in the menu!'));
                    break;
            }
        }
        $db = \Loader::db();
        $r = $db->Execute('SELECT * FROM pkgCustomMenuItems ORDER BY DisplayOrder');
        $cIDs = array();
        while ($row = $r->fetchRow()) {
            $cIDs[] = $row['cID'];
        }
        $this->set('cIDs', $cIDs);
    }

    public function delete($cID = false, $toke = false)
    {
        if (!$this->token->validate('delete', $toke)) {
            $this->redirect('/dashboard/system/basics/custom_menu_items');
        }
        $db = \Loader::db();
        $db->Execute('DELETE FROM pkgCustomMenuItems WHERE cID = ?', array($cID));
        $this->redirect('/dashboard/system/basics/custom_menu_items/deleted');
    }

    public function add($cID = false, $toke = false)
    {
        if (!$this->token->validate('add', $toke)) {
            $this->redirect('/dashboard/system/basics/custom_menu_items');
        }
        $page = \Page::getByID($cID);
        if (!is_object($page) || $page->isError()) {
            $this->redirect('/dashboard/system/basics/custom_menu_items/no');
        }
        $db = \Loader::db();
        $exists = $db->getOne('SELECT cID FROM pkgCustomMenuItems WHERE cID = ?', array($cID));
        if ($exists) {
            $this->redirect('/dashboard/system/basics/custom_menu_items/exists');
        }
        $order = $db->GetOne('SELECT count(cID) FROM pkgCustomMenuItems');
        $db->Execute('INSERT into pkgCustomMenuItems (DisplayOrder,cID) VALUES (?,?)', array($order,$cID));
        $this->redirect('/dashboard/system/basics/custom_menu_items/added');
    }

    public function reorder()
    {
        if (!isset($_POST['order']) || !is_array($_POST['order'])) {
            $this->redirect('/dashboard/system/basics/custom_menu_items');
        }
        $order = $this->post('order');
        $l = count($order);
        for ($i = 0; $i < $l; $i++) {
            try {
                $db = \Loader::db();
                $db->Execute(
                    'UPDATE pkgCustomMenuItems SET DisplayOrder=? WHERE cID=?',
                    array($i, $order[$i])
                );
            } catch (\Exception $e) {
            }
        }
        exit;

    }
}