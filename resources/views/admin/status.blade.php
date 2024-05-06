@extends('layouts.guest')
@section('main')
    
    @include('layouts.navigation')
    <x-alert />

    @include('front')

    <div x-data="app()" x-cloak class="block max-w-7xl mx-auto py-6 sm:px-6 lg:px-8 text-black">

        <div class="card">
            <div class="card-body">
                <form action="" method="POST" @submit.prevent="submit($event)">
                @csrf

                    <div class="py-2 flex justify-between items-center pr-4">
                        <input type="search" name="search" placeholder="Search Name / Sl. No" class="input w-max mx-2 my-auto" @input="searchInput = $event.target.value">
                    </div>

                    <table class="w-full table-auto">
                       <thead>
                           <tr class="text-gray-400 font-light text-sm leading-normal">
                               <template x-for="(header, i) in Object.keys(headers)">
                                   <th class="font-normal text-left pr-3 text-center">
                                        <span class="mr-0.5 inline-block">
                                          <svg @click="sort(header)" xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 hover:text-gray-500 hover:cursor-pointer transition-all ease-in-out duration-150" viewBox="0 0 20 20" fill="currentColor" :class="{ 'text-gray-600': sorted.field == header, 'rotate-180': sorted.field == header && sorted.rule == 'desc' }">
                                              <path fill-rule="evenodd" d="M3.293 9.707a1 1 0 010-1.414l6-6a1 1 0 011.414 0l6 6a1 1 0 01-1.414 1.414L11 5.414V17a1 1 0 11-2 0V5.414L4.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                          </svg>
                                        </span>
                                       <span x-text="headers[header].name"></span>
                                   </th>
                               </template>
                           </tr>
                       </thead>
                       <tbody class="text-gray-300 text-sm font-light">
                            <template x-for="(tr, c) in sortedData()" :key="tr.id">
                                <tr class="border-b border-gray-100">
                                    <template x-for="(td, d) in Object.keys(headers)">
                                        <td class="py-3 pr-3 text-center align-middle text-gray-700">
                                            <template x-if="!headers[td].includesInput">
                                                <div class="py-3 pr-3 text-center text-gray-700" x-text="typeof tr[td] === 'number' ? tr[td] : tr[td].replace('u', '')"></div>
                                            </template>
                                            <template x-if="headers[td].includesInput && headers[td].includesInput.type != 'textarea' && headers[td].includesInput.type != 'checkbox'">
                                                <div class="py-3 pr-3 text-center text-gray-700">
                                                    <input :type="headers[td].includesInput.type" class="input mb-0" @input="updateField(c + 1, td, $event)" :value="fieldvalue(c + 1, td)">
                                                </div>
                                            </template>
                                            <template x-if="headers[td].includesInput && headers[td].includesInput.type == 'checkbox'">
                                                <div class="py-3 pr-3 text-center text-gray-700">
                                                    <input :type="headers[td].includesInput.type" value="1" class="input mb-0" @input="updateField(c + 1, td, $event)" :checked="fieldvalue(c + 1, td) == 1">
                                                </div>
                                            </template>
                                            <template x-if="headers[td].includesInput && headers[td].includesInput.type == 'textarea'">
                                                <div class="py-3 pr-3 text-center text-gray-700">
                                                    <textarea class="input mb-0" @input="updateField(c + 1, td, $event)" :value="fieldvalue(c + 1, td)" x-text="fieldvalue(c + 1, td)"></textarea>
                                                </div>
                                            </template>
                                        </td>
                                    </template>
                                </tr>
                            </template>
                       </tbody>
                    </table>

                    <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center justify-between px-4 pr-6 py-2">
                        <div class="flex justify-between flex-1 sm:hidden">
                            <button type="button" @click="previousPage($event)" :disabled="currentPage == 1" :class="{ 'cursor-not-allowed': currentPage == 1 }" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 leading-5 rounded-md hover:text-gray-500 focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150">&laquo; Previous
                            </button>
                            <button type="button" @click="nextPage($event)" :disabled="currentPage == lastPage()" :class="{ 'cursor-not-allowed': currentPage == lastPage() }" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 leading-5 rounded-md hover:text-gray-500 focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150">Next &raquo;
                            </button>
                        </div>
                        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                            <div>
                                <p class="text-sm text-gray-700 leading-5">
                                    Showing
                                    <span class="font-medium" x-text="pageFrom()">2</span>
                                    to
                                    <span class="font-medium" x-text="pageTo()">2</span>
                                    of
                                    <span class="font-medium" x-text="pageTotal()">2</span>
                                    results

                                </p>
                            </div>
                            <div>
                                <span class="relative z-0 inline-flex shadow-sm rounded-md">
                                    <button type="button" @click="previousPage($event)" :disabled="currentPage == 1" :class="{ 'cursor-not-allowed': currentPage == 1 }" rel="prev" class="relative inline-flex items-center px-2 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-l-md leading-5 hover:text-gray-400 focus:z-10 focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 active:bg-gray-100 active:text-gray-500 transition ease-in-out duration-150" aria-label="&amp;laquo; Previous">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                        </svg>
                                    </button>
                                    
                                    <template x-for="(p, i) in pages" :key="i">
                                        <div>
                                            <template x-if="currentPage != p">
                                                <button type="button" @click="currentPage = p; $event.target.blur()" class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-gray-700 bg-white border border-gray-300 leading-5 hover:text-gray-500 focus:z-10 focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150" aria-label="Go to page 1" x-text="p"></button>
                                            </template>
                                            <template x-if="currentPage == p">
                                                <span aria-current="page">
                                                    <span class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-gray-500 bg-white border border-gray-300 cursor-default leading-5" x-text="p"></span>
                                                </span>
                                            </template>
                                        </div>
                                    </template>

                                    <button type="button" @click="nextPage($event)" :disabled="currentPage == lastPage()" :class="{ 'cursor-not-allowed': currentPage == lastPage() }" rel="next" class="relative inline-flex items-center px-2 py-2 -ml-px text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-r-md leading-5 hover:text-gray-400 focus:z-10 focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 active:bg-gray-100 active:text-gray-500 transition ease-in-out duration-150" aria-label="Next &amp;raquo;">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                    </button>
                                </span>
                            </div>
                        </div>
                    </nav>

                    <div class="py-2 text-center">
                        <button type="submit" class="btn btn-green py-2 px-10 mr-2">
                            <span x-show="!saved" class="">Update Data</span>
                            <span x-show="saved" class="inline-flex items-center justify-center">
                                <svg class="animate-spin mr-3 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span>Processing...</span>
                            </span>
                        </button>
                        <button @click="reset($nextTick)" type="button" class="btn shadow-sm bg-gray-100 text-gray-700 border border-gray-200 focus:ring-0 py-2 px-10 mr-2">
                            Reset Back
                        </button>
                    </div>

                </form>
            </div>
        </div>
        
    </div>
<script src="https://cdn.jsdelivr.net/npm/fuse.js@6.5.3"></script>
<script type="text/javascript">

    function app() {
        var data = {
            count: 0,
            state: false,
            saved: false,
            ...dataTable2(),
            dispatch2(message, type = 'success') {
                this.$dispatch('alpine-show-message', { data: message, type: type });
            },
            buttonStatus() {
                var data = this.updateDataTrimmed();
                return Object.keys(data).length < 1 || this.saved;
            },
            updateDataTrimmed() {
                var data = this.updateData;
                var filtered = {};
                var key;
                var value;
                var addIt;
                Object.entries(data).forEach((o, i) => {
                    addIt = false;
                    key = o[0];
                    value = o[1];
                    console.log(value);
                    if (Object.keys(value).length < 1)
                        return false;
                    var ogIndex = this.ogData.findIndex(x => x.id === value.id);
                    var filteredValue = {};
                    Object.keys(value).forEach(v => {
                        if ((!this.ogData[ogIndex] || value[v] != this.ogData[ogIndex][v]) && this.notEmpty(value[v]) && v != 'id') {
                            addIt = true;
                            filteredValue[v] = value[v];
                        }
                    });
                    if (addIt) {
                        filtered[key] = filteredValue;
                    }
                });
                return filtered;
            },
            notEmpty(v) {
                return v != '' && v != null && typeof v !== 'undefined';
            },
            compareValues(...arrayOfObjects) {
                var check;
                arrayOfObjects.forEach((a, io) => {
                    var sorted_keys = Object.keys(a).sort();
                    var sort_json = {};
                    sorted_keys.forEach(i => sort_json[i] = a[i]);
                    if (io == 0) {
                        check = sort_json;
                    } else
                    if (JSON.stringify(check) === JSON.stringify(sort_json)) return true;
                });
                return false;
            },
            submit(ev) {
                if (!Object.keys(this.updateData).length) {
                    this.dispatch2('No Change Made!', 'error');
                    return false;
                }
                if (this.state) return false;
                this.state = true;
                this.saved = true;
                this.$nextTick(() => {
                    var formData = new FormData();
                    formData.append("data", JSON.stringify(this.updateDataTrimmed()));
                    formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);

                    fetch('{{ route('status') }}', {
                      method: 'post',
                      headers: {
                        'Accept': 'application/json',
                      },
                      body: formData
                    }).then(res => res.json())
                      .then(res => {
                        this.state = false;
                        this.saved = false;
                        console.log(res);
                        if (res.response == 'success') {
                                this.dispatch2(res.message, 'success');
                                this.ogData = JSON.parse(JSON.stringify(this.data));
                                this.updateData = {};
                                ev.target.querySelectorAll('[type="password"]').forEach(pass => pass.value = '');
                            }
                        else
                            this.dispatch2('<li>' + Object.values(res.errors).join('</li><li>') + '</li>', 'error');
                      })
                      .catch(e => {
                        console.log(e);
                        this.dispatch2('Something Went Wrong!', 'error');
                        this.saved = false;
                      });

                });
            }
        };
        return data;
    }

    function dataTable2() {
        var headers = {
           'id': {
                name: 'Sl. No',
                includesInput: false,
            },
           'name': {
                name: 'Name',
                includesInput: false,
            },
           'status': {
                name: 'Status',
                includesInput: {
                    type: 'checkbox',
                },
            },
        };
        var data = {!! $applicantsAll->toJson() !!};
        return {
            init() {

            },
            previousPage(e) {
                e.target.blur();
                if (this.currentPage <= 1)
                    return false;
                this.currentPage--;
            },
            nextPage(e) {
                e.target.blur();
                if (this.currentPage >= this.lastPage())
                    return false;
                this.currentPage++;
            },
            performing: false,
            add($nextTick) {
                if (this.performing) return false;
                this.performing = true;
                this.searchInput = '';
                this.currentPage = this.lastPage();
                $nextTick(()=> {
                    if (this.columns() < 25) {
                        this.data.push({
                            'id': 'u' + (this.getLastId() + 1)
                        });
                        this.currentPage = this.lastPage();
                        $nextTick(() => {
                            this.performing = false;
                        });
                        return false;
                    }
                    this.performing = false;
                });
            },
            headers: headers,
            updateData: {},
            data: data,
            ogData: JSON.parse(JSON.stringify(data)),
            columns() {
                return this.sortedData().length;
            },
            fieldvalue(c, name, defaultVal = '') {
                var ogIndex = this.getDataByIndex(c);
                return (ogIndex > -1 && this.data[ogIndex][name]) ? this.data[ogIndex][name] : defaultVal;
            },
            getLastId() {
                var lastId = 0;
                this.data.forEach(d => {
                    d.id = typeof d.id === 'string' ? d.id.replace('u', '') : d.id;
                    lastId = Math.max(d.id, lastId);
                });
                return lastId;
            },
            reset($nextTick) {
                this.data = JSON.parse(JSON.stringify(this.ogData));
                this.updateData = {};
                $nextTick(() => this.data = this.data);
            },
            updateField(c, name, e) {
                console.log(e.target.value);
                var ogIndex = this.getDataByIndex(c);
                var data = {};
                var value = e.target.type == 'checkbox' ? (e.target.checked ? 1 : 0) : e.target.value;
                data[name] = value;
                // if (ogIndex == -1) {
                //     ogIndex = this.data.length;
                //     data.id = 'u' + (this.getLastId() + 1);
                //     this.data.push(data);
                // } else {
                    this.data[ogIndex][name] = value;
                    data.id = this.data[ogIndex].id;
                // }
                this.updateData['c' + this.data[ogIndex].id] = {...this.updateData['c' + this.data[ogIndex].id], ...data};
                // (this.count > 1) && this.count--;
            },
            getDataByIndex(c) {
                c--;
                var data = this.sortedData();
                var dataId = data[c] && data[c].id ? data[c].id : false;

                if (!dataId) return -1;

                var ogIndex = this.data.findIndex(x => x.id === dataId);
                return ogIndex;
            },
            items() {
                var data = this.data;

                if (this.searchInput.length) {
                    const options = {
                      shouldSort: true,
                      keys: ['name', 'id'],
                      threshold: 0
                  };                
                  const fuse = new Fuse(data, options);
                  data = fuse.search(this.searchInput).map(elem => elem.item);
              }

              return data;
          },
          view: 2, // maximum page list to be shown in each side of current selected page if many pages are available [ value between 1 and 5 is recommended ]
          searchInput: '',
          offset: 5,

          pageTotal() {
            return this.items().length;
        },
        pages() {
            var pages = [];
            var c;
            var pageTo = Math.min(this.lastPage() ,this.currentPage + this.view);
            var pageFrom = c = Math.max(1, this.currentPage - this.view);
            while(pageTo >= c) {
                pages.push(c);
                c++;
            }
            return pages;
        },
        lastPage() {
            return Math.ceil(this.pageTotal() / this.perPage);
        },
        perPage: 5,
        currentPage: 1,
        pageFrom() {
            return (this.currentPage - 1) * this.perPage + 1;
        },
        pageTo() {
            return Math.min(this.pageFrom() + this.perPage - 1, this.items().length);
        },

        sorted: {
          field: 'id',
          rule: 'asc'
      },
      prettify(e, defaultVal = 'string') {
        var d = {string: '', number: 0}[defaultVal] || '';
        return (e == 'null' || typeof e === 'undefined' || ( typeof e !== 'string' && typeof e !== 'number' && isNaN(e) )) ? d : e;
      },
      sortedData() {
        var items = this.items();
        if (items.length > this.perPage) {
            items = data.filter((v, i) => {
                return i >= this.pageFrom() - 1 && i < this.pageFrom() + this.perPage - 1;
            });
        }
        return items.sort((a, b) => {
            a = a[this.sorted.field];
            b = b[this.sorted.field];
            a = this.prettify(a, typeof b);
            b = this.prettify(b, typeof a);
            if (typeof a === 'string' && typeof b === 'string' && isNaN(b) && isNaN(a)) {
                a = a.toUpperCase();
                b = b.toUpperCase();

                if (a > b) {
                    return this.sorted.rule == 'asc' ? 1 : -1;
                }
                if (a < b) {
                    return this.sorted.rule == 'asc' ? -1 : 1;
                }
                return 0;
            }
            return this.sorted.rule == 'asc' ? a - b : b - a;
        }); 
    },
    sort(field) {
        this.sorted.rule = this.sorted.field != field ? 'asc' : this.sorted.rule == 'desc' ? 'asc' : 'desc';
        this.sorted.field = field;
    }
}
}

</script>
@endsection('main')