<?php
namespace App\Enums;

enum PaymentMethod: string
{
    case Online = 'online';
    case Cod = 'cod';
}