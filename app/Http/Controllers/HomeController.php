<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Applicant;
use App\Models\Setting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{


    protected $centres = [
        ['Dubhri', '04/02/2024'],
        ['Goraimari', '10/02/2024'],
        ['Karupetia', '11/02/2024'],
        ['Guwahati', '18/02/2024'],
        ['Bongaigaon', '24/02/2024'],
        ['Manipur', '24/02/2024'],
        ['Manipur', '25/02/2024'],
        ['Tripura', '29/02/2024'],
        ['Karimganj', '02/03/2024'],
        ['Baisha - Barmara DH Campus', '03/03/2024'],
        ['Baisha - Barmara DH Campus', '07/03/2024'],
    ];

    private function code($centre_id)
    {
        $centre = $this->centres[$centre_id - 1][0] ?? 'DH';
        return strtoupper(substr($centre ?? 'DH', 0, 2));
    }

    public function index()
    {
        $settings = Cache::rememberForever('settings', function () {
            $settings_all = Setting::all();
            $settings_ = new \stdClass;
            foreach ($settings_all as $name) {
                $settings_->{$name->name} = $name->value;
            }
            return $settings_;
        });
        $applicants_results = ($settings->results_starting_at ?? false) && \Carbon\Carbon::now()->between($settings->results_starting_at, $settings->results_ending_at);
        return view('home', ['title' => 'Home', 'robots' => 'index,follow', 'results' => $applicants_results]);
    }

    public function settingsStore(Request $request)
    {
        $rules = [
            'starting_at' => 'required|date',
            'ending_at' => 'required|date|after_or_equal:starting_at',
            'results_starting_at' => 'required|date|after:ending_at',
            'results_ending_at' => 'required|date|after:results_starting_at',
        ];
        $request->validate($rules);

        foreach ($rules as $key => $value) {
            Setting::updateOrCreate(['name' => $key], ['value' => $request->get($key)]);
        }

        return back()->with('message', 'Settings Updated Successfully!');
    }

    public function applicantStatus()
    {
        $applicationsAll = Applicant::where('remarks', '<>', 'deleted')
            ->orWhereNull('remarks')
            ->select('id', 'name', 'status')
            ->get();
        return view('admin.status', ['applicantsAll' => $applicationsAll]);
    }

    public function updateApplicantStatus(Request $request)
    {
        $update = [];
        $errors = [];
        foreach ($request->all() as $key => $value) {
            if (!Str::contains($key, 'id')) {
                continue;
            }
            $id = str_replace('id', '', $key);
            $value = collect(['id' => $id, 'status' => ($value ?? 0)]);
            $validator = Validator::make($value->all(), [
                'id' => 'bail|required|exists:applicants,id',
                'status' => 'bail|required|in:0,1'
            ])->validate();

            $update[] = ['id' => $value->get('id'), 'status' => $value->get('status')];
        }
        if (!count($update)) {
            return back()->with('message', 'No Data to Update!')->with('type', 'error');
        }
        foreach ($update as $u) {
            $c = Applicant::where('id', $u['id'])->update(['status' => $u['status']]);
        }
        return back()->with('message', count($update) . ' Results Updated Successfully!');
    }

    public function results()
    {
        return view('results');
    }

    public function resultShow(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:applicants,id',
            'dob' => 'required|exists:applicants,dob',
        ]);
        $applicant = Applicant::where('remarks', '<>', 'deleted')->orWhereNull('remarks')->get(['name', 'dob', 'id', 'status', 'examcentre']);
        $applicant = $applicant->where('id', $request->id)->where('dob', $request->dob)->first();

        if ($applicant) {
            return redirect()->route('results')->with(['result' => $applicant, 'code' => $this->code($applicant->examcentre)]);
        }

        return back()->with('message', 'Incorrect Entry')->with('type', 'error');
    }

    public function marksheet()
    {

        // $file = file_get_contents(Storage::path('marksheet.json'));
        // echo '<pre>'; print_r(json_decode($file)); echo '</pre>';
        // exit;

        $student = false;
        if (session('result_ad_no') && session('result_roll_no')) {
            $student = $this->getStudentByAdNoAndRoll(session('result_ad_no'), session('result_roll_no'));
        }
        return view('marksheet', compact('student'));
    }

    private function getStudentByAdNoAndRoll($ad_no, $roll_no)
    {
        $file = file_get_contents(Storage::path('marksheet.json'));
        $presentData = false;
        if ($file) {
            $students = json_decode($file);
            $student = array_filter($students, function ($student) use ($ad_no, $roll_no) {
                return $student->ad_no == $ad_no && $student->roll_no == $roll_no;
            });
            if (count($student)) {
                return array_values($student)[0];
            }
        }
        return false;
    }

    public function marksheetPost(Request $request)
    {
        $request->validate([
            'ad_no' => 'required',
            'roll_no' => 'required',
        ]);

        $request->roll_no = strtoupper($request->roll_no);

        if ($this->getStudentByAdNoAndRoll($request->ad_no, $request->roll_no)) {
            session()->flash('result_ad_no', $request->ad_no);
            session()->flash('result_roll_no', $request->roll_no);
            return redirect()->route('marksheet');
        }

        return back()->withErrors(['error' => 'Please Enter Correct Admission No and Roll No.']);
    }
}
