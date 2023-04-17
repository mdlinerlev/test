<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true);
?>
<? if ($arResult['ITEMS']): ?>
    <div class="queries-list" style="display:none;">
        <ul class="queries-list__list  js-queries-list  js-class">
            <? foreach ($arResult['ITEMS'] as $Item): ?>
                <? if ($Item['TITLE'] && $Item['URL']): ?>
                    <li>
                        <a title="<?= $Item['TITLE'] ?>" href="<?= $Item['URL'] ?>">
                            <?= $Item['TITLE'] ?>
                        </a>
                    </li>
                <? endif; ?>
            <? endforeach; ?>
            <li class="queries-list__toggler  js-class__toggler">
                <span>Еще...</span>
                <span>Скрыть...</span>
            </li>
        </ul>
    </div>
<? endif; ?>