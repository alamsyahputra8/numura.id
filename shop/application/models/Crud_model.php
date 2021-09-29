<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Crud_model extends CI_Model {
  public function __construct(){
    parent::__construct();
    $this->db2 = $this->load->database('dblicense', TRUE);
    // $this->db2->get('siswa');
  }
 
  function get_all_produk() { //ambil data barang dari table barang yang akan di generate ke datatable
        $this->datatables->select('*');
        $this->datatables->from('sample_big');
        // $this->datatables->join('kategori', 'barang_kategori_id=kategori_id');
        $this->datatables->add_column('view', '','a, b, c, d, e, f, g, h, i, j, k, l, m, n, o, p, q, r, s, t, u, v, w, x, y, z, a1, b1, c1, d1, e1, f1, g1, h1, i1, j1, k1, l1, m1, n1, o1, p1, q1, r1, s1, t1, u1, v1, w1, x1, y1, z1, a2, b2, c2, d2, e2, f2, g2, h2, i2, j2, k2, l2, m2, n2, o2, p2, q2, r2, s2, t2, u2, v2, w2, x2, y2, z2, a3, b3');
        return $this->datatables->generate();
  }
}