import React from "react";

let Sidebar = () => {
    return (
        <div className={'b2b-basket__sidebar'}>
            <div className={'b2b-basket__total-wrp'}>
                <div className="b2b-basket__total">
                    <div className="b2b-basket__total-zag">Информация о заказе</div>
                    <div className="b2b-basket__total-promo">
                        <input type="text" placeholder="Введите размер скидки в %"/>
                    </div>
                    <ul className="b2b-basket__total-list">
                        <li><span><b>Количество товаров</b></span><span>5 шт.</span></li>
                        <li><span><b>К оплате</b></span><span>1770,70 р.</span></li>
                        <li><span><b>Скидка</b></span><span>100 р.</span></li>
                        <li><span><b>К оплате со скидкой</b></span><span>1670,70 р.</span></li>
                    </ul>
                    <button className="button ajax-form" data-class="w820 b2b-wrapper"
                            data-href="./popup-formerKP.html">Сформировать КП
                    </button>
                </div>
                <button className="button _grey">
                    <svg className="icon icon-close ">
                        <use xlinkHref={'./img/svg/symbol/sprite.svg#close'}></use>
                    </svg> 
                    <span>Очистить всю коризну</span>
                </button>
            </div>
        </div>
);
};

export default Sidebar;