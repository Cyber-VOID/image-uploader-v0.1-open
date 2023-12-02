@extends('layouts.app')

@section('css')
    <style>.checkbox:checked + .check-icon {
            display: flex;
        }
    </style>
@endsection

@section('header-section')
    {{ 'Generate Url' }}
@endsection

@section('main')
    <div class="py-10">
        <div class="max-w-8xl mx-auto sm:px-6 lg:px-56">
            <div class="sm:px-6 w-full">
                <div class="px-4 md:px-10 py-4 md:py-7">
                    <div class="flex items-center justify-between">


                    </div>
                </div>
                <div class="bg-white py-4 md:py-7 px-4 md:px-8 xl:px-10">
                    <div class="sm:flex items-center justify-between">
                        <div class="flex items-center">
                            <a class="rounded-full focus:outline-none focus:ring-2  focus:bg-indigo-50 focus:ring-indigo-800"
                               href=" javascript:void(0)">
                                <div class="py-2 px-8 bg-indigo-100 text-indigo-700 rounded-full">
                                    <p>All</p>
                                </div>
                            </a>
                            <a class="rounded-full focus:outline-none focus:ring-2 focus:bg-indigo-50 focus:ring-indigo-800 ml-4 sm:ml-8"
                               href="javascript:void(0)">
                                <div
                                    class="py-2 px-8 text-gray-600 hover:text-indigo-700 hover:bg-indigo-100 rounded-full ">
                                    <p>latest</p>
                                </div>
                            </a>
                            <a class="rounded-full focus:outline-none focus:ring-2 focus:bg-indigo-50 focus:ring-indigo-800 ml-4 sm:ml-8"
                               href="javascript:void(0)">
                                <div
                                    class="py-2 px-8 text-gray-600 hover:text-indigo-700 hover:bg-indigo-100 rounded-full ">
                                    <p>oldest</p>
                                </div>
                            </a>
                        </div>

                    </div>
                    <div class="mt-7 overflow-x-auto">
                        <table class="w-full whitespace-nowrap">
                            <tbody>
                            @forelse($urls as $key => $url)
                                <tr tabindex="0" class="focus:outline-none h-16 border border-gray-100 rounded">
                                    <td>
                                        <div class="ml-5">
                                            <div>
                                                <a href="{{ route('collections.show',[$url->id,$url->token]) }}"
                                                   class="text-base font-medium leading-none text-gray-700 mr-2">{{ $url->id  }}</a>

                                            </div>
                                        </div>
                                    </td>
                                    <td class="">
                                        <div class="flex items-center pl-5">
                                            <a href="{{ route('collections.show',[$url->id,$url->token]) }}"
                                               class="text-base font-medium leading-none text-gray-700 mr-2">{{ $url->name  }}</a>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                 viewBox="0 0 16 16" fill="none">
                                                <path
                                                    d="M6.66669 9.33342C6.88394 9.55515 7.14325 9.73131 7.42944 9.85156C7.71562 9.97182 8.02293 10.0338 8.33335 10.0338C8.64378 10.0338 8.95108 9.97182 9.23727 9.85156C9.52345 9.73131 9.78277 9.55515 10 9.33342L12.6667 6.66676C13.1087 6.22473 13.357 5.62521 13.357 5.00009C13.357 4.37497 13.1087 3.77545 12.6667 3.33342C12.2247 2.89139 11.6251 2.64307 11 2.64307C10.3749 2.64307 9.77538 2.89139 9.33335 3.33342L9.00002 3.66676"
                                                    stroke="#3B82F6" stroke-linecap="round"
                                                    stroke-linejoin="round"></path>
                                                <path
                                                    d="M9.33336 6.66665C9.11611 6.44492 8.8568 6.26876 8.57061 6.14851C8.28442 6.02825 7.97712 5.96631 7.66669 5.96631C7.35627 5.96631 7.04897 6.02825 6.76278 6.14851C6.47659 6.26876 6.21728 6.44492 6.00003 6.66665L3.33336 9.33332C2.89133 9.77534 2.64301 10.3749 2.64301 11C2.64301 11.6251 2.89133 12.2246 3.33336 12.6666C3.77539 13.1087 4.37491 13.357 5.00003 13.357C5.62515 13.357 6.22467 13.1087 6.66669 12.6666L7.00003 12.3333"
                                                    stroke="#3B82F6" stroke-linecap="round"
                                                    stroke-linejoin="round"></path>
                                            </svg>
                                        </div>
                                    </td>

                                    <td class="pl-5">
                                        <div class="flex items-center">
                                            <p class="text-sm leading-none text-gray-600 ml-2">
                                                @if($url->isimage)
                                                    Image
                                                @else
                                                    Collection
                                                @endif
                                            </p>
                                        </div>
                                    </td>

                                    <td class="pl-4">
                                        @if(!$url->ispublic)
                                            <button
                                                data-modal-toggle="ispublic-modal{{$key}}"
                                                class="focus:ring-2 focus:ring-offset-2 focus:ring-yellow-300 text-sm leading-none text-yellow-700 py-3 px-5 bg-yellow-100 rounded hover:bg-red-50 focus:outline-none">
                                                Private
                                            </button>
                                        @else
                                            <button
                                                data-modal-toggle="ispublic-modal{{$key}}"
                                                class="focus:ring-2 focus:ring-offset-2 focus:ring-green-300 text-sm leading-none text-green-700 py-3 px-5 bg-green-100 rounded hover:bg-green-100 focus:outline-none">
                                                Public
                                            </button>
                                        @endif
                                    </td>
                                    <td class="pl-4">
                                        <form
                                            action="{{ route('url-generator.delete-url',[$url->id,$url->token]) }}"
                                            method="post">
                                            @csrf
                                            @method('DELETE')
                                            <input
                                                type="submit" value="Delete"
                                                class="focus:ring-2 focus:ring-offset-2 focus:ring-red-300 text-sm leading-none text-red-700 py-3 px-5 bg-red-700 rounded hover:bg-red-50 focus:outline-none">
                                        </form>
                                    </td>
                                    <td class="pl-4">
                                        @if($url->isimage)
                                            <a href="{{ route('images.get', [$url->urlable->id,$url->urlable->token]) }}"
                                               class="focus:ring-2 focus:ring-offset-2 focus:ring-red-300 text-sm leading-none text-gray-600 py-3 px-5 bg-gray-100 rounded hover:bg-gray-200 focus:outline-none">
                                                Edit
                                            </a>
                                        @else
                                            <a href="{{ route('collections.show', [$url->urlable->id,$url->urlable->token]) }}"
                                               class="focus:ring-2 focus:ring-offset-2 focus:ring-red-300 text-sm leading-none text-gray-600 py-3 px-5 bg-gray-100 rounded hover:bg-gray-200 focus:outline-none">
                                                Edit
                                            </a>
                                        @endif

                                    </td>
                                    <td class="pl-4">

                                            <a href="{{ route('short-url',$url->short_url) }}"
                                               class="focus:ring-2 focus:ring-offset-2 focus:ring-red-300 text-sm leading-none text-gray-600 py-3 px-5 bg-gray-100 rounded hover:bg-gray-200 focus:outline-none">
                                                View
                                            </a>


                                    </td>
                                    <td class="pl-5">
                                        <button
                                            class="py-3 px-3 text-sm focus:outline-none leading-none text-black bg-gray-100 rounded">
                                            {{ $url->created_at }}
                                        </button>
                                    </td>
                                </tr>
                                @include('components.url-generator.ispublic-modal')
                            @empty
                                <h1>You Have No URLs</h1>
                            @endforelse


                            </tbody>
                        </table>
                        {{ $urls->links() }}
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
                @if(session()->get('infos'))
                    ' <a href="{{ session()->get('infos')['url'] }}">Take Me There </a>',
            @endif
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
