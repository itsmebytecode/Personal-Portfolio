<?php

namespace App\Http\Controllers;

use App\Models\AcademicLink;
use App\Models\Experience;
use App\Models\PortfolioCertificate;
use App\Models\PortfolioProject;
use App\Models\PortfolioSetting;
use App\Models\TechLogo;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class ResumeController extends Controller
{
    public function show()
    {
        return view('resume', $this->resumeData());
    }

    public function download()
    {
        $data = $this->resumeData();
        $data['forPdf'] = true;

        return Pdf::loadView('resume', $data)
            ->setPaper('a4')
            ->download(Str::slug($data['profile']['name']).'-resume.pdf');
    }

    private function resumeData(): array
    {
        $settings = Schema::hasTable('portfolio_settings')
            ? PortfolioSetting::query()->pluck('value', 'key')->all()
            : [];
        $photo = $settings['photo_path'] ?? 'images/reference-profile.png';
        $photoFile = public_path($photo);
        $photoDataUri = null;
        $photoMime = null;

        if (is_file($photoFile) && is_readable($photoFile)) {
            $photoMime = mime_content_type($photoFile) ?: 'image/jpeg';
            $photoDataUri = 'data:'.$photoMime.';base64,'.base64_encode((string) file_get_contents($photoFile));
        }

        // DomPDF requires GD to embed PNGs. Keep the generated document reliable
        // on hosts where that PHP extension is not enabled.
        if ($photoMime === 'image/png' && ! extension_loaded('gd')) {
            $photoDataUri = null;
        }

        $name = trim($settings['name'] ?? 'Portfolio Owner');

        return [
            'forPdf' => false,
            'profile' => [
                'name' => $name,
                'initials' => collect(preg_split('/\s+/', $name))->filter()->map(fn (string $word) => mb_substr($word, 0, 1))->take(2)->implode(''),
                'role' => trim($settings['role'] ?? ''),
                'location' => trim($settings['location'] ?? ''),
                'email' => trim($settings['contact_email'] ?? $settings['email'] ?? ''),
                'phone' => trim($settings['contact_phone'] ?? $settings['phone'] ?? ''),
                'about' => trim($settings['about'] ?? ''),
                'photo_url' => asset($photo),
                'photo_data_uri' => $photoDataUri,
            ],
            'experiences' => Schema::hasTable('experiences') ? Experience::query()->orderBy('sort_order')->orderByDesc('id')->get() : collect(),
            'projects' => Schema::hasTable('portfolio_projects') ? PortfolioProject::query()->orderBy('sort_order')->orderByDesc('id')->get() : collect(),
            'skills' => Schema::hasTable('tech_logos') ? TechLogo::query()->orderBy('sort_order')->orderByDesc('id')->pluck('name')->filter()->values() : collect(),
            'academicLinks' => Schema::hasTable('academic_links') ? AcademicLink::query()->orderBy('sort_order')->orderByDesc('id')->get() : collect(),
            'certificates' => Schema::hasTable('portfolio_certificates') ? PortfolioCertificate::query()->orderBy('sort_order')->orderByDesc('id')->get() : collect(),
        ];
    }
}
