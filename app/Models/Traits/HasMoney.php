<?php

namespace App\Models\Traits;

use Cknow\Money\Money;

trait HasMoney
{

    public function initializeHasMoney()
    {
        $this->appends[] = 'total_price_tl';

    }
    //region Getter & Setters
    public function getTotalPriceTlAttribute(): string
    {
        return Money::parseByDecimal($this->total_price,'TRY')->format();
    }
    //endregion
}
