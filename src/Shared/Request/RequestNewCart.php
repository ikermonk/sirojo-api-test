<?php
namespace Src\Shared\Request;

use Src\App\Cart\Domain\Cart;

class RequestNewCart {
    private Cart $cart;
    public function __construct(Cart $cart) {
        $this->cart = $cart;
    }

    public function get_cart(): Cart {
        return $this->cart;
    }
}
?>