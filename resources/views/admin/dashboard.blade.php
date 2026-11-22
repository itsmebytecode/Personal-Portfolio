<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Portfolio settings</title>
    @vite(['resources/css/app.css'])
</head>
<body class="admin-page bg-[#f4f6f6] text-[#202327] antialiased">
    <main class="admin-dashboard">
        <header class="admin-dashboard-head">
            <div><p class="admin-kicker">Administrator area</p><h1>Portfolio settings</h1><p>Manage the public profile and timeline shown on your portfolio.</p></div>
            <div class="admin-head-actions"><a class="admin-secondary" href="{{ url('/') }}">View portfolio</a><form method="POST" action="{{ route('admin.logout') }}">@csrf<button class="admin-secondary" type="submit">Sign out</button></form></div>
        </header>
        @if(session('status'))<div class="admin-notice" role="status">{{ session('status') }}</div>@endif

        <section class="admin-section">
            <div class="admin-section-title"><div><p class="admin-kicker">Profile</p><h2>Personal information</h2></div><p>Update the header and About card.</p></div>
            <form method="POST" action="{{ route('admin.profile.update') }}" enctype="multipart/form-data" class="settings-form">
                @csrf @method('PUT')
                <div class="settings-photo"><div class="settings-photo-preview" style="background-image:url('{{ asset($settings['photo_path'] ?? 'images/reference-profile.png') }}')"></div><div><label for="photo">Profile photo</label><input id="photo" name="photo" type="file" accept="image/jpeg,image/png,image/webp"><small>JPG, PNG, or WebP · 4 MB maximum</small>@error('photo')<p class="admin-error">{{ $message }}</p>@enderror</div></div>
                <div class="settings-grid">
                    <div><label for="name">Full name</label><input id="name" name="name" value="{{ old('name', $settings['name'] ?? 'Klent Russell Aguilar') }}" required></div><div><label for="role">Headline</label><input id="role" name="role" value="{{ old('role', $settings['role'] ?? 'BSIT Student / Aspiring Web Developer') }}" required></div><div><label for="location">Location</label><input id="location" name="location" value="{{ old('location', $settings['location'] ?? 'Central Luzon, Philippines') }}" required></div>
                </div>
                <div><label for="about">About</label><textarea id="about" name="about" rows="9" required>{{ old('about', $settings['about'] ?? "I am a 4th-year Bachelor of Science in Information Technology student at La Consolacion University Philippines, passionate about building modern and user-centered digital solutions. I am currently focusing on Web Development, Mobile Application Development, and Software Engineering.\n\nI enjoy solving real-world problems through technology—whether by developing systems that improve daily processes or by creating innovative ideas and turning them into functional applications. I am continuously learning, exploring new tools, and enhancing my skills to grow into a professional developer.\n\nI am actively seeking an Internship / OJT opportunity where I can apply what I’ve learned and gain hands-on industry experience. I am based in Malolos City, Bulacan, Central Luzon, Philippines, and I am 21 years old with a strong passion for technology and continuous improvement.") }}</textarea>@error('about')<p class="admin-error">{{ $message }}</p>@enderror</div>
                <button class="admin-submit" type="submit">Save profile</button>
            </form>
        </section>

        <section class="admin-section">
            <div class="admin-section-title"><div><p class="admin-kicker">Portfolio content</p><h2>Recent Projects</h2></div><p>Add, edit, or remove the project cards shown on your portfolio.</p></div>
            <div class="experience-editor-list">@foreach($projects as $project)<div class="experience-editor"><form method="POST" action="{{ route('admin.projects.update', $project) }}" class="experience-update">@csrf @method('PUT')<div class="project-fields"><input name="name" value="{{ $project->name }}" required aria-label="Project name"><input name="description" value="{{ $project->description }}" aria-label="Project description"><input name="url" value="{{ $project->url }}" aria-label="Project URL"></div><button class="admin-secondary" type="submit">Save</button></form><form method="POST" action="{{ route('admin.projects.destroy', $project) }}">@csrf @method('DELETE')<button class="admin-danger" type="submit">Remove</button></form></div>@endforeach</div>
            <form method="POST" action="{{ route('admin.projects.store') }}" class="experience-editor new-experience">@csrf<div class="project-fields"><input name="name" placeholder="Project name" required><input name="description" placeholder="Description"><input name="url" placeholder="Project link, e.g. https://example.com"></div><button class="admin-submit">Add project</button></form>
        </section>

        <section class="admin-section"><div class="admin-section-title"><div><p class="admin-kicker">Portfolio content</p><h2>Goals</h2></div><p>Manage the goals displayed on the public portfolio.</p></div><div class="experience-editor-list">@foreach($goals as $goal)<div class="experience-editor"><form method="POST" action="{{ route('admin.goals.update', $goal) }}" class="experience-update">@csrf @method('PUT')<div class="single-content-field"><input name="content" value="{{ $goal->content }}" required></div><button class="admin-secondary">Save</button></form><form method="POST" action="{{ route('admin.goals.destroy', $goal) }}">@csrf @method('DELETE')<button class="admin-danger">Remove</button></form></div>@endforeach</div><form method="POST" action="{{ route('admin.goals.store') }}" class="experience-editor new-experience">@csrf<div class="single-content-field"><input name="content" placeholder="Goal content" required></div><button class="admin-submit">Add goal</button></form></section>

        <section class="admin-section"><div class="admin-section-title"><div><p class="admin-kicker">Portfolio content</p><h2>Social Links</h2></div><p>Set the social buttons shown on your portfolio.</p></div><div class="experience-editor-list">@foreach($socialLinks as $link)<div class="experience-editor"><form method="POST" action="{{ route('admin.social-links.update', $link) }}" class="experience-update">@csrf @method('PUT')<div class="academic-link-fields"><input name="name" value="{{ $link->name }}" required><input name="url" type="url" value="{{ $link->url }}" required></div><button class="admin-secondary">Save</button></form><form method="POST" action="{{ route('admin.social-links.destroy', $link) }}">@csrf @method('DELETE')<button class="admin-danger">Remove</button></form></div>@endforeach</div><form method="POST" action="{{ route('admin.social-links.store') }}" class="experience-editor new-experience">@csrf<div class="academic-link-fields"><input name="name" placeholder="Social platform name" required><input name="url" type="url" placeholder="https://..." required></div><button class="admin-submit">Add social link</button></form></section>

        <section class="admin-section"><div class="admin-section-title"><div><p class="admin-kicker">Portfolio content</p><h2>Contact details</h2></div><p>Manage the Gmail address, contact number, and message displayed in the Contact area.</p></div><form method="POST" action="{{ route('admin.contact.update') }}" class="contact-settings-form">@csrf @method('PUT')<div><label for="contact_email">Gmail address</label><input id="contact_email" name="contact_email" type="email" value="{{ old('contact_email', $settings['contact_email'] ?? '') }}" placeholder="yourname@gmail.com"></div><div><label for="contact_phone">Contact number</label><input id="contact_phone" name="contact_phone" value="{{ old('contact_phone', $settings['contact_phone'] ?? '') }}" placeholder="09XX XXX XXXX"></div><div class="contact-message-field"><label for="contact_message">Contact message</label><input id="contact_message" name="contact_message" value="{{ old('contact_message', $settings['contact_message'] ?? '') }}" placeholder="Your availability or contact invitation"></div><button class="admin-submit" type="submit">Save contact details</button></form></section>

        <section class="admin-section">
            <div class="admin-section-title"><div><p class="admin-kicker">Timeline</p><h2>Experience</h2></div><p>Add, edit, or remove entries from the Experience card.</p></div>
            <div class="experience-editor-list">
                @foreach($experiences as $experience)
                    <div class="experience-editor"><form method="POST" action="{{ route('admin.experience.update', $experience) }}" class="experience-update">@csrf @method('PUT')<div class="experience-fields"><input name="title" value="{{ $experience->title }}" aria-label="Experience title" required><input name="detail" value="{{ $experience->detail }}" aria-label="Experience detail"><input name="year" value="{{ $experience->year }}" aria-label="Year"></div><button class="admin-secondary" type="submit">Save</button></form><form method="POST" action="{{ route('admin.experience.destroy', $experience) }}" onsubmit="return confirm('Remove this experience entry?')">@csrf @method('DELETE')<button class="admin-danger" type="submit">Remove</button></form></div>
                @endforeach
            </div>
            <form method="POST" action="{{ route('admin.experience.store') }}" class="experience-editor new-experience">@csrf<div class="experience-fields"><input name="title" placeholder="Experience title" aria-label="Experience title" required><input name="detail" placeholder="Short description" aria-label="Experience detail"><input name="year" placeholder="Year" aria-label="Year"></div><button class="admin-submit" type="submit">Add experience</button></form>
        </section>

        <section class="admin-section">
            <div class="admin-section-title"><div><p class="admin-kicker">Academic work</p><h2>Activities & project links</h2></div><p>Create the buttons that link visitors to your Google Drive folders or project files.</p></div>
            <div class="experience-editor-list">
                @foreach ($academicLinks as $academicLink)
                    <div class="experience-editor"><form method="POST" action="{{ route('admin.academic-links.update', $academicLink) }}" class="experience-update">@csrf @method('PUT')<div class="academic-link-fields"><input name="name" value="{{ $academicLink->name }}" aria-label="Button name" required><input name="url" type="url" value="{{ $academicLink->url }}" aria-label="Direct link" required></div><button class="admin-secondary" type="submit">Save</button></form><form method="POST" action="{{ route('admin.academic-links.destroy', $academicLink) }}" onsubmit="return confirm('Remove this academic link?')">@csrf @method('DELETE')<button class="admin-danger" type="submit">Remove</button></form></div>
                @endforeach
            </div>
            <form method="POST" action="{{ route('admin.academic-links.store') }}" class="experience-editor new-experience">@csrf<div class="academic-link-fields"><input name="name" placeholder="Button name, e.g. View academic projects" aria-label="Button name" required><input name="url" type="url" placeholder="https://drive.google.com/..." aria-label="Direct link" required></div><button class="admin-submit" type="submit">Add link button</button></form>
        </section>

        <section class="admin-section">
            <div class="admin-section-title"><div><p class="admin-kicker">Tech stack</p><h2>Tool logos</h2></div><p>Upload the tool logos that appear in the moving Tech Stack strip.</p></div>
            <div class="logo-admin-grid">
                @forelse ($techLogos as $techLogo)
                    <article class="logo-admin-item"><img src="{{ asset($techLogo->path) }}" alt="{{ $techLogo->name }}"><div><strong>{{ $techLogo->name ?: 'Tool logo' }}</strong><form method="POST" action="{{ route('admin.tech-logos.destroy', $techLogo) }}" onsubmit="return confirm('Remove this tool logo?')">@csrf @method('DELETE')<button class="admin-danger" type="submit">Remove</button></form></div></article>
                @empty
                    <p class="academic-empty">No tool logos added yet.</p>
                @endforelse
            </div>
            <form method="POST" action="{{ route('admin.tech-logos.store') }}" enctype="multipart/form-data" class="logo-upload-form">
                @csrf
                <input name="name" placeholder="Tool name (optional)" aria-label="Tool name">
                <input name="logo" type="file" accept="image/jpeg,image/png,image/webp,image/gif" required aria-label="Tool logo upload">
                <button class="admin-submit" type="submit">Upload logo</button>
            </form>
        </section>

        <section class="admin-section">
            <div class="admin-section-title"><div><p class="admin-kicker">Certificates</p><h2>Recent certificates</h2></div><p>Upload a certificate image and add its official verification URL.</p></div>
            <div class="certificate-admin-grid">
                @forelse ($certificates as $certificate)
                    <article class="certificate-admin-item">@if(str_ends_with(strtolower($certificate->image_path), '.pdf'))<span class="certificate-file-icon"><x-icon name="file" size="32" /></span>@else<img src="{{ asset($certificate->image_path) }}" alt="{{ $certificate->title }}">@endif<div><strong>{{ $certificate->title }}</strong><small>{{ $certificate->issuer }}</small><form method="POST" action="{{ route('admin.certificates.destroy', $certificate) }}" onsubmit="return confirm('Remove this certificate?')">@csrf @method('DELETE')<button class="admin-danger" type="submit">Remove</button></form></div></article>
                @empty
                    <p class="academic-empty">No certificate images uploaded yet.</p>
                @endforelse
            </div>
            <form method="POST" action="{{ route('admin.certificates.store') }}" enctype="multipart/form-data" class="certificate-upload-form">
                @csrf
                <input name="title" placeholder="Certificate title" aria-label="Certificate title" required>
                <input name="issuer" placeholder="Issuer or organization" aria-label="Certificate issuer">
                <input name="verification_url" type="url" placeholder="https://verification-link.example/..." aria-label="Verification URL">
                <input name="image" type="file" accept="image/jpeg,image/png,image/webp,application/pdf" required aria-label="Certificate image or PDF upload">
                <button class="admin-submit" type="submit">Upload certificate</button>
            </form>
        </section>
    </main>
</body>
</html>
