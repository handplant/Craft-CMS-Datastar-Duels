<?php

namespace modules\versus\controllers;

use craft\elements\Entry;
use craft\web\Controller;

class DashboardController extends Controller
{
    public function actionIndex(): \yii\web\Response
    {
        $blogEntries = Entry::find()
            ->section('duels')
            ->orderBy('duelScore DESC')
            ->limit(100)
            ->all();

        $templateVariables = [
            'title' => 'Versus Dashboard',
            'entries' => $blogEntries,
        ];

        return $this->renderTemplate(
            'versus/_dashboard/index.twig',
            $templateVariables,
            \craft\web\View::TEMPLATE_MODE_CP
        );
    }
}
