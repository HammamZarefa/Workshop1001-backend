<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class OrderFilter
{
    protected $request;

    public function __construct(
        Request $request
    )
    {
        $this->request = $request;
    }

    public function apply(Builder $query): Builder
    {
        return $query
            ->when($this->request->filled('status'), function (Builder $q) {
                $this->filterStatus($q, $this->request->string('status'));
            })
            ->when($this->request->filled('from_date'), function (Builder $q) {
                $this->filterFromDate($q, $this->request->string('from_date'));
            })
            ->when($this->request->filled('to_date'), function (Builder $q) {
                $this->filterToDate($q, $this->request->string('to_date'));
            })
            ->when($this->request->filled('customer_id'), function (Builder $q) {
                $this->filterCustomer($q, $this->request->integer('customer_id'));
            })
            ->when($this->request->filled('q'), function (Builder $q) {
                $this->filterSearch($q, $this->request->string('q'));
            });
    }

    protected function filterStatus(Builder $query, string $value): Builder
    {
        return $query->where('status', $value);
    }

    protected function filterFromDate(Builder $query, string $value): Builder
    {
        return $query->whereDate('created_at', '>=', $value);
    }

    protected function filterToDate(Builder $query, string $value): Builder
    {
        return $query->whereDate('created_at', '<=', $value);
    }

    protected function filterCustomer(Builder $query, int $value): Builder
    {
        return $query->where('user_id', $value);
    }

    protected function filterSearch(Builder $query, string $term): Builder
    {
        $term = trim($term);

        return $query->where(function ($q) use ($term) {

            if (is_numeric($term)) {
                $q->orWhere('id', $term);
            }

            $q->orWhereHas('user', function ($u) use ($term) {
                $u->where('email', 'LIKE', "%{$term}%");
            });
        });
    }
}
