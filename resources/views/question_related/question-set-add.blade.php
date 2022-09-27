<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Question Set Add New') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="ml-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">

                <!-- Validation Errors -->
                <div class="flex justify-center">
                    <x-auth-validation-errors class="mb-4" :errors="$errors" />
                </div>
                <div class="w-full rounded-xl bg-white p-4 shadow-2xl shadow-white/40">
                    <div class="flex justify-center">
                        <form action="{{ route('question-set.store') }}" method="POST" class="w-full">
                            @csrf
                            <div class="flex flex-col w-full mt-5">
                                <label for="text" class="mb-2 font-semibold">Name</label>
                                <input type="text" id="name" name="name"
                                    class="w-full rounded-lg border bg-gray-100 border-slate-200 px-2 py-1 hover:border-blue-500 focus:outline-none focus:ring focus:ring-blue-500/40 active:ring active:ring-blue-500/40" />
                            </div>
                            <div class="flex flex-col w-full mt-5">
                                <label for="text" class="mb-2 font-semibold">Exam Category</label>
                                <select name="exam_category" id="exam_category" data-dependent="exam_group"
                                    class="w-full rounded-lg border bg-gray-100 border-slate-200 px-2 py-1 hover:border-blue-500 focus:outline-none focus:ring focus:ring-blue-500/40 active:ring active:ring-blue-500/40 dynamic">
                                    <option disabled selected value>Select one:</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->name }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="flex flex-col w-full mt-5">
                                <label for="text" class="mb-2 font-semibold">Exam Group</label>
                                <select name="exam_group" id="exam_group"
                                    class="w-full rounded-lg border bg-gray-100 border-slate-200 px-2 py-1 hover:border-blue-500 focus:outline-none focus:ring focus:ring-blue-500/40 active:ring active:ring-blue-500/40">
                                    <option disabled selected value>Select one:</option>
                                </select>
                            </div>
                            <div class="flex justify-center">
                                <button class="m-2 p-2 bg-gray-200 hover:bg-gray-300 rounded mt-5">Submit</button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        $(document).ready(function() {
            $('.dynamic').change(function() {
                if ($(this).val() != '') {
                    var value = $(this).val();
                    var dependent = $(this).data('dependent');
                    var _token = $('input[name="_token"]').val();
                    $.ajax({
                        url: "{{ route('question-set.fetch') }}",
                        method: "POST",
                        data: {
                            value: value,
                            _token: _token,
                        },
                        success: function(result) {
                            $('#' + dependent).html(result);
                        }
                    })
                }
            });
        });
    </script>
</x-app-layout>
