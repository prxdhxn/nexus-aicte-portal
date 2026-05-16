<x-mail::message>
# New Curriculum Published

A new model curriculum has been published on the Nexus Portal.

**Title:** {{ $curriculum->title }}  
**Deadline for Adoption:** {{ \Carbon\Carbon::parse($curriculum->deadline)->format('d M Y') }}

<x-mail::button :url="route('curricula.index')">
View Curricula
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
