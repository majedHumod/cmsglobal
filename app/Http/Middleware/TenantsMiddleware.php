<?php

namespace App\Http\Middleware;

use App\Models\Tenant;
use App\Services\TenantService;
use Illuminate\Support\Facades\DB;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TenantsMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
   $host = $request->getHost();
   $tenant = Tenant::on('system')->where('domain', $host)->firstOrFail();
    TenantService::switchToTenant($tenant);
   //$tenant = Tenant::where ('domain',$host)->first();
//    DB::purge('system');
//    config(['database.connections.tenant.database' => $tenant->db_name]);
//    DB::connection('tenant')->reconnect();
//    DB::setDefaultConnection('tenant');
   //dd(DB::connection()->getDatabaseName());

        return $next($request);
    }
}
