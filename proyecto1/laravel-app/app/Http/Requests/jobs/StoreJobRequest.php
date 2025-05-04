<?php

namespace App\Http\Requests\jobs;

use Illuminate\Foundation\Http\FormRequest;

class StoreJobRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Put your access logic here
        return true; // or auth()->user()->isAdmin()
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ["required","string","min:3","unique:job_listing,name"],
            "salary" => ["nullable","numeric"]
        ];
    }

    public function messages()
    {
        return [
            "name.required" => "É requerido indicar o nome!!",
            "salary.numeric" => "O salario debe ser un numero"
        ];
    }
}
