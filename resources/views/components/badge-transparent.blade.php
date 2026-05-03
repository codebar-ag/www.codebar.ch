@props(['label','title' => '','classAttributes' => ""])

<x-ui.badge :label="$label" :title="$title" variant="outline" class-attributes="{{ $classAttributes }}" />
