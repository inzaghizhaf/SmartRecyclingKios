<!DOCTYPE html>
<html>

<head>

<script src="https://cdn.tailwindcss.com"></script>

</head>

<body class="bg-[#CFDCF6] flex items-center justify-center min-h-screen">

<div class="bg-white p-10 rounded-3xl shadow-xl w-[500px] text-center">
 

<h2 class="text-3xl font-bold mt-5">

Login dengan Barcode

</h2>

<img
src="{{ asset('images/qrcode.png') }}"
class="w-72 mx-auto mt-8 border rounded-xl p-3">

<p class="mt-6">

Silakan scan QR menggunakan HP Anda

</p>

<p
id="status"
class="mt-4 text-green-600 font-bold">

● Menunggu Login....

</p>

<a href="{{ route('login.choice') }}">

<button
class="mt-8 bg-red-500 text-white px-8 py-3 rounded-full">

Kembali

</button>

</a>

</div>

<script>

setInterval(function(){

fetch("/qr/status")

.then(res=>res.json())

.then(data=>{

if(data.status=="success"){

window.location="/dashboard";

}

});

},1000);

</script>

</body>

</html>