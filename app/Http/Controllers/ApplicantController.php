<?php

namespace App\Http\Controllers;

use App\Exports\ApplicantsExport;
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
use Maatwebsite\Excel\Excel;

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

        $applications = Applicant::whereIn('uuid', $cookie)->paginate(25);
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

        $cookie[] = $applicant->uuid;

        Cookie::queue('applied_applications_list', json_encode($cookie), (24 * 7 * 60 * 365));

        return $request->wantsJson() ? response()->json([
            'status' => 'success',
            'redirect' => route('applications')
        ])
            : redirect()->route('applications');
    }

    public function success(StoreApplicantRequest $request)
    {
        if (!$request->cookie('application_uuid')) {
            abort(404);
        }
        $uuid = $request->cookie('application_uuid');
        if (!($data = Applicant::where('uuid', $uuid)->first())) {
            abort(404);
        }
        return view('success', ['data' => $data, 'title' => 'Admission | ' . $data->name]);
    }

    public function applicationPrint(UpdateApplicantRequest $request, $uuid)
    {
        $data = Applicant::where('uuid', $uuid)->first();
        $cookie = $request->cookie('applied_applications_list');

        try {
            $cookie = $cookie ? json_decode($cookie) : [];
        } catch (\Exception $e) {
            $cookie = [];
        }

        if (!$data || (!Auth::check() && $request->cookie('application_uuid') != $uuid && !in_array($uuid, $cookie))) {
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
        $pdf->setSourceFile(storage_path('/application-form.pdf'));
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

        $pdf->SetXY(39, 74.5);
        $pdf->Write(0, $data->id);

        //insert image
        $filename = $data->uuid . '-image.' . $data->image;
        $image = storage_path('app/uploads/image/' . $filename);
        $pdf->Image($image, 159, 52.75, 30, 38, $data->image);

        $pdf->setFontSize(9);

        $pdf->SetXY(63, 102.25);
        $pdf->Write(0, strtoupper($data->name));

        $pdf->SetXY(63, 111);
        $pdf->Write(0, Carbon::createFromFormat('Y-m-d', $data->dob)->format('d/m/Y'));

        $pdf->SetXY(63, 119.5);
        $pdf->Write(0, strtoupper($data->guardian));

        $pdf->SetXY(63, 128.25);
        $pdf->Write(0, substr(strtoupper($data->address), 0, 68));

        if (strlen($data->address) > 68) {
            $pdf->SetXY(63, 137.25);
            $pdf->Write(0, substr(strtoupper($data->address), 69, 68));
        }

        $pdf->SetXY(63, 146);
        $pdf->Write(0, $data->mobile);

        $pdf->SetXY(63, 154.5);
        $pdf->Write(0, $data->type);

        if ($data->type == 'Orphan' && !empty($data->dod_father)) {
            $pdf->SetXY(85, 163.5);
            $pdf->Write(0, Carbon::createFromFormat('Y-m-d', $data->dod_father)->format('d/m/Y'));
        }

        $pdf->SetXY(85, 172.25);
        $pdf->Write(0, strtoupper($data->class));

        $pdf->SetXY(63, 181);
        $pdf->Write(0, strtoupper($data->mother));

        $pdf->SetXY(98, 189.75);
        $pdf->Write(0, $data->brothers);

        $pdf->SetXY(145, 189.75);
        $pdf->Write(0, $data->sisters);

        $pdf->SetFont('Helvetica', 'I');
        $pdf->SetXY(15.75, 290.75);
        $pdf->Write(0, 'System Generated File on ' . Carbon::now()->format('d-M-Y h:i:s a'));



        $pdf->Output("", 'application-form-' . $data->name . '.pdf');
        exit;
    }

    public function create()
    {
        $year = 2025;
        return view('apply', ['title' => "Application Form - Admission $year ", 'robots' => 'index,follow', 'description' => "Admission Portal for Hasanth Girls Campus in West Bengal. Darul Hasanath Islamiyya Complex serves the Muslim community in India, especially Kerala, through great visionary scholars and supportive community leaders, providing quality education and training."]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreApplicantRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreApplicantRequest $request)
    {
        $data = $request->validate([
            'name' => 'bail|required|string|max:255|min:5',
            'dob' => 'bail|required|date',
            'guardian' => 'bail|required|max:255|min:4',
            'address' => 'bail|required|string|max:255|min:4',
            'city' => 'bail|required|max:255',
            'postalcode' => 'bail|required|numeric|digits:6',
            'state' => 'bail|required|max:255|min:3',
            'mobile' => 'bail|required|numeric|digits:10',
            'type' => 'bail|nullable|numeric|in:0,1,2',
            'dod_father' => 'bail|nullable|required_if:type,1|date',
            'class' => 'bail|required|string|max:255',
            'mother' => 'bail|required|string|max:255|min:3',
            'image' => 'bail|required|file|mimes:jpg,jpeg,png,pdf|max:512',
            'brothers' => 'bail|required|numeric|min:0',
            'sisters' => 'bail|required|numeric|min:0',
            'declare' => 'bail|required|accepted',
        ]);

        $data['addrass'] = $data['address'] . ', ' . $data['city'] . ', ' . $data['state'] . ' - ' . $data['postalcode'];
        unset($data['city'], $data['state'], $data['postalcode'], $data['declare']);

        $uuid = Str::slug($request->name);

        while (Applicant::where('uuid', $uuid)->withTrashed()->count()) {
            $uuid .= '-' . Str::random(4);
        }

        $file = $request->file('image');

        $extension = $file->extension();

        $data['image'] = $extension;

        $filename = $uuid . '-image.' . $extension;
        $file->storeAs('uploads/image/', $filename);

        $data['uuid'] = $uuid;

        $applicant = Applicant::create($data);

        $cookie = $request->cookie('applied_applications_list');

        try {
            $cookie = $cookie ? json_decode($cookie) : [];
        } catch (\Exception $e) {
            $cookie = [];
        }

        $cookie[] = $uuid;
        $request->session()->flash('message', 'Application Form Submitted Successfully!');

        $cookiesNeeded = [
            'applied_applications_list' => json_encode($cookie),
            'application_uuid' => $uuid
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
        $applicant->delete();

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

    public function export(Excel $excel)
    {
        return $excel->download(new ApplicantsExport, 'applicants.xlsx');
    }

    public function ended()
    {
        return view('admission-ended');
    }
}
