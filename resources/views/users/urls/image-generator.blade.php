@extends('layouts.app')


@section('main')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-32 px-6 py-3 flex flex-wrap place-content-between"
             id="contentContainer"
             style="background: #1F2937">
            <div class="flex flex-wrap place-content-between w-full">
                <form action="{{ route('url-generator.new-url',['image',$image->id,$image->token]) }}"
                      method="post">
                    @csrf

                    <input type="text" name="name" value="Enter Url Name">
                    <button
                        class="focus:ring-2 focus:ring-offset-2 focus:ring-gray-300 text-sm leading-none text-gray-700 py-2 px-3 bg-gray-200 rounded hover:bg-gray-400 focus:outline-none">
                        Generate Url
                    </button>
                    <select id="ispublic" required name="ispublic"
                            class="da-input bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option value="0">private</option>
                        <option value="1">public</option>
                    </select>
                    <div class="container flex mx-auto md:h-[500px] w-full justify-center items-center px-3 py-3">
                        <img src="{{ asset($image->path.$image->image_name) }}" alt="{{ $image->name }}"
                             style="object-fit: cover;height: 100%">
                    </div>
                </form>
                <div class="container flex w-1/2 justify-between items-center px-3 py-3">



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
                        ' <a href="{{session('infos')}}">Click </a> To View Product',
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
