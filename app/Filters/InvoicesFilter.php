<?php

namespace App\Filters;

use App\Filters\ApiFilter;
use Illuminate\Http\Request;

class InvoicesFilter extends ApiFilter
{
    protected $safedParams = [
      'customerId' => ['eq'],
      'amount' => ['eq', 'gt', 'lt', 'get', 'lte'],
      'status' => ['eq', 'ne'],
      'billedDate' => ['eq', 'gt', 'lt', 'get', 'lte'],
      'paidDate' => ['eq', 'gt', 'lt', 'get', 'lte'],
    ];

    protected $columnMap = [
      'customerId' => 'customer_id',
      'billedDate' => 'billed_date',
      'paidDate' => 'payed_date',
    ];

    protected $operatorMap = [
      'eq' => '=',
      'lt' => '<',
      'lte' => '<=',
      'gt' => '>',
      'gte' => '>=',
      'ne' => '!='
    ];

}
