<table border="1">
    <tr><th>KODE BARANG</th><th>NAMA BARANG</th><th>HARGA</th></tr>
    @foreach($barang as $b)
    <tr>
        <td>{{$b->kode_barang}}</td>
        <td>{{$b->nama_barang}}</td>
        <td>{{$b->harga}}</td>
    </tr>
    @endforeach
</table>