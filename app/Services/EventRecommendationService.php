<?php

namespace App\Services;

use App\Models\Event;
use App\Models\EventClickLog;
use App\Models\User;
use Illuminate\Support\Collection;

class EventRecommendationService
{
    /**
     * Urutkan koleksi event untuk seorang user berdasarkan rumus:
     * S = (Bobot_Minat x Data_Profil) + (Bobot_Perilaku x Data_Klik)
     *
     * @param  Collection<int, Event>  $events
     * @return Collection<int, Event>
     */
    public function scoreEvents(User $user, Collection $events): Collection
    {
        $bobotMinat = (float) config('recommendation.bobot_minat');
        $bobotPerilaku = (float) config('recommendation.bobot_perilaku');

        $interestCategoryIds = $user->interestCategories()->pluck('categories.id')->all();

        $clickCountsByCategory = EventClickLog::query()
            ->where('user_id', $user->id)
            ->selectRaw('category_id, count(*) as total')
            ->groupBy('category_id')
            ->pluck('total', 'category_id');

        $maxClicks = $clickCountsByCategory->max() ?: 1;

        return $events
            ->map(function (Event $event) use ($interestCategoryIds, $clickCountsByCategory, $maxClicks, $bobotMinat, $bobotPerilaku) {
                $dataProfil = in_array($event->category_id, $interestCategoryIds, true) ? 1 : 0;
                $dataKlik = $clickCountsByCategory->get($event->category_id, 0) / $maxClicks;

                $event->recommendation_score = ($bobotMinat * $dataProfil) + ($bobotPerilaku * $dataKlik);

                return $event;
            })
            ->sortByDesc('recommendation_score')
            ->values();
    }
}
