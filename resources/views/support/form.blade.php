<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Submit a Support Ticket') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="list-disc pl-5 text-sm text-red-600">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="max-w-xl">
                    <form method="post" action="{{ route('tickets.store') }}" class="mt-6 space-y-6">
                        @csrf
                        <div>
                            <label for="category">{{ __('Select Category') }}</label>
                            <select name="category_id" id="category" class="mt-1 block w-full" required>
                                <option value="">Select a category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>

                            <x-input-error class="mt-2" :messages="$errors->get('category_id')" />
                        </div>
                        <div>
                            <x-input-label for="subject" :value="__('Subject')" />
                            <x-text-input id="subject" name="subject" type="text" class="mt-1 block w-full" maxlength="2000" required autofocus autocomplete="subject" />
                            <x-input-error class="mt-2" :messages="$errors->get('subject')" />
                        </div>
                        <div>
                            <x-input-label for="message" :value="__('Message')" />
                            <x-textarea id="message" name="message" class="mt-1 block w-full" maxlength="2000" required autofocus autocomplete="message"/>
                            <x-input-error class="mt-2" :messages="$errors->get('message')" />
                        </div>

                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 ms-3">
                            {{ __('Submit Ticket') }}
                        </button>

                        @if (session('success'))
                            <div class="mb-4 font-medium text-sm text-green-600">
                                {{ session('success') }}
                            </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

