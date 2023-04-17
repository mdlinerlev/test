<?

namespace Ml\Settings\Model;

use Bitrix\Main\Entity;
use Bitrix\Main\Localization\Loc;

class PropertyTable extends Entity\DataManager
{
    /**
     * Returns DB table name for entity.
     *
     * @return string
     */
    public static function getTableName()
    {
        return 'ml_settings_properties';
    }

    /**
     * Returns entity map definition.
     *
     * @return array
     */
    public static function getMap()
    {
        return [
            new Entity\IntegerField(
                'ID',
                [
                    'primary' => true,
                    'autocomplete' => true
                ]
            ),
            new Entity\TextField(
                'SECTION',
                [
                    'required' => true,
                    'title' => 'Раздел'
                ]
            ),
            new Entity\TextField('PROPERTY',
                [
                    'required' => true,
                    'title' => 'Свойство'
                ]
            ),
            new Entity\TextField('PROPERTY_VALUE',
                [
                    'required' => true,
                    'title' => 'Значение свойства'
                ]
            ),
            new Entity\TextField('LINK',
                [
                    'required' => true,
                    'title' => 'Ссылка'
                ]
            ),
            new Entity\BooleanField(
                'NEW_WINDOW',
                [
                    'values' => ['Y', 'N'],
                    'default_value' => 'N',
                    'title' => 'Открывать в новом окне'
                ]
            ),
        ];
    }
}
