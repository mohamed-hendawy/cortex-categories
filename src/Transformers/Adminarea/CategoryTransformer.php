<?php

declare(strict_types=1);

namespace Cortex\Categories\Transformers\Adminarea;

use Rinvex\Support\Traits\Escaper;
use Cortex\Categories\Models\Category;
use League\Fractal\TransformerAbstract;

class CategoryTransformer extends TransformerAbstract
{
    use Escaper;

    /**
     * Transform category model.
     *
     * @param \Cortex\Categories\Models\Category $category
     *
     * @throws \Exception
     *
     * @return array
     */
    public function transform(Category $category): array
    {
        return $this->escape([
            'id' => (string) $category->getRouteKey(),
            'name' => (string) $category->name,
            'created_at' => (string) $category->created_at,
            'updated_at' => (string) $category->updated_at,
        ]);
    }
}
