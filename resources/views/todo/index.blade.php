<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Todo') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-xl text-gray-900 dark:text-gray-100">
                    <div class="flex items-center justify-between">
                        <x-create-button href="{{ route('todo.create') }}" />
                        <div>
                            @if (session('success'))
                            <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 5000)"
                                class="text-sm text-green-600 dark:text-green-400">
                                {{ session('success') }}
                            </p>
                            @endif
                            @if (session('danger'))
                            <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 5000)"
                                class="text-sm text-red-600 dark:text-red-400">
                                {{ session('danger') }}
                            </p>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3">Title</th>
                                <th scope="col" class="px-6 py-3">Category</th>
                                <th scope="col" class="px-6 py-3">Status</th>
                                <th scope="col" class="px-6 py-3">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($todos as $todo)
                            <tr class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
                                <td class="px-6 py-4 font-medium text-white dark:text-gray-900">
                                    <a href="{{ route('todo.edit', $todo->id) }}" class="hover:underline text-xs">
                                        {{ $todo->title }}
                                    </a>
                                </td>

                                <td class="px-6 py-4">
                                    @if ($todo->category)
                                    {{ $todo->category->title }}
                                    @endif
                                </td>

                                <td class="px-6 py-4">
                                    @if (!$todo->is_done)
                                    <span class="inline-flex items-center bg-red-100 text-red-800 text-sm font-medium px-2.5 py-0.5 rounded-sm dark:bg-red-900 dark:text-red-300">
                                        On Going
                                    </span>
                                    @else
                                    <span class="inline-flex items-center bg-green-100 text-green-800 text-sm font-medium px-2.5 py-0.5 rounded-sm dark:bg-green-900 dark:text-green-300">
                                        Done
                                    </span>
                                    @endif
                                </td>

                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        @if (!$todo->is_done)
                                        <form action="{{ route('todo.complete', $todo) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="text-white bg-green-700 hover:bg-green-800 focus:ring-4 
                                                focus:outline-none focus:ring-green-300 font-medium rounded-lg px-2.5 
                                                py-1.5 text-center dark:bg-green-600 dark:hover:bg-green-700 
                                                dark:focus:ring-green-800">
                                                Done
                                            </button>
                                        </form>
                                        @else
                                        <form action="{{ route('todo.uncomplete', $todo) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 
                                                focus:outline-none focus:ring-red-300 font-medium rounded-lg px-2.5 
                                                py-1.5 text-center dark:bg-red-600 dark:hover:bg-red-700 
                                                dark:focus:ring-red-800">
                                                Uncomplete
                                            </button>
                                        </form>
                                        @endif

                                        <form action="{{ route('todo.destroy', $todo) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 dark:text-red-400 whitespace-nowrap hover:underline ms-2">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
                                <td colspan="4" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                    No data available
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($todosCompleted > 1)
                <div class="p-6 text-xl text-gray-900 dark:text-gray-100">
                    <form action="{{ route('todo.deleteallcompleted') }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <x-primary-button>
                            Delete All Completed Task
                        </x-primary-button>
                    </form>
                </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>