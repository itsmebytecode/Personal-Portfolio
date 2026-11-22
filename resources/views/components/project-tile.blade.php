@props(['project'])
<article class="project-item">
    <div class="project-card-top"><h3>{{ $project['name'] }}</h3><span class="project-card-mark" aria-hidden="true">↗</span></div>
    @if($project['description'])<p>{{ $project['description'] }}</p>@endif
    @if($project['url'])
        @php($url = $project['url'])
        <a class="project-link-button" href="{{ str_starts_with($url, 'http://') || str_starts_with($url, 'https://') ? $url : 'https://'.$url }}" target="_blank" rel="noopener noreferrer">View project <x-icon name="external" size="13" /></a>
    @endif
</article>
