@extends('layouts.app')

@section('main')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-32 px-6 py-3 flex flex-wrap place-content-between"
             id="contentContainer"
             style="background: #1F2937">
            <div class="px-3 py-3 flex w-1/2 justify-start items-center">
                <h1 style="color: white;font-size: 24px">Collection : {{ $collection->collection_name }}</h1>
            </div>
            <div class="px-3 py-3 flex w-1/2 justify-end items-center gap-3">
                <div class="flex justify-end items-center">
                    <button
                        data-modal-toggle="edit-modal"
                        class="focus:ring-2 focus:ring-offset-2 focus:ring-gray-300 text-sm leading-none text-gray-700 py-3 px-5 bg-gray-100 rounded hover:bg-gray-100 focus:outline-none">
                        Upload More Images
                    </button>
                </div>
                <div>
                    <a type="submit" href="{{ route('url-generator.new-url', ['collection',$collection->id,$collection->token]) }}"
                       class="focus:ring-2 focus:ring-offset-2 focus:ring-red-300 text-sm leading-none text-gray-600 py-3 px-5 bg-gray-100 rounded hover:bg-gray-200 focus:outline-none">
                        Generate Url
                    </a>
                </div>
                <div>

                    @if(!$collection->ispublic)
                        <button
                            data-modal-toggle="ispublic-modal"
                            class="focus:ring-2 focus:ring-offset-2 focus:ring-red-300 text-sm leading-none text-yellow-700 py-3 px-5 bg-yellow-200 rounded hover:bg-yellow-400 focus:outline-none">
                            Private
                        </button>

                    @else
                        <button
                            data-modal-toggle="ispublic-modal"
                            class="focus:ring-2 focus:ring-offset-2 focus:ring-red-300 text-sm leading-none text-green-700 py-3 px-5 bg-green-100 rounded hover:bg-green-100 focus:outline-none">
                            Public
                        </button>
                    @endif
                </div>

            </div>
            @include('components.collections.ispublic-modal')
            @forelse($images as $key => $image)
                <div class="flex flex-col justify-center items-center max-w-sm mx-auto my-8">
                    <div class="h-64 w-full rounded-md shadow-sm bg-cover bg-center"
                         style="max-height: 216px;background: #111827">
                        <img src="{{ asset($image->path.$image->image_name) }}" alt="{{ $image->name }}"
                             style="max-height: 200px;max-width:300px ;object-fit: cover;">
                    </div>
                    <div class="w-56 md:w-64  -mt-10 shadow-lg rounded-md overflow-hidden">
                        <div class="py-2 text-center font-bold uppercase tracking-wide text-white"
                             style="background: #1F2937">{{ $image->name }}</div>
                        <div class="flex items-center justify-between py-2 px-3" style="background:  #111827">
                            @if(!$image->ispublic)
                                <button
                                    data-modal-toggle="ispublic-modal{{$image->id}}"
                                    class="focus:ring-2 focus:ring-offset-2 focus:ring-red-300 text-sm leading-none text-yellow-700 py-2 px-3 bg-yellow-200 rounded hover:bg-yellow-400 focus:outline-none">
                                    Private
                                </button>

                            @else
                                <button
                                    data-modal-toggle="ispublic-modal{{$image->id}}"
                                    class="focus:ring-2 focus:ring-offset-2 focus:ring-red-300 text-sm leading-none text-green-700 py-3 px-5 bg-green-100 rounded hover:bg-green-100 focus:outline-none">
                                    Public
                                </button>
                            @endif
                            <a
                                href="{{ route('images.get',[$image->id,$image->token]) }}"
                                class="focus:ring-2 focus:ring-offset-2 focus:ring-gray-300 text-sm leading-none text-gray-700 py-2 px-3 bg-gray-200 rounded hover:bg-yellow-400 focus:outline-none">
                                View
                            </a>
                            <form
                                action="{{ route('images.delete',[$image->id,$collection->id,$image->token]) }}"
                                method="post">
                                @csrf
                                @method('DELETE')
                                <button

                                    class="focus:ring-2 focus:ring-offset-2 focus:ring-red-300 text-sm leading-none text-red-700 py-2 px-3 bg-red-100 rounded hover:bg-red-100 focus:outline-none">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @include('components.images.ispublic-modal')
            @empty
                <div class="flex flex-col justify-center items-center max-w-sm mx-auto my-8">
                    <div class="h-64 w-full rounded-md shadow-sm bg-cover bg-center"
                         style="max-height: 216px;background: #111827">
                        <img src="" alt="" style="max-height: 200px;width: 300px ;max-width:300px ;object-fit: cover;">
                    </div>
                    <div class="w-56 md:w-64  -mt-10 shadow-lg rounded-md overflow-hidden">
                        <div class="py-2 text-center font-bold uppercase tracking-wide text-white"
                             style="background: #1F2937">
                            <h1>You Have No Images</h1>
                        </div>
                        <div class="flex items-center justify-between py-2 px-3" style="background:  #111827">
                            <button
                                data-modal-toggle="#"
                                class="focus:ring-2 focus:ring-offset-2 focus:ring-red-300 text-sm leading-none text-yellow-700 py-2 px-3 bg-yellow-200 rounded hover:bg-yellow-400 focus:outline-none">
                                Private
                            </button>


                            <a
                                href="#"
                                class="focus:ring-2 focus:ring-offset-2 focus:ring-gray-300 text-sm leading-none text-gray-700 py-2 px-3 bg-gray-200 rounded hover:bg-yellow-400 focus:outline-none">
                                View
                            </a>
                            <button

                                class="focus:ring-2 focus:ring-offset-2 focus:ring-red-300 text-sm leading-none text-red-700 py-2 px-3 bg-red-100 rounded hover:bg-red-100 focus:outline-none">
                                Delete
                            </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforelse
            @include('components.images.edit-modal')
            <div class="px-3 py-3 flex w-full justify-center items-center">
                {{ $images->links() }}
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
                    window.onload = function () {
                }

            </script>
@endsection
