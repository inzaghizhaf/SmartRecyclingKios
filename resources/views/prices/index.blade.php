<x-app-layout>
  <div class="p-6">
    <h2 class="text-lg font-bold">Daftar Poin Sampah</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
      @foreach($prices as $p)
      <div class="bg-white p-4 rounded shadow">
        <div class="font-semibold">{{ $p->name }}</div>
        <div class="text-sm">{{ $p->description }}</div>
        <div class="mt-2">Harga: Rp {{ number_format($p->price_per_point,0,',','.') }} / poin</div>
        <form action="{{ route('transactions.exchange') }}" method="POST" class="mt-3">
          @csrf
          <input type="hidden" name="price_id" value="{{ $p->id }}">
          <input type="number" name="points" placeholder="Jumlah poin" class="w-2/3 border rounded px-2 py-1" />
          <button class="ml-2 bg-green-600 text-white px-3 py-1 rounded">Tukar</button>
        </form>
      </div>
      @endforeach
    </div>
  </div>
</x-app-layout>