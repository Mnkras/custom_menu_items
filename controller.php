<?php
namespace Concrete\Package\CustomMenuItems;

defined('C5_EXECUTE') or die("Access Denied.");

/**
 *
 * Custom Menu Items Package.
 * @author Michael Krasnow <mnkras@gmail.com>
 *
 */

class Controller extends \Concrete\Core\Package\Package
{

    protected $pkgHandle = 'custom_menu_items';
    protected $appVersionRequired = '5.7.0.4';
    protected $pkgVersion = '0.9';

    public function getPackageDescription()
    {
        return t("Create menu items for any page on your site.");
    }

    public function getPackageName()
    {
        return t("Custom Menu Items");
    }

    public function install()
    {
        $pkg = parent::install();
        $sp = \SinglePage::add('/dashboard/system/basics/custom_menu_items', $pkg);
        if (is_object($sp)) {
            $sp->update(array('cName'=>t('Custom Menu Items')));
        }
    }

    public function on_start()
    {
        $u = new \User();
        if ($u->isLoggedIn()) {
            $db = \Loader::db();
            $r = $db->Execute('SELECT * FROM pkgCustomMenuItems ORDER BY DisplayOrder');
            while ($row = $r->fetchRow()) {
                /** @var $menu \Concrete\Core\Application\Service\UserInterface\Menu **/
                $menu = \Core::make('helper/concrete/ui/menu');
                $menu->addPageHeaderMenuItem(
                    'custom_menu_item',
                    'custom_menu_items',
                    array(
                        'href' => $row['cID'],
                        'position' => 'left'
                    )
                );
            }
        }
    }
}
