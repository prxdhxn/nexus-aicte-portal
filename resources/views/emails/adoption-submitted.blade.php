<x-mail::message>
# New Adoption Submitted

An institute has submitted an adoption plan for your curriculum.

**Institute:** {{ $adoption->institute->name }}  
**Curriculum:** {{ $curriculum->title }}

<x-mail::button :url="route('curricula.show', $curriculum)">
View Adoption
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
