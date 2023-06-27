<?php
namespace Src\App\Cart\Domain\Transform;

use Src\App\Cart\Domain\Cart;
use Src\App\Cart\Domain\Dto\Cart as CartDto;

class CartTransform {
    public function transform(Cart $cart): CartDto {
        return new CartDto($cart->id, $cart->uuid, $cart->user_id, $cart->items);
    }
}
?>