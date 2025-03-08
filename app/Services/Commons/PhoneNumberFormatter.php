<?php

namespace App\Services\Commons;

class PhoneNumberFormatter
{
    /**
     * Reformat the phone number by removing spaces and country code.
     *
     * @param string $phoneNumber
     * @param string $countryCode
     * @return string
     */
    public static function reformat(string $phoneNumber, string $countryCode = '+237'): string
    {
        // Remove spaces
        $phoneNumber = str_replace(' ', '', $phoneNumber);

        // Remove country code
        if (strpos($phoneNumber, $countryCode) === 0) {
            $phoneNumber = substr($phoneNumber, strlen($countryCode));
        }

        return $phoneNumber;
    }
}
