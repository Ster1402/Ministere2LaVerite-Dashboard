<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('System Deployment') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                @if ($success)
                    <div class="alert alert-success mb-4">
                        <span class="font-bold">Success!</span>
                        <span>System deployment completed in {{ $executionTime }} seconds.</span>
                    </div>

                    <h3 class="text-lg font-medium mb-3">Deployment Log:</h3>
                    <div class="bg-gray-100 p-4 rounded mb-6">
                        <ul class="list-disc pl-6">
                            @foreach ($log as $entry)
                                <li class="mb-1">{{ $entry }}</li>
                            @endforeach
                        </ul>
                    </div>
                @else
                    <div class="alert alert-danger mb-4">
                        <span class="font-bold">Error!</span>
                        <span>System deployment failed.</span>
                    </div>

                    <h3 class="text-lg font-medium mb-3">Error Details:</h3>
                    <div class="bg-gray-100 p-4 rounded mb-6">
                        <p class="font-medium">{{ $error }}</p>
                    </div>

                    <h3 class="text-lg font-medium mb-3">Error Trace:</h3>
                    <div class="bg-gray-100 p-4 rounded mb-6 overflow-auto">
                        <pre class="text-sm">{{ $trace }}</pre>
                    </div>
                @endif

                <div class="mt-6 flex space-x-4">
                    <a href="{{ route('dashboard') }}" class="btn btn-primary">
                        Return to Dashboard
                    </a>

                    @if ($success)
                        <a href="{{ route('admin.deploy-update') }}" class="btn btn-secondary">
                            Run Again
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
