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
        'every week'  => 'weekly',
        'every year'  => 'yearly',

        'bimonthly'   => 'every 2 month',
        'bi monthly'  => 'every 2 month',
        'bi-monthly'  => 'every 2 month',
        'every month' => 'every 1 month',
        'monthly'     => 'every 1 month',

        'every monday'    => 'weekly on monday',
        'every tuesday'   => 'weekly on tuesday',
        'every wednesday' => 'weekly on wednesday',
        'every thursday'  => 'weekly on thursday',
        'every friday'    => 'weekly on friday',
        'every saturday'  => 'weekly on saturday',
        'every sunday'    => 'weekly on sunday',

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
            $string = $this->replaceWord($string, $search, $replace);
        }

        $this->preparedString = $string;
    }

    protected function replaceWord($input, $search, $replace)
    {
        $input = preg_replace('/(^'.$search.'$)/', "{$replace}",   $input);
        $input = preg_replace('/(^'.$search.' )/', "{$replace} ",  $input);
        $input = preg_replace('/( '.$search.'$)/', " {$replace}",  $input);
        return   preg_replace('/( '.$search.' )/', " {$replace} ", $input);
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
