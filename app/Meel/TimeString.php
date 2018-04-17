<?php

namespace App\Meel;

class TimeString
{
    protected $string;

    protected $originalString;

    protected $replaceWords = [
        'a'       => 1,
        'an'      => 1,
        'one'     => 1,
        'two'     => 2,
        'three'   => 3,
        'four'    => 4,
        'five'    => 5,
        'six'     => 6,
        'seven'   => 7,
        'eight'   => 8,
        'nine'    => 9,
        'ten'     => 10,
        'fifteen' => 15,
        'thirty'  => 30,
        'sixty'   => 60,

        'every week'  => 'weekly',
        'every month' => 'monthly',
        'every year'  => 'yearly',
    ];

    public function __construct(string $string)
    {
        $this->originalString = $string;

        $this->string = $this->prepareInput($string);
    }

    protected function prepareInput($string): string
    {
        $string = trim($string);

        $string = strtolower($string);

        foreach ($this->replaceWords as $search => $replace) {
            $string = $this->replaceWord($string, $search, $replace);
        }

        return $string;
    }

    protected function replaceWord($input, $search, $replace)
    {
        $input = preg_replace('/(^'.$search.'$)/', "{$replace}", $input);

        $input = preg_replace('/(^'.$search.' )/', "{$replace} ", $input);

        $input = preg_replace('/( '.$search.'$)/', " {$replace}", $input);

        return preg_replace('/( '.$search.' )/', " {$replace} ", $input);
    }

    public function getPreparedString()
    {
        return $this->string;
    }

    public function getOriginalString()
    {
        return $this->originalString;
    }

    public static function prepare($string): string
    {
        $timeString = new static($string);

        return $timeString->getPreparedString();
    }
}
