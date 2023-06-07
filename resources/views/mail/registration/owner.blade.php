<x-mail::message>
<div class="text-base font-bold pb-large">
  Neue Anmeldung für
  <div class="font-regular">{{ $data->event }}</div>
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
