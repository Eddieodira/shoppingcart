<?php
declare(strict_types=1);

namespace Eddieodira\Shoppingcart\Config;

use Eddieodira\Shoppingcart\Cart;
use CodeIgniter\Config\BaseService;

class Services extends BaseService
{
    public static function cart($getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('cart');
        }

        return new Cart();
    }
}
