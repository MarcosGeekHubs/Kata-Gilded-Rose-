<?php

namespace Kata;

class GildedRose
{

    const MAX_QUALITY = 50;
    const LAST_DAYS_DOUBLE_QUALITY = 10;
    const LAST_DAYS_TRIPLE_QUALITY = 5;
    const ITEM_CONCERT = "Backstage passes to a TAFKAL80ETC concert";
    const ITEM_SULFURAS = "Sulfuras, Hand of Ragnaros";
    const ITEM_BRIE = "Aged Brie";

    public static function updateQuality($items)
    {
        for ($i = 0; $i < count($items); $i++) {
            if (!self::isItemQualityUnderMax($items[$i])) {
                continue;
            }

            self::changeQualityByName($items, $i);

            if (self::ITEM_SULFURAS != $items[$i]->getName()) {
                $items[$i]->setSellIn($items[$i]->getSellIn() - 1);
            }

            self::changeQualityFromLapsedItems($items, $i);
        }
    }

    /**
     * @param $item
     * @return bool
     */
    public static function isItemQualityUnderMax($item)
    {
        return $item->getQuality() < self::MAX_QUALITY;
    }

    /**
     * @param $item
     */
    public static function incrementItemQuality(&$item)
    {
        $item->setQuality($item->getQuality() + 1);
    }

    /**
     * @param Item $item
     */
    public static function decreaseItemQuality(&$item)
    {
        $decreasement = 1;

        if ($item->getName() == 'conjured') {
            $decreasement = $decreasement * 2;
        }

        $item->setQuality($item->getQuality() - $decreasement);
    }

    /**
     * @param $items
     * @param $i
     */
    public static function changeQualityByName($items, $i)
    {
        if (self::ITEM_SULFURAS == $items[$i]->getName()) {
            return;
        }

        if (self::ITEM_CONCERT == $items[$i]->getName()) {
            self::incrementItemQuality($items[$i]);

            if ($items[$i]->getSellIn() <= self::LAST_DAYS_DOUBLE_QUALITY) {
                self::incrementItemQuality($items[$i]);
            }
            if ($items[$i]->getSellIn() <= self::LAST_DAYS_TRIPLE_QUALITY) {
                self::incrementItemQuality($items[$i]);
            }
            return;
        }

        if (self::ITEM_BRIE == $items[$i]->getName()) {
            self::incrementItemQuality($items[$i]);
            return;
        }

        if ($items[$i]->getQuality() > 0) {
            self::decreaseItemQuality($items[$i]);
        }
    }

    protected static function changeQualityFromLapsedItems($items, $i)
    {
        if ($items[$i]->getSellIn() < 0) {

            if (self::ITEM_BRIE === $items[$i]->getName()) {
                self::incrementItemQuality($items[$i]);
            }

            if (self::ITEM_CONCERT === $items[$i]->getName()) {
                $items[$i]->setQuality(0);
            }

            if ($items[$i]->getQuality() <= 0) {
                return;
            }

            if (self::ITEM_SULFURAS != $items[$i]->getName()) {
                self::decreaseItemQuality($items[$i]);
            }
        }
    }
}


