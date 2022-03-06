<?php

namespace O21\CryptoPaymentApi\Objects;

use O21\Support\FreeObject;

/**
 * @property-read int $blocks
 * @property-read float $valuePerKbyte
 * @property-read float $valuePerByte
 * @property-read float $valuePerByteInSatoshi
 * @property-read int $approximateTimeInMinutes
 */
class Fee extends FreeObject
{

}