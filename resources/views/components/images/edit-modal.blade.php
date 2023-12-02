<!-- Main modal -->
<div id="edit-modal" tabindex="-1" aria-hidden="true"
     class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-2xl max-h-full">
        <form action="{{ route('collections.patch.collection',[$collection->id,$collection->token]) }}" method="post" class="space-y-4"
              enctype="multipart/form-data">
                @csrf
            @method('PATCH')
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">

                <!-- Modal header -->
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                        Edit Collection: {{ $collection->collection_name }}
                    </h3>
                    <button type="button"
                            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                            data-modal-hide="edit-modal">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                             viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>

                <div class="rounded-lg  p-8 shadow-lg lg:col-span-3 lg:p-12" style="background: #1F2937">
                    @csrf
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 py-5">
                        <div>
                            <input
                                class="w-full rounded-lg border-gray-200 p-3 text-sm da-input"
                                placeholder="Collection Name"
                                type="text"
                                id="collection_name"
                                name="collection_name"
                                value="{{ $collection->collection_name }}"
                            />
                        </div>
                        <div>
                            <select id="ispublic" name="ispublic"
                                    class="da-input bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <option value="0">private</option>
                                <option value="1">public</option>
                            </select>
                        </div>

                    </div>


                    <div class="grid place-items-center">
                        <div
                            class="flex flex-col items-center justify-center w-full h-full my-20 bg-transparent sm:w-3/4 sm:rounded-lg sm:shadow-xl"
                            style="background: #111827">
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



                    </div>
                </div>
            </div>
            <!-- Modal body -->
            <div class="bg-transparent dark:bg-gray-700 overflow-hidden shadow-sm sm:rounded-lg ">
                <!-- Modal footer -->
                <div class="flex items-center p-4 md:p-5 border-t border-gray-200 rounded-b dark:border-gray-600">
                    <input
                        type="submit"
                        class="da-input inline-block w-full rounded-lg bg-black px-5 py-3 font-medium text-white sm:w-auto"
                        value="Update"
                    >
                    <button data-modal-hide="default-modal" type="button"
                            class="ms-3 text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">
                        Decline
                    </button>
                </div>

            </div>
        </form>

    </div>
</div>

