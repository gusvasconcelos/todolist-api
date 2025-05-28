<?php

namespace App\Packages\Api\News\Everything\Request;

use Carbon\Carbon;
use InvalidArgumentException;
use Illuminate\Contracts\Support\Arrayable;

class ArticleRequest implements Arrayable
{
    protected string|null $q = null;

    protected string|null $searchIn = null;

    protected string|null $sources = null;

    protected string|null $domains = null;

    protected string|null $excludeDomains = null;

    protected string|null $from = null;

    protected string|null $to = null;

    protected string|null $language = null;

    protected string|null $sortBy = null;

    protected int|null $page = null;

    protected int|null $pageSize = null;

    public function __construct()
    {
        $this->page = 1;
        $this->pageSize = 10;
    }

    public function toArray(): array
    {
        $data = [
            'q' => $this->q,
            'searchIn' => $this->searchIn,
            'sources' => $this->sources,
            'domains' => $this->domains,
            'excludeDomains' => $this->excludeDomains,
            'from' => $this->from,
            'to' => $this->to,
            'language' => $this->language,
            'sortBy' => $this->sortBy,
            'page' => $this->page,
            'pageSize' => $this->pageSize,
        ];

        return array_filter($data, fn ($value) => $value !== null);
    }

    public function setQ(string|null $q): self
    {
        if ($q === null) {
            return $this;
        }

        if (strlen($q) > 500) {
            throw new InvalidArgumentException('Query must be less than 500 characters');
        }

        $this->q = $q;

        return $this;
    }

    public function setSearchIn(string|null $searchIn): self
    {
        if ($searchIn === null) {
            return $this;
        }

        $availableSearchIn = [
            'title',
            'description',
            'content',
        ];

        if (!in_array($searchIn, $availableSearchIn)) {
            throw new InvalidArgumentException('Invalid search in');
        }

        $this->searchIn = $searchIn;

        return $this;
    }

    public function setSources(array|null $sources): self
    {
        if ($sources === null) {
            return $this;
        }

        if (count($sources) > 20) {
            throw new InvalidArgumentException('Sources must be less than 20');
        }

        $this->sources = implode(',', $sources);

        return $this;
    }

    public function setDomains(array|null $domains): self
    {
        if ($domains === null) {
            return $this;
        }

        $this->domains = implode(',', $domains);

        return $this;
    }

    public function setExcludeDomains(array|null $excludeDomains): self
    {
        if ($excludeDomains === null) {
            return $this;
        }

        $this->excludeDomains = implode(',', $excludeDomains);

        return $this;
    }

    public function setFrom(string|null $from): self
    {
        if ($from === null) {
            return $this;
        }

        if (! Carbon::parse($from)->isValid()) {
            throw new InvalidArgumentException('Invalid from date');
        }

        $this->from = Carbon::parse($from)->format('Y-m-d');

        return $this;
    }

    public function setTo(string|null $to): self
    {
        if ($to === null) {
            return $this;
        }

        if (! Carbon::parse($to)->isValid()) {
            throw new InvalidArgumentException('Invalid to date');
        }

        $this->to = Carbon::parse($to)->format('Y-m-d');

        return $this;
    }

    public function setLanguage(string|null $language): self
    {
        if ($language === null) {
            return $this;
        }

        $availableLanguages = [
            'ar',
            'de',
            'en',
            'es',
            'fr',
            'he',
            'it',
            'nl',
            'no',
            'pt',
            'ru',
            'sv',
            'ud',
            'zh',
        ];

        if (!in_array($language, $availableLanguages)) {
            throw new InvalidArgumentException('Invalid language');
        }

        $this->language = $language;

        return $this;
    }

    public function setSortBy(string|null $sortBy): self
    {
        if ($sortBy === null) {
            return $this;
        }

        $availableSortBy = [
            'relevancy',
            'popularity',
            'publishedAt',
        ];

        if (!in_array($sortBy, $availableSortBy)) {
            throw new InvalidArgumentException('Invalid sort by');
        }

        $this->sortBy = $sortBy;

        return $this;
    }

    public function setPage(int|null $page): self
    {
        if ($page === null) {
            return $this;
        }

        if ($page < 1) {
            throw new InvalidArgumentException('Page must be greater than 0');
        }

        if ($page > 100) {
            throw new InvalidArgumentException('Page must be less than 100');
        }

        $this->page = $page;

        return $this;
    }

    public function setPageSize(int|null $pageSize): self
    {
        if ($pageSize === null) {
            return $this;
        }

        $this->pageSize = $pageSize;

        return $this;
    }
}
