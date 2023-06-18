<?php
namespace Src\Shared\Request;

use Illuminate\Support\Facades\Log;

class RequestClearCart {
    public array $items;
    public function __construct(array $items) {
        $this->items = $items;
    }

    public function validate(): bool {
        Log::info("Data => " . json_encode($this->items));
        return isset($this->items) && is_array($this->items) && sizeof($this->items);
    }
    
}
?>