<?php

namespace App\Http\Controllers;

use App\Models\Experience;
use App\Models\AcademicLink;
use App\Models\TechLogo;
use App\Models\PortfolioCertificate;
use App\Models\PortfolioProject;
use App\Models\PortfolioGoal;
use App\Models\SocialLink;
use App\Models\PortfolioSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class AdminPortfolioController extends Controller
{
    public function dashboard()
    {
        $settings = PortfolioSetting::query()->pluck('value', 'key')->all();

        return view('admin.dashboard', [
            'settings' => $settings,
            'experiences' => Experience::query()->orderBy('sort_order')->orderByDesc('id')->get(),
            'academicLinks' => AcademicLink::query()->orderBy('sort_order')->orderByDesc('id')->get(),
            'techLogos' => TechLogo::query()->orderBy('sort_order')->orderByDesc('id')->get(),
            'certificates' => PortfolioCertificate::query()->orderBy('sort_order')->orderByDesc('id')->get(),
            'projects' => PortfolioProject::query()->orderBy('sort_order')->orderByDesc('id')->get(),
            'goals' => PortfolioGoal::query()->orderBy('sort_order')->orderByDesc('id')->get(),
            'socialLinks' => SocialLink::query()->orderBy('sort_order')->orderByDesc('id')->get(),
        ]);
    }

    public function updateProfile(Request $request)
    {
        $profile = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'location' => ['required', 'string', 'max:150'],
            'role' => ['required', 'string', 'max:150'],
            'email' => ['nullable', 'email', 'max:255'],
            'about' => ['required', 'string', 'max:5000'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);

        $photo = PortfolioSetting::query()->where('key', 'photo_path')->value('value');
        if ($request->hasFile('photo')) {
            $directory = public_path('uploads/profile');
            File::ensureDirectoryExists($directory);
            $filename = Str::uuid().'.'.$request->file('photo')->extension();
            $request->file('photo')->move($directory, $filename);

            if ($photo && str_starts_with($photo, 'uploads/profile/') && File::exists(public_path($photo))) {
                File::delete(public_path($photo));
            }

            $profile['photo_path'] = 'uploads/profile/'.$filename;
        }

        foreach ($profile as $key => $value) {
            if ($key !== 'photo') {
                PortfolioSetting::query()->updateOrCreate(['key' => $key], ['value' => $value ?? '']);
            }
        }

        return back()->with('status', 'Profile information saved.');
    }

    public function storeExperience(Request $request)
    {
        Experience::query()->create($this->validatedExperience($request) + [
            'sort_order' => ((int) Experience::query()->max('sort_order')) + 1,
        ]);

        return back()->with('status', 'Experience entry added.');
    }

    public function updateExperience(Request $request, Experience $experience)
    {
        $experience->update($this->validatedExperience($request));

        return back()->with('status', 'Experience entry updated.');
    }

    public function destroyExperience(Experience $experience)
    {
        $experience->delete();

        return back()->with('status', 'Experience entry removed.');
    }

    public function storeAcademicLink(Request $request)
    {
        AcademicLink::query()->create($this->validatedAcademicLink($request) + [
            'sort_order' => ((int) AcademicLink::query()->max('sort_order')) + 1,
        ]);

        return back()->with('status', 'Academic link added.');
    }

    public function updateAcademicLink(Request $request, AcademicLink $academicLink)
    {
        $academicLink->update($this->validatedAcademicLink($request));

        return back()->with('status', 'Academic link updated.');
    }

    public function destroyAcademicLink(AcademicLink $academicLink)
    {
        $academicLink->delete();

        return back()->with('status', 'Academic link removed.');
    }

    public function storeTechLogo(Request $request)
    {
        $data = $request->validate([
            'name' => ['nullable', 'string', 'max:120'],
            'logo' => ['required', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:2048'],
        ]);

        $directory = public_path('uploads/tools');
        File::ensureDirectoryExists($directory);
        $filename = Str::uuid().'.'.$request->file('logo')->extension();
        $request->file('logo')->move($directory, $filename);

        TechLogo::query()->create([
            'name' => $data['name'] ?? '',
            'path' => 'uploads/tools/'.$filename,
            'sort_order' => ((int) TechLogo::query()->max('sort_order')) + 1,
        ]);

        return back()->with('status', 'Tech logo added.');
    }

    public function destroyTechLogo(TechLogo $techLogo)
    {
        if (str_starts_with($techLogo->path, 'uploads/tools/') && File::exists(public_path($techLogo->path))) {
            File::delete(public_path($techLogo->path));
        }

        $techLogo->delete();

        return back()->with('status', 'Tech logo removed.');
    }

    public function storeCertificate(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:180'],
            'issuer' => ['nullable', 'string', 'max:180'],
            'verification_url' => ['nullable', 'url', 'max:2048'],
            'image' => ['required', 'file', 'mimes:jpg,jpeg,png,webp,pdf', 'max:10240'],
        ]);

        $directory = public_path('uploads/certificates');
        File::ensureDirectoryExists($directory);
        $filename = Str::uuid().'.'.$request->file('image')->extension();
        $request->file('image')->move($directory, $filename);

        PortfolioCertificate::query()->create([
            'title' => $data['title'],
            'issuer' => $data['issuer'] ?? '',
            'verification_url' => $data['verification_url'] ?? '',
            'image_path' => 'uploads/certificates/'.$filename,
            'sort_order' => ((int) PortfolioCertificate::query()->max('sort_order')) + 1,
        ]);

        return back()->with('status', 'Certificate added.');
    }

    public function destroyCertificate(PortfolioCertificate $certificate)
    {
        if (str_starts_with($certificate->image_path, 'uploads/certificates/') && File::exists(public_path($certificate->image_path))) {
            File::delete(public_path($certificate->image_path));
        }

        $certificate->delete();

        return back()->with('status', 'Certificate removed.');
    }

    public function storeProject(Request $request) { PortfolioProject::query()->create($this->validatedProject($request) + ['sort_order' => ((int) PortfolioProject::query()->max('sort_order')) + 1]); return back()->with('status', 'Project added.'); }
    public function updateProject(Request $request, PortfolioProject $project) { $project->update($this->validatedProject($request)); return back()->with('status', 'Project updated.'); }
    public function destroyProject(PortfolioProject $project) { $project->delete(); return back()->with('status', 'Project removed.'); }
    public function updateContact(Request $request)
    {
        $contact = $request->validate([
            'contact_email' => ['nullable', 'email', 'max:255'],
            'contact_phone' => ['nullable', 'string', 'max:50'],
            'contact_message' => ['nullable', 'string', 'max:500'],
        ]);

        foreach ($contact as $key => $value) {
            PortfolioSetting::query()->updateOrCreate(['key' => $key], ['value' => $value ?? '']);
        }

        return back()->with('status', 'Contact details saved.');
    }
    public function storeGoal(Request $request) { PortfolioGoal::query()->create($this->validatedGoal($request) + ['sort_order' => ((int) PortfolioGoal::query()->max('sort_order')) + 1]); return back()->with('status', 'Goal added.'); }
    public function updateGoal(Request $request, PortfolioGoal $goal) { $goal->update($this->validatedGoal($request)); return back()->with('status', 'Goal updated.'); }
    public function destroyGoal(PortfolioGoal $goal) { $goal->delete(); return back()->with('status', 'Goal removed.'); }
    public function storeSocialLink(Request $request) { SocialLink::query()->create($this->validatedSocialLink($request) + ['sort_order' => ((int) SocialLink::query()->max('sort_order')) + 1]); return back()->with('status', 'Social link added.'); }
    public function updateSocialLink(Request $request, SocialLink $socialLink) { $socialLink->update($this->validatedSocialLink($request)); return back()->with('status', 'Social link updated.'); }
    public function destroySocialLink(SocialLink $socialLink) { $socialLink->delete(); return back()->with('status', 'Social link removed.'); }

    private function validatedExperience(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:160'],
            'detail' => ['nullable', 'string', 'max:255'],
            'year' => ['nullable', 'string', 'max:20'],
        ]);
    }

    private function validatedAcademicLink(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'url' => ['required', 'url', 'max:2048'],
        ]);
    }
    private function validatedProject(Request $request): array { return $request->validate(['name' => ['required','string','max:120'], 'description' => ['nullable','string','max:1000'], 'url' => ['nullable','string','max:2048']]); }
    private function validatedGoal(Request $request): array { return $request->validate(['content' => ['required','string','max:1000']]); }
    private function validatedSocialLink(Request $request): array { return $request->validate(['name' => ['required','string','max:80'], 'url' => ['required','url','max:2048']]); }
}
