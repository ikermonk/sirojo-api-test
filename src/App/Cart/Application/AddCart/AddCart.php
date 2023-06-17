<?php
namespace Src\App\Cart\Application\AddCart;

use Src\App\Cart\Domain\Cart;
use Src\Shared\Request\RequestNewCart;
use Src\App\Cart\Infrastructure\Persitence\CartRepository;

class AddCart {
    private CartRepository $cart_repo;
    public function __construct(private readonly CartRepository $cartRepo) {
        $this->cart_repo = $cartRepo;
    }

    public function add(RequestNewCart $request): Cart {
        return $this->cart_repo->add($request->get_cart());
    }
}
?>