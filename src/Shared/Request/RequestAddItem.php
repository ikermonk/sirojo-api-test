<?php
namespace Src\Shared\Request;

use stdClass;
use Illuminate\Support\Facades\Log;

class RequestAddItem {
    public string $id_cart;
    public string $user_id;
    public string $product_id;
    public int $quantity;
    public function __construct(string $id_cart, string $user_id, string $product_id, int $quantity) {
        $this->id_cart = $id_cart;
        $this->user_id = $user_id;
        $this->product_id = $product_id;
        $this->quantity = $quantity;
    }

    public function validate(): bool {
        Log::info("Data => " . $this->id_cart . " // " . $this->user_id . " // " . $this->product_id . " // " . $this->quantity);
        return isset($this->id_cart) && $this->id_cart !== "" 
            && isset($this->user_id) && $this->user_id !== ""
            && isset($this->product_id) && $this->product_id !== ""
            && isset($this->quantity) && $this->quantity !== "";
    }
    
}
?>