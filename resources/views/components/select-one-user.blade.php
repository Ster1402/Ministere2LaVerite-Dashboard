@props(['title' => 'Utilisateur', 'name' => 'user'])

<x-select-users :users="[$user]" title="{{ $title }}" name="{{ $name }}" />
