<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\MaintenanceModel;

class MaintenanceMode
{

    public function handle($request, Closure $next)
    {
        $setting = MaintenanceModel::first();
        if ($setting && $setting->maintenance_mode == 0) {

            // Admin routes allow   
            if ($request->is('admin*')) {
                return $next($request);
            }

            return response()->view('maintenance');
        }

        return $next($request);
    }

}
