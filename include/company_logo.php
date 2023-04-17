<? if ($curPage != SITE_DIR . "index.php"){ ?>
    <a class="header-logo__link" href="<?= SITE_DIR ?>">
        <img class="header-logo__image" src="<?=SITE_TEMPLATE_PATH?>/img/logo.png"/>
    </a>
<?}else{?>
  <img class="header-logo__image" src="<?=SITE_TEMPLATE_PATH?>/img/logo.png"/>
        
<?}?>