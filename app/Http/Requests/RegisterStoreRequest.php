<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class RegisterStoreRequest extends FormRequest
{
  /**
   * Determine if the user is authorized to make this request.
   *
   * @return bool
   */

  public function authorize()
  {
    return true;
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array
   */

  public function rules()
  {
    return [
      'name' => 'required',
      'firstname' => 'required',
      'address' => 'required',
      'zip_city' => 'required',
      'phone' => 'required',
      'email' => 'required|email',
    ];
  }

  /**
   * Custom message for validation
   *
   * @return array
   */

  public function messages()
  {
    return [
      'name.required' => 'Name wird benötigt!',
      'firstname.required' => 'Vorname wird benötigt!',
      'address.required' => 'Adresse wird benötigt!',
      'zip_city.required' => 'PLZ/Ort wird benötigt!',
      'phone.required' => 'Telefon wird benötigt!',
      'email.required' => 'E-Mail wird benötigt!',
      'email.email' => 'E-Mail ist ungültig!',
    ];
  }
}