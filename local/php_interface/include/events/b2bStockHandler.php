<?

class B2bStockHandler{
    public function FieldValidation(\Bitrix\Main\Entity\Event $event){
        $entity = $event->getEntity();

        $arErrors = [];
        $arFields = $event->getParameter("fields");

        if($arFields['UF_BEFORE'] >= $arFields['UF_AFTER']){
            $arErrors[] = new \Bitrix\Main\Entity\FieldError($entity->getField("UF_BEFORE"), 'Ошибка: не верно указан диапазон');
        }

        $result = new \Bitrix\Main\Entity\EventResult();
        if(!empty($arErrors)){
            $result->setErrors($arErrors);
        }

        return $result;
    }
}
