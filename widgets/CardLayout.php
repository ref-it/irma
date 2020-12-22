<?php


namespace app\widgets;

use yii\base\Widget;
use yii\bootstrap4\Html;

class CardLayout extends Widget
{
    public const CARD_GROUP = 'card-group';
    public const CARD_DECK = 'card-deck';
    public const CARD_COLUMNS = 'card-columns';

    public array $containerOptions = [];

    public array $items = [];

    public array $defaultCardConfig = [];

    public function run() : string
    {
        $out = '';
        $out .= Html::beginTag('div', $this->containerOptions);
        foreach ($this->items as $item){
            $item_with_defaults = $item + $this->defaultCardConfig; // add defaults if missing
            $out .= Card::widget($item_with_defaults); // render sub card
        }
        $out .= Html::endTag('div');

        return $out;
    }


}