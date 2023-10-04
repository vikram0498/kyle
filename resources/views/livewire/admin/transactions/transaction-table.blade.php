<div>
  <div class="relative">
    <div class="flex items-center justify-between mb-1">
      <div class="flex items-center">
        <div class="items-center justify-between p-2 sm:flex">
          <div class="flex items-center my-2 sm:my-0">
            <span class="items-center justify-between p-2 sm:flex"> Show <select name="perPage" class="ml-2 mr-2 border block w-full py-2 pl-3 pr-10 mt-1 text-base border-gray-300 form-select leading-6 focus:outline-none focus:shadow-outline-blue focus:border-blue-300 sm:text-sm sm:leading-5" wire:model="perPage">
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="100">100</option>
                <!-- <option value="99999999">All</option> -->
              </select> entries </span>
          </div>
          <div class="flex justify-end text-gray-600">
            <div class="flex rounded-lg w-96 shadow-sm">
              <div class="relative flex-grow focus-within:z-10">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                  <svg class="w-5 h-5 text-gray-400" viewBox="0 0 20 20" stroke="currentColor" fill="none">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                  </svg>
                </div>
                <input wire:model.debounce.500ms="search" class="block w-full py-3 pl-10 text-sm border-gray-300 leading-4 rounded-md shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 focus:outline-none" placeholder="Search" type="text">
                <div class="absolute inset-y-0 right-0 flex items-center pr-2">
                  <button wire:click="$set('search', null)" class="text-gray-300 hover:text-red-600 focus:outline-none">
                    <svg class="h-5 w-5 stroke-current w-5 h-5 stroke-current" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="flex flex-wrap items-center space-x-1">
        <!-- <svg class="h-5 w-5 stroke-current text-gray-400 h-9 w-9 animate-spin" wire:loading="wire:loading" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
 -->
      </div>
    </div>
    <div wire:loading.class="opacity-50" class="rounded-lg  rounded-b-none  shadow-lg bg-white max-w-screen overflow-x-scroll border-2  border-transparent  ">
      <div>
        <div class="table min-w-full align-middle">
          <div class="table-row divide-x divide-gray-200">
            <div class="relative table-cell h-12 overflow-hidden align-top">
              <div class="w-full h-full px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider flex items-center focus:outline-none ">
                <span class="inline ">#</span>
              </div>
            </div>
            <div class="relative table-cell h-12 overflow-hidden align-top">
              <button wire:click="sort('2')" class="w-full h-full px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider flex items-center focus:outline-none ">
                <span class="inline ">Payment Intent Id</span>
                <span class="inline text-xs text-blue-400"></span>
              </button>
            </div>
            <div class="relative table-cell h-12 overflow-hidden align-top">
              <button wire:click="sort('3')" class="w-full h-full px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider flex items-center focus:outline-none ">
                <span class="inline ">User</span>
                <span class="inline text-xs text-blue-400"></span>
              </button>
            </div>
            <div class="relative table-cell h-12 overflow-hidden align-top">
              <button wire:click="sort('4')" class="w-full h-full px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider flex items-center focus:outline-none ">
                <span class="inline ">Amount</span>
                <span class="inline text-xs text-blue-400"></span>
              </button>
            </div>
            <div class="relative table-cell h-12 overflow-hidden align-top">
              <button wire:click="sort('5')" class="w-full h-full px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider flex items-center focus:outline-none ">
                <span class="inline ">Currency</span>
                <span class="inline text-xs text-blue-400"></span>
              </button>
            </div>
            <div class="relative table-cell h-12 overflow-hidden align-top">
              <button wire:click="sort('6')" class="w-full h-full px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider flex items-center focus:outline-none ">
                <span class="inline ">Payment Method</span>
                <span class="inline text-xs text-blue-400"></span>
              </button>
            </div>
            <div class="relative table-cell h-12 overflow-hidden align-top">
              <button wire:click="sort('7')" class="w-full h-full px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider flex items-center focus:outline-none ">
                <span class="inline ">Status</span>
                <span class="inline text-xs text-blue-400"></span>
              </button>
            </div>
          </div>
          <div class="table-row bg-blue-100 divide-x divide-blue-200">
            <div class="table-cell overflow-hidden align-top"></div>
            <div class="table-cell overflow-hidden align-top"></div>
            <div class="table-cell overflow-hidden align-top"></div>
            <div class="table-cell overflow-hidden align-top"></div>
            <div class="table-cell overflow-hidden align-top"></div>
            <div class="table-cell overflow-hidden align-top"></div>
            <div class="table-cell overflow-hidden align-top"></div>
          </div>
          <div class="table-row p-1 divide-x divide-gray-100 text-sm text-gray-900 bg-gray-50">
            <div class="table-cell px-6 py-2   text-left  whitespace-no-wrap text-sm text-gray-900 px-6 py-2"> 1 </div>
            <div class="table-cell px-6 py-2   text-left  whitespace-no-wrap text-sm text-gray-900 px-6 py-2"> pi_3NwUSTSF94v4X0UF0WMhs0zt </div>
            <div class="table-cell px-6 py-2   text-left  whitespace-no-wrap text-sm text-gray-900 px-6 py-2"> Amit Kr </div>
            <div class="table-cell px-6 py-2   text-left  whitespace-no-wrap text-sm text-gray-900 px-6 py-2">
              <i class="fa fa-dollar"></i>100.00
            </div>
            <div class="table-cell px-6 py-2   text-left  whitespace-no-wrap text-sm text-gray-900 px-6 py-2"> USD </div>
            <div class="table-cell px-6 py-2   text-left  whitespace-no-wrap text-sm text-gray-900 px-6 py-2"> Card </div>
            <div class="table-cell px-6 py-2   text-left  whitespace-no-wrap text-sm text-gray-900 px-6 py-2"> Success </div>
          </div>
          <div class="table-row p-1 divide-x divide-gray-100 text-sm text-gray-900 bg-gray-100">
            <div class="table-cell px-6 py-2   text-left  whitespace-no-wrap text-sm text-gray-900 px-6 py-2"> 2 </div>
            <div class="table-cell px-6 py-2   text-left  whitespace-no-wrap text-sm text-gray-900 px-6 py-2"> pi_3NwUO5SF94v4X0UF1DOg4tk4 </div>
            <div class="table-cell px-6 py-2   text-left  whitespace-no-wrap text-sm text-gray-900 px-6 py-2"> Amit Kr </div>
            <div class="table-cell px-6 py-2   text-left  whitespace-no-wrap text-sm text-gray-900 px-6 py-2">
              <i class="fa fa-dollar"></i>100.00
            </div>
            <div class="table-cell px-6 py-2   text-left  whitespace-no-wrap text-sm text-gray-900 px-6 py-2"> USD </div>
            <div class="table-cell px-6 py-2   text-left  whitespace-no-wrap text-sm text-gray-900 px-6 py-2"> Card </div>
            <div class="table-cell px-6 py-2   text-left  whitespace-no-wrap text-sm text-gray-900 px-6 py-2"> Success </div>
          </div>
          <div class="table-row p-1 divide-x divide-gray-100 text-sm text-gray-900 bg-gray-50">
            <div class="table-cell px-6 py-2   text-left  whitespace-no-wrap text-sm text-gray-900 px-6 py-2"> 3 </div>
            <div class="table-cell px-6 py-2   text-left  whitespace-no-wrap text-sm text-gray-900 px-6 py-2"> pi_3NwU1VSF94v4X0UF0Dip997P </div>
            <div class="table-cell px-6 py-2   text-left  whitespace-no-wrap text-sm text-gray-900 px-6 py-2"> Amit Kr </div>
            <div class="table-cell px-6 py-2   text-left  whitespace-no-wrap text-sm text-gray-900 px-6 py-2">
              <i class="fa fa-dollar"></i>100.00
            </div>
            <div class="table-cell px-6 py-2   text-left  whitespace-no-wrap text-sm text-gray-900 px-6 py-2"> USD </div>
            <div class="table-cell px-6 py-2   text-left  whitespace-no-wrap text-sm text-gray-900 px-6 py-2"> Card </div>
            <div class="table-cell px-6 py-2   text-left  whitespace-no-wrap text-sm text-gray-900 px-6 py-2"> Success </div>
          </div>
        </div>
      </div>
    </div>
    
    <div class="max-w-screen bg-white  rounded-b-lg  border-4 border-t-0 border-b-0  border-transparent ">
      <div class="items-center justify-between p-2 sm:flex">
        <div class="flex justify-end text-gray-600"> Showing 1 to 3 of 3 entries </div>
        <div class="my-4 sm:my-0">
          <div class="lg:hidden">
            <span class="space-x-2">
              <div class="flex justify-between">
                <!-- Previous Page Link -->
                <div class="w-32 flex justify-between items-center relative px-4 py-2 border border-gray-300 text-sm leading-5 font-medium rounded-md text-gray-400 bg-gray-50">
                  <svg class="h-5 w-5 stroke-current" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                  </svg> Previous
                </div>
                <!-- Next Page pnk -->
                <div class="w-32 flex justify-between items-center relative px-4 py-2 border border-gray-300 text-sm leading-5 font-medium rounded-md text-gray-400 bg-gray-50"> Next <svg class="h-5 w-5 stroke-current inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                  </svg>
                </div>
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
    <span class="hidden text-sm text-left text-center text-right text-gray-900 bg-gray-100 bg-yellow-100 leading-5 bg-gray-50"></span>
  </div>
</div>