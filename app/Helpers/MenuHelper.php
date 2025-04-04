<?php
// app/Helpers/MenuHelper.php

namespace App\Helpers;

use App\Models\Notificacion;
use App\Models\NotificacionesChecklist;

class MenuHelper
{
    public static function getNotificacionesNuevas()
    {
        return NotificacionesChecklist::where('nueva', true)->count();
    }
}
