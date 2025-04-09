<?php

namespace Webkul\Shipping\Carriers;

use Webkul\Checkout\Models\CartShippingRate;

class StorePickup extends AbstractShipping
{
    /**
     * Código del carrier.
     *
     * @var string
     */
    protected $code = 'storepickup';

    /**
     * Código del método.
     *
     * @var string
     */
    protected $method = 'storepickup_storepickup';

    /**
     * Calcula la tarifa para el método de "Recogida en tienda".
     *
     * @return CartShippingRate|false
     */
    public function calculate()
    {
        if (! $this->isAvailable()) {
            return false;
        }

        return $this->getRate();
    }

    /**
     * Obtiene la tarifa.
     *
     * @return CartShippingRate
     */
    public function getRate(): CartShippingRate
    {
        $cartShippingRate = new CartShippingRate;

        $cartShippingRate->carrier = $this->getCode();
        // Usamos la clave 'title' configurada para mostrar "Recogida en tienda"
        $cartShippingRate->carrier_title = $this->getConfigData('title');
        $cartShippingRate->method = $this->getMethod();
        $cartShippingRate->method_title = $this->getConfigData('title');
        $cartShippingRate->method_description = $this->getConfigData('description');
        $cartShippingRate->price = 0;
        $cartShippingRate->base_price = 0;

        return $cartShippingRate;
    }
}
