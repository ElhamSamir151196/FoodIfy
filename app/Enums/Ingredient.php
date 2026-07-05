<?php

namespace App\Enums;

enum Ingredient: string
{
    case Tomato    = 'tomato';
    case Potato    = 'potato';
    case Cucumber  = 'cucumber';
    case Lettuce   = 'lettuce';
    case Egg       = 'egg';
    case Meat      = 'meat';
    case Cheese    = 'cheese';
    case Onion     = 'onion';

    public function label(): string
    {
        return match($this) {
            self::Tomato   => 'Tomato',
            self::Potato   => 'Potato',
            self::Cucumber => 'Cucumber',
            self::Lettuce  => 'Lettuce',
            self::Egg      => 'Egg',
            self::Meat     => 'Meat',
            self::Cheese   => 'Cheese',
            self::Onion    => 'Onion',
        };
    }

    public function icon(): string
    {
        return match($this) {
            self::Tomato   => 'tomato.svg',
            self::Potato   => 'potato.svg',
            self::Cucumber => 'cucumber.svg',
            self::Lettuce  => 'lettuce.svg',
            self::Egg      => 'egg.svg',
            self::Meat     => 'meat.svg',
            self::Cheese   => 'cheese.svg',
            self::Onion    => 'onion.svg',
        };
    }
}