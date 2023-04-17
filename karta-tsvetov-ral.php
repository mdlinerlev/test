<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("description", "Страница с картой цветов RAL на сайте belwooddoors.ru. Воспользуйтесь ей при заказе белорусских дверей");
$APPLICATION->SetPageProperty("keywords", "Карта цветов RAL, belwooddoors.ru");
$APPLICATION->SetPageProperty("title", "Карта цветов RAL на belwooddoors.ru");
$APPLICATION->SetTitle("Карта цветов RAL");
?><style>
    body {
  color: #2c3e50;  
}
.half {
  float: left;
  width: 100%;
  padding: 0 1em;
}
.tab {
  position: relative;
  margin-bottom: 1px;
  width: 100%;
  color: #fff;
  overflow: hidden;
}

.tab input {
  position: absolute;
  opacity: 0;
  z-index: -1;
}
.tab label {
  position: relative;
  display: block;
  padding: 0 0 0 1em;
  background: #40404b;
  font-weight: bold;
  line-height: 3;
  cursor: pointer;
}
.tab label:hover {  
    background: #363642; 
    transition: background .15s linear;   
}
.tab label:active {  
    background: #202022;
    transition: background .05s linear;  
}
.tab-content {
  display: flex;
  flex-wrap: wrap; 
  max-height: 0;
  overflow: hidden;
  background: #f7f7f7;
  color: #40404b;
  -webkit-transition: max-height 0.5s;
  -o-transition: max-height 0.5s;
  transition: max-height 0.5s;  
  padding: 0px 0px 0px 18px;
}
.tab-content p {
  margin: 0px;
  padding: 0px;
}
.tab input:checked ~ .tab-content {
  max-height: 1000vh;  
}
.tab label::after {
  position: absolute;
  right: 0;
  top: 0;
  display: block;
  width: 3em;
  height: 3em;
  line-height: 3;
  text-align: center;
  -webkit-transition: all .85s;
  -o-transition: all .85s;
  transition: all .85s;
}
.tab input[type=checkbox] + label::after {
  content: "+";
}
.tab input[type=checkbox]:checked + label::after {
  transform: rotate(315deg);
}
.col{
    float: left;  
    margin: 15px 25px 5px 0; 
    padding: 0px;  
    width: 80px;
    font-size: 12px;
}
.col-box{    
    width: 90px;
    height: 90px;
    margin: 0px 0px 10px 0; 
}
.name{
    font-weight: bold;
}

  </style>
<div class="half">
	<div class="tab">
 <input id="tab-1" type="checkbox" name="tabs"> <label for="tab-1">Yellow-Beige </label>
		<div class="tab-content">
			<div class="col">
				<div class="col-box" style="background-color: #d2b78a">
				</div>
				<p class="name">
					 Beige
				</p>
				<p>
					 1001
				</p>
			</div>
			<div class="col">
				<div class="col-box" style="background-color: #deae40">
				</div>
				<p class="name">
					 Broom Yellow
				</p>
				<p>
					 1032
				</p>
			</div>
			<div class="col">
				<div class="col-box" style="background-color: #b38c5e">
				</div>
				<p class="name">
					 Brown Beige
				</p>
				<p>
					 1011
				</p>
			</div>
			<div class="col">
				<div class="col-box" style="background-color: #a88d38">
				</div>
				<p class="name">
					 Curry
				</p>
				<p>
					 1027
				</p>
			</div>
			<div class="col">
				<div class="col-box" style="background-color: #e89a15">
				</div>
				<p class="name">
					 Daffodil Yellow
				</p>
				<p>
					 1007
				</p>
			</div>
			<div class="col">
				<div class="col-box" style="background-color: #f5a637">
				</div>
				<p class="name">
					 Dahlia Yellow
				</p>
				<p>
					 1033
				</p>
			</div>
			<div class="col">
				<div class="col-box" style="background-color: #e7ad2d">
				</div>
				<p class="name">
					 Golden Yellow
				</p>
				<p>
					 1004
				</p>
			</div>
			<div class="col">
				<div class="col-box" style="background-color: #cec18d">
				</div>
				<p class="name">
					 Green Beige
				</p>
				<p>
					 1000
				</p>
			</div>
			<div class="col">
				<div class="col-box" style="background-color: #aa9881">
				</div>
				<p class="name">
					 Grey Beige
				</p>
				<p>
					 1019
				</p>
			</div>
			<div class="col">
				<div class="col-box" style="background-color: #c9a031">
				</div>
				<p class="name">
					 Honey Yellow
				</p>
				<p>
					 1005
				</p>
			</div>
			<div class="col">
				<div class="col-box" style="background-color: #decc9e">
				</div>
				<p class="name">
					 Ivory
				</p>
				<p>
					 1014
				</p>
			</div>
			<div class="col">
				<div class="col-box" style="background-color: #dcb945">
				</div>
				<p class="name">
					 Lemon Yellow
				</p>
				<p>
					 1012
				</p>
			</div>
			<div class="col">
				<div class="col-box" style="background-color: #e7d8bb">
				</div>
				<p>
					 Light Ivory
				</p>
				<p>
					 1015
				</p>
			</div>
			<div class="col">
				<div class="col-box" style="background-color: #e09e27">
				</div>
				<p class="name">
					 Maize Yellow
				</p>
				<p>
					 1006
				</p>
			</div>
			<div class="col">
				<div class="col-box" style="background-color: #ffa919">
				</div>
				<p class="name">
					 Melon Yellow
				</p>
				<p>
					 1028
				</p>
			</div>
			<div class="col">
				<div class="col-box" style="background-color: #bb9c5c">
				</div>
				<p class="name">
					 Ochre Yellow
				</p>
				<p>
					 1024
				</p>
			</div>
			<div class="col">
				<div class="col-box" style="background-color: #a6996f">
				</div>
				<p class="name">
					 Olive Yellow
				</p>
				<p>
					 1020
				</p>
			</div>
			<div class="col">
				<div class="col-box" style="background-color: #e4decb">
				</div>
				<p class="name">
					 Oyster White
				</p>
				<p>
					 1013
				</p>
			</div>
			<div class="col">
				<div class="col-box" style="background-color: #efa85d">
				</div>
				<p class="name">
					 Pastel Yellow
				</p>
				<p>
					 1034
				</p>
			</div>
			<div class="col">
				<div class="col-box" style="background-color: #f4c321">
				</div>
				<p class="name">
					 Rapeseed Yellow
				</p>
				<p>
					 1021
				</p>
			</div>
			<div class="col">
				<div class="col-box" style="background-color: #f5b25b">
				</div>
				<p class="name">
					 Saffron Yellow
				</p>
				<p>
					 1017
				</p>
			</div>
			<div class="col">
				<div class="col-box" style="background-color: #d4b578">
				</div>
				<p class="name">
					 Sand Yellow
				</p>
				<p>
					 1002
				</p>
			</div>
			<div class="col">
				<div class="col-box" style="background-color: #f7b422">
				</div>
				<p class="name">
					 Signal Yellow
				</p>
				<p>
					 1003
				</p>
			</div>
			<div class="col">
				<div class="col-box" style="background-color: #f0e95d">
				</div>
				<p class="name">
					 Sulfur Yellow
				</p>
				<p>
					 1016
				</p>
			</div>
			<div class="col">
				<div class="col-box" style="background-color: #f19f29">
				</div>
				<p class="name">
					 Sun Yellow
				</p>
				<p>
					 1037
				</p>
			</div>
			<div class="col">
				<div class="col-box" style="background-color: #f7c624">
				</div>
				<p class="name">
					 Traffic Yellow
				</p>
				<p>
					 1023
				</p>
			</div>
			<div class="col">
				<div class="col-box" style="background-color: #fed54e">
				</div>
				<p class="name">
					 Zinc Yellow
				</p>
				<p>
					 1018
				</p>
			</div>
		</div>
	</div>
	<div class="tab">
 <input id="tab-2" type="checkbox" name="tabs"> <label for="tab-2">Red </label>
		<div class="tab-content">
            
            <div class="col">
				<div class="col-box" style="background-color: rgb(209, 122, 126)">
				</div>
				<p class="name">
                    Antique Rose
				</p>
				<p>
					 3014
                </p>
            </div>

            <div class="col">
				<div class="col-box" style="background-color: rgb(202, 142, 120)">
				</div>
				<p class="name">
                    Beige Red
				</p>
				<p>
					 3012
                </p>
            </div>
            <div class="col">
				<div class="col-box" style="background-color: rgb(84, 63, 65)">
				</div>
				<p class="name">
                    Black Red
				</p>
				<p>
					 3007
                </p>
            </div>
            <div class="col">
				<div class="col-box" style="background-color: rgb(134, 61, 62)">
				</div>
				<p class="name">
                    Brown Red
				</p>
				<p>
					 3011
                </p>
            </div>
            <div class="col">
				<div class="col-box" style="background-color: rgb(168, 62, 63)">
				</div>
				<p class="name">
                    Carmine Red
				</p>
				<p>
					 3002
                </p>
            </div>
            <div class="col">
				<div class="col-box" style="background-color: rgb(176, 78, 67)">
				</div>
				<p class="name">
                    Coral Red
				</p>
				<p>
					 3016
                </p>
            </div>
            <div class="col">
				<div class="col-box" style="background-color: rgb(169, 63, 60)">
				</div>
				<p class="name">
                    Flame Red
				</p>
				<p>
					 3000
                </p>
            </div>
            <div class="col">
				<div class="col-box" style="background-color: rgb(226, 163, 170)">
				</div>
				<p class="name">
                    Light Pink
				</p>
				<p>
					 3015
                </p>
            </div>
            <div class="col">
				<div class="col-box" style="background-color: rgb(175, 72, 75)">
				</div>
				<p class="name">
                    Orient Red
				</p>
				<p>
					 3031
                </p>
            </div>
            <div class="col">
				<div class="col-box" style="background-color: rgb(119, 72, 67)">
				</div>
				<p class="name">
                    Oxide Red
				</p>
				<p>
					 3009
                </p>
            </div>
            <div class="col">
				<div class="col-box" style="background-color: rgb(122, 63, 64)">
				</div>
				<p class="name">
                    Purple Red
				</p>
				<p>
					 3004
                </p>
            </div>
            <div class="col">
				<div class="col-box" style="background-color: rgb(187, 63, 81)">
				</div>
				<p class="name">
                    Raspberry Red
				</p>
				<p>
					 3027
                </p>
            </div>
            <div class="col">
				<div class="col-box" style="background-color: rgb(213, 95, 104)">
				</div>
				<p class="name">
                    Rose
				</p>
				<p>
					 3017
                </p>
            </div>
            <div class="col">
				<div class="col-box" style="background-color: rgb(150, 60, 64)">
				</div>
				<p class="name">
                    Ruby Red
				</p>
				<p>
					 3003
                </p>
            </div>
            <div class="col">
				<div class="col-box" style="background-color: rgb(214, 116, 96)">
				</div>
				<p class="name">
                    Salmon Pink
				</p>
				<p>
					 3022
                </p>
            </div>
            <div class="col">
				<div class="col-box" style="background-color: rgb(170, 61, 57)">
				</div>
				<p class="name">
                    Signal Red
				</p>
				<p>
					 3001
                </p>
            </div>
            <div class="col">
				<div class="col-box" style="background-color: rgb(211, 81, 90)">
				</div>
				<p class="name">
                    Strawberry Red
				</p>
				<p>
					 3018
                </p>
            </div>
            <div class="col">
				<div class="col-box" style="background-color: rgb(161, 68, 63)">
				</div>
				<p class="name">
                    Tomato Red
				</p>
				<p>
					 3013
                </p>
            </div>
            <div class="col">
				<div class="col-box" style="background-color: rgb(196, 60, 56)">
				</div>
				<p class="name">
                    Traffic Red
				</p>
				<p>
					 3020
                </p>
            </div>
            <div class="col">
				<div class="col-box" style="background-color: rgb(109, 61, 65)">
				</div>
				<p class="name">
                    Wine Red
				</p>
				<p>
					 3005
                </p>
            </div>
		</div>
	</div>
	<div class="tab">
 <input id="tab-3" type="checkbox" name="tabs"> <label for="tab-3"> Brown </label>
		<div class="tab-content">
			<div class="col">
				<div class="col-box" style="background-color: rgb(129, 97, 79)">
				</div>
				<p class="name">
                    Beige Brown
				</p>
				<p>
					 8024
                </p>
            </div>
            <div class="col">
				<div class="col-box" style="background-color: rgb(65, 62, 63)">
				</div>
				<p class="name">
                    Black Brown
				</p>
				<p>
					 8022
                </p>
            </div>
            <div class="col">
				<div class="col-box" style="background-color: rgb(105, 71, 65)">
				</div>
				<p class="name">
                    Chestnut Brown
				</p>
				<p>
					 8015
                </p>
            </div>
            <div class="col">
				<div class="col-box" style="background-color: rgb(84, 68, 64)">
				</div>
				<p class="name">
                    Chocolate Brown
				</p>
				<p>
					 8017
                </p>
            </div>
            <div class="col">
				<div class="col-box" style="background-color: rgb(136, 93, 66)">
				</div>
				<p class="name">
                    Clay Brown
				</p>
				<p>
					 8003
                </p>
            </div>
            <div class="col">
				<div class="col-box" style="background-color: rgb(150, 90, 70)">
				</div>
				<p class="name">
                    Copper Brown
				</p>
				<p>
					 8004
                </p>
            </div>
            <div class="col">
				<div class="col-box" style="background-color: rgb(118, 84, 67)">
				</div>
				<p class="name">
                    Fawn Brown
				<p>
					 8007
                </p>
            </div>
            <div class="col">
				<div class="col-box" style="background-color: rgb(79, 74, 73)">
				</div>
				<p class="name">
                    Gray Brown
				<p>
					 8019
                </p>
            </div>
            <div class="col">
				<div class="col-box" style="background-color: rgb(143, 118, 80)">
				</div>
				<p class="name">
                    Green Brown
				<p>
					 8000
                </p>
            </div>
            <div class="col">
				<div class="col-box" style="background-color: rgb(93, 71, 65)">
				</div>
				<p class="name">
                    Mahogany Brown
				<p>
					 8016
                </p>
            </div>
            <div class="col">
				<div class="col-box" style="background-color: rgb(104, 77, 66)">
				</div>
				<p class="name">
                    Nut Brown
				<p>
					 8011
                </p>
            </div>
            <div class="col">
				<div class="col-box" style="background-color: rgb(160, 115, 70)">
				</div>
				<p class="name">
                    Ocher Brown
				<p>
					 8001
                </p>
            </div>
            <div class="col">
				<div class="col-box" style="background-color: rgb(123, 93, 67)">
				</div>
				<p class="name">
                    Olive Brown
				<p>
					 8008
                </p>
            </div>
            <div class="col">
				<div class="col-box" style="background-color: rgb(171, 104, 68)">
				</div>
				<p class="name">
                    Orange Brown
				<p>
					 8023
                </p>
            </div>
            <div class="col">
				<div class="col-box" style="background-color: rgb(124, 101, 86)">
				</div>
				<p class="name">
                    Pale Brown
				<p>
					 8025
                </p>
            </div>
            <div class="col">
				<div class="col-box" style="background-color: rgb(114, 73, 68)">
				</div>
				<p class="name">
                    Red Brown
				<p>
					 8012
                </p>
            </div>
            <div class="col">
				<div class="col-box" style="background-color: rgb(89, 76, 67)">
				</div>
				<p class="name">
                    Sepia Brown
				<p>
					 8014
                </p>
            </div>
            <div class="col">
				<div class="col-box" style="background-color: rgb(129, 91, 77)">
				</div>
				<p class="name">
                    Signal Brown
				<p>
					 8002
                </p>
            </div>
            <div class="col">
				<div class="col-box" style="background-color: rgb(94, 79, 69)">
				</div>
				<p class="name">
                    Terra Brown
				<p>
					 8028
                </p>
            </div>
		</div>
	</div>
	<div class="tab">
 <input id="tab-4" type="checkbox" name="tabs"> <label for="tab-4">White </label>
		<div class="tab-content">
            <div class="col">
				<div class="col-box" style="background-color: rgb(236, 229, 215)">
				</div>
				<p class="name">
                    Cream
				<p>
					 9001
                </p>
            </div>
            <div class="col">
				<div class="col-box" style="background-color: rgb(219, 218, 207)">
				</div>
				<p class="name">
                    Grey White
				<p>
					 9002
                </p>
            </div>
            <div class="col">
				<div class="col-box" style="background-color: rgb(203, 209, 201)">
				</div>
				<p class="name">
                    Papyrus White
				<p>
					 9018
                </p>
            </div>
            <div class="col">
				<div class="col-box" style="background-color: rgb(241, 238, 227)">
				</div>
				<p class="name">
                    Pure White
				<p>
					 9010
                </p>
            </div>
            <div class="col">
				<div class="col-box" style="background-color: rgb(237, 237, 236)">
				</div>
				<p class="name">
                    Signal White
				<p>
					 9003
                </p>
            </div>
            <div class="col">
				<div class="col-box" style="background-color: rgb(238, 240, 235)">
				</div>
				<p class="name">
                    Traffic White
				<p>
					 9016
                </p>
            </div>
		</div>
	</div>
	<div class="tab">
 <input id="tab-5" type="checkbox" name="tabs"> <label for="tab-5">Violet </label>
		<div class="tab-content">
            <div class="col">
				<div class="col-box" style="background-color: rgb(135, 111, 159)">
				</div>
				<p class="name">
                    Blue Lilac
				<p>
					 4005
                </p>
            </div>
            <div class="col">
				<div class="col-box" style="background-color: rgb(116, 61, 77)">
				</div>
				<p class="name">
                    Claret Violet
				<p>
					 4004
                </p>
            </div>
            <div class="col">
				<div class="col-box" style="background-color: rgb(209, 104, 148)">
				</div>
				<p class="name">
                    Heather Violet
				<p>
					 4003
                </p>
            </div>
            <div class="col">
				<div class="col-box" style="background-color: rgb(163, 139, 150)">
				</div>
				<p class="name">
                    Pastel Violet
				<p>
					 4009
                </p>
            </div>
            <div class="col">
				<div class="col-box" style="background-color: rgb(89, 64, 78)">
				</div>
				<p class="name">
                    Purple Violet
				<p>
					 4007
                </p>
            </div>
            <div class="col">
				<div class="col-box" style="background-color: rgb(144, 105, 136)">
				</div>
				<p class="name">
                    Red Lilac
				<p>
					 4001
                </p>
            </div>
            <div class="col">
				<div class="col-box" style="background-color: rgb(156, 80, 93)">
				</div>
				<p class="name">
                    Red Violet
				<p>
					 4002
                </p>
            </div>
            <div class="col">
				<div class="col-box" style="background-color: rgb(147, 83, 136)">
				</div>
				<p class="name">
                    Signal Violet
				<p>
					 4008
                </p>
            </div>
            <div class="col">
				<div class="col-box" style="background-color: rgb(200, 77, 125)">
				</div>
				<p class="name">
                    Telemagenta
				<p>
					 4010
                </p>
            </div>
            <div class="col">
				<div class="col-box" style="background-color: rgb(157, 68, 120)">
				</div>
				<p class="name">
                    Traffic Purple
				<p>
					 4006
                </p>
            </div>
		</div>
	</div>
	<div class="tab">
 <input id="tab-6" type="checkbox" name="tabs"> <label for="tab-6">Blue </label>
		<div class="tab-content">
            <div class="col">
				<div class="col-box" style="background-color: rgb(63, 100, 128)">
				</div>
				<p class="name">
                    Azure Blue
				<p>
					 5009
                </p>
            </div>
            <div class="col">
				<div class="col-box" style="background-color: rgb(61, 62, 68)">
				</div>
				<p class="name">
                    Black Blue
				<p>
					 5004
                </p>
            </div>
            <div class="col">
				<div class="col-box" style="background-color: rgb(77, 108, 144)">
				</div>
				<p class="name">
                    Brilliant Blue
				<p>
					 5007
                </p>
            </div>
            <div class="col">
				<div class="col-box" style="background-color: rgb(42, 99, 140)">
				</div>
				<p class="name">
                    Capri Blue
				<p>
					 5019
                </p>
            </div>
            <div class="col">
				<div class="col-box" style="background-color: rgb(64, 71, 98)">
				</div>
				<p class="name">
                    Cobalt Blue
				<p>
					 5013
                </p>
            </div>
            <div class="col">
				<div class="col-box" style="background-color: rgb(87, 112, 147)">
				</div>
				<p class="name">
                    Distant Blue
				<p>
					 5023
                </p>
            </div>
            <div class="col">
				<div class="col-box" style="background-color: rgb(47, 85, 133)">
				</div>
				<p class="name">
                    Gentian Blue
				<p>
					 5010
                </p>
            </div>
            <div class="col">
				<div class="col-box" style="background-color: rgb(56, 86, 111)">
				</div>
				<p class="name">
                    Green Blue
				<p>
					 5001
                </p>
            </div>
            <div class="col">
				<div class="col-box" style="background-color: rgb(71, 78, 86)">
				</div>
				<p class="name">
                    Grey Blue
				<p>
					 5008
                </p>
            </div>
            <div class="col">
				<div class="col-box" style="background-color: rgb(59, 135, 186)">
				</div>
				<p class="name">
                    Light Blue
				<p>
					 5012
                </p>
            </div>
            <div class="col">
				<div class="col-box" style="background-color: rgb(66, 66, 100)">
				</div>
				<p class="name">
                    Night Blue
				<p>
					 5022
                </p>
            </div>
            <div class="col">
				<div class="col-box" style="background-color: rgb(47, 78, 90)">
				</div>
				<p class="name">
                    Ocean Blue
				<p>
					 5020
                </p>
            </div>
            <div class="col">
				<div class="col-box" style="background-color: rgb(111, 149, 176)">
				</div>
				<p class="name">
                    Pastel Blue
				<p>
					 5024
                </p>
            </div>
            <div class="col">
				<div class="col-box" style="background-color: rgb(110, 128, 154)">
				</div>
				<p class="name">
                    Pigeon Blue
				<p>
					 5014
                </p>
            </div>
            <div class="col">
				<div class="col-box" style="background-color: rgb(62, 72, 101)">
				</div>
				<p class="name">
                    Sapphire Blue
				<p>
					 5003
                </p>
            </div>
            <div class="col">
				<div class="col-box" style="background-color: rgb(32, 86, 143)">
				</div>
				<p class="name">
                    Signal Blue
				<p>
					 5005
                </p>
            </div>
            <div class="col">
				<div class="col-box" style="background-color: rgb(42, 123, 181)">
				</div>
				<p class="name">
                    Sky Blue
				<p>
					 5015
                </p>
            </div>
            <div class="col">
				<div class="col-box" style="background-color: rgb(61, 66, 81)">
				</div>
				<p class="name">
                    Steel Blue
				<p>
					 5011
                </p>
            </div>
            <div class="col">
				<div class="col-box" style="background-color: rgb(24, 93, 149)">
				</div>
				<p class="name">
                    Traffic Blue
				<p>
					 5017
                </p>
            </div>
            <div class="col">
				<div class="col-box" style="background-color: rgb(54, 142, 148)">
				</div>
				<p class="name">
                    Turquoise Blue
				<p>
					 5018
                </p>
            </div>
            <div class="col">
				<div class="col-box" style="background-color: rgb(63, 73, 130)">
				</div>
				<p class="name">
                    Ultramarine Blue
				<p>
					 5002
                </p>
            </div>
            <div class="col">
				<div class="col-box" style="background-color: rgb(70, 89, 120)">
				</div>
				<p class="name">
                    Violet Blue
				<p>
					 5000
                </p>
            </div>
            <div class="col">
				<div class="col-box" style="background-color: rgb(1, 122, 129)">
				</div>
				<p class="name">
                    Water Blue
				<p>
					 5021
                </p>
            </div>
		</div>
	</div>
	<div class="tab">
 <input id="tab-7" type="checkbox" name="tabs"> <label for="tab-7">Black</label>
		<div class="tab-content">
			<div class="col">
				<div class="col-box" style="background-color: rgb(64, 65, 67)">
				</div>
				<p class="name">
                    Graphite Black
				<p>
					 9011
                </p>
            </div>
            <div class="col">
				<div class="col-box" style="background-color: rgb(60, 60, 61)">
				</div>
				<p class="name">
                    Jet Black
				<p>
					 9005
                </p>
            </div>
            <div class="col">
				<div class="col-box" style="background-color: rgb(66, 66, 67)">
				</div>
				<p class="name">
                    Signal Black
				<p>
					 9004
                </p>
            </div>
            <div class="col">
				<div class="col-box" style="background-color: rgb(61, 61, 61)">
				</div>
				<p class="name">
                    Traffic Black
				<p>
					 9017
                </p>
            </div>
		</div>
	</div>
	<div class="tab">
 <input id="tab-8" type="checkbox" name="tabs"> <label for="tab-8">Orange </label>
		<div class="tab-content">
            <div class="col">
				<div class="col-box" style="background-color: rgb(228, 118, 67)">
				</div>
				<p class="name">
                    Bright Red Orange
				<p>
					 2008
                </p>
            </div>
            <div class="col">
				<div class="col-box" style="background-color: rgb(224, 124, 62)">
				</div>
				<p class="name">
                    Deep Orange
				<p>
					 2011
                </p>
            </div>
            <div class="col">
				<div class="col-box" style="background-color: rgb(251, 133, 62)">
				</div>
				<p class="name">
                    Pastel Orange
				<p>
					 2003
                </p>
            </div>
            <div class="col">
				<div class="col-box" style="background-color: rgb(228, 99, 58)">
				</div>
				<p class="name">
                    Pure Orange
				<p>
					 2004
                </p>
            </div>
            <div class="col">
				<div class="col-box" style="background-color: rgb(196, 93, 61)">
				</div>
				<p class="name">
                    Red Orange
				<p>
					 2001
                </p>
            </div>
            <div class="col">
				<div class="col-box" style="background-color: rgb(223, 107, 83)">
				</div>
				<p class="name">
                    Salmon Orange
				<p>
					 2012
                </p>
            </div>
            <div class="col">
				<div class="col-box" style="background-color: rgb(208, 102, 57)">
				</div>
				<p class="name">
                    Signal Orange
				<p>
					 2010
                </p>
            </div>
            <div class="col">
				<div class="col-box" style="background-color: rgb(217, 106, 61)">
				</div>
				<p class="name">
                    Traffic Orange
				<p>
					 2009
                </p>
            </div>
            <div class="col">
				<div class="col-box" style="background-color: rgb(199, 76, 60)">
				</div>
				<p class="name">
                    Vermillion
				<p>
					 2002
                </p>
            </div>
            <div class="col">
				<div class="col-box" style="background-color: rgb(222, 128, 48)">
				</div>
				<p class="name">
                    Yellow Orange
				<p>
					 2000
                </p>
            </div>
		</div>
	</div>
	<div class="tab">
 <input id="tab-9" type="checkbox" name="tabs"> <label for="tab-9">Grey </label>
		<div class="tab-content">
            <div class="col">
				<div class="col-box" style="background-color: rgb(179, 179, 173)">
				</div>
				<p class="name">
                    Agate Gray
				<p>
					 7038
                </p>
            </div>
            <div class="col">
				<div class="col-box" style="background-color: rgb(77, 81, 85)">
				</div>
				<p class="name">
                    Anthracite Gray
				<p>
					 7016
                </p>
            </div>
            <div class="col">
				<div class="col-box" style="background-color: rgb(102, 107, 109)">
				</div>
				<p class="name">
                    Basalt Gray
				<p>
					 7012
                </p>
            </div>
            <div class="col">
				<div class="col-box" style="background-color: rgb(126, 115, 102)">
				</div>
				<p class="name">
                    Beige Gray
				<p>
					 7006
                </p>
            </div>
            <div class="col">
				<div class="col-box" style="background-color: rgb(71, 73, 75)">
				</div>
				<p class="name">
                    Black Gray
				<p>
					 7021
                </p>
            </div>
            <div class="col">
				<div class="col-box" style="background-color: rgb(102, 113, 118)">
				</div>
				<p class="name">
                    Blue Gray
				<p>
					 7031
                </p>
            </div>
            <div class="col">
				<div class="col-box" style="background-color: rgb(99, 95, 85)">
				</div>
				<p class="name">
                    Brown Gray
				<p>
					 7013
                </p>
            </div>
            <div class="col">
				<div class="col-box" style="background-color: rgb(130, 136, 124)">
				</div>
				<p class="name">
                    Cement Gray
				<p>
					 7033
                </p>
            </div>
            <div class="col">
				<div class="col-box" style="background-color: rgb(134, 135, 127)">
				</div>
				<p class="name">
                    Concrete Gray
				<p>
					 7023
                </p>
            </div>
            <div class="col">
				<div class="col-box" style="background-color: rgb(133, 133, 132)">
				</div>
				<p class="name">
                    Dusty Gray
				<p>
					 7037
                </p>
            </div>
            <div class="col">
				<div class="col-box" style="background-color: rgb(75, 83, 85)">
				</div>
				<p class="name">
                    Granite Gray
				<p>
					 7026
                </p>
            </div>
            <div class="col">
				<div class="col-box" style="background-color: rgb(86, 88, 93)">
				</div>
				<p class="name">
                    Graphite Gray
				<p>
					 7024
                </p>
            </div>
            <div class="col">
				<div class="col-box" style="background-color: rgb(101, 105, 98)">
				</div>
				<p class="name">
                    Green Gray
				<p>
					 7009
                </p>
            </div>
            <div class="col">
				<div class="col-box" style="background-color: rgb(97, 103, 106)">
				</div>
				<p class="name">
                    Iron Gray
				<p>
					 7011
                </p>
            </div>
            <div class="col">
				<div class="col-box" style="background-color: rgb(125, 111, 82)">
				</div>
				<p class="name">
                    Khaki Gray
				<p>
					 7008
                </p>
            </div>
            <div class="col">
				<div class="col-box" style="background-color: rgb(198, 201, 198)">
				</div>
				<p class="name">
                    Light Gray
				<p>
					 7035
                </p>
            </div>
            <div class="col">
				<div class="col-box" style="background-color: rgb(128, 126, 113)">
				</div>
				<p class="name">
                    Moss Gray
				<p>
					 7003
                </p>
            </div>
            <div class="col">
				<div class="col-box" style="background-color: rgb(119, 123, 120)">
				</div>
				<p class="name">
                    Mouse Gray
				<p>
					 7005
                </p>
            </div>
            <div class="col">
				<div class="col-box" style="background-color: rgb(138, 132, 112)">
				</div>
				<p class="name">
                    Olive Gray
				<p>
					 7002
                </p>
            </div>
            <div class="col">
				<div class="col-box" style="background-color: rgb(185, 181, 165)">
				</div>
				<p class="name">
                    Pebble Gray
				<p>
					 7032
                </p>
            </div>
            <div class="col">
				<div class="col-box" style="background-color: rgb(153, 149, 148)">
				</div>
				<p class="name">
                    Platinum Gray
				<p>
					 7036
                </p>
            </div>
            <div class="col">
				<div class="col-box" style="background-color: rgb(118, 116, 110)">
				</div>
				<p class="name">
                    Quartz Gray
				<p>
					 7039
                </p>
            </div>
            <div class="col">
				<div class="col-box" style="background-color: rgb(160, 160, 161)">
				</div>
				<p class="name">
                    Signal Gray
				<p>
					 7004
                </p>
            </div>
            <div class="col">
				<div class="col-box" style="background-color: rgb(188, 185, 175)">
				</div>
				<p class="name">
                    Silk Gray
				<p>
					 7044
                </p>
            </div>
            <div class="col">
				<div class="col-box" style="background-color: rgb(147, 156, 161)">
				</div>
				<p class="name">
                    Silver Gray
				<p>
					 7001
                </p>
            </div>
            <div class="col">
				<div class="col-box" style="background-color: rgb(93, 96, 101)">
				</div>
				<p class="name">
                    Slate Gray
				<p>
					 7015
                </p>
            </div>
            <div class="col">
				<div class="col-box" style="background-color: rgb(129, 141, 149)">
				</div>
				<p class="name">
                    Squirrel Gray
				<p>
					 7000
                </p>
            </div>
            <div class="col">
				<div class="col-box" style="background-color: rgb(148, 146, 138)">
				</div>
				<p class="name">
                    Stone Gray
				<p>
					 7030
                </p>
            </div>
            <div class="col">
				<div class="col-box" style="background-color: rgb(101, 103, 99)">
				</div>
				<p class="name">
                    Tarpaulin Gray
				<p>
					 7010
                </p>
            </div>
            <div class="col">
				<div class="col-box" style="background-color: rgb(147, 151, 155)">
				</div>
				<p class="name">
                    Telegray
				<p>
					 7045
                </p>
            </div>
            <div class="col">
				<div class="col-box" style="background-color: rgb(133, 138, 143)">
				</div>
				<p class="name">
                    Telegray 2
				<p>
					 7046
                </p>
            </div>
            <div class="col">
				<div class="col-box" style="background-color: rgb(203, 202, 201)">
				</div>
				<p class="name">
                    Telegray 4
				<p>
					 7047
                </p>
            </div>
            <div class="col">
				<div class="col-box" style="background-color: rgb(149, 153, 154)">
				</div>
				<p class="name">
                    Traffic Gray A
				<p>
					 7042
                </p>
            </div>
            <div class="col">
				<div class="col-box" style="background-color: rgb(93, 96, 96)">
				</div>
				<p class="name">
                    Traffic Gray B
				<p>
					 7043
                </p>
            </div>
            <div class="col">
				<div class="col-box" style="background-color: rgb(88, 87, 84)">
				</div>
				<p class="name">
                    Umbra Gray
				<p>
					 7022
                </p>
            </div>
            <div class="col">
				<div class="col-box" style="background-color: rgb(159, 163, 167)">
				</div>
				<p class="name">
                    Window Gray
				<p>
					 7040
                </p>
            </div>
            <div class="col">
				<div class="col-box" style="background-color: rgb(151, 144, 122)">
				</div>
				<p class="name">
                    Yellow Gray
				<p>
				<p>
					 7034
                </p>
            </div>



		</div>
	</div>
	<div class="tab">
 <input id="tab-10" type="checkbox" name="tabs"> <label for="tab-10">Green </label>
		<div class="tab-content">
			<div class="col">
				<div class="col-box" style="background-color: rgb(67, 77, 75)">
				</div>
				<p class="name">
                    Black Green
				<p>
				<p>
					 6012
                </p>
            </div>
            <div class="col">
				<div class="col-box" style="background-color: rgb(75, 77, 72)">
				</div>
				<p class="name">
                    Black Olive
				<p>
				<p>
					 6015
                </p>
            </div>
            <div class="col">
				<div class="col-box" style="background-color: rgb(57, 86, 86)">
				</div>
				<p class="name">
                    Blue Green
				<p>
				<p>
					 6004
                </p>
            </div>
            <div class="col">
				<div class="col-box" style="background-color: rgb(68, 75, 65)">
				</div>
				<p class="name">
                    Bottle Green
				<p>
				<p>
					 6007
                </p>
            </div>
            <div class="col">
				<div class="col-box" style="background-color: rgb(72, 71, 64)">
				</div>
				<p class="name">
                    Brown Green
				<p>
				<p>
					 6008
                </p>
            </div>
            <div class="col">
				<div class="col-box" style="background-color: rgb(75, 86, 73)">
				</div>
				<p class="name">
                    Chrome Green
				<p>
				<p>
					 6020
                </p>
            </div>
            <div class="col">
				<div class="col-box" style="background-color: rgb(66, 116, 76)">
				</div>
				<p class="name">
                    Emerald Green
				<p>
				<p>
					 6001
                </p>
            </div>
            <div class="col">
				<div class="col-box" style="background-color: rgb(99, 121, 77)">
				</div>
				<p class="name">
                    Fern Green
				<p>
				<p>
					 6025
                </p>
            </div>
            <div class="col">
				<div class="col-box" style="background-color: rgb(67, 76, 70)">
				</div>
				<p class="name">
                    Fir Green
				<p>
				<p>
					 6009
                </p>
            </div>
            <div class="col">
				<div class="col-box" style="background-color: rgb(78, 118, 74)">
				</div>
				<p class="name">
                    Grass Green
				<p>
				<p>
					 6010
                </p>
            </div>
            <div class="col">
				<div class="col-box" style="background-color: rgb(76, 78, 71)">
				</div>
				<p class="name">
                    Grey Olive
				<p>
				<p>
					 6006
                </p>
            </div>
            <div class="col">
				<div class="col-box" style="background-color: rgb(67, 106, 72)">
				</div>
				<p class="name">
                    Leaf Green
				<p>
				<p>
					 6002
                </p>
            </div>
            <div class="col">
				<div class="col-box" style="background-color: rgb(126, 188, 186)">
				</div>
				<p class="name">
                    Light Green
				<p>
				<p>
					 6027
                </p>
            </div>
            <div class="col">
				<div class="col-box" style="background-color: rgb(91, 137, 84)">
				</div>
				<p class="name">
                    May Green
				<p>
				<p>
					 6017
                </p>
            </div>
            <div class="col">
				<div class="col-box" style="background-color: rgb(1, 119, 81)">
				</div>
				<p class="name">
                    Mint Green
				<p>
				<p>
					 6029
                </p>
            </div>
            <div class="col">
				<div class="col-box" style="background-color: rgb(83, 140, 135)">
				</div>
				<p class="name">
                    Mint Turquoise
				<p>
				<p>
					 6033
                </p>
            </div>
            <div class="col">
				<div class="col-box" style="background-color: rgb(53, 84, 72)">
				</div>
				<p class="name">
                    Moss Green
				<p>
				<p>
					 6005
                </p>
            </div>
            <div class="col">
				<div class="col-box" style="background-color: rgb(76, 73, 65)">
				</div>
				<p class="name">
                    Olive Drab
				<p>
				<p>
					 6022
                </p>
            </div>
            <div class="col">
				<div class="col-box" style="background-color: rgb(93, 99, 81)">
				</div>
				<p class="name">
                    Olive Green
				<p>
				<p>
					 6003
                </p>
            </div>
            <div class="col">
				<div class="col-box" style="background-color: rgb(17, 103, 94)">
				</div>
				<p class="name">
                    Opal Green
				<p>
				<p>
					 6026
                </p>
            </div>
            <div class="col">
				<div class="col-box" style="background-color: rgb(139, 164, 132)">
				</div>
				<p class="name">
                    Pale Green
				<p>
				<p>
					 6021
                </p>
            </div>
            <div class="col">
				<div class="col-box" style="background-color: rgb(178, 209, 177)">
				</div>
				<p class="name">
                    Pastel Green
				<p>
				<p>
					 6019
                </p>
            </div>
            <div class="col">
				<div class="col-box" style="background-color: rgb(125, 175, 176)">
				</div>
				<p class="name">
                    Pastel Turquoise
				<p>
				<p>
					 6034
                </p>
            </div>
            <div class="col">
				<div class="col-box" style="background-color: rgb(72, 124, 109)">
				</div>
				<p class="name">
                    Patina Green
				<p>
				<p>
					 6000
                </p>
            </div>
            <div class="col">
				<div class="col-box" style="background-color: rgb(70, 97, 86)">
				</div>
				<p class="name">
                    Pine Green
				<p>
				<p>
					 6028
                </p>
            </div>
            <div class="col">
				<div class="col-box" style="background-color: rgb(126, 126, 101)">
				</div>
				<p class="name">
                    Reed Green
				<p>
				<p>
					 6013
                </p>
            </div>
            <div class="col">
				<div class="col-box" style="background-color: rgb(114, 135, 105)">
				</div>
				<p class="name">
                    Reseda Green
				<p>
				<p>
					 6011
                </p>
            </div>
            <div class="col">
				<div class="col-box" style="background-color: rgb(43, 135, 96)">
				</div>
				<p class="name">
                    Signal Green
				<p>
				<p>
					 6032
                </p>
            </div>
            <div class="col">
				<div class="col-box" style="background-color: rgb(49, 137, 99)">
				</div>
				<p class="name">
                    Traffic Green
				<p>
				<p>
					 6024
                </p>
            </div>
            <div class="col">
				<div class="col-box" style="background-color: rgb(30, 112, 93)">
				</div>
				<p class="name">
                    Turquoise Green
				<p>
				<p>
					 6016
                </p>
            </div>
            <div class="col">
				<div class="col-box" style="background-color: rgb(89, 161, 79)">
				</div>
				<p class="name">
                    Yellow Green
				<p>
				<p>
					 6018
                </p>
            </div>
            <div class="col">
				<div class="col-box" style="background-color: rgb(84, 81, 71)">
				</div>
				<p class="name">
                    Yellow Olive
				<p>
				<p>
					 6014
                </p>
            </div>




		
		</div>
	</div>
 
</div><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>