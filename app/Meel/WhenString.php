<?php

namespace App\Meel;

class WhenString
{
    protected $preparedString;

    protected $replaceWords = [
        'a'       => '1',
        'an'      => '1',
        'one'     => '1',
        'two'     => '2',
        'three'   => '3',
        'four'    => '4',
        'five'    => '5',
        'six'     => '6',
        'seven'   => '7',
        'eight'   => '8',
        'nine'    => '9',
        'ten'     => '10',
        'fifteen' => '15',
        'thirty'  => '30',
        'sixty'   => '60',

        'every day'   => 'daily',
        'every year'  => 'yearly',

        'bimonthly'   => 'every 2 month',
        'bi monthly'  => 'every 2 month',
        'bi-monthly'  => 'every 2 month',
        'every month' => 'every 1 month',
        'monthly'     => 'every 1 month',

        'every week' => 'every 1 week',
        'biweekly'   => 'every 2 week',
        'bi weekly'  => 'every 2 week',
        'bi-weekly'  => 'every 2 week',
        'weekly'     => 'every 1 week',

        'every monday'    => 'every 1 week on monday',
        'every tuesday'   => 'every 1 week on tuesday',
        'every wednesday' => 'every 1 week on wednesday',
        'every thursday'  => 'every 1 week on thursday',
        'every friday'    => 'every 1 week on friday',
        'every saturday'  => 'every 1 week on saturday',
        'every sunday'    => 'every 1 week on sunday',

        'first'  => '1st',
        'second' => '2nd',
        'third'  => '3rd',
        'fourth' => '4th',
    ];

    public function __construct(string $string)
    {
        $string = trim($string);

        $string = strtolower($string);

        foreach ($this->replaceWords as $search => $replace) {
            $string = preg_replace('/(^| )'.$search.'( |$)/', '${1}'.$replace.'${2}', $string);
        }

        $this->preparedString = $string;
    }

    public function getPreparedString()
    {
        return $this->preparedString;
    }

    public static function prepare($string): string
    {
        $whenString = new static($string);

        return $whenString->getPreparedString();
    }
}
