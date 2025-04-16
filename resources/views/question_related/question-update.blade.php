<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
            {{ __('Question Update') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="ml-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">

                <!-- Validation Errors -->
                <div class="flex justify-center">
                    <x-auth-validation-errors class="mb-4" :errors="$errors" />
                </div>
                <div class="w-full rounded-xl bg-white dark:text-black p-4 shadow-2xl shadow-white/40">
                    <div class="flex justify-center">
                        <form action="{{ route('question.update') }}" method="POST" class="w-full">
                            @csrf
                            <input type="hidden" name="id" value="{{ $question->id }}">
                            <div class="flex flex-col w-full mt-5">
                                <label for="text" class="mb-2 font-semibold">Exam Category</label>
                                <select name="exam_category" id="exam_category" data-dependent="exam_group"
                                    class="w-full rounded-lg border bg-gray-100 border-slate-200 px-2 py-1 hover:border-blue-500 focus:outline-none focus:ring focus:ring-blue-500/40 active:ring active:ring-blue-500/40 dynamic">
                                    <option disabled selected value>Select one:</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->name }}"
                                            {{ $category->name == $exam_category->name ? 'selected' : '' }}>
                                            {{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="flex flex-col w-full mt-5">
                                <label for="text" class="mb-2 font-semibold">Exam Group</label>
                                <select name="exam_group" id="exam_group" data-dependent="question_set"
                                    class="w-full rounded-lg border bg-gray-100 border-slate-200 px-2 py-1 hover:border-blue-500 focus:outline-none focus:ring focus:ring-blue-500/40 active:ring active:ring-blue-500/40 dynamic">
                                    @foreach ($groups as $group)
                                        <option value="{{ $group->name }}"
                                            {{ $group->name == $exam_group->name ? 'selected' : '' }}>
                                            {{ $group->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="flex flex-col w-full mt-5">
                                <label for="text" class="mb-2 font-semibold">Question Set</label>
                                <select name="question_set" id="question_set"
                                    class="w-full rounded-lg border bg-gray-100 border-slate-200 px-2 py-1 hover:border-blue-500 focus:outline-none focus:ring focus:ring-blue-500/40 active:ring active:ring-blue-500/40">
                                    @foreach ($examSets as $examSet)
                                        <option value="{{ $examSet->name }}"
                                            {{ $examSet->name == $question_set->name ? 'selected' : '' }}>
                                            {{ $examSet->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="flex flex-col w-full mt-5">
                                <label for="text" class="mb-2 font-semibold">Question</label>
                                <textarea type="text" id="question" name="question"
                                    class="w-full rounded-lg border bg-gray-100 border-slate-200 px-2 py-1 hover:border-blue-500 focus:outline-none focus:ring focus:ring-blue-500/40 active:ring active:ring-blue-500/40">{{ $question->question }}</textarea>
                            </div>
                            <div class="flex flex-col w-full mt-5">
                                <label for="text" class="mb-2 font-semibold">Option One</label>
                                <input type="text" id="option1" name="option1" value="{{ $question->option1 }}"
                                    class="w-full rounded-lg border bg-gray-100 border-slate-200 px-2 py-1 hover:border-blue-500 focus:outline-none focus:ring focus:ring-blue-500/40 active:ring active:ring-blue-500/40" />
                            </div>
                            <div class="flex flex-col w-full mt-5">
                                <label for="text" class="mb-2 font-semibold">Option Two</label>
                                <input type="text" id="option2" name="option2" value="{{ $question->option2 }}"
                                    class="w-full rounded-lg border bg-gray-100 border-slate-200 px-2 py-1 hover:border-blue-500 focus:outline-none focus:ring focus:ring-blue-500/40 active:ring active:ring-blue-500/40" />
                            </div>
                            <div class="flex flex-col w-full mt-5">
                                <label for="text" class="mb-2 font-semibold">Option Three</label>
                                <input type="text" id="option3" name="option3" value="{{ $question->option3 }}"
                                    class="w-full rounded-lg border bg-gray-100 border-slate-200 px-2 py-1 hover:border-blue-500 focus:outline-none focus:ring focus:ring-blue-500/40 active:ring active:ring-blue-500/40" />
                            </div>
                            <div class="flex flex-col w-full mt-5">
                                <label for="text" class="mb-2 font-semibold">Option Four</label>
                                <input type="text" id="option4" name="option4" value="{{ $question->option4 }}"
                                    class="w-full rounded-lg border bg-gray-100 border-slate-200 px-2 py-1 hover:border-blue-500 focus:outline-none focus:ring focus:ring-blue-500/40 active:ring active:ring-blue-500/40" />
                            </div>
                            <div class="flex flex-col w-full mt-5">
                                <label for="text" class="mb-2 font-semibold">Correct Answer</label>
                                <div
                                    class="flex w-full rounded-lg border bg-gray-100 border-slate-200 px-2 py-3 justify-evenly">
                                    <div
                                        class=" hover:border-blue-500 focus:outline-none focus:ring focus:ring-blue-500/40 active:ring active:ring-blue-500/40">

                                        <input type="radio" class="form-radio" name="correct_answer" value="option1"
                                            {{ $question->correct_answer == $question->option1 ? 'checked' : '' }} />
                                        <span>Option One</span>

                                    </div>
                                    <div
                                        class=" hover:border-blue-500 focus:outline-none focus:ring focus:ring-blue-500/40 active:ring active:ring-blue-500/40">

                                        <input type="radio" class="form-radio" name="correct_answer" value="option2"
                                            {{ $question->correct_answer == $question->option2 ? 'checked' : '' }} />
                                        <span>Option Two</span>

                                    </div>
                                    <div
                                        class=" hover:border-blue-500 focus:outline-none focus:ring focus:ring-blue-500/40 active:ring active:ring-blue-500/40">

                                        <input type="radio" class="form-radio" name="correct_answer" value="option3"
                                            {{ $question->correct_answer == $question->option3 ? 'checked' : '' }} />
                                        <span>Option Three</span>

                                    </div>
                                    <div
                                        class=" hover:border-blue-500 focus:outline-none focus:ring focus:ring-blue-500/40 active:ring active:ring-blue-500/40">

                                        <input type="radio" class="form-radio" name="correct_answer" value="option4"
                                            {{ $question->correct_answer == $question->option4 ? 'checked' : '' }} />
                                        <span>Option Four</span>

                                    </div>
                                </div>
                            </div>
                            <div class="flex flex-col w-full mt-5">
                                <label for="text" class="mb-2 font-semibold">Explanation</label>
                                <textarea type="text" id="explanation" name="explanation"
                                    class="w-full rounded-lg border bg-gray-100 border-slate-200 px-2 py-1 hover:border-blue-500 focus:outline-none focus:ring focus:ring-blue-500/40 active:ring active:ring-blue-500/40">{{ $question->explanation }}</textarea>
                            </div>
                            <div class="flex flex-col w-full mt-5">
                                <label for="text" class="mb-2 font-semibold">Section</label>
                                <select name="section" id="section"
                                    class="w-full rounded-lg border bg-gray-100 border-slate-200 px-2 py-1 hover:border-blue-500 focus:outline-none focus:ring focus:ring-blue-500/40 active:ring active:ring-blue-500/40 dynamic">
                                    <option disabled selected value>Select one:</option>
                                    @foreach ($sections as $section)
                                        <option value="{{ $section->name }}"
                                            {{ $section->name == $question->section ? 'selected' : '' }}>
                                            {{ $section->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @if ($question_reports->isNotEmpty())
                                <div class="w-full mt-8 p-4 border rounded-lg">
                                    <h3 class="text-lg font-semibold mb-4">Question Reports</h3>

                                    @foreach ($question_reports as $report)
                                        <div class="report-item mb-4 p-3 border-b">
                                            <div class="flex justify-between items-center">
                                                <div>
                                                    <p class="text-sm text-gray-600">
                                                        রিপোর্টকারী: {{ $report->user_email }}<br>
                                                        @if (
                                                            $report->question_wrong ||
                                                                $report->answer_wrong ||
                                                                $report->explanation_wrong ||
                                                                $report->typo_mistake ||
                                                                $report->others)
                                                            <span class="font-medium">ত্রুটির ধরন:</span>
                                                            @if ($report->question_wrong)
                                                                <span class="badge">প্রশ্ন ভুল</span>
                                                            @endif
                                                            @if ($report->answer_wrong)
                                                                <span class="badge">উত্তর ভুল</span>
                                                            @endif
                                                            @if ($report->explanation_wrong)
                                                                <span class="badge">ব্যাখ্যা ভুল</span>
                                                            @endif
                                                            @if ($report->typo_mistake)
                                                                <span class="badge">টাইপো ত্রুটি</span>
                                                            @endif
                                                            @if ($report->others && $report->others_text)
                                                                <span class="badge">অন্যান্য:
                                                                    {{ $report->others_text }}</span>
                                                            @endif
                                                        @endif
                                                    </p>
                                                    <span class="text-xs text-gray-500">
                                                        {{ \Carbon\Carbon::parse($report->created_at)->diffForHumans() }}</span>
                                                </div>

                                                <label class="flex items-center space-x-4">
                                                    <input type="checkbox" name="resolved_reports[]"
                                                        value="{{ $report->id }}"
                                                        class="form-checkbox h-4 w-4 text-blue-600">
                                                    <span class="text-sm ml-2">ত্রুটি সমাধান করা হয়েছে</span>
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
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
                        url: "{{ route('question.fetch') }}",
                        method: "POST",
                        data: {
                            dependent: dependent,
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
