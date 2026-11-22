<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $profile['name'] }} - Resume</title>
    <style>
        @page { margin: 13mm; }
        * { box-sizing: border-box; }
        body { margin: 0; background: #eef1f1; color: #111; font-family: DejaVu Sans, Arial, sans-serif; font-size: 10px; line-height: 1.48; }
        .resume { width: 210mm; min-height: 297mm; margin: 16px auto; padding: 13mm; background: #fff; box-shadow: 0 10px 34px rgba(20, 28, 32, .12); }
        .resume-header { display: table; width: 100%; padding-bottom: 13px; border-bottom: 1.5px solid #171717; }
        .photo-wrap, .identity { display: table-cell; vertical-align: top; }
        .photo-wrap { width: 34mm; padding-right: 7mm; }
        .photo { width: 34mm; height: 43mm; object-fit: cover; border: 1px solid #171717; }
        .photo-placeholder { display: block; padding-top: 15mm; background: #f1f1f1; color: #171717; font-size: 28px; font-weight: 800; text-align: center; }
        .identity { padding-top: 2mm; }
        .name { margin: 0 0 6px; font-size: 25px; font-weight: 800; letter-spacing: -.7px; line-height: 1.04; text-transform: uppercase; }
        .role { margin: 0 0 10px; font-size: 11px; font-weight: 700; }
        .contact-row { margin: 5px 0; color: #252525; font-size: 10px; }
        .contact-symbol { display: inline-block; width: 43px; margin-right: 8px; color: #111; font-size: 8.5px; font-weight: 800; letter-spacing: .45px; vertical-align: 1px; }
        .actions { margin: 0 auto 10px; width: 184mm; text-align: right; }
        .actions a, .actions button { display: inline-block; margin-left: 6px; padding: 8px 12px; border: 1px solid #1b1b1b; border-radius: 3px; background: #171717; color: #fff; font: inherit; font-weight: 700; text-decoration: none; cursor: pointer; }
        .actions button { background: #fff; color: #171717; }
        .section { margin-top: 10px; page-break-inside: auto; }
        .section-title { margin: 0 0 7px; padding-bottom: 5px; border-bottom: 1px solid #232323; font-size: 13px; font-weight: 800; letter-spacing: -.1px; text-transform: uppercase; }
        .summary { margin: 0; text-align: justify; }
        .item { margin: 0 0 6px; page-break-inside: avoid; }
        .item-head { display: table; width: 100%; }
        .item-title, .item-year { display: table-cell; vertical-align: top; }
        .item-title { font-size: 10.5px; font-weight: 800; }
        .item-year { width: 27mm; text-align: right; white-space: nowrap; }
        .item-detail { margin: 2px 0 0; color: #2b2b2b; }
        .project-list, .academic-list, .certificate-list { margin: 0; padding-left: 15px; }
        .project-list li, .academic-list li, .certificate-list li { margin: 0 0 3px; }
        .project-list strong { font-weight: 800; }
        .skills { margin: 0; }
        .skill { display: inline-block; margin: 0 6px 5px 0; padding: 3px 6px; border: 1px solid #b8b8b8; border-radius: 2px; }
        .muted { color: #5c5c5c; }
        @media screen and (max-width: 760px) { body { background: #fff; } .resume { width: 100%; min-height: 0; margin: 0; padding: 20px; box-shadow: none; } .actions { width: 100%; } .name { font-size: 20px; } }
        @media print { body { background: #fff; } .resume { width: auto; min-height: 0; margin: 0; padding: 0; box-shadow: none; } .actions { display: none; } }
    </style>
</head>
<body>
    @if (! $forPdf)
        <div class="actions"><a href="{{ route('resume.download') }}">Download PDF</a><button type="button" onclick="window.print()">Print</button></div>
    @endif
    <main class="resume">
        <header class="resume-header">
            <div class="photo-wrap">
                @if ($forPdf ? $profile['photo_data_uri'] : $profile['photo_url'])
                    <img class="photo" src="{{ $forPdf ? $profile['photo_data_uri'] : $profile['photo_url'] }}" alt="{{ $profile['name'] }}">
                @else
                    <div class="photo photo-placeholder" aria-label="{{ $profile['name'] }}">{{ $profile['initials'] }}</div>
                @endif
            </div>
            <div class="identity">
                <h1 class="name">{{ $profile['name'] }}</h1>
                @if ($profile['role'])<p class="role">{{ $profile['role'] }}</p>@endif
                @if ($profile['phone'])<p class="contact-row"><span class="contact-symbol">TEL</span>{{ $profile['phone'] }}</p>@endif
                @if ($profile['email'])<p class="contact-row"><span class="contact-symbol">EMAIL</span>{{ $profile['email'] }}</p>@endif
                @if ($profile['location'])<p class="contact-row"><span class="contact-symbol">LOC</span>{{ $profile['location'] }}</p>@endif
            </div>
        </header>

        <section class="section"><h2 class="section-title">Professional Summary</h2><p class="summary">{{ $profile['about'] ?: 'Professional summary will be available soon.' }}</p></section>

        @if ($experiences->isNotEmpty())
            <section class="section"><h2 class="section-title">Experience</h2>@foreach ($experiences as $experience)<article class="item"><div class="item-head"><div class="item-title">{{ $experience->title }}</div><div class="item-year">{{ $experience->year }}</div></div>@if($experience->detail)<p class="item-detail">{{ $experience->detail }}</p>@endif</article>@endforeach</section>
        @endif

        @if ($projects->isNotEmpty())
            <section class="section"><h2 class="section-title">Projects</h2><ul class="project-list">@foreach ($projects as $project)<li><strong>{{ $project->name }}</strong>@if($project->description) - {{ $project->description }}@endif</li>@endforeach</ul></section>
        @endif

        @if ($skills->isNotEmpty())
            <section class="section"><h2 class="section-title">Technical Skills</h2><p class="skills">@foreach ($skills as $skill)<span class="skill">{{ $skill }}</span>@endforeach</p></section>
        @endif

        @if ($academicLinks->isNotEmpty())
            <section class="section"><h2 class="section-title">Academic Activities &amp; Projects</h2><ul class="academic-list">@foreach ($academicLinks as $link)<li>{{ $link->name }}</li>@endforeach</ul></section>
        @endif

        @if ($certificates->isNotEmpty())
            <section class="section"><h2 class="section-title">Certifications</h2><ul class="certificate-list">@foreach ($certificates as $certificate)<li><strong>{{ $certificate->title }}</strong>@if($certificate->issuer) <span class="muted">- {{ $certificate->issuer }}</span>@endif</li>@endforeach</ul></section>
        @endif
    </main>
</body>
</html>
