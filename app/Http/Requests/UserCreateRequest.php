<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;

class UserCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'username' => 'required|string|max:255|regex:/^[a-zA-Z0-9_-]{3,16}$/',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($this->user() ? $this->user()->id : null, 'id')->whereNull('deleted_at'), 'regex: /^([a-zA-Z0-9._%+-]+)@([a-zA-Z0-9.-]+)\.([a-zA-Z]{2,})$/'],
            'password' => 'required|string|regex:/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/',
        ];
    }

    public function messages(): array
    {
        return [
            'username.regex' => 'نام وارد شده معتبر نیست!',
            'email.regex' => 'ایمیل وارد شده معتبر نیست',
            'password.regex' => 'پسورد وارد شده نامعتبر است!',
            'email.unique' => 'این کاربر قبلا در سیستم ثبت شده است'
        ];
    }

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {

        if ($this->expectsJson()) {
            throw new HttpResponseException(response()->json(['errors' => $validator->errors()], JsonResponse::HTTP_UNPROCESSABLE_ENTITY));
        }

        parent::failedValidation($validator);
    }
}
