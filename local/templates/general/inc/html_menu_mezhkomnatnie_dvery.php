<div class="header-catalog-menu__container header-catalog-menu__container--level2">
    <ul class="header-catalog-menu__list header-catalog-menu__list--level2">
        <li class="header-catalog-menu__item header-catalog-menu__item--level2 header-catalog-menu__item--doors-item">
            <div style="background-image:url(<?= SITE_TEMPLATE_PATH ?>/assets/uploads/header-catalog/12.jpg)"
                 class="header-catalog-menu__image header-catalog-menu__image--top">
            </div>
            <span class="header-catalog-menu__link header-catalog-menu__link--level2 header-catalog-menu__link--doors-item">По материалу
                          </span>
            <div class="header-catalog-menu__container header-catalog-menu__container--level3">
                <ul class="header-catalog-menu__list header-catalog-menu__list--level3">
                    <li class="header-catalog-menu__item header-catalog-menu__item--level3">
                        <a href="<?= SITE_DIR ?>catalog/mezhkomnatnye_dveri/shpon/"
                           class="header-catalog-menu__link header-catalog-menu__link--level3">Шпон
                        </a>
                    </li>
                    <li class="header-catalog-menu__item header-catalog-menu__item--level3">
                        <a href="<?= SITE_DIR ?>catalog/mezhkomnatnye_dveri/ekoshpon/"
                           class="header-catalog-menu__link header-catalog-menu__link--level3">Экошпон
                        </a>
                    </li>
                    <li class="header-catalog-menu__item header-catalog-menu__item--level3">
                        <a href="<?= SITE_DIR ?>catalog/mezhkomnatnye_dveri/dveri-mdf/"
                           class="header-catalog-menu__link header-catalog-menu__link--level3">Двери МДФ
                        </a>
                    </li>
                    <li class="header-catalog-menu__item header-catalog-menu__item--level3">
                        <a href="<?= SITE_DIR ?>catalog/mezhkomnatnye_dveri/steklyannye/"
                           class="header-catalog-menu__link header-catalog-menu__link--level3">Стеклянные
                        </a>
                    </li>
                    <!--<li class="header-catalog-menu__item header-catalog-menu__item--level3">
                                <a href="<?= SITE_DIR ?>catalog/mezhkomnatnye_dveri/laminirovannye/" class="header-catalog-menu__link header-catalog-menu__link--level3">Ламинированные
                                </a>
                              </li>-->
                    <li class="header-catalog-menu__item header-catalog-menu__item--level3">
                        <a href="<?= SITE_DIR ?>catalog/mezhkomnatnye_dveri/derevyannye/"
                           class="header-catalog-menu__link header-catalog-menu__link--level3">Деревянные
                        </a>
                    </li>
                    <li class="header-catalog-menu__item header-catalog-menu__item--level3">
                        <a href="<?= SITE_DIR ?>catalog/mezhkomnatnye_dveri/emal/"
                           class="header-catalog-menu__link header-catalog-menu__link--level3">Эмаль
                        </a>
                    </li>
                </ul>
            </div>
        </li>
        <li class="header-catalog-menu__item header-catalog-menu__item--level2 header-catalog-menu__item--doors-item">
            <div style="background-image:url(<?= SITE_TEMPLATE_PATH ?>/assets/uploads/header-catalog/13.jpg)"
                 class="header-catalog-menu__image header-catalog-menu__image--top">
            </div>
            <span class="header-catalog-menu__link header-catalog-menu__link--level2 header-catalog-menu__link--doors-item">По стилю
                          </span>
            <div class="header-catalog-menu__container header-catalog-menu__container--level3">
                <ul class="header-catalog-menu__list header-catalog-menu__list--level3">
                    <li class="header-catalog-menu__item header-catalog-menu__item--level3">
                        <a href="<?= SITE_DIR ?>catalog/mezhkomnatnye_dveri/klassika/"
                           class="header-catalog-menu__link header-catalog-menu__link--level3">Классика
                        </a>
                    </li>
                    <li class="header-catalog-menu__item header-catalog-menu__item--level3">
                        <a href="<?= SITE_DIR ?>catalog/mezhkomnatnye_dveri/modern/"
                           class="header-catalog-menu__link header-catalog-menu__link--level3">Модерн
                        </a>
                    </li>
                    <li class="header-catalog-menu__item header-catalog-menu__item--level3">
                        <a href="<?= SITE_DIR ?>catalog/mezhkomnatnye_dveri/khaytek/"
                           class="header-catalog-menu__link header-catalog-menu__link--level3">Хайтек
                        </a>
                    </li>
                    <li class="header-catalog-menu__item header-catalog-menu__item--level3">
                        <a href="<?= SITE_DIR ?>catalog/mezhkomnatnye_dveri/provans/"
                           class="header-catalog-menu__link header-catalog-menu__link--level3">Прованс
                        </a>
                    </li>
                    <li class="header-catalog-menu__item header-catalog-menu__item--level3">
                        <a href="<?= SITE_DIR ?>catalog/mezhkomnatnye_dveri/skandinavskie-dveri/"
                           class="header-catalog-menu__link header-catalog-menu__link--level3">Скандинавские двери
                        </a>
                    </li>
                </ul>
            </div>
        </li>
        <? if ($USER->isAdmin()) { ?>
            <li class="header-catalog-menu__item header-catalog-menu__item--level2 header-catalog-menu__item--doors-item">
                <div style="background-image:url(<?= SITE_TEMPLATE_PATH ?>/assets/uploads/header-catalog/14.jpg)"
                     class="header-catalog-menu__image header-catalog-menu__image--top">
                </div>
                <span class="header-catalog-menu__link header-catalog-menu__link--level2 header-catalog-menu__link--doors-item">Колекция
                          </span>
                <div class="header-catalog-menu__container header-catalog-menu__container--level3">
                    <ul class="header-catalog-menu__list header-catalog-menu__list--level3">
                        <li class="header-catalog-menu__item header-catalog-menu__item--level3">
                            <a href="<?= SITE_DIR ?>catalog/kolektsiya/klassicheskie-dveri/sotby/"
                               class="header-catalog-menu__link header-catalog-menu__link--level3">SOTBY
                            </a>
                        </li>
                        <li class="header-catalog-menu__item header-catalog-menu__item--level3">
                            <a href="<?= SITE_DIR ?>catalog/kolektsiya/klassicheskie-dveri/grand/"
                               class="header-catalog-menu__link header-catalog-menu__link--level3">Гранд
                            </a>
                        </li>
                        <li class="header-catalog-menu__item header-catalog-menu__item--level3">
                            <a href="<?= SITE_DIR ?>catalog/kolektsiya/klassicheskie-dveri/optima/"
                               class="header-catalog-menu__link header-catalog-menu__link--level3">Оптима
                            </a>
                        </li>
                        <li class="header-catalog-menu__item header-catalog-menu__item--level3">
                            <a href="<?= SITE_DIR ?>catalog/kolektsiya/klassicheskie-dveri/klassika-premium/"
                               class="header-catalog-menu__link header-catalog-menu__link--level3">Классика премиум
                            </a>
                        </li>
                        <li class="header-catalog-menu__item header-catalog-menu__item--level3">
                            <a href="<?= SITE_DIR ?>catalog/kolektsiya/sovremennye-dveri/narvika/"
                               class="header-catalog-menu__link header-catalog-menu__link--level3">NARVIKA
                            </a>
                        </li>
                        <li class="header-catalog-menu__item header-catalog-menu__item--level3">
                            <a href="<?= SITE_DIR ?>catalog/kolektsiya/sovremennye-dveri/maksimum/"
                               class="header-catalog-menu__link header-catalog-menu__link--level3">Максимум
                            </a>
                        </li>
                        <li class="header-catalog-menu__item header-catalog-menu__item--level3">
                            <a href="<?= SITE_DIR ?>catalog/kolektsiya/sovremennye-dveri/modern2/"
                               class="header-catalog-menu__link header-catalog-menu__link--level3">Модерн
                            </a>
                        </li>
                        <li class="header-catalog-menu__item header-catalog-menu__item--level3">
                            <a href="<?= SITE_DIR ?>catalog/kolektsiya/sovremennye-dveri/premium-ofis/"
                               class="header-catalog-menu__link header-catalog-menu__link--level3">Премиум офис
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
        <? } else { ?>

            <li class="header-catalog-menu__item header-catalog-menu__item--level2 header-catalog-menu__item--doors-item">
                <div style="background-image:url(<?= SITE_TEMPLATE_PATH ?>/assets/uploads/header-catalog/14.jpg)"
                     class="header-catalog-menu__image header-catalog-menu__image--top">
                </div>
                <span class="header-catalog-menu__link header-catalog-menu__link--level2 header-catalog-menu__link--doors-item">По цене
                          </span>
                <div class="header-catalog-menu__container header-catalog-menu__container--level3">
                    <ul class="header-catalog-menu__list header-catalog-menu__list--level3">
                        <li class="header-catalog-menu__item header-catalog-menu__item--level3">
                            <a href="<?= SITE_DIR ?>catalog/mezhkomnatnye_dveri/ot-50-do-500/"
                               class="header-catalog-menu__link header-catalog-menu__link--level3">От 50 до 150
                            </a>
                        </li>
                        <li class="header-catalog-menu__item header-catalog-menu__item--level3">
                            <a href="<?= SITE_DIR ?>catalog/mezhkomnatnye_dveri/ot-500-do-1000/"
                               class="header-catalog-menu__link header-catalog-menu__link--level3">От 150 до 250
                            </a>
                        </li>
                        <li class="header-catalog-menu__item header-catalog-menu__item--level3">
                            <a href="<?= SITE_DIR ?>catalog/mezhkomnatnye_dveri/ot-1000-do-1500/"
                               class="header-catalog-menu__link header-catalog-menu__link--level3">От 250 до 350
                            </a>
                        </li>
                        <li class="header-catalog-menu__item header-catalog-menu__item--level3">
                            <a href="<?= SITE_DIR ?>catalog/mezhkomnatnye_dveri/ot-1500-do-2000/"
                               class="header-catalog-menu__link header-catalog-menu__link--level3">От 350 и выше
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
        <? } ?>
        <li class="header-catalog-menu__item header-catalog-menu__item--level2 header-catalog-menu__item--doors-item">
            <div style="background-image:url(<?= SITE_TEMPLATE_PATH ?>/assets/uploads/header-catalog/15.jpg)"
                 class="header-catalog-menu__image header-catalog-menu__image--top">
            </div>
            <span class="header-catalog-menu__link header-catalog-menu__link--level2 header-catalog-menu__link--doors-item">По типу
                          </span>
            <div class="header-catalog-menu__container header-catalog-menu__container--level3">
                <ul class="header-catalog-menu__list header-catalog-menu__list--level3">
                    <li class="header-catalog-menu__item header-catalog-menu__item--level3">
                        <a href="<?= SITE_DIR ?>catalog/mezhkomnatnye_dveri/glukhaya/"
                           class="header-catalog-menu__link header-catalog-menu__link--level3">Глухая
                        </a>
                    </li>
                    <li class="header-catalog-menu__item header-catalog-menu__item--level3">
                        <a href="<?= SITE_DIR ?>catalog/mezhkomnatnye_dveri/so-steklom/"
                           class="header-catalog-menu__link header-catalog-menu__link--level3">Со стеклом
                        </a>
                    </li>
                    <li class="header-catalog-menu__item header-catalog-menu__item--level3">
                        <a href="<?= SITE_DIR ?>catalog/mezhkomnatnye_dveri/dveri-kupe/"
                           class="header-catalog-menu__link header-catalog-menu__link--level3">Двери купе
                        </a>
                    </li>
                    <li class="header-catalog-menu__item header-catalog-menu__item--level3">
                        <a href="<?= SITE_DIR ?>catalog/mezhkomnatnye_dveri/stroitelnye/"
                           class="header-catalog-menu__link header-catalog-menu__link--level3">Строительные
                        </a>
                    </li>
                </ul>
            </div>
        </li>
        <li class="header-catalog-menu__item header-catalog-menu__item--level2 header-catalog-menu__item--doors-item">
            <div style="background-image:url(<?= SITE_TEMPLATE_PATH ?>/assets/uploads/header-catalog/16.jpg)"
                 class="header-catalog-menu__image header-catalog-menu__image--top">
            </div>
            <span class="header-catalog-menu__link header-catalog-menu__link--level2 header-catalog-menu__link--doors-item">По классу
                          </span>
            <div class="header-catalog-menu__container header-catalog-menu__container--level3">
                <ul class="header-catalog-menu__list header-catalog-menu__list--level3">
                    <li class="header-catalog-menu__item header-catalog-menu__item--level3">
                        <a href="<?= SITE_DIR ?>catalog/mezhkomnatnye_dveri/ekonom/"
                           class="header-catalog-menu__link header-catalog-menu__link--level3">Эконом
                        </a>
                    </li>
                    <li class="header-catalog-menu__item header-catalog-menu__item--level3">
                        <a href="<?= SITE_DIR ?>catalog/mezhkomnatnye_dveri/premium/"
                           class="header-catalog-menu__link header-catalog-menu__link--level3">Премиум
                        </a>
                    </li>
                </ul>
            </div>
        </li>
        <li class="header-catalog-menu__item header-catalog-menu__item--level2 header-catalog-menu__item--doors-item  header-catalog-menu__item--splitable-doors"
            style="
position: absolute !important;
bottom: 137px;
right: 0;
border-top: 1px solid #ccc !important;
padding: 0 !important;">
            <a href="<?= SITE_DIR ?>planed/"
               class="header-catalog-menu__link header-catalog-menu__link--level2 header-catalog-menu__link--doors-item header-catalog-menu__link--splitable-doors"
            >Погонаж
            </a>
        </li>
        <li class="header-catalog-menu__item header-catalog-menu__item--level2 header-catalog-menu__item--doors-item header-catalog-menu__item--splitable-doors">
            <a href="<?= SITE_DIR ?>razdvizhnye-dveri/"
               class="header-catalog-menu__link header-catalog-menu__link--level2 header-catalog-menu__link--doors-item header-catalog-menu__link--splitable-doors">Раздвижные
                двери
            </a>
        </li>
        <li class="header-catalog-menu__item header-catalog-menu__item--level2 header-catalog-menu__item--doors-item header-catalog-menu__item--colors-item">
                          <span class="header-catalog-menu__link header-catalog-menu__link--level2 header-catalog-menu__link--doors-item header-catalog-menu__link--colors-item">По цвету
                          </span>
            <div class="header-catalog-menu__container header-catalog-menu__container--level3">
                <ul class="header-catalog-menu__list header-catalog-menu__list--level3">
                    <li class="header-catalog-menu__item header-catalog-menu__item--level3">
                        <a href="<?= SITE_DIR ?>catalog/mezhkomnatnye_dveri/belye/"
                           class="header-catalog-menu__link header-catalog-menu__link--level3">
                            <div style="background-image:url(<?= SITE_TEMPLATE_PATH ?>/assets/uploads/colors/1.jpg)"
                                 class="header-catalog-menu__color">
                            </div>
                            Белые
                        </a>
                    </li>
                    <li class="header-catalog-menu__item header-catalog-menu__item--level3">
                        <a href="<?= SITE_DIR ?>catalog/mezhkomnatnye_dveri/svetlye/"
                           class="header-catalog-menu__link header-catalog-menu__link--level3">
                            <div style="background-image:url(<?= SITE_TEMPLATE_PATH ?>/assets/uploads/colors/2.jpg)"
                                 class="header-catalog-menu__color">
                            </div>
                            Светлые
                        </a>
                    </li>
                    <li class="header-catalog-menu__item header-catalog-menu__item--level3">
                        <a href="<?= SITE_DIR ?>catalog/mezhkomnatnye_dveri/venge/"
                           class="header-catalog-menu__link header-catalog-menu__link--level3">
                            <div style="background-image:url(<?= SITE_TEMPLATE_PATH ?>/assets/uploads/colors/3.jpg)"
                                 class="header-catalog-menu__color">
                            </div>
                            Венге
                        </a>
                    </li>
                    <li class="header-catalog-menu__item header-catalog-menu__item--level3">
                        <a href="<?= SITE_DIR ?>catalog/mezhkomnatnye_dveri/orekh/"
                           class="header-catalog-menu__link header-catalog-menu__link--level3">
                            <div style="background-image:url(<?= SITE_TEMPLATE_PATH ?>/assets/uploads/colors/4.jpg)"
                                 class="header-catalog-menu__color">
                            </div>
                            Орех
                        </a>
                    </li>
                    <li class="header-catalog-menu__item header-catalog-menu__item--level3">
                        <a href="<?= SITE_DIR ?>catalog/mezhkomnatnye_dveri/korichnevye/"
                           class="header-catalog-menu__link header-catalog-menu__link--level3">
                            <div style="background-image:url(<?= SITE_TEMPLATE_PATH ?>/assets/uploads/colors/5.jpg)"
                                 class="header-catalog-menu__color">
                            </div>
                            Коричневые
                        </a>
                    </li>
                    <li class="header-catalog-menu__item header-catalog-menu__item--level3">
                        <a href="<?= SITE_DIR ?>catalog/mezhkomnatnye_dveri/tyemnye/"
                           class="header-catalog-menu__link header-catalog-menu__link--level3">
                            <div style="background-image:url(<?= SITE_TEMPLATE_PATH ?>/assets/uploads/colors/6.jpg)"
                                 class="header-catalog-menu__color">
                            </div>
                            Тёмные
                        </a>
                    </li>
                    <li class="header-catalog-menu__item header-catalog-menu__item--level3">
                        <a href="<?= SITE_DIR ?>catalog/mezhkomnatnye_dveri/serye/"
                           class="header-catalog-menu__link header-catalog-menu__link--level3">
                            <div style="background-image:url(<?= SITE_TEMPLATE_PATH ?>/assets/uploads/colors/7.jpg)"
                                 class="header-catalog-menu__color">
                            </div>
                            Серые
                        </a>
                    </li>
                    <li class="header-catalog-menu__item header-catalog-menu__item--level3">
                        <a href="<?= SITE_DIR ?>catalog/mezhkomnatnye_dveri/chyernye/"
                           class="header-catalog-menu__link header-catalog-menu__link--level3">
                            <div style="background-image:url(<?= SITE_TEMPLATE_PATH ?>/assets/uploads/colors/8.jpg)"
                                 class="header-catalog-menu__color">
                            </div>
                            Чёрные
                        </a>
                    </li>
                </ul>
            </div>
        </li>
    </ul>
</div>