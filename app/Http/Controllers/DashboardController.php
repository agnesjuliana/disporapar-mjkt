<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\ParticipantRegistration;
use App\Models\Tenant;
use App\Models\User;
use App\Services\EventRecommendationService;
use App\Support\RoleMenu;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class DashboardController extends Controller
{
    public function index(Request $request, EventRecommendationService $recommendationService): View
    {
        $user = $request->user();
        $menu = RoleMenu::for($user?->role);

        return view('dashboard.index', [
            'user' => $user,
            'menu' => $menu,
            'recommendedEvents' => $user ? $this->recommendedEventsFor($user, $recommendationService) : collect(),
        ]);
    }

    /**
     * @return Collection<int, Event>
     */
    private function recommendedEventsFor(User $user, EventRecommendationService $recommendationService): Collection
    {
        if (! in_array($user->role, ['MASYARAKAT', 'TENANT'], true)) {
            return collect();
        }

        $candidates = Event::query()
            ->with(['organizer', 'venue'])
            ->whereIn('status', ['APPROVED', 'ONGOING'])
            ->where(function (Builder $query) {
                $query->whereNull('event_end')->orWhere('event_end', '>=', now());
            })
            ->when($user->role === 'MASYARAKAT', function (Builder $query) use ($user) {
                $registeredEventIds = ParticipantRegistration::query()
                    ->where('user_id', $user->id)
                    ->pluck('event_id');
                $query->whereNotIn('id', $registeredEventIds);
            })
            ->when($user->role === 'TENANT', function (Builder $query) use ($user) {
                $tenant = Tenant::query()->where('user_id', $user->id)->first();
                $registeredEventIds = $tenant
                    ? EventRegistration::query()
                        ->where('tenant_id', $tenant->id)
                        ->where('registration_status', '!=', 'CANCELLED')
                        ->pluck('event_id')
                    : collect();
                $query->whereNotIn('id', $registeredEventIds);
            })
            ->latest('event_start')
            ->limit(20)
            ->get();

        return $recommendationService->scoreEvents($user, $candidates)->take(6);
    }

    public function profile(Request $request): View
    {
        $user = $request->user()?->load(['tenant', 'eventOrganizer']);
        $menu = RoleMenu::for($user?->role);

        return view('dashboard.profile', [
            'user' => $user,
            'menu' => $menu,
        ]);
    }

    public function placeholder(Request $request, string $page): View
    {
        $user = $request->user();
        $menu = RoleMenu::for($user?->role);
        $item = collect($menu['items'])
            ->flatMap(fn (array $item) => [$item, ...($item['children'] ?? [])])
            ->firstWhere('page', $page);

        abort_if(! $item, 404);

        return view('dashboard.placeholder', [
            'user' => $user,
            'menu' => $menu,
            'item' => $item,
        ]);
    }
}
