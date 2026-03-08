<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectStaleFilamentEditRequests
{
    public function handle(Request $request, Closure $next): Response
    {
        // Sadece gerçek API isteklerini (livewire hariç) bypass et
        if ($request->expectsJson() && !$request->is('livewire/*')) {
            return $next($request);
        }

        // Panel veya Livewire isteklerinde aktiviteyi güncelle
        // Bu sayede kullanıcı form doldururken de (wire:model POST istekleri) aktivite kaydedilir
        if ($request->is('yonetim/*') || $request->is('livewire/*')) {
            $request->session()->put('filament_last_activity_at', time());
        }

        // Sadece edit/create sayfalarında GET isteklerinde (sayfa yenileme) timeout kontrolü yap
        if ($request->isMethod('GET') && 
            ($request->is('yonetim/*/edit') || $request->is('yonetim/*/create'))) {
            
            $now = time();
            $lastActivityAt = (int) $request->session()->get('filament_last_activity_at', 0);
            $timeoutMinutes = (int) env('FILAMENT_STALE_PAGE_TIMEOUT_MINUTES', 45);
            $timeoutSeconds = max(60, $timeoutMinutes * 60);

            if ($lastActivityAt > 0 && ($now - $lastActivityAt) > $timeoutSeconds) {
                return redirect('/yonetim')
                    ->with('warning', 'Sayfa uzun sure bekledigi icin guvenli olarak panel ana sayfasina yonlendirildi.');
            }
        }

        return $next($request);
    }
}
