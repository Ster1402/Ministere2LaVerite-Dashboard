@if ($errors->any())
    <ul class="mt-3 list-disc list-inside text-sm text-red-600 dark:text-red-400">
        @foreach ($errors->all() as $error)
            <li class="alert alert-danger">{{ $error }}</li>
        @endforeach
    </ul>
@endif
