<?php
declare(strict_types=1);

namespace Task1\Model\Attribute;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
class Route
{
    public function __construct(
        public string $method,
        public string $path,
        public string $name
    ) {
    }
}