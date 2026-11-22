@props(['name', 'skills'])
<div class="skill-group"><h3>{{ $name }}</h3><div class="flex flex-wrap gap-2">@foreach($skills as $skill)<span class="skill-chip">{{ $skill }}</span>@endforeach</div></div>
