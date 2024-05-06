@extends('layouts.guest')
@section('main')
@include('layouts.navigation')
@if($student)

<div class="block max-w-5xl px-4 py-6 mx-auto text-black uppercase sm:px-6 lg:px-8" x-data='{
        marks: {!! json_encode($student->marks) !!},
        status() {
          var status = "Passed";
          this.marks.forEach(m => {
            if (m.mark < 40) status = "Failed";
          });
          return status;
        },
        total() {
          return this.marks.reduce((m, mm) => m * 1 + mm.mark * 1, 0);
        },
        percent() {
          return (Math.round((this.total() * 100) / 14) / 100) + "%";
        }
      }'>


    <div class="px-4 py-2 text-center bg-white border-t-4 border-b-2 border-blue-400 rounded-t-md">
        <div class="text-2xl font-semibold">
            Darul Huda Assam Campus
        </div>
        <h1 class="mb-4 text-xl font-medium">Academic Year 2022-2023</h1>
        <h2 class="mb-4 text-4xl font-bold text-blue-400">Students Mark Sheet</h2>
    </div>
    <div class="px-4 py-2 text-center bg-white border-b sm:px-8">
        <div class="overflow-x-auto">
            <table class="w-full table-auto">
                <tr>
                    <td class="p-2 font-medium text-left text-gray-500 uppercase"></td>
                    <td class="p-2 font-medium text-left text-gray-500 uppercase">Name: {{ $student->name }}</td>
                    <td class="p-2 font-medium text-left text-gray-500 uppercase">Admission No.: {{ $student->ad_no }}
                    </td>
                </tr>
                <tr>
                    <td class="p-2 font-medium text-left text-gray-500 uppercase"></td>
                    <td class="p-2 font-medium text-left text-gray-500 uppercase">Class: I</td>
                    <td class="p-2 font-medium text-left text-gray-500 uppercase">Roll No: {{ $student->roll_no }}</td>
                </tr>
                <tr>
                    <td class="p-2 font-medium text-left text-gray-500 uppercase"></td>
                    <td class="p-2 font-medium text-left text-gray-500 uppercase">Status: <span
                            :class="{ 'text-green-500' : status() == 'Passed', 'text-red-500': status() == 'Failed'  }"
                            x-text="status()"></span></td>
                    <td class="p-2 font-medium text-left text-gray-500 uppercase">Rank: <span
                            x-text="status() == 'Failed' ? 'N/A' : {{ $student->rank }}"></span></td>
                </tr>
            </table>
        </div>
    </div>
    <div class="px-4 py-2 text-center bg-white border-b-4 border-blue-400 sm:px-8 rounded-b-md">
        <div class="overflow-x-auto">
            <table class="w-full table-auto">
                <thead class="text-xs font-semibold text-white uppercase bg-blue-400">
                    <tr>
                        <th class="p-2 whitespace-nowrap">
                            <div class="font-semibold text-center">#</div>
                        </th>
                        <th class="p-2 whitespace-nowrap">
                            <div class="font-semibold text-left">Subject</div>
                        </th>
                        <th class="p-2 whitespace-nowrap">
                            <div class="font-semibold text-center">Marks</div>
                        </th>
                        <th class="p-2 whitespace-nowrap">
                            <div class="font-semibold text-center">Status</div>
                        </th>
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-gray-100">
                    <template x-for="mark in marks">
                        <tr>
                            <td class="p-2 font-medium text-center text-gray-500 uppercase whitespace-nowrap"
                                x-text="mark.id"></td>
                            <td class="p-2 font-medium text-left text-gray-500 uppercase whitespace-nowrap"
                                x-text="mark.subject"></td>
                            <td class="p-2 font-medium text-center text-gray-500 uppercase whitespace-nowrap"
                                x-text="mark.mark"></td>
                            <td class="p-2 font-semibold text-center whitespace-nowrap"
                                :class="{ 'text-green-500' : mark.mark >= 40, 'text-red-400': mark.mark < 40  }"
                                x-text="mark.mark < 40 ? 'Failed' : 'Passed'"></td>
                        </tr>
                    </template>
                    <tr>
                        <td class="p-2 font-medium text-center text-gray-600 uppercase whitespace-nowrap"></td>
                        <td class="p-2 font-medium text-left text-gray-600 uppercase whitespace-nowrap"></td>
                        <td class="p-2 font-medium text-center text-gray-600 uppercase whitespace-nowrap"></td>
                        <td class="p-2 font-semibold text-center whitespace-nowrap"></td>
                    </tr>
                    <tr class="text-xs font-semibold text-gray-700 uppercase bg-gray-50">
                        <th class="p-2 whitespace-nowrap">
                            <div class="font-semibold text-center"></div>
                        </th>
                        <th class="p-2 whitespace-nowrap">
                            <div class="font-semibold text-left">Total</div>
                        </th>
                        <th class="p-2 whitespace-nowrap">
                            <div class="font-semibold text-center" x-text="total()"></div>
                        </th>
                        <th class="p-2 whitespace-nowrap">
                            <div class="font-bold text-center" x-text="percent()"></div>
                        </th>
                    </tr>
                </tbody>
            </table>
        </div>

        <p class="my-2 text-sm text-left text-gray-400">
            <b>Disclaimer:</b> The Result published online is provisional and can't be used for any official purpose.
            Care has been given to present it as accurately as possible.
        </p>

        <a href="{{ route('marksheet') }}"
            class="block w-full max-w-xl px-8 py-2 my-4 font-semibold text-white uppercase bg-gray-500 rounded-lg shadow sm:w-auto sm:inline-block hover:bg-gray-400">
            Check Another Result
        </a>

    </div>

</div>

@else

<div class="block max-w-5xl px-4 py-6 mx-auto text-black uppercase sm:px-6 lg:px-8">
    <div class="px-4 py-2 text-center bg-white border-t-4 border-b-4 border-blue-400 rounded-t-md">
        <div class="text-2xl font-semibold">
            Darul Huda Assam Campus
        </div>
        <h1 class="mb-4 text-xl font-medium">Academic Year 2022-2023</h1>
        <h2 class="mb-4 text-4xl font-bold text-blue-400">Examination Results</h2>

        <x-alert />
        <!-- Validation Errors -->
        <x-auth-validation-errors class="px-4 mb-4 text-center" :errors="$errors" />

        <div class="my-4 text-center">
            <form class="block" method="POST" action="{{ route('marksheet') }}">
                @csrf
                <div class="flex items-center justify-center my-4 form-wrapper">
                    <div class="block w-full max-w-xl">
                        <label for="roll_no" class="block text-sm font-medium text-left text-gray-700">Roll No.</label>
                        <input required type="text" name="roll_no" id="roll_no" autocomplete="roll_no"
                            class="block w-full max-w-xl mt-1 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        <p class="mt-1 text-sm text-left text-gray-600">Enter your <b>Roll No.</b></p>
                    </div>
                </div>
                <div class="flex items-center justify-center my-4 form-wrapper">
                    <div class="block w-full max-w-xl">
                        <label for="ad_no" class="block text-sm font-medium text-left text-gray-700">Admission
                            No.</label>
                        <input required type="number" name="ad_no" id="ad_no" autocomplete="ad_no"
                            class="block w-full max-w-xl mt-1 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    </div>
                </div>
                <button type="submit"
                    class="block w-full max-w-xl px-8 py-2 my-4 font-semibold text-white uppercase bg-blue-500 rounded-lg shadow sm:w-auto sm:inline-block hover:bg-blue-400">
                    Check Result
                </button>
            </form>
        </div>

    </div>
</div>

@endif

@if(config('developerMode') == 'on')

<div class="my-8" x-data="{ value: '', convert(){

  var returnData = [];
  var paste = this.value;
  if (paste.indexOf('\t') == '-1' && paste.indexOf('\r\n') == '-1') return false;

  paste = paste.trim();

  var lines = paste.split('\n');
  lines.forEach(line => {
    var lineData = [];
    var cells = line.split('\t');
    cells.forEach(cell => {
      lineData.push(cell);
    });
    returnData.push(lineData);
  });

  var json = [];
  var not_marks = ['id', 'ad_no', 'roll_no', 'name', 'percentage', 'rank', 'total'];
  var headers = returnData.shift();
  returnData.forEach(d => {
    var json_obj = {
      marks: []
    };
    var sub_counts = 0;
    headers.forEach((h, i) => {

      if (d[i] == null || typeof d[i] === 'undefined' || d[i] == '') return false;

      if(not_marks.includes(h)) {
        json_obj[h] = d[i];
      } else {
        sub_counts++;
        json_obj.marks.push({
          id: sub_counts,
          subject: h,
          mark: d[i]
        });
      }
    });
    json.push(json_obj);
  });
  document.write(JSON.stringify(json));

} }">
    <textarea @change="value = $event.target.value"></textarea>
    <button @click="convert()">Get</button>
</div>
@endif

@endsection
