<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use App\Http\Requests\StoreApplicantRequest;
use App\Http\Requests\UpdateApplicantRequest;
use Illuminate\Support\Str;
use Carbon\Carbon;
use setasign\Fpdi\Fpdi;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ApplicantController extends Controller
{

    public function index()
    {
        $applications = Applicant::paginate(100);
        return view('admin.dashboard', ['applications' => $applications]);
    }

    public function applications(StoreApplicantRequest $request)
    {
        $cookie = $request->cookie('applied_applications_list');

        try {
            $cookie = $cookie ? json_decode($cookie) : [];
        } catch (\Exception $e) {
            $cookie = [];
        }

        $applications = Applicant::whereIn('slug', $cookie)->paginate(25);
        return view('applications', ['applications' => $applications]);
    }

    public function search(StoreApplicantRequest $request)
    {
        $applicant = Applicant::where([
            'id' => $request->ref,
            'dob' => $request->dob
        ])->first();

        if (!$applicant) {
            $request->session()->flash('message', 'Application Not Found!');
            $request->session()->flash('type', 'error');
            abort(404);
            return false;
        }

        $cookie = $request->cookie('applied_applications_list');

        try {
            $cookie = $cookie ? json_decode($cookie) : [];
        } catch (\Exception $e) {
            $cookie = [];
        }

        $cookie[] = $applicant->slug;

        Cookie::queue('applied_applications_list', json_encode($cookie), (24 * 7 * 60 * 365));

        return $request->wantsJson() ? response()->json([
            'status' => 'success',
            'redirect' => route('applications')
        ])
            : redirect()->route('applications');
    }

    public function success(StoreApplicantRequest $request)
    {
        if (!$request->cookie('hallticket_slug') && !$request->cookie('application_slug')) {
            abort(404);
        }
        $slug = $request->cookie('application_slug') ?: $request->cookie('hallticket_slug');
        if (!($data = Applicant::where('slug', $slug)->first())) {
            abort(404);
        }
        return view('success', ['data' => $data, 'title' => 'Admission | ' . $data->name]);
    }

    public function applicationPrint(UpdateApplicantRequest $request, $slug)
    {
        $data = Applicant::where('slug', $slug)->first();
        $cookie = $request->cookie('applied_applications_list');

        try {
            $cookie = $cookie ? json_decode($cookie) : [];
        } catch (\Exception $e) {
            $cookie = [];
        }

        if (!$data || (!Auth::check() && $request->cookie('hallticket_slug') != $slug && !in_array($slug, $cookie))) {
            abort(404);
            return false;
        }

        // initiate FPDI
        $pdf = new Fpdi(/*'P','mm','A4'*/);
        $pdf->SetAutoPageBreak(false, 0);
        $pdf->SetMargins(0, 0, 0);
        // add a page
        $pdf->AddPage();
        // set the source file
        $pdf->setSourceFile(storage_path('/app/application-form.pdf'));
        // import page 1
        $tplIdx = $pdf->importPage(1);
        $pdf->SetMargins(0, 0, 0);
        // use the imported page and place it at position 10,10 with a width of 100 mm

        $sizes = $pdf->getImportedPageSize($tplIdx);
        $pdf->useImportedPage($tplIdx, 0, 0, $pdf->GetPageWidth(), $pdf->GetPageHeight());

        // now write some text above the imported page
        $pdf->SetFont('Helvetica');
        $pdf->setFontSize(10.5);
        $pdf->SetTextColor(0, 0, 0);

        $pdf->SetXY(39, 75.25);
        $pdf->Write(0, $data->id);

        //insert image
        $filename = $data->slug . '-image.' . $data->image;
        $image = storage_path('app/uploads/image/' . $filename);
        $pdf->Image($image, 159.25, 53.5, 30, 38, $data->image);

        $pdf->setFontSize(9);

        $pdf->SetXY(63, 102.5);
        $pdf->Write(0, strtoupper($data->name));

        $pdf->SetXY(63, 113.25);
        $pdf->Write(0, Carbon::createFromFormat('Y-m-d', $data->dob)->format('d/m/Y'));

        $pdf->SetXY(63, 123.75);
        $pdf->Write(0, strtoupper($data->guardian));

        $data->address .= ', ' . $data->city . ', ' . $data->state . ' - ' . $data->postalcode;
        $pdf->SetXY(63, 134.25);
        $pdf->Write(0, substr(strtoupper($data->address), 0, 65));

        if (strlen($data->address) > 65) {
            $pdf->SetXY(63, 144.95);
            $pdf->Write(0, substr(strtoupper($data->address), 66, 65));
        }

        $pdf->SetXY(63, 155.5);
        $pdf->Write(0, $data->mobile);

        $pdf->SetXY(63, 166.15);
        $pdf->Write(0, $data->mobile2);

        $pdf->SetXY(63, 176.5);
        $pdf->Write(0, strtolower($data->email));

        $pdf->SetXY(63, 187.25);
        $pdf->Write(0, strtoupper($data->city));

        $pdf->SetXY(120, 197.75);
        $pdf->Write(0, strtoupper($data->makthab));

        $pdf->SetXY(63, 207.9);
        $pdf->Write(0, $data->makthab_years ?: 'N/A');

        $pdf->SetFont('Helvetica', 'I');
        $pdf->SetXY(15.75, 290.75);
        $pdf->Write(0, 'System Generated File on ' . Carbon::now()->format('d-M-Y H:i:s'));

        // $pdf->SetXY(77.5, 166.5);
        // $pdf->Write(0, 'Niyaz VPP');



        $pdf->Output("D", 'application-form-' . $data->name . '.pdf');
        exit;
    }

    public function hallTicket(UpdateApplicantRequest $request, $slug)
    {
        $data = Applicant::where('slug', $slug)->first();
        $cookie = $request->cookie('applied_applications_list');

        try {
            $cookie = $cookie ? json_decode($cookie) : [];
        } catch (\Exception $e) {
            $cookie = [];
        }

        if (!$data || (!Auth::check() && $request->cookie('hallticket_slug') != $slug && !in_array($slug, $cookie))) {
            abort(404);
            return false;
        }

        // initiate FPDI
        $pdf = new Fpdi('P', 'mm', 'A4');
        $pdf->SetAutoPageBreak(false, 0);
        $pdf->SetMargins(0, 0, 0);
        // add a page
        $pdf->AddPage();
        $pdf->SetMargins(0, 0, 0);
        // set the source file
        $pageCount = $pdf->setSourceFile(storage_path('/app/hallticket.pdf'));
        // import page 1
        $tplIdx = $pdf->importPage(1);
        $pdf->SetMargins(0, 0, 0);
        // use the imported page and place it at position 10,10 with a width of 100 mm

        $sizes = $pdf->getImportedPageSize($tplIdx);
        $pdf->useImportedPage($tplIdx, 0, 0, $pdf->GetPageWidth(), $pdf->GetPageHeight());
        $pdf->SetMargins(0, 0, 0);

        // now write some text above the imported page
        $pdf->SetFont('Helvetica');
        $pdf->setFontSize(10.5);
        $pdf->SetTextColor(0, 0, 0);

        $pdf->SetXY(40.5, 85.35);
        $pdf->Write(0, $data->id);

        $pdf->SetXY(154.75, 138.5);
        $pdf->Write(0, $data->id);

        //insert image
        $filename = $data->slug . '-image.' . $data->image;
        $image = storage_path('app/uploads/image/' . $filename);
        $pdf->Image($image, 154, 83, 30, 38, $data->image);

        $filename = 'frame.png';
        $image = storage_path('app/' . $filename);
        $pdf->Image($image, 160, 235, 25, 25, 'png');

        $pdf->setFontSize(5);
        $pdf->SetXY(159.5, 261.5);
        $pdf->Write(0, 'Scan to Join WhatsApp Group');

        $pdf->setFontSize(10.5);

        $pdf->SetXY(75.5, 100);
        $pdf->Write(0, strtoupper($data->name));

        $pdf->SetXY(75.5, 110);
        $pdf->Write(0, strtoupper($data->guardian));

        $pdf->SetXY(75.5, 119.75);
        $pdf->Write(0, substr(strtoupper($data->address), 0, 32));

        if (strlen($data->address) > 32) {
            $pdf->SetXY(75.5, 124);
            $pdf->Write(0, substr(strtoupper($data->address), 32, 32));
        }

        $pdf->SetXY(75.5, 129.25);
        $pdf->Write(0, Carbon::createFromFormat('Y-m-d', $data->dob)->format('d/m/Y'));

        $pdf->SetXY(75.5, 139.25);
        $pdf->Write(0, $data->mobile . ', ' . $data->mobile2);


        $pdf->SetXY(75.5, 158);
        $pdf->Write(0, strtoupper('dfdf'));

        $pdf->SetXY(75.5, 167.5);
        $pdf->Write(0, 'dfdfdf');

        $pdf->Output("D", 'hall-ticket-' . $data->name . '.pdf');
        exit;
    }

    public function create()
    {
        $year = 2024;
        return view('apply', ['title' => "Application Form - Admission $year ", 'robots' => 'index,follow', 'description' => "Admission Test - $year of Darul Huda Islamic University Assam Off Campus will be held at different centres in Assam in March, April months. Darul Huda Islamic University Kerala serve the Muslim community in India through great visionary scholars and supportive community leaders."]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreApplicantRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreApplicantRequest $request)
    {
        $rules = [
            'address' => 'bail|required|string|max:255|min:4',
            'bc' => 'bail|required|file|mimes:jpg,jpeg,png,pdf|max:1024',
            'city' => 'bail|required|max:255',
            'declare' => 'bail|required|accepted',
            'dob' => 'bail|required|date|after_or_equal:01-Nov-2011|before_or_equal:30-Apr-2013',
            'email' => 'bail|required|email|max:255',
            'guardian' => 'bail|required|max:255|min:4',
            'image' => 'bail|required|file|mimes:jpg,jpeg,png,pdf|max:512',
            'mobile' => 'bail|required|numeric|digits:10',
            'mobile2' => 'bail|required|numeric|digits:10',
            'name' => 'bail|required|string|max:255|min:5',
            'postalcode' => 'bail|required|numeric|digits:6',
            'state' => 'bail|required|max:255|min:3',
            'tc' => 'bail|nullable|file|mimes:jpg,jpeg,png,pdf|max:1024',
        ];
        $request->validate($rules);

        $slug = Str::slug($request->name);

        while (count(Applicant::where('slug', $slug)->get())) {
            $slug .= '-' . Str::random(4);
        }

        $applicant = new Applicant;

        foreach (['bc', 'tc', 'image'] as $photo) {

            if (!$request->$photo) {
                continue;
            }

            $file = $request->file($photo);

            $extension = $file->extension();

            $applicant->$photo = $extension;

            $filename = $slug . '-' . $photo . '.' . $extension;
            $file->storeAs('uploads/' . $photo . '/', $filename);
        }

        foreach ($rules as $key => $value) {
            if (!in_array($key, ['tc', 'bc', 'image', 'declare'])) {
                $applicant->$key = $request->$key;
            }
        }

        $applicant->slug = $slug;

        $applicant->save();

        $cookie = $request->cookie('applied_applications_list');

        try {
            $cookie = $cookie ? json_decode($cookie) : [];
        } catch (\Exception $e) {
            $cookie = [];
        }

        $cookie[] = $slug;
        $request->session()->flash('message', 'Application Form Submitted Successfully!');

        $cookiesNeeded = [
            'applied_applications_list' => json_encode($cookie),
            'hallticket_slug' => $slug,
            'application_slug' => $slug
        ];

        foreach ($cookiesNeeded as $name => $value) {
            Cookie::queue($name, $value, (24 * 7 * 60 * 365));
        }
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return $request->wantsJson() ? response()->json([
            'status' => 'success',
            'redirect' => route('applied')
        ])
            : redirect()->route('applied');
    }

    public function delete($id)
    {

        $applicant = Applicant::findOrFail($id);
        $applicant->remarks = 'deleted';
        $applicant->save();

        return redirect()->route('dashboard')->with('message', 'Application Deleted Successfully!');
    }

    public function destroy(UpdateApplicantRequest $request, Applicant $applicant)
    {
        if (!Hash::check($request->password, $request->user()->password)) {
            return back()->withErrors([
                'password' => ['Incorrect Password!']
            ])->with([
                'message' => 'Incorrect Password. Please Retry!',
                'type' => 'error'
            ]);
        }
        $applicant = Applicant::all();
        if (!($count = count($applicant))) {
            return redirect()->route('dashboard')->with([
                'message' => 'No Data Found!',
                'type' => 'error'
            ]);
        }
        $json = $applicant->toJson();

        $json_new = 1;

        while (file_exists(storage_path('app/deleted/database/' . $json_new . '.json'))) {
            $json_new++;;
        }

        $json_new .= '.json';
        Storage::put('deleted/database/' . $json_new, $json);

        $new_uploads = 1;

        while (file_exists(storage_path('app/deleted/uploads/' . $new_uploads))) {
            $new_uploads++;
        }

        Storage::move('uploads', 'deleted/uploads/' . $new_uploads);

        Applicant::truncate();
        $count .=  'Application' . ($count == 1 ? 's' : '');
        return redirect()->route('dashboard')->with('message', $count . ' Deleted!');
    }

    public function ended()
    {
        return view('admission-ended');
    }
}
