@extends('layouts.app')


@section('css')
    <style>
        .da-input {
            background: #111827;
            border-color: #111827 #111827;
            color: white;
        }
    </style>
@endsection


@section('header-section')
    {{ 'New Collection' }}
@endsection

@section('main')
    <div>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-64">
                <div class="bg-transparent dark:bg-transparent overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="rounded-lg  p-8 shadow-lg lg:col-span-3 lg:p-12" style="background: #1F2937">
                        <form action="{{ route('collections.new.post') }}" method="post" class="space-y-4" enctype="multipart/form-data">
                            @csrf
                            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                                Create New Collection
                            </h2>

                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                <div>
                                    <label class="sr-only" for="collection_name">Collection Name </label>
                                    <input
                                        class="w-full rounded-lg border-gray-200 p-3 text-sm da-input"
                                        placeholder="Collection Name"
                                        type="text"
                                        id="collection_name"
                                        name="collection_name"
                                    />
                                </div>
                                <div>
                                    <select id="ispublic" name="ispublic" class="da-input bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                        <option value="0">private</option>
                                        <option value="1">public</option>
                                    </select>
                                </div>

                            </div>


                            <div class="grid place-items-center">
                                <div
                                    class="flex flex-col items-center justify-center w-full h-full my-20 bg-transparent sm:w-3/4 sm:rounded-lg sm:shadow-xl" style="background: #111827">
                                    <div class="mt-10 mb-10 text-center">
                                        <h2 class="text-2xl font-semibold mb-2 text-white">Upload your files</h2>
                                        <p class="text-xs text-gray-500">File should be of format .jpg , .jpeg , .png</p>
                                    </div>
                                    <input type="file" id="imgs" name="imgs[]" accept="image/jpeg"
                                           multiple
                                           data-allow-reorder="true"
                                           data-max-file-size="20MB"
                                           data-max-files="10"
                                           value="{{ old('imgs') }}"
                                    >
                                        <label for="images"
                                               class="z-20 flex flex-col-reverse items-center justify-center w-full h-full cursor-pointer">
                                            <p class="z-10 text-xs font-light text-center text-gray-500">Drag & Drop your files
                                                here</p>
                                            <svg class="z-10 w-8 h-8 text-indigo-400" fill="currentColor" viewBox="0 0 20 20"
                                                 xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"></path>
                                            </svg>
                                        </label>
                                </div>
                            </div>

                            <div class="mt-4">
                                <input
                                    type="submit"
                                    class="da-input inline-block w-full rounded-lg bg-black px-5 py-3 font-medium text-white sm:w-auto"
                                    value="Upload"
                                >


                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        @if($errors->any())
        @foreach($errors->all() as $error)
        toastr.error('{{ $error }}!')
        @endforeach
        @elseif(session('message'))
        swal.fire({
            title: '<strong>{{ session('message') }}</strong>',
            icon: 'success',
            html:
                ' <a href="{{session('infos')}}">Click </a> To Take You To It',
            showCloseButton: true,
            showCancelButton: false,
            focusConfirm: false,
            confirmButtonText:
                '<i class="fa fa-thumbs-up"></i> I\'m Good!',
            confirmButtonAriaLabel: 'Thumbs up, great!',
            cancelButtonText:
                '<i class="fa fa-thumbs-down"></i>',
            cancelButtonAriaLabel: 'Thumbs down'
        })
        @endif

    </script>
@endsection
