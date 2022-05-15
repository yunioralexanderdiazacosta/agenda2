@component('mail::message')
{{ auth()->user()->name}}, le ha asignado una tarea: {{$title}}
@endcomponent