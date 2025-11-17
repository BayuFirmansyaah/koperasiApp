<?php

use Carbon\Carbon;

if (!function_exists('formatDate')) {
    /**
     * Safely format date with null checking and string handling
     *
     * @param mixed $date
     * @param string $format
     * @return string
     */
    function formatDate($date, string $format = 'd/m/Y'): string
    {
        if (!$date) {
            return '-';
        }
        
        try {
            if (is_string($date)) {
                return Carbon::parse($date)->format($format);
            }
            
            if ($date instanceof Carbon || $date instanceof DateTime) {
                return $date->format($format);
            }
        } catch (Exception $e) {
            return '-';
        }
        
        return '-';
    }
}

if (!function_exists('formatDateTime')) {
    /**
     * Safely format datetime with null checking
     *
     * @param mixed $date
     * @return string
     */
    function formatDateTime($date): string
    {
        return formatDate($date, 'd/m/Y H:i');
    }
}

if (!function_exists('formatDateLong')) {
    /**
     * Safely format date in long format with null checking
     *
     * @param mixed $date
     * @return string
     */
    function formatDateLong($date): string
    {
        return formatDate($date, 'd F Y');
    }
}

if (!function_exists('formatDateTimeLong')) {
    /**
     * Safely format datetime in long format with null checking
     *
     * @param mixed $date
     * @return string
     */
    function formatDateTimeLong($date): string
    {
        return formatDate($date, 'd F Y H:i');
    }
}
