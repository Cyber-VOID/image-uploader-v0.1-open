@extends('layouts.app')


@section('main')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-32 px-6 py-3 flex flex-wrap place-content-between"
             id="contentContainer"
             style="background: #1F2937">
            <div class="flex flex-wrap place-content-between w-full">
                <form action="{{ route('images.patch',[$image->id,$image->token]) }}" method="post">
                    @csrf
                    @method('PATCH')
                    <div class="px-3 py-3 flex w-full justify-start items-center">
                        <h1 style="color: white;font-size: 24px">Image Name : <input
                                style="font-size: 20px ; color: white;border:none ;border-bottom: solid white;background: transparent"
                                type="text" name="name" id="" value="{{ $image->name }}"></h1>
                    </div>
                    <div class="container flex mx-auto md:h-[500px] w-full justify-center items-center px-3 py-3">
                        <img src="{{ asset($image->path.$image->image_name) }}" alt="{{ $image->name }}"
                             style="object-fit: cover;height: 100%">
                    </div>
                    <div class="container flex w-1/2 justify-between items-center px-3 py-3">
                        <button
                            type="submit"
                            class="focus:ring-2 focus:ring-offset-2 focus:ring-red-300 text-sm leading-none text-green-700 py-3 px-5 bg-green-100 rounded hover:bg-green-100 focus:outline-none">
                            Save
                        </button>
                    </div>
                </form>
                <div class="container flex w-1/2 justify-between items-center px-3 py-3">

                    <div class="flex justify-end">
                        <form
                            @if(isset($image->collection_id))
                                action="{{ route('images.delete' ,[$image->id,$image->collection_id,$image->token]) }}"
                            @else
                                action="{{ route('images.delete' ,[$image->id,'null',$image->token]) }}"
                            @endif
                              method="post">
                            @csrf
                            @method('DELETE')
                            <button
                                type="submit"
                                class="focus:ring-2 focus:ring-offset-2 focus:ring-red-300 text-sm leading-none text-green-700 py-3 px-5 bg-green-100 rounded hover:bg-green-100 focus:outline-none">
                                delete
                            </button>
                        </form>

                        <button
                            class="focus:ring-2 focus:ring-offset-2 focus:ring-red-300 text-sm leading-none text-green-700 py-3 px-5 bg-green-100 rounded hover:bg-green-100 focus:outline-none">
                            Generate URL
                        </button>
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
                        ' <a href="#">Click </a> To View Product',
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
