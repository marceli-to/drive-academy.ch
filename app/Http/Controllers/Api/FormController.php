<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Statamic\Facades\Entry;
use Statamic\Fields\Value;
use App\Http\Requests\RegisterStoreRequest;
use Illuminate\Support\Facades\Notification;
use App\Notifications\RegisterOwnerEmail;
use App\Notifications\RegisterUserEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class FormController extends Controller
{
  /**
   * @param RegisterStoreRequest $request 
   * @return \Illuminate\Http\Response
   */
  public function store(RegisterStoreRequest $request)
  { 
    $title = $request->input('option') . ' – ' . $request->input('firstname') . ' ' . $request->input('name') . ', ' . $request->input('email');
    $registration = Entry::make()
    ->collection('registration')
    ->slug($title)
    ->data(
      array_merge(
        [
          'title' => $title,
        ], 
        $request->all()
      )
    );
    
    $registration->save();
    Notification::route('mail', env('MAIL_TO'))->notify(new RegisterOwnerEmail($registration));
    Notification::route('mail', $request->input('email'))->notify(new RegisterUserEmail($registration));
    return response()->json($registration->id, 201);
  }

}
