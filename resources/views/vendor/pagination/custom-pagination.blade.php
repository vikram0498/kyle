@if ($paginator->hasPages())
<div class="max-w-screen bg-white  rounded-b-lg  border-4 border-t-0 border-b-0  border-transparent ">
      <div class="items-center justify-between p-2 sm:flex">
        <div class="flex justify-end text-gray-600">
             {!! __('Showing') !!} {{ $paginator->firstItem() }} to {{ $paginator->lastItem() }} of {{ $paginator->total() }} entries 
        </div>
        <div class="my-4 sm:my-0">
          <div class="lg:hidden">
            <span class="space-x-2">
              <div class="flex justify-between">
                <!-- Previous Page Link -->
                @if ($paginator->onFirstPage())
                <div class="w-32 flex justify-between items-center relative px-4 py-2 border border-gray-300 text-sm leading-5 font-medium rounded-md text-gray-400 bg-gray-50">
                    <span class="page-link" aria-hidden="true">&lsaquo;</span>
                    {{ __('Previous')}}
                </div>
                @else
                <button wire:click="previousPage" id="pagination-mobile-page-previous" class="w-32 flex justify-between items-center relative px-4 py-2 border border-gray-300 text-sm leading-5 font-medium rounded-md text-gray-700 bg-white hover:text-gray-500 focus:outline-none focus:shadow-outline-blue focus:border-blue-300 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150">
                    &lsaquo;
                    {{ __('Previous')}}
                </button>
                @endif
                
                
                <!-- Next Page pnk -->
                @if ($paginator->hasMorePages())
                <button wire:click="nextPage" id="pagination-mobile-page-next" class="w-32 flex justify-between items-center relative items-center px-4 py-2 border border-gray-300 text-sm leading-5 font-medium rounded-md text-gray-700 bg-white hover:text-gray-500 focus:outline-none focus:shadow-outline-blue focus:border-blue-300 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150">
                    {{ __('Next')}}
                    &rsaquo;
                </button>
                @else
                <div class="w-32 flex justify-between items-center relative px-4 py-2 border border-gray-300 text-sm leading-5 font-medium rounded-md text-gray-400 bg-gray-50">
                    {{ __('Next')}}
                    <span class="page-link" aria-hidden="true">&rsaquo;</span>
                </div>
                @endif
             </div>
            </span>
          </div>
          <div class="justify-center hidden lg:flex">
            <span>
              <div class="flex overflow-hidden border border-gray-300 divide-x divide-gray-300 rounded pagination">
                <!-- Previous Page Link -->
                <button class="relative inline-flex items-center px-2 py-2 text-sm font-medium leading-5 text-gray-500 bg-white" disabled="">
                  <span>«</span>
                </button>
                <div class="divide-x divide-gray-300">
                  <!-- Array Of Links -->
                  <button wire:click="gotoPage(1)" id="pagination-desktop-page-1" class="-mx-1 relative inline-flex items-center px-4 py-2 text-sm leading-5 font-medium text-gray-700 hover:text-gray-500 focus:z-10 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150 bg-gray-200"> 1 </button>
                </div>
                <!-- Next Page Link -->
                <button class="relative inline-flex items-center px-2 py-2 -ml-px text-sm font-medium leading-5 text-gray-500 bg-white " disabled="">
                  <span>»</span>
                </button>
              </div>
            </span>
          </div>
        </div>
      </div>
    </div>
@endif