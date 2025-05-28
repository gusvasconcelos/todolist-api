<?php

namespace App\Http\Requests\News;

use Illuminate\Foundation\Http\FormRequest;

class GetArticlesRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'q' => ['required_without_all:searchIn,sources,domains', 'string'],
            'searchIn' => ['required_without_all:q,sources,domains', 'string'],
            'sources' => ['required_without_all:q,searchIn,domains', 'array'],
            'domains' => ['required_without_all:q,searchIn,sources', 'array'],
            'excludeDomains' => ['nullable', 'array'],
            'from' => ['nullable', 'date_format:Y-m-d'],
            'to' => ['nullable', 'date_format:Y-m-d'],
            'sortBy' => ['nullable', 'string'],
            'language' => ['nullable', 'string'],
            'page' => ['nullable', 'integer'],
            'pageSize' => ['nullable', 'integer'],
        ];
    }
}
