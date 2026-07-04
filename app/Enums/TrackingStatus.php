<?php

namespace App\Enums;

enum TrackingStatus: string
{
    case Assigned = 'assigned';
    case PickedUp = 'picked_up';
    case OnTheWay = 'on_the_way';
    case Arrived = 'arrived';
}