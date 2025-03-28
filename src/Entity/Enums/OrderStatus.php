<?php

namespace App\Entity\Enums;

enum OrderStatus: int
{
    case NEW = 0;
    case IN_PROGRESS = 1;

    case READY = 2;
    case DONE = 3;
    case CANCELED = 4;

    public function getLabel(): string
    {
        return match ($this) {
            self::NEW => 'New',
            self::IN_PROGRESS => 'In progress',
            self::READY => 'ready',
            self::DONE => 'Done',
            self::CANCELED => 'Canceled',
        };
    }

}
