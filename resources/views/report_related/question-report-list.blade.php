<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
            {{ __('Question Reports') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="ml-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                        <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50 dark:bg-gray-600 dark:text-gray-200">
                                    <tr>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">
                                            Report ID
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">
                                            Question
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">
                                            Reported By
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">
                                            Reasons
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:text-black divide-y divide-gray-200">
                                    @foreach ($questionReports as $report)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $report->id }}</td>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap max-w-xs overflow-hidden overflow-ellipsis">
                                                {{ Str::limit($report->question_text, 50) }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $report->user_email }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex flex-col space-y-1">
                                                    @if ($report->question_wrong)
                                                        <span class="text-red-600 text-sm">• Question Wrong</span>
                                                    @endif
                                                    @if ($report->answer_wrong)
                                                        <span class="text-red-600 text-sm">• Answer Wrong</span>
                                                    @endif
                                                    @if ($report->explanation_wrong)
                                                        <span class="text-red-600 text-sm">• Explanation Wrong</span>
                                                    @endif
                                                    @if ($report->typo_mistake)
                                                        <span class="text-red-600 text-sm">• Typo Mistake</span>
                                                    @endif
                                                    @if ($report->others)
                                                        <span class="text-red-600 text-sm">• Other Issues</span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex justify-center space-x-2">
                                                    <a href="{{ route('question.edit', ['id' => $report->question_id]) }}"
                                                        class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded text-sm">
                                                        Edit Question
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="m-2 p-2">
                                {{ $questionReports->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
