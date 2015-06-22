<?php defined('C5_EXECUTE') or die('Access Denied');

$ps = Core::make('helper/form/page_selector');
?>
<div class="row">
    <div class="col-md-8">
        <?php
        echo $ps->selectPage('menu_cID');
        ?>
    </div>
    <div class="col-md-4">
        <a href="#" style="margin-top: 10px;" class="btn btn-primary" onclick="window.location='<?php echo $this->action('add') . '/\'+$(\'[name=menu_cID]\').val()+\'' . '/' . Core::make('helper/validation/token')->generate('add')?>'"><?php echo t('Add Menu Item')?></a>
    </div>
</div>

<div class="row">
    <?php if (count($cIDs) > 0) {
    ?>
        <div class="col-md-8">
            <table class="table table-striped">
                <?php foreach ($cIDs as $cID) {
    $page = \Page::getByID($cID);
    if (is_object($page) && !$page->isError()) {
        $name = h(t($page->getCollectionName()));
    } else {
        $name = t('Unknown Page');
    }
    ?>
                    <tr data-cID="<?php echo $cID?>">
                        <td><a target="_blank" href="<?php echo $page->getCollectionLink()?>"><?php echo $name?></a></td>
                        <td><a class="btn btn-danger" href="<?php echo $this->action('delete', $cID, Core::make('helper/validation/token')->generate('delete'))?>"><?php echo t('Delete')?></a></td>
                        <td style="text-align:right"><i style="cursor: move" class="fa fa-arrows"></i></td>
                    </tr>
                    <?php

}
    ?>
            </table>
        </div>
        <script type="text/javascript">
            (function($,location){
                'use strict';
                $(function(){
                    var sortableTable = $('table.table tbody');
                    sortableTable.sortable({
                        handle: 'i.fa-arrows',
                        helper: function(e, ui) {
                            ui.children().each(function() {
                                var me = $(this);
                                me.width(me.width());
                            });
                            return ui;
                        },
                        cursor: 'move',
                        stop: function(e, ui) {
                            var order = [];
                            sortableTable.children().each(function() {
                                var me = $(this);
                                order.push(me.attr('data-cID'));
                            });
                            $.post('<?=$view->action('reorder')?>', {order: order});
                        }
                    });
                });
            })(jQuery, window.location);
        </script>

    <?php 
} else {
    ?>
        <p><?php echo t("You have not added any menu items.")?></p>
    <?php 
} ?>
</div>