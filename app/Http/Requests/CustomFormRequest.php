<?php namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;

class CustomFormRequest extends FormRequest {
    public function response(array $errors)
    {
        if ($this->expectsJson()) {
            $error_response = [];
            foreach ($errors as $field_name => $error) {
               $error_response[] = [
                   'name' => $field_name,
                   'message' => $error[0]
               ];
            }
            return new JsonResponse($error_response, 422);
        }

        return $this->redirector->to($this->getRedirectUrl())
            ->withInput($this->except($this->dontFlash))
            ->withErrors($errors, $this->errorBag);
    }
}