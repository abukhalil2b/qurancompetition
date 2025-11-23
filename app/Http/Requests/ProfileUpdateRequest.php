<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
	public function rules(): array
	{
		return [
			'name' => ['string', 'max:255'],
			'email' => ['number', 'max:255', Rule::unique(User::class)->ignore($this->user()->id)],
			'name' => ['required', 'string', 'max:255'],
			'phone' => ['required', 'digits:8'],
			'title' => ['nullable', 'string', 'max:50'],
			'first_name' => ['nullable', 'string', 'max:20'],
			'second_name' => ['nullable', 'string', 'max:20'],
			'third_name' => ['nullable', 'string', 'max:20'],
			'last_name' => ['nullable', 'string', 'max:20'],
			'nationality' => ['nullable', 'string', 'max:20'],
		];
	}
}
