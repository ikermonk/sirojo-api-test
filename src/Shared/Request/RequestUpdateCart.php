<?php
namespace Src\Shared\Request;

use Illuminate\Support\Facades\Log;

class RequestUpdateCart {
    public string $id_cart;
    public string $user_id;
    public array $items;
    public function __construct(string $id_cart, string $user_id, array $items) {
        $this->id_cart = $id_cart;
        $this->user_id = $user_id;
        $this->items = $items;
    }

    public function validate(): bool {
        Log::info("Data => " . $this->id_cart . " // " . $this->user_id . " // " . json_encode($this->items));
        return isset($this->id_cart) && $this->id_cart !== ""
            && isset($this->user_id) && $this->user_id !== ""
            && isset($this->items) && is_array($this->items) && sizeof($this->items);
    }    

}
?>