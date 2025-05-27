<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Resources\Json\ResourceCollection;

class PaginationResource extends ResourceCollection
{
    public function __construct(
        protected LengthAwarePaginator $paginator,
        protected string $resourceClass
    ) {
        parent::__construct($paginator);

        $this->resourceClass = $resourceClass;
    }

    public function toArray(Request $request): array
    {
        return [
            'current_page' => $this->paginator->currentPage(),
            'data' => $this->resourceClass::collection($this->paginator),
            'first_page_url' => $this->paginator->url(1),
            'from' => $this->paginator->firstItem(),
            'last_page' => $this->paginator->lastPage(),
            'last_page_url' => $this->paginator->url($this->paginator->lastPage()),
            'links' => $this->paginator->linkCollection()->toArray(),
            'next_page_url' => $this->paginator->nextPageUrl(),
            'path' => $this->paginator->path(),
            'per_page' => $this->paginator->perPage(),
            'prev_page_url' => $this->paginator->previousPageUrl(),
            'to' => $this->paginator->lastItem(),
            'total' => $this->paginator->total(),
        ];
    }
}
