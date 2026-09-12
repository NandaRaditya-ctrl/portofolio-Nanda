<?php

namespace App\Http\Controllers;

use App\Models\Opportunity;
use App\Models\PklApplication;
use Dompdf\Dompdf;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class PklController extends Controller
{
    private function manager(Request $r): void
    {
        abort_unless(in_array($r->user()->role, ['company', 'admin']), 403);
    }

    private function owns(Request $r, Opportunity $opportunity): void
    {
        $this->manager($r);
        abort_unless($r->user()->role === 'admin' || $opportunity->user_id === $r->user()->id, 403);
    }

    private function visibleApplications(Request $r)
    {
        $q = PklApplication::with(['opportunity', 'user']);
        if ($r->user()->role === 'student') {
            $q->where('user_id', $r->user()->id);
        } elseif ($r->user()->role !== 'admin') {
            $q->whereHas('opportunity', fn ($q) => $q->where('user_id', $r->user()->id));
        }

        return $q;
    }

    public function index(Request $r)
    {
        $r->validate(['q' => 'nullable|string|max:100', 'city' => 'nullable|string|max:100', 'category' => 'nullable|string|max:100']);
        $q = Opportunity::where('active', true)->whereDate('deadline', '>=', today());
        if ($r->filled('q')) {
            $q->where(fn ($q) => $q->where('title', 'like', '%'.$r->q.'%')->orWhere('company', 'like', '%'.$r->q.'%'));
        }
        if ($r->filled('city')) {
            $q->where('city', $r->city);
        }
        if ($r->filled('category')) {
            $q->where('category', $r->category);
        }

        return view('journey.pkl', ['opportunities' => $q->withCount('applications')->latest()->paginate(6)->withQueryString(),
            'cities' => Opportunity::where('active', true)->distinct()->pluck('city'),
            'categories' => Opportunity::where('active', true)->distinct()->pluck('category')]);
    }

    public function show(Opportunity $opportunity)
    {
        return view('journey.opportunity', compact('opportunity'));
    }

    public function dashboard(Request $r)
    {
        $base = $this->visibleApplications($r);
        $stats = ['Total lamaran' => (clone $base)->count(), 'Menunggu' => (clone $base)->where('status', 'pending')->count(), 'Diterima' => (clone $base)->where('status', 'accepted')->count()];
        $jobs = Opportunity::query();
        if ($r->user()->role !== 'admin') {
            $jobs->where('user_id', $r->user()->id);
        }

        return view('journey.dashboard', ['applications' => $base->latest()->paginate(10)->withQueryString(), 'stats' => $stats,
            'jobs' => $jobs->latest()->paginate(6, ['*'], 'jobs_page')]);
    }

    public function form(Request $r, ?Opportunity $opportunity = null)
    {
        $this->manager($r);
        if ($opportunity) {
            $this->owns($r, $opportunity);
        }

        return view('journey.job-form', ['job' => $opportunity ?? new Opportunity]);
    }

    public function save(Request $r, ?Opportunity $opportunity = null)
    {
        $this->manager($r);
        if ($opportunity) {
            $this->owns($r, $opportunity);
        }
        $data = $r->validate(['title' => 'required|string|max:150', 'company' => 'required|string|max:150', 'city' => 'required|string|max:100',
            'category' => 'required|string|max:100', 'description' => 'required|string|max:10000', 'deadline' => 'required|date_format:Y-m-d|after_or_equal:today', 'capacity' => 'required|integer|between:1,1000', 'active' => 'required|boolean']);
        if ($opportunity) {
            $opportunity->update($data);
        } else {
            Opportunity::create($data + ['user_id' => $r->user()->id]);
        }

        return redirect('/pkl/dashboard')->with('success', 'Lowongan berhasil disimpan.');
    }

    public function apply(Request $r, Opportunity $opportunity)
    {
        abort_unless($r->user()->role === 'student', 403);
        abort_unless($opportunity->active && $opportunity->deadline >= today()->toDateString(), 422, 'Lowongan sudah ditutup.');
        $data = $r->validate(['motivation' => 'required|string|min:20|max:3000', 'cv' => 'required|file|mimes:pdf|max:2048']);
        if (PklApplication::where('user_id', $r->user()->id)->where('opportunity_id', $opportunity->id)->exists()) {
            return back()->withErrors(['cv' => 'Anda sudah melamar lowongan ini.']);
        }
        $path = $r->file('cv')->store('pkl-cv', 'local');
        try {
            PklApplication::create(['user_id' => $r->user()->id, 'opportunity_id' => $opportunity->id, 'motivation' => $data['motivation'], 'cv_path' => $path]);
        } catch (\Throwable $e) {
            Storage::disk('local')->delete($path);
            if ($e instanceof UniqueConstraintViolationException) {
                return back()->withErrors(['cv' => 'Anda sudah melamar lowongan ini.']);
            } throw $e;
        }

        return redirect('/pkl/dashboard')->with('success', 'Lamaran berhasil dikirim. Pantau statusnya di dashboard.');
    }

    public function status(Request $r, PklApplication $application)
    {
        $this->owns($r, $application->opportunity);
        $data = $r->validate(['status' => ['required', Rule::in(['pending', 'accepted', 'rejected'])]]);
        DB::transaction(function () use ($application, $data) {
            // Serialize decisions on the parent; the write lock works on SQLite and MySQL.
            Opportunity::whereKey($application->opportunity_id)->update(['updated_at' => now()]);
            $job = Opportunity::findOrFail($application->opportunity_id);
            $accepted = PklApplication::where('opportunity_id', $job->id)->where('status', 'accepted')->where('id', '!=', $application->id)->count();
            if ($data['status'] === 'accepted' && $accepted >= $job->capacity) {
                throw ValidationException::withMessages(['status' => 'Kuota penerimaan sudah penuh.']);
            }
            $application->update($data);
        });

        return back()->with('success', 'Status lamaran diperbarui.');
    }

    public function cv(Request $r, PklApplication $application)
    {
        abort_unless($this->visibleApplications($r)->whereKey($application->id)->exists(), 403);
        abort_unless(Storage::disk('local')->exists($application->cv_path), 404);

        return Storage::disk('local')->download($application->cv_path, 'cv-pelamar-'.$application->id.'.pdf', ['X-Content-Type-Options' => 'nosniff']);
    }

    public function export(Request $r, string $format)
    {
        abort_unless(in_array($format, ['pdf', 'excel']), 404);
        $applications = $this->visibleApplications($r)->latest()->get();
        if ($format === 'pdf') {
            $pdf = new Dompdf(['isRemoteEnabled' => false]);
            $pdf->loadHtml(view('journey.report', compact('applications'))->render());
            $pdf->setPaper('A4', 'landscape');
            $pdf->render();

            return response($pdf->output(), 200, ['Content-Type' => 'application/pdf', 'Content-Disposition' => 'attachment; filename="laporan-pkl.pdf"']);
        }
        // Excel 2003 XML keeps all user input as literal strings, never formulas.
        $xml = '<?xml version="1.0" encoding="UTF-8"?><?mso-application progid="Excel.Sheet"?><Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet" xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet"><Worksheet ss:Name="Lamaran"><Table>';
        $rows = [['Pelamar', 'Email', 'Lowongan', 'Perusahaan', 'Status']];
        foreach ($applications as $a) {
            $rows[] = [$a->user->name, $a->user->email, $a->opportunity->title, $a->opportunity->company, $a->status];
        }
        foreach ($rows as $row) {
            $xml .= '<Row>';
            foreach ($row as $v) {
                $xml .= '<Cell><Data ss:Type="String">'.htmlspecialchars($v,ENT_XML1 | ENT_QUOTES,'UTF-8').'</Data></Cell>';
            } $xml .= '</Row>';
        }

        return response($xml.'</Table></Worksheet></Workbook>',200,['Content-Type' => 'application/vnd.ms-excel', 'Content-Disposition' => 'attachment; filename="laporan-pkl.xml"']);
    }
}
