<?php

namespace modules\versus\assetbundles;

use craft\web\AssetBundle;
use craft\web\assets\cp\CpAsset;

class VersusAsset extends AssetBundle
{
    public function init()
    {
        $this->sourcePath = '@modules/versus/assets';

// Definiere deine CSS- und JS-Dateien
        $this->css = [
            'css/custom.css',
        ];

        $this->js = [
            'js/custom.js',
        ];

        $this->depends = [
            CpAsset::class, // Je nachdem, ob die Assets im Control Panel oder Frontend geladen werden sollen
        ];

        parent::init();
    }
}
