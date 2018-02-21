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

        $result = true;
        $timestamp = filemtime($filename);
        if (!file_exists($filename) || $timestamp < filemtime($sourceFilename)) {
            FileHelper::createDirectory(Yii::$app->assetManager->basePath.DIRECTORY_SEPARATOR.'translations'.DIRECTORY_SEPARATOR.$category);
            $result = $this->generateJsFile($sourceFilename, $filename);
        }

        if ($result) $view->registerJsFile(Yii::$app->assetManager->baseUrl.'/translations/'.$category.'/'.$language.'.js?v='.$timestamp);
    }

    public function generateJsFile($src, $dst) {
        if (!file_exists($src)) return false;
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
        return file_put_contents($dst, $jsSource);
    }
}
