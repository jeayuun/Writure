<?php

namespace App\Traits;

use App\Models\Language;
use Illuminate\Support\Facades\App;

trait LanguageValidator
{
    protected function validateLanguage(string $lang): void
    {
        $languages = Language::pluck('slug')->toArray();
        if (!in_array($lang, $languages)) {
            abort(404, 'Invalid language code');
        }
    }

    protected function getValidLanguage(string $lang): string
    {
        $languages = Language::pluck('slug')->toArray();
        return in_array($lang, $languages) ? $lang : App::getLocale();
    }

    protected function getLanguageSwitchLinks(string $currentLang, string $currentUrl): array
    {
        $languages = Language::all();
        $links = [];

        foreach ($languages as $language) {
            if ($language->slug !== $currentLang) {
                $translatedSlug = $this->getTranslatedSlug($language->slug);
                $links[$language->slug] = $this->generateLanguageUrl($currentUrl, $translatedSlug);
            }
        }

        return $links;
    }

    protected function getTranslatedSlug(string $languageSlug): ?string
    {
        return null;
    }

    protected function generateLanguageUrl(string $currentUrl, string $translatedSlug): string
    {
        return preg_replace('/\/[a-z]{2}\//', "/{$translatedSlug}/", $currentUrl);
    }
}