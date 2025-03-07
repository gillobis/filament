<?php

namespace App\Enums;

enum AvvisoPrioritaEnum: string
{
    case WARNING = 'WARNING';
    case ERRORE = 'ERRORE';

    public static function getLabels(): array
    {
        return [
            self::WARNING => 'Warning',
            self::ERRORE => 'Errore',
        ];
    }

    public static function getColors(): array
    {
        return [
            self::WARNING => 'warning',
            self::ERRORE => 'danger',
        ];
    }
}
