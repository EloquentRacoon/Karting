<?php


namespace App\Twig;


use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AppExtension extends AbstractExtension
{

    public function getFilters()
    {
        return [
          new TwigFilter('time', [$this, 'getTime'])
        ];
    }

    public function getTime($time){

        $hour = $time->format('H');
        $minutes = $time->format('i');

        if ($hour == "00"){
            $answer =  $minutes;
        } else {
            $hoursInMinutes = $hour * 60;
            $answer = $minutes + $hoursInMinutes;
        }


        return $answer . " min.";

    }
}