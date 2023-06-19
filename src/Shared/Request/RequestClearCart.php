<?php
namespace Src\Shared\Request;

use Illuminate\Support\Facades\Log;

class RequestClearCart {
    public string $user_id;
    public array $items;
    public function __construct(string $user_id, array $items) {
        $this->user_id = $user_id;
        $this->items = $items;
    }

    public function validate(): bool {
        return isset($this->user_id) && $this->user_id !== ""
            && isset($this->items) && is_array($this->items) && sizeof($this->items);
    }
    
}
?>