<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Applicant;
use App\Models\Setting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class HomeController extends Controller
{
    public function index()
    {
        $settings = Cache::rememberForever('settings', function () {
            return (object) Setting::pluck('value', 'name')->toArray();
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

        Cache::forget('settings');
        return back()->with('message', 'Settings Updated Successfully!');
    }

    public function applicantStatus()
    {
        $applicationsAll = Applicant::select('id', 'name', 'status')
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
            'id' => 'required|numeric',
            'dob' => 'required|date',
        ]);
        $applicant = Applicant::where('dob', $request->dob)
            ->select('name', 'dob', 'id', 'status')
            ->find($request->id);

        if ($applicant) {
            return redirect()->route('results')->with(['result' => $applicant]);
        }

        return back()->with('message', 'Incorrect Entry')->with('type', 'error');
    }
}
