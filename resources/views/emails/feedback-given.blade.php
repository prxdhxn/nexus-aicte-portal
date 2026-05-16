<x-mail::message>
# Adoption Feedback Given

Your adoption plan for **{{ $adoption->curriculum->title }}** has been reviewed by the SME.

**Approval Score:** {{ $adoption->approval_score }} / 100

**Feedback:**  
{{ $adoption->feedback }}

<x-mail::button :url="route('adoptions.show', $adoption)">
View Details
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
