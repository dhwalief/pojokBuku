<?php

namespace App\Http\Middleware;

use Closure;
// Import the necessary classes from Illuminate
use Illuminate\Container\Attributes\Log as AttributesLog;
// Import the Log facade for logging
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;

class PdfSecurityMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Pastikan user sudah login
        if (!Auth::check()) {
            abort(403, 'Authentication required');
        }

        $user = Auth::user();
        $userKey = 'pdf-access:' . $user->id;
        
        // Rate limiting: maksimal 10 akses PDF per menit per user
        if (RateLimiter::tooManyAttempts($userKey, 10)) {
            $seconds = RateLimiter::availableIn($userKey);
            abort(429, "Too many PDF access attempts. Try again in {$seconds} seconds.");
        }

        RateLimiter::hit($userKey, 60); // 60 detik window

        // Validasi Referer untuk mencegah hotlinking
        $referer = $request->header('referer');
        $allowedReferers = [
            config('app.url'),
            request()->getSchemeAndHttpHost()
        ];
        
        if ($referer && !$this->isValidReferer($referer, $allowedReferers)) {
            Log::warning('Invalid referer for PDF access', [
                'user_id' => $user->id,
                'referer' => $referer,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);
            abort(403, 'Invalid referer');
        }

        // Validasi User-Agent untuk mencegah bot
        $userAgent = $request->userAgent();
        if ($this->isSuspiciousUserAgent($userAgent)) {
            Log::warning('Suspicious user agent for PDF access', [
                'user_id' => $user->id,
                'user_agent' => $userAgent,
                'ip' => $request->ip()
            ]);
            abort(403, 'Suspicious user agent detected');
        }

        // Cek apakah request menggunakan tools download seperti wget, curl, dll
        if ($this->isDownloadTool($userAgent)) {
            Log::warning('Download tool detected for PDF access', [
                'user_id' => $user->id,
                'user_agent' => $userAgent,
                'ip' => $request->ip()
            ]);
            abort(403, 'Download tools not allowed');
        }

        // Block akses jika bukan dari browser utama
        if (!$this->isValidBrowser($userAgent)) {
            abort(403, 'Please use a standard web browser');
        }

        return $next($request);
    }

    /**
     * Cek apakah referer valid
     */
    private function isValidReferer($referer, $allowedReferers)
    {
        foreach ($allowedReferers as $allowed) {
            if (strpos($referer, $allowed) === 0) {
                return true;
            }
        }
        return false;
    }

    /**
     * Deteksi user agent yang mencurigakan
     */
    private function isSuspiciousUserAgent($userAgent)
    {
        $suspiciousPatterns = [
            '/bot/i',
            '/crawler/i',
            '/spider/i',
            '/scraper/i',
            '/automated/i',
            '/python/i',
            '/requests/i',
        ];

        foreach ($suspiciousPatterns as $pattern) {
            if (preg_match($pattern, $userAgent)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Deteksi tools download
     */
    private function isDownloadTool($userAgent)
    {
        $downloadTools = [
            'wget',
            'curl',
            'HTTPie',
            'Postman',
            'Insomnia',
            'Thunder Client',
            'REST Client',
            'Advanced REST client',
            'download',
            'fetch',
            'aria2'
        ];

        $userAgentLower = strtolower($userAgent);
        
        foreach ($downloadTools as $tool) {
            if (strpos($userAgentLower, strtolower($tool)) !== false) {
                return true;
            }
        }

        return false;
    }

    /**
     * Validasi apakah menggunakan browser yang valid
     */
    private function isValidBrowser($userAgent)
    {
        $validBrowsers = [
            'Chrome',
            'Firefox',
            'Safari',
            'Edge',
            'Opera',
            'Brave'
        ];

        foreach ($validBrowsers as $browser) {
            if (strpos($userAgent, $browser) !== false) {
                return true;
            }
        }

        return false;
    }
}