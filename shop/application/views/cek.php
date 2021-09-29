<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Crud dengan ignited datatables pada CodeIgniter</title>
  <link href="<?php echo base_url().'assets/bigsample/css/bootstrap.css'?>" rel="stylesheet" type="text/css"/>
    <link href="<?php echo base_url().'assets/bigsample/css/jquery.datatables.min.css'?>" rel="stylesheet" type="text/css"/>
  <link href="<?php echo base_url().'assets/bigsample/css/dataTables.bootstrap.css'?>" rel="stylesheet" type="text/css"/>
</head>
<body>
  <div class="container">
    <h2>Product List</h2>
        <button class="btn btn-success" data-toggle="modal" data-target="#myModalAdd">Add New</button>
    <table class="table table-striped" id="mytable">
      <thead>
        <tr>
         <th>a</th> <th>b</th> <th>c</th> <th>d</th> <th>e</th> <th>f</th> <th>g</th> <th>h</th> <th>i</th> <th>j</th> <th>k</th> <th>l</th> <th>m</th> <th>n</th> <th>o</th> <th>p</th> <th>q</th> <th>r</th> <th>s</th> <th>t</th> <th>u</th> <th>v</th> <th>w</th> <th>x</th> <th>y</th> <th>z</th> <th>a1</th> <th>b1</th> <th>c1</th> <th>d1</th> <th>e1</th> <th>f1</th> <th>g1</th> <th>h1</th> <th>i1</th> <th>j1</th> <th>k1</th> <th>l1</th> <th>m1</th> <th>n1</th> <th>o1</th> <th>p1</th> <th>q1</th> <th>r1</th> <th>s1</th> <th>t1</th> <th>u1</th> <th>v1</th> <th>w1</th> <th>x1</th> <th>y1</th> <th>z1</th> <th>a2</th> <th>b2</th> <th>c2</th> <th>d2</th> <th>e2</th> <th>f2</th> <th>g2</th> <th>h2</th> <th>i2</th> <th>j2</th> <th>k2</th> <th>l2</th> <th>m2</th> <th>n2</th> <th>o2</th> <th>p2</th> <th>q2</th> <th>r2</th> <th>s2</th> <th>t2</th> <th>u2</th> <th>v2</th> <th>w2</th> <th>x2</th> <th>y2</th> <th>z2</th> <th>a3</th> <th>b3<th>
        </tr>
      </thead>
    </table>
  </div>
 
    <!-- Modal Add Produk-->
      <form id="add-row-form" action="<?php echo base_url().'crud/simpan'?>" method="post">
         <div class="modal fade" id="myModalAdd" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
               <div class="modal-content">
                   <div class="modal-header">
                       <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                       <h4 class="modal-title" id="myModalLabel">Add New</h4>
                   </div>
                   <div class="modal-body">
                       <div class="form-group">
                           <input type="text" name="kode_barang" class="form-control" placeholder="Kode Barang" required>
                       </div>
                                         <div class="form-group">
                           <input type="text" name="nama_barang" class="form-control" placeholder="Nama Barang" required>
                       </div>
                                         <div class="form-group">

                       </div>
                                         <div class="form-group">
                           <input type="text" name="harga" class="form-control" placeholder="Harga" required>
                       </div>
 
                   </div>
                   <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" id="add-row" class="btn btn-success">Save</button>
                   </div>
                    </div>
            </div>
         </div>
     </form>
 
     <!-- Modal Update Produk-->
      <form id="add-row-form" action="<?php echo base_url().'crud/update'?>" method="post">
         <div class="modal fade" id="ModalUpdate" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
               <div class="modal-content">
                   <div class="modal-header">
                       <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                       <h4 class="modal-title" id="myModalLabel">Update Produk</h4>
                   </div>
                   <div class="modal-body">
                       <div class="form-group">
                           <input type="text" name="kode_barang" class="form-control" placeholder="Kode Barang" required>
                       </div>
                                         <div class="form-group">
                           <input type="text" name="nama_barang" class="form-control" placeholder="Nama Barang" required>
                       </div>
                                         <div class="form-group">
                           <select name="kategori" class="form-control" placeholder="Kode Barang" required>

                                                 </select>
                       </div>
                                         <div class="form-group">
                           <input type="text" name="harga" class="form-control" placeholder="Harga" required>
                       </div>
 
                   </div>
                   <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" id="add-row" class="btn btn-success">Update</button>
                   </div>
                    </div>
            </div>
         </div>
     </form>
 
     <!-- Modal Hapus Produk-->
      <form id="add-row-form" action="<?php echo base_url().'crud/delete'?>" method="post">
         <div class="modal fade" id="ModalHapus" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
               <div class="modal-content">
                   <div class="modal-header">
                       <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                       <h4 class="modal-title" id="myModalLabel">Hapus Produk</h4>
                   </div>
                   <div class="modal-body">
                           <input type="hidden" name="kode_barang" class="form-control" placeholder="Kode Barang" required>
                                                 <strong>Anda yakin mau menghapus record ini?</strong>
                   </div>
                   <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" id="add-row" class="btn btn-success">Hapus</button>
                   </div>
                    </div>
            </div>
         </div>
     </form>
 
<script src="<?php echo base_url().'assets/bigsample/js/jquery-2.1.4.min.js'?>"></script>
<script src="<?php echo base_url().'assets/bigsample/js/bootstrap.js'?>"></script>
<script src="<?php echo base_url().'assets/bigsample/js/jquery.datatables.min.js'?>"></script>
<script src="<?php echo base_url().'assets/bigsample/js/dataTables.bootstrap.js'?>"></script>
 
<script>
    $(document).ready(function(){
        // Setup datatables
        $.fn.dataTableExt.oApi.fnPagingInfo = function(oSettings)
      {
          return {
              "iStart": oSettings._iDisplayStart,
              "iEnd": oSettings.fnDisplayEnd(),
              "iLength": oSettings._iDisplayLength,
              "iTotal": oSettings.fnRecordsTotal(),
              "iFilteredTotal": oSettings.fnRecordsDisplay(),
              "iPage": Math.ceil(oSettings._iDisplayStart / oSettings._iDisplayLength),
              "iTotalPages": Math.ceil(oSettings.fnRecordsDisplay() / oSettings._iDisplayLength)
          };
      };
 
      var table = $("#mytable").dataTable({
          initComplete: function() {
              var api = this.api();
              $('#mytable_filter input')
                  .off('.DT')
                  .on('input.DT', function() {
                      api.search(this.value).draw();
              });
          },
              oLanguage: {
              sProcessing: "loading..."
          },
              processing: true,
              serverSide: true,
              ajax: {"url": "<?php echo base_url().'crud/get_produk_json'?>", "type": "POST"},
                order: [[1, 'asc']],
          rowCallback: function(row, data, iDisplayIndex) {
              var info = this.fnPagingInfo();
              var page = info.iPage;
              var length = info.iLength;
              $('td:eq(0)', row).html();
          }
 
      });
            // end setup datatables
            // get Edit Records
            $('#mytable').on('click','.edit_record',function(){
            var kode=$(this).data('kode');
                        var nama=$(this).data('nama');
                        var harga=$(this).data('harga');
                        var kategori=$(this).data('kategori');
            $('#ModalUpdate').modal('show');
            $('[name="kode_barang"]').val(kode);
                        $('[name="nama_barang"]').val(nama);
                        $('[name="harga"]').val(harga);
                        $('[name="kategori"]').val(kategori);
      });
            // End Edit Records
            // get Hapus Records
            $('#mytable').on('click','.hapus_record',function(){
            var kode=$(this).data('kode');
            $('#ModalHapus').modal('show');
            $('[name="kode_barang"]').val(kode);
      });
            // End Hapus Records
 
    });
</script>
</body>
</html>