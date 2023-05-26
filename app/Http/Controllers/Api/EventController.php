<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Statamic\Facades\Entry;
use Statamic\Fields\Value;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EventController extends Controller
{
  /**
   * Get all events from the Event collection
   * @return \Illuminate\Http\Response
   */
  public function get()
  { 
    $events = Entry::query()->where('collection', 'events')->where('event_date', '>', date('Y-m-d'))->orderBy('event_date')->get();

    // filter out everything execpt the id and the title
    $events = $events->map(function ($event) {
      return [
        'id' => $event->id(),
        'title' => $event->title,
      ];
    });

    return response()->json($events);
  }

}
