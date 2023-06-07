<x-mail::message>
  <div class="text-base font-regular pb-large">
    Vielen Dank für Deine Anmeldung für
    <div class="font-bold">{{ $data->event }}</div>
  </div>
  <div class="text-base pb-base">
    Nachfolgend eine Zusammenfassung Deiner Angaben:
  </div>
  <div class="text-base pb-base">
    <strong>Vorname</strong><br>
    {{ $data->firstname }}
  </div>
  <div class="text-base pb-base">
    <strong>Name</strong><br>
    {{ $data->name }}
  </div>
  <div class="text-base pb-base">
    <strong>E-Mail</strong><br>
    {{ $data->email }}
  </div>
  <div class="text-base pb-base">
    <strong>Telefon</strong><br>
    {{ $data->phone }}
  </div>
  <div class="text-base pb-base">
    <strong>Nachricht</strong><br>
    {!! nl2br($data->message) !!}
  </div>
</x-mail::message>
