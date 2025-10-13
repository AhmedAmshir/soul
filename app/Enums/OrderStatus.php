<?php

namespace App\Enums;

enum OrderStatus: string {

    case PLACED = 'placed';
    case OUT_FOR_DELIVERY = 'out_for_delivery';
    case SHIPPED = 'shipped';
    case DELIVERED = 'delivered';

    /**
     * Get all statuses as an array.
     *
     * @return array
     */
    public static function all() {

        return array_map(fn ($case) => $case->value, self::cases());
    }
}