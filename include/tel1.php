<div class="tooltip-link tooltip-bottom">
    <?if (CModule::IncludeModule("spectr.targetcontent")):?>
        <?
        $APPLICATION->IncludeComponent("spectr:spectr.target-content", "template1", Array(
            "COMPONENT_TEMPLATE" => ".default"
        ),
            false
        );
        ?>
    <?else:?>
        <a href="tel:+74993808919">+7 (499) 380-89-19</a>
    <?endif;?>
	<div class="tooltip">
		<p class="phone-list">
			<br>
			<span class="fw-bold">Время работы</span>
			<br>
			Пн — Вс: с 7:00 до 22:00
		</p>
		<p class="phone-list">
			<a class="tel1 phone-list__item" href="viber://chat?number=+375293150012" target="_blank">
				Написать в Viber
			</a>
			<br>
			<a class="tel1 phone-list__item" href="https://api.whatsapp.com/send?phone=375293150012" target="_blank">
				Написать в Whatsapp
			</a>
			<br>
			<a class="tel1 phone-list__item" href="https://telegram.me/Belwooddoorsbot" target="_blank">
				Написать в Telegram
			</a>
		</p>
		<p class="phone-list">
            <script data-b24-form="click/8/bv4tdf" data-skip-moving="true"> (function(w,d,u){ var s=d.createElement('script');s.async=true;s.src=u+'?'+(Date.now()/180000|0); var h=d.getElementsByTagName('script')[0];h.parentNode.insertBefore(s,h); })(window,document,'https://bitrix.belwood.ru/upload/crm/form/loader_8_bv4tdf.js'); </script>
            <a data-popup="" class="button product__button button popup-link">Заказать звонок</a> <!-- data-popup="popup--callback" -->
		</p>
	</div>
</div>