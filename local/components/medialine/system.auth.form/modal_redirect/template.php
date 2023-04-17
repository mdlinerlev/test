<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

setcookie('REDIRECT_KRASNODAR', 'N', time() + 50000, '/');
$_COOKIE['REDIRECT_KRASNODAR'] = 'N';
$_SESSION['REDIRECT_KRASNODAR'] = 'N';
?>
<div class="popup-b2b__wrp">
    <div class="popup-b2b__zag">Вы из Краснодарского края?</div>
    <form name="system_auth_form<?= $arResult["RND"] ?>"
          method="POST"
          class="popup-b2b__form"
          id="form_auth"
    >

        <div class="popup-b2b__form-footer">
            <button class="button" name="REDIRECT_KRASNODAR" value="Y" form="form_auth"
                    type="submit">Верно!
            </button>
        </div>
    </form>
</div>
