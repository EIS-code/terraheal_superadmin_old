<?php

namespace App\Repositories;

use App\Currency;
use App\User;

class CurrencyRepository extends BaseRepository
{
    protected $currency, $user;

    public function __construct()
    {
        $this->currency = new Currency();
        $this->user     = new User;
    }

    public function getDefultCurrency()
    {
        return $this->currency::$defaultCurrency;
    }

    public function getDefaultShopCurrency(int $userId, bool $isId = false)
    {
        $user = $this->user->find($userId);

        if (!empty($user)) {
            $getCurrencyId = $user->shop->currency_id;

            if (!empty($getCurrencyId)) {
                return ($isId) ? $getCurrencyId : $this->getCodeFromId($getCurrencyId);
            }
        }

        return $thise->getDefultCurrency();
    }

    public function getCodeFromId(int $id)
    {
        $currency = $this->getCurrencyById($id);

        return (!empty($currency)) ? $currency->code : $this->currency::$defaultCurrency;
    }

    public function getRate(int $currencyId)
    {
        $rate = $this->getCurrencyById($currencyId);

        if (!empty($rate)) {
            return $rate->exchange_rate;
        }

        return 0;
    }

    public function getCurrencyById(int $currencyId)
    {
        return $this->currency->find($currencyId);
    }
}
