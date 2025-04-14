<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Language;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        // Kiểm tra xem đây có phải là route language.change không
        if ($request->route() && $request->route()->getName() === 'language.change') {
            // Đối với route thay đổi ngôn ngữ, bỏ qua việc kiểm tra và chuyển hướng
            return $next($request);
        }
        
        // Lấy danh sách ngôn ngữ hoạt động
        $languages = Language::getActive()->pluck('code')->toArray();
        
        // Kiểm tra segment đầu tiên từ URL (có thể là mã ngôn ngữ)
        $locale = $request->segment(1);
        
        // Kiểm tra xem locale trong URL có hợp lệ không
        $validLocale = $locale && in_array($locale, $languages);
        
        if ($validLocale) {
            // Nếu URL có ngôn ngữ hợp lệ, sử dụng nó
            app()->setLocale($locale);
            
            // Lưu lựa chọn ngôn ngữ vào cookie
            cookie()->queue('locale', $locale, 60*24*30); // Cookie có thời hạn 30 ngày
        } else {
            // Nếu không có ngôn ngữ trong URL hoặc ngôn ngữ không hợp lệ
            
            // Kiểm tra xem người dùng đã lưu ngôn ngữ trong cookie chưa
            $localeCookie = $request->cookie('locale');
            
            if ($localeCookie && in_array($localeCookie, $languages)) {
                // Sử dụng ngôn ngữ từ cookie
                $localeToUse = $localeCookie;
            } else {
                // Mặc định sử dụng tiếng Việt
                $defaultLanguage = Language::where('code', 'en')->first() ?? Language::getDefault();
                $localeToUse = $defaultLanguage->code;
            }
            
            // Đặt ngôn ngữ cho ứng dụng
            app()->setLocale($localeToUse);
            
            // Chỉ chuyển hướng nếu đang ở trang gốc '/'
            if ($request->path() === '/') {
                return redirect('/' . $localeToUse);
            }
        }
        
        return $next($request);
    }
}