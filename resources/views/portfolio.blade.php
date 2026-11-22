<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $profile['name'] }} — Portfolio</title>
    <meta name="description" content="{{ $profile['name'] }} — {{ $profile['role'] }} in {{ $profile['location'] }}.">
    <link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin><link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#f4f6f6] text-[#202327] antialiased" style="--portfolio-profile:url('{{ asset($profile['photo']) }}')">
    <main class="mx-auto max-w-[1032px] px-5 pb-8 pt-10 sm:px-7 lg:px-0">
        <header class="profile-header">
            <div class="profile-photo" role="img" aria-label="{{ $profile['name'] }}" style="background-image:url('{{ asset($profile['photo']) }}'){{ $profile['photo'] !== 'images/reference-profile.png' ? ';background-size:cover;background-position:center' : '' }}"></div>
            <div class="profile-copy"><h1>{{ $profile['name'] }} <span class="verified" title="Verified">✓</span></h1><p class="location"><x-icon name="pin" size="14" /> {{ $profile['location'] }}</p><p class="role">{{ $profile['role'] }}</p><div class="flex flex-wrap gap-2.5"><a class="button button-dark" href="{{ route('resume') }}" target="_blank"><x-icon name="file" size="15" /> <span>View Resume</span></a>@if ($profile['email'])<a class="button" href="https://mail.google.com/mail/?view=cm&amp;fs=1&amp;to={{ rawurlencode($profile['email']) }}" target="_blank" rel="noopener noreferrer"><x-icon name="mail" size="15" /> <span>Send Email</span></a>@endif</div></div>
            <button id="theme-toggle" class="settings-button" aria-label="Toggle color theme" title="Toggle color theme" aria-pressed="false"><span><x-icon name="settings" size="14" /></span></button>
        </header>

        <div class="grid grid-cols-1 gap-4 lg:grid-cols-[1.45fr_1fr] lg:gap-[18px]">
            <div class="flex flex-col gap-4 lg:gap-[18px]">
                <x-panel title="About" icon-image="images/icons/profile.gif" icon-bare>
                    <div class="about-copy">
                        @foreach ($profile['about_paragraphs'] as $paragraph)
                            <p>{{ $paragraph }}</p>
                        @endforeach
                    </div>
                </x-panel>
                <x-panel title="Academic Activities & Projects" icon="academic">
                    <p class="academic-copy">Open my academic activities and project files.</p>
                    <div class="academic-link-list">
                        @forelse ($portfolio['academicLinks'] as $academicLink)
                            <a class="academic-link-button" href="{{ $academicLink->url }}" target="_blank" rel="noopener noreferrer">{{ $academicLink->name }} <span aria-hidden="true">↗</span></a>
                        @empty
                            <p class="academic-empty">Academic links will appear here soon.</p>
                        @endforelse
                    </div>
                </x-panel>
            </div>
            <x-panel title="Experience" icon-image="images/icons/experience.gif" class="experience-card">
                <ol class="timeline">@foreach($portfolio['experience'] as $index => $entry)<x-timeline-entry :entry="$entry" :first="$index === 0" />@endforeach</ol>
            </x-panel>
        </div>

        <div class="mt-4 grid grid-cols-1 gap-4 lg:mt-[18px] lg:grid-cols-2 lg:gap-[18px]">
            <x-panel title="Recent Projects" icon="projects"><div class="project-list">@foreach($portfolio['savedProjects'] as $project)<x-project-tile :project="['name' => $project->name, 'description' => $project->description, 'url' => $project->url]" />@endforeach</div></x-panel>
            <x-panel title="Recent Certifications" icon="certificate">
                @if ($portfolio['savedCertificates']->isNotEmpty())
                    <div class="certificate-grid">
                        @foreach ($portfolio['savedCertificates'] as $certificate)
                            @php($isPdf = str_ends_with(strtolower($certificate->image_path), '.pdf'))
                            <button class="certificate-preview" type="button" data-certificate-preview data-title="{{ $certificate->title }}" data-issuer="{{ $certificate->issuer }}" data-document="{{ asset($certificate->image_path) }}" data-document-type="{{ $isPdf ? 'pdf' : 'image' }}" data-verification-url="{{ $certificate->verification_url }}">
                                @if ($isPdf)
                                    <span class="certificate-pdf-card">
                                        <iframe src="{{ asset($certificate->image_path) }}#page=1&view=FitH" title="{{ $certificate->title }} first page"></iframe>
                                        <span class="certificate-pdf-badge">PDF</span>
                                    </span>
                                @else
                                    <img src="{{ asset($certificate->image_path) }}" alt="{{ $certificate->title }}">
                                @endif
                                <span class="certificate-preview-label"><strong>{{ $certificate->title }}</strong><small>{{ $certificate->issuer }}</small></span>
                            </button>
                        @endforeach
                    </div>
                @endif
            </x-panel>
        </div>

        <x-panel title="" class="mt-4 lg:mt-[18px]"><div class="bottom-grid">
            <div><h2 class="small-title"><x-icon name="goal" size="15" /> Goals</h2><div class="about-copy goal-copy">@forelse($portfolio['savedGoals'] as $goal)<p>{{ $goal->content }}</p>@empty<p>Goals will appear here soon.</p>@endforelse</div></div>
            <div><h2 class="small-title"><x-icon name="link" size="15" /> Social Links</h2>@foreach($portfolio['savedSocialLinks'] as $link)<a class="social-link" href="{{ $link->url }}" target="_blank" rel="noreferrer"><x-icon name="link" size="15" /> {{ $link->name }}</a>@endforeach</div>
            <div class="contact-column"><h2 class="small-title"><x-icon name="message" size="15" /> Contact</h2>@if(!empty($settings['contact_message']))<article class="contact-message"><p>{{ $settings['contact_message'] }}</p></article>@endif<div class="contact-stack">@if(!empty($settings['contact_email']))<x-contact-link icon="mail" title="Gmail" :detail="$profile['email']" :href="'mailto:'.$profile['email']"/>@endif @if(!empty($settings['contact_phone']))<x-contact-link icon="phone" title="Contact number" :detail="$profile['phone']" :href="'tel:'.$profile['phone']"/>@endif</div></div>
        </div></x-panel>

        <section class="technical-stack mt-9 lg:mt-12" aria-labelledby="technical-stack-title">
            <header class="technical-stack-heading">
                <p>Tools I work with</p>
                <h2 id="technical-stack-title">Technical Stack</h2>
            </header>
            <div class="tool-marquee" aria-label="Tools and technologies">
                <div class="tool-marquee-track">
                    @if ($portfolio['techLogos']->isEmpty())
                        <p class="academic-empty">Tech logos will appear here soon.</p>
                    @else
                        @foreach ([0, 1] as $copy)
                            @foreach ($portfolio['techLogos'] as $tool)
                                <div class="tool-logo" @if ($copy === 1) aria-hidden="true" @endif title="{{ $copy === 0 ? $tool->name : '' }}">
                                    <img src="{{ asset($tool->path) }}" alt="{{ $copy === 0 ? $tool->name : '' }}">
                                </div>
                            @endforeach
                        @endforeach
                    @endif
                </div>
            </div>
        </section>
        <footer class="footer">© 2026 Rogelniño Mondido Fe Inal — All rights reserved.<a class="admin-access" href="{{ route('admin.login') }}" aria-label="Admin access" title="Admin access"></a></footer>
    </main>

    <button id="chat-launcher" class="chat-launcher" aria-label="Open chat" title="Any questions?"><x-icon name="message" size="22" /></button>
    <aside id="chat-panel" class="chat-panel" aria-label="Chat with {{ $profile['name'] }}" data-assistant-name="{{ $profile['name'] }}" hidden>
        <div class="chat-head"><div class="chat-avatar" style="background-image:url('{{ asset($profile['photo']) }}')" role="img" aria-label="{{ $profile['name'] }}"></div><div><strong>{{ $profile['name'] }}</strong><span><i></i> Online</span></div><div class="chat-actions"><button id="chat-expand" aria-label="Expand chat"><x-icon name="expand" size="16" /></button><button id="chat-close" aria-label="Close chat"><x-icon name="close" size="17" /></button></div></div>
        <div id="chat-messages" class="chat-messages"><div class="incoming-wrap"><div class="chat-avatar mini" style="background-image:url('{{ asset($profile['photo']) }}')"></div><div><span class="sender-name">{{ $profile['name'] }}</span><div class="bubble incoming">Hi there! 👋 I’m {{ $profile['name'] }}’s portfolio assistant. Ask me about skills, projects, experience, or background.</div><time>{{ now()->format('h:i A') }}</time></div></div></div>
        <div class="chat-compose"><form id="chat-form"><input id="chat-input" maxlength="1000" autocomplete="off" placeholder="Type your message..." aria-label="Type your message"><button class="chat-send-button" type="submit" aria-label="Send message" disabled><x-icon name="send" size="17" /></button></form><p>Ask about skills, projects, experience, or background.</p></div>
    </aside>
    <div id="certificate-modal" class="certificate-modal" hidden role="dialog" aria-modal="true" aria-labelledby="certificate-modal-title">
        <button id="certificate-modal-backdrop" class="certificate-modal-backdrop" aria-label="Close certificate preview"></button>
        <section class="certificate-modal-content">
            <button id="certificate-modal-close" class="certificate-modal-close" aria-label="Close certificate preview"><x-icon name="close" size="20" /></button>
            <img id="certificate-modal-image" src="" alt="">
            <iframe id="certificate-modal-pdf" title="Certificate PDF preview" src="" hidden></iframe>
            <div class="certificate-modal-details"><div><h2 id="certificate-modal-title"></h2><p id="certificate-modal-issuer"></p></div><a id="certificate-verify-link" class="certificate-verify-link" href="#" target="_blank" rel="noopener noreferrer">Verify certificate <x-icon name="external" size="15" /></a></div>
        </section>
    </div>
</body>
</html>
