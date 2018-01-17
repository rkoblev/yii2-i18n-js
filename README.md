# yii2-i18n-js
Extend yii2 i18n component for simple js translating reusing php translation files

## How to use it?
1. Create php translation files.
2. Set extended class in config:
```php
'i18n' => [
            'class' => 'app\models\yii2i18njs\I18N',
            'translations' => [
                '*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                ],
            ],
        ],
```
3. Register js translations in your view:
```php
Yii::$app->i18n->register($this, 'some_category', 'your_language');
```
4. Use translations in javascript via `Yii.t('your string')`.

## Notes
- It generates js translation file only if php translation file was changed (caching support).
- It is very simple code which works with PhpMessageSource only and simple translations phrase-to-phrase.
- It doesn't support dynamic multi-language and dynamic language changing.
- You can extend it in any way you want
