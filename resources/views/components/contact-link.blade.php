@props(['icon', 'title', 'detail', 'href'])
<a class="contact-link" href="{{ $href }}"><span class="contact-icon"><x-icon :name="$icon" size="16" /></span><span><strong>{{ $title }}</strong><small>{{ $detail }}</small></span><span class="contact-arrow"><x-icon name="external" size="13" /></span></a>
