<?php
namespace Src\App\Cart\Application\Find;

use Src\App\Cart\Domain\Cart;
use Src\Shared\Request\RequestId;
use Illuminate\Support\Facades\Log;
use Src\App\Cart\Infrastructure\Persitence\CartRepository;

class FindUserCart {
    private CartRepository $cart_repo;
    public function __construct(private readonly CartRepository $cartRepo) {
        $this->cart_repo = $cartRepo;
    }

    public function find(RequestId $request): Cart {
        return $this->cart_repo->get($request->getId(), $request->getBy());
    }
}
?>