<?php

namespace App\Http\Controllers;

use App\Mail\EventDeletedNotification;
use App\Mail\EventRegistrationConfirmation;
use App\Mail\NewEventNotification;
use App\Models\Category;
use App\Models\Event;
use App\Models\EventImage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{

    public function index()
    {
        $events = Event::with('category', 'organizer', 'images')
                       ->orderBy('event_date', 'asc')
                       ->paginate(9);

        return view('index', compact('events'));
    }

    public function show(Event $event)
    {
        $event->load('category', 'organizer', 'registeredUsers', 'images');
        return view('events.show', compact('event'));
    }


    public function create()
    {
        $categories = Category::all();
        return view('events.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'            => 'required|string|max:255',
            'description'      => 'required|string',
            'event_date'       => 'required|date|after:now',
            'location'         => 'required|string|max:255',
            'city'             => 'required|string|max:100',
            'max_participants' => 'nullable|integer|min:1',
            'category_id'      => 'required|exists:categories,id',
            'images'           => 'nullable|array|max:10',
            'images.*'         => 'image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        $event = Event::create([
            'title'            => $validated['title'],
            'description'      => $validated['description'],
            'event_date'       => $validated['event_date'],
            'location'         => $validated['location'],
            'city'             => $validated['city'],
            'max_participants'  => $validated['max_participants'] ?? null,
            'category_id'      => $validated['category_id'],
            'user_id'          => auth()->id(),
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $file) {
                $path = $file->store('events', 'public');

                EventImage::create([
                    'event_id'   => $event->id,
                    'path'       => $path,
                    'is_cover'   => $index === 0,
                    'sort_order' => $index,
                ]);
            }
            $firstImage = $event->images()->first();
            if ($firstImage) {
                $event->update(['image' => $firstImage->path]);
            }
        }

        $recipients = User::whereHas('roles', fn($q) => $q->where('name', 'user'))->get();
        foreach ($recipients as $user) {
            try {
                Mail::to($user->email)->send(new NewEventNotification($event));
            } catch (\Exception $e) {
                Log::error('Не удалось отправить email о новом событии: ' . $e->getMessage());
            }
        }

        return redirect()->route('home')
                         ->with('success', 'Мероприятие успешно создано!');
    }


    public function edit(Event $event)
    {
        if (!auth()->user()->canManageEvent($event)) {
            abort(403, 'У вас нет прав на редактирование этого мероприятия.');
        }

        $event->load('images');
        $categories = Category::all();
        return view('events.edit', compact('event', 'categories'));
    }

    public function update(Request $request, Event $event)
    {
        if (!auth()->user()->canManageEvent($event)) {
            abort(403);
        }

        $validated = $request->validate([
            'title'            => 'required|string|max:255',
            'description'      => 'required|string',
            'event_date'       => 'required|date',
            'location'         => 'required|string|max:255',
            'city'             => 'required|string|max:100',
            'max_participants' => 'nullable|integer|min:1',
            'category_id'      => 'required|exists:categories,id',
            'images'           => 'nullable|array|max:10',
            'images.*'         => 'image|mimes:jpg,jpeg,png,webp|max:5120',
            'cover_image_id'   => 'nullable|exists:event_images,id',
            'delete_images'    => 'nullable|array',
            'delete_images.*'  => 'exists:event_images,id',
        ]);

        $event->update([
            'title'            => $validated['title'],
            'description'      => $validated['description'],
            'event_date'       => $validated['event_date'],
            'location'         => $validated['location'],
            'city'             => $validated['city'],
            'max_participants' => $validated['max_participants'] ?? null,
            'category_id'      => $validated['category_id'],
        ]);

        if (!empty($validated['delete_images'])) {
            $toDelete = EventImage::where('event_id', $event->id)
                ->whereIn('id', $validated['delete_images'])->get();
            foreach ($toDelete as $img) {
                $img->delete();
            }
        }

        if (!empty($validated['cover_image_id'])) {
            EventImage::where('event_id', $event->id)->update(['is_cover' => false]);
            EventImage::where('id', $validated['cover_image_id'])->update(['is_cover' => true]);
        }

        if ($request->hasFile('images')) {
            $existingCount = $event->images()->count();
            foreach ($request->file('images') as $index => $file) {
                $path = $file->store('events', 'public');
                EventImage::create([
                    'event_id'   => $event->id,
                    'path'       => $path,
                    'is_cover'   => ($existingCount === 0 && $index === 0),
                    'sort_order' => $existingCount + $index,
                ]);
            }
        }

        $cover = $event->images()->where('is_cover', true)->first()
               ?? $event->images()->first();
        if ($cover) {
            $event->update(['image' => $cover->path]);
        }

        return redirect()->route('events.show', $event)
                         ->with('success', 'Мероприятие успешно обновлено!');
    }


    public function destroy(Event $event)
    {
        if (!auth()->user()->canManageEvent($event)) {
            abort(403);
        }

        $organizer      = $event->organizer;
        $eventTitle     = $event->title;
        $adminName      = auth()->user()->name;
        $deletedByAdmin = auth()->user()->isAdmin() && $event->user_id !== auth()->id();

        $event->images()->each(fn($img) => $img->delete());

        if ($event->image && !EventImage::where('path', $event->image)->exists()) {
            Storage::disk('public')->delete($event->image);
        }

        $event->delete();

        if ($deletedByAdmin && $organizer) {
            try {
                Mail::to($organizer->email)->send(
                    new EventDeletedNotification($eventTitle, $organizer, $adminName)
                );
            } catch (\Exception $e) {
                Log::error('Не удалось отправить email об удалении события: ' . $e->getMessage());
            }
        }

        return redirect()->route('home')
                         ->with('success', 'Мероприятие успешно удалено!');
    }


    public function destroyImage(Event $event, EventImage $image)
    {
        if (!auth()->user()->canManageEvent($event)) {
            abort(403);
        }

        if ($image->event_id !== $event->id) {
            abort(404);
        }

        $wasCover = $image->is_cover;
        $image->delete();

        if ($wasCover) {
            $next = $event->images()->first();
            if ($next) {
                $next->update(['is_cover' => true]);
                $event->update(['image' => $next->path]);
            } else {
                $event->update(['image' => null]);
            }
        }

        return back()->with('success', 'Изображение удалено.');
    }


    public function registerParticipant(Event $event)
    {
        $user = auth()->user();

        if ($event->registeredUsers()->where('user_id', $user->id)->exists()) {
            return back()->with('error', 'Вы уже записаны на это мероприятие.');
        }

        if ($event->max_participants && $event->registeredUsers()->count() >= $event->max_participants) {
            return back()->with('error', 'К сожалению, все места заняты.');
        }

        $event->registeredUsers()->attach($user->id);

        try {
            Mail::to($user->email)->send(new EventRegistrationConfirmation($event, $user));
        } catch (\Exception $e) {
            Log::error('Не удалось отправить email о регистрации: ' . $e->getMessage());
        }

        return back()->with('success', 'Вы успешно записались на мероприятие!');
    }

    public function unregisterParticipant(Event $event)
    {
        auth()->user()->registeredEvents()->detach($event->id);
        return back()->with('success', 'Запись отменена.');
    }
}
