<?php

namespace modules\versus;

use Craft;
use craft\base\Element;
use craft\elements\Entry;
use craft\web\UrlManager;
use craft\events\RegisterUrlRulesEvent;
use craft\web\twig\variables\Cp;
use craft\events\RegisterCpNavItemsEvent;
use craft\web\View;
use craft\events\RegisterTemplateRootsEvent;
use yii\base\Event;
use yii\base\ModelEvent;
use yii\base\Module;
use modules\versus\assetbundles\VersusAsset;

class Versus extends Module

{
    private static function hotScore(int $votes, int $timestamp, ?int $lastVoteTimestamp = null): float
    {
        // Logarithmische Skalierung der Votes (wie auf Reddit)
        $voteScore = $votes > 0 ? log10($votes) * 2 : 0;

        // Zeitfaktor basierend auf Erstellungsdatum
        $baseTime = strtotime('2025-01-01');
        $timeScore = ($timestamp - $baseTime) / 45000;

        // Aktivitätsbonus basierend auf letztem Vote
        $activityBonus = 0;
        if ($lastVoteTimestamp) {
            $hoursSinceLastVote = (time() - $lastVoteTimestamp) / 3600;
            // Exponentiell abnehmender Bonus über 72 Stunden
            $activityBonus = max(0, 1 - ($hoursSinceLastVote / 72));
        }

        // Gewichtung der Komponenten
        $score = ($voteScore * 1.5) + $timeScore + ($activityBonus * 0.5);

        return round($score, 7);
    }

    public function init()
    {
        parent::init();

        // Set aliases for module directories
        Craft::setAlias('@modules', dirname(__DIR__));
        Craft::setAlias('@modules/versus', __DIR__);

        $this->controllerNamespace = 'modules\\versus\\controllers';

        // Set namespace for console commands
        if (Craft::$app->request->getIsConsoleRequest()) {
            $this->controllerNamespace = 'modules\\versus\\console\\controllers';
        }

        // Register template roots
        Event::on(
            View::class,
            View::EVENT_REGISTER_CP_TEMPLATE_ROOTS,
            function(RegisterTemplateRootsEvent $event) {
                $event->roots[$this->id] = __DIR__ . '/templates';
            }
        );

        // Extend CP navigation
        Event::on(
            Cp::class,
            Cp::EVENT_REGISTER_CP_NAV_ITEMS,
            function(RegisterCpNavItemsEvent $event) {
                $event->navItems[] = [
                    'label' => 'Versus',
                    'url' => 'versus',
                    'icon' => '@modules/versus/icon.svg'
                ];
            }
        );

        // Register dashboard route
        Event::on(
            UrlManager::class,
            UrlManager::EVENT_REGISTER_CP_URL_RULES,
            function(RegisterUrlRulesEvent $event) {
                $event->rules['versus'] = 'versus/dashboard/index';
            }
        );

        // Register asset bundle
        Event::on(
            View::class,
            View::EVENT_BEFORE_RENDER_TEMPLATE,
            function() {
                if (Craft::$app->getRequest()->getIsCpRequest()) {
                    Craft::$app->view->registerAssetBundle(VersusAsset::class);
                }
            }
        );

        // HotScore-Event registrieren
        Event::on(Entry::class, Element::EVENT_AFTER_SAVE, function(ModelEvent $event) {
            $entry = $event->sender;

            if ($entry->type->handle === 'vote') {
                $duel = $entry->getFieldValue('voteDuel')->one();
                if ($duel) {
                    $votes = Entry::find()
                        ->section('votes')
                        ->relatedTo(['targetElement' => $duel])
                        ->count();
                    $lastVote = Entry::find()
                        ->section('votes')
                        ->relatedTo(['targetElement' => $duel])
                        ->orderBy(['dateCreated' => SORT_DESC])
                        ->one();
                    $timestamp = $duel->dateCreated->getTimestamp();
                    $lastVoteTimestamp = $lastVote ? $lastVote->dateCreated->getTimestamp() : null;
                    $score = self::hotScore($votes, $timestamp, $lastVoteTimestamp);
                    $duel->setFieldValue('duelScore', $score);
                    Craft::$app->elements->saveElement($duel, false);
                }
            }
        });
    }
}
