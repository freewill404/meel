<?php

namespace App\Http\Controllers\User;

use App\Http\Rules\UsableRecurringWhen;
use App\Models\Feed;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FeedsController
{
    public function index()
    {
        /** @var User $user */
        $user = Auth::user();

        return view('feeds.index', [
            'feeds' => $user->feeds,
            'noFeedsYet' => $user->feeds_created === 0,
        ]);
    }

    public function create()
    {
        return view('feeds.create', [
            'user' => $user = Auth::user(),
            'feedCount' => $user->feeds()->count(),
        ]);
    }

    public function store(Request $request)
    {
        $values = $request->validate([
            'url' => 'required|string|url|max:255',
            'when' => ['nullable', 'present', 'string', 'max:255', new UsableRecurringWhen],
            'group_new_entries' => 'required|boolean',
        ]);

        $request->user()->feeds()->create($values);

        return redirect()->route('user.feeds');
    }

    public function show(Feed $feed)
    {
        return view('feeds.show', [
            'feed' => $feed,
        ]);
    }

    public function update(Request $request, Feed $feed)
    {
        $values = $request->validate([
            'url' => 'required|string|url|max:255',
            'when' => ['nullable', 'present', 'string', 'max:255', new UsableRecurringWhen],
            'group_new_entries' => 'required|boolean',
        ]);

        $feed->update($values);

        return redirect()->route('user.feeds');
    }

    public function delete(Feed $feed)
    {
        $feed->delete();

        return redirect()->route('user.feeds');
    }
}
