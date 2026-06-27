<?php

namespace App\Http\Controllers;

use App\Support\RoleMenu;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        $menu = RoleMenu::for($user?->role);

        return view('dashboard.index', [
            'user' => $user,
            'menu' => $menu,
        ]);
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
