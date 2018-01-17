<?php

namespace app\models\yii2i18njs;

use yii\helpers\FileHelper;
use yii\web\View;
use Yii;

class I18N extends \yii\i18n\I18N {
    /**
     * @param $view View
     * @param $language
     * @param $category
     */
    public function register($view, $category, $language) {
        $filename = Yii::$app->assetManager->basePath.DIRECTORY_SEPARATOR.'translations'.DIRECTORY_SEPARATOR.$category.DIRECTORY_SEPARATOR.$language.'.js';
        $sourceFilename = Yii::getAlias($this->getMessageSource($category)->basePath).DIRECTORY_SEPARATOR.$language.DIRECTORY_SEPARATOR.$category.'.php';

        if (!file_exists($filename) || filemtime($filename) < filemtime($sourceFilename)) {
            FileHelper::createDirectory(Yii::$app->assetManager->basePath.DIRECTORY_SEPARATOR.'translations'.DIRECTORY_SEPARATOR.$category);
            $this->generateJsFile($sourceFilename, $filename);
        }

        $view->registerJsFile(Yii::$app->assetManager->baseUrl.'/translations/'.$category.'/'.$language.'.js');
    }

    public function generateJsFile($src, $dst) {
        $items = include $src;
        $jsSource = "window.Yii = {
t: function (text) {
    if (window.yiiTranslateSource[text] !== undefined) return window.yiiTranslateSource[text];
        return text;
    }
}
window.yiiTranslateSource = {\n";
        $i = 0;
        foreach ($items as $from => $to) {
            if ($i) $jsSource .= ",\n";
            $jsSource .= "'".str_replace("'", "\\'", $from)."':'".str_replace("'", "\\'", $to)."'";
            $i ++;
        }
        $jsSource .= "\n}\n";
        file_put_contents($dst, $jsSource);
    }
}
