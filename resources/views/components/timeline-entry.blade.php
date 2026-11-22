@props(['entry', 'first' => false])
<li class="timeline-entry {{ $first ? 'is-first' : '' }}"><span class="timeline-dot"></span><div class="flex items-start justify-between gap-3"><h3>{{ $entry['title'] }}</h3><span class="timeline-year">{{ $entry['year'] }}</span></div><p>{{ $entry['detail'] }}</p></li>
