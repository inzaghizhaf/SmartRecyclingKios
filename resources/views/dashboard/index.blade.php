<x-app-layout>
  <x-slot name="header">
      <h2 class="font-semibold text-xl">Dashboard</h2>
  </x-slot>

  <div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white p-4 rounded shadow">
          <h3 class="text-sm">Total Poin</h3>
          <p class="text-2xl font-bold">{{ auth()->user()->points }}</p>
        </div>
        <div class="bg-white p-4 rounded shadow">
          <h3 class="text-sm">Saldo</h3>
          <p class="text-2xl font-bold">Rp {{ number_format(auth()->user()->balance,0,',','.') }}</p>
        </div>
        <div class="bg-white p-4 rounded shadow">
          <h3 class="text-sm">Aksi</h3>
          <a href="{{ route('prices.index') }}" class="inline-block mt-2 bg-blue-600 text-white px-3 py-1 rounded">Tukar Poin</a>
        </div>
      </div>

      <div class="mt-6 bg-white rounded p-4 shadow">
         <h4 class="font-semibold">History Terakhir</h4>
         <ul>
           @foreach($recent as $t)
             <li class="py-2 border-b flex justify-between">
               <div>
                 <div class="text-sm">{{ ucfirst($t->type) }}</div>
                 <div class="text-xs text-gray-500">{{ $t->note }}</div>
               </div>
               <div class="text-right">
                 <div class="text-sm">{{ $t->points }} poin</div>
                 <div class="text-xs">Rp {{ number_format($t->amount,0,',','.') }}</div>
               </div>
             </li>
           @endforeach
         </ul>
      </div>
    </div>
  </div>
</x-app-layout>