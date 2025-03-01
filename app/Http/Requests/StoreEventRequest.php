<?php

namespace App\Http\Requests;

use App\DTOs\events\EventDTO;
use App\Models\Event;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use JetBrains\PhpStorm\Pure;

class StoreEventRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows('create', Event::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|min:3',
            'assemblies' => 'required|array'
        ];
    }

    public function toDTO(): EventDTO
    {
        $eventDTO = new EventDTO(
            title: $this->input('title'),
            description: $this->input('description'),
            from: $this->input('from'),
            to: $this->input('to'),
            user: \Auth::id()
        );

        $eventDTO->assemblies = collect($this->input('assemblies'));

        return $eventDTO;
    }
}
