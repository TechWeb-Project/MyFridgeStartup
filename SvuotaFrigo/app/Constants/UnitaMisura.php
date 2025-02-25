<?php
namespace App\Constants;

class UnitaMisura 
{
    const PEZZI  = 'pezzi';
    const GRAMMI = 'grammi';
    const FETTE  = 'fette';
    const ML     = 'ml';

    public static function all()
    {
        return [
            self::PEZZI, 
            self::GRAMMI, 
            self::FETTE, 
            self::ML
        ];
    }
}
