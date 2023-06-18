<?php
namespace Src\Shared\Request;

use Illuminate\Support\Facades\Log;

class RequestId {
    private string $id;
    public function __construct(string $id) {
        $this->id = $id;
    }

    public function getId(): string {
        return $this->id;
    }

    public function validate(): bool {
        Log::info("Data => " . $this->id);
        return isset($this->id) && $this->id !== "" ;
    }

}
?>