<?php

namespace App\Providers;

use App\Models\Language;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Using a composer to share languages and the current language with all views
        View::composer('*', function ($view) {
            $languages = Cache::remember('languages', now()->addHours(24), function () {
                // Ensure the Language model is imported or use the full namespace
                return Language::all();
            });

            // Stop if the languages table is empty to prevent further errors.
            if ($languages->isEmpty()) {
                $view->with([
                    'languages' => collect(),
                    'currentLanguage' => null,
                    'lang' => config('app.fallback_locale'), // Use app's fallback locale
                ]);
                return;
            }

            $langSegment = request()->segment(1);
            $currentLanguage = null;
            $lang = null;

            // Check if the first URL segment is a valid language slug
            if ($langSegment && $languages->contains('slug', $langSegment)) {
                $currentLanguage = $languages->firstWhere('slug', '===', $langSegment);
                $lang = $langSegment;
            } else {
                // If not, fall back to the default language
                $defaultLanguage = $languages->firstWhere('is_default', true);
                if ($defaultLanguage) {
                    $currentLanguage = $defaultLanguage;
                    $lang = $defaultLanguage->slug;
                } else {
                    // As a last resort, use the first language in the database
                    $currentLanguage = $languages->first();
                    $lang = $currentLanguage->slug;
                }
            }

            $view->with([
                'languages' => $languages,
                'currentLanguage' => $currentLanguage,
                'lang' => $lang,
            ]);
        });
    }
}
