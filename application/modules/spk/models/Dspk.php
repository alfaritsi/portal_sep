<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @application  : SPK Transaksi - Model
 * @author     : Octe Reviyanto Nugroho
 * @contributor  : 
 * 1. Lukman Hakim (7143) 28.03.2019
 * CR#1883 -> http://10.0.0.18/home/pdfviewer.php?q=crpdf/cr/CR_1883.pdf&n=CR_1883.pdf
 * 2. <insert your fullname> (<insert your nik>) <insert the date>
 * <insert what you have modified> 
 * etc.  
 */  
class Dspk extends CI_Model 
{    
	function get_data_email($conn = NULL, $plant = NULL, $id_status = NULL, $id_spk = NULL) {
		if ($conn !== NULL)
			$this->general->connectDbPortal();  
  
		$this->db->select('tbl_karyawan.nama'); 
		$this->db->select('tbl_karyawan.email');  
		$this->db->from('tbl_user');   
		$this->db->join('tbl_karyawan', 'tbl_karyawan.id_karyawan = tbl_user.id_karyawan', 'left outer');
		$this->db->where("tbl_user.na='n'");
		
		if($plant != NULL){ 
			if($id_status==18){	//drop 
				// $this->db->where("tbl_user.leg_level_id=1 or (tbl_user.leg_level_id=2 and tbl_karyawan.gsber='$plant')");
				if($id_spk!=NULL){
					$this->db->where("
						tbl_user.leg_level_id=1 or (tbl_user.leg_level_id=2 and tbl_karyawan.gsber='$plant')
						or
						tbl_user.leg_level_id in (
						SELECT 
						tbl_leg_divisi.id_divisi
						FROM tbl_leg_divisi
						LEFT OUTER JOIN tbl_leg_oto_divisi ON tbl_leg_divisi.id_divisi = tbl_leg_oto_divisi.id_divisi 
						LEFT OUTER JOIN tbl_leg_approval ON tbl_leg_approval.id_oto_div = tbl_leg_oto_divisi.id_oto_divisi 
						WHERE tbl_leg_divisi.na = 'n' AND tbl_leg_divisi.del = 'n' AND tbl_leg_approval.id_spk = $id_spk					
					)");
				}
			}else if($id_status==17){	//cancel
				// $this->db->where("tbl_user.leg_level_id=1 or (tbl_user.leg_level_id=2 and tbl_karyawan.gsber='$plant')");
				if($id_spk!=NULL){
					$this->db->where("
						tbl_user.leg_level_id=1 or (tbl_user.leg_level_id=2 and tbl_karyawan.gsber='$plant')
						or
						tbl_user.leg_level_id in (
						SELECT 
						tbl_leg_divisi.id_divisi
						FROM tbl_leg_divisi
						LEFT OUTER JOIN tbl_leg_oto_divisi ON tbl_leg_divisi.id_divisi = tbl_leg_oto_divisi.id_divisi 
						LEFT OUTER JOIN tbl_leg_approval ON tbl_leg_approval.id_oto_div = tbl_leg_oto_divisi.id_oto_divisi 
						WHERE tbl_leg_divisi.na = 'n' AND tbl_leg_divisi.del = 'n' AND tbl_leg_approval.id_spk = $id_spk					
					)");
				}
			}else{
				$this->db->where("tbl_user.leg_level_id=1 or (tbl_user.leg_level_id=2 and tbl_karyawan.gsber='$plant')");	
			}
		}else{
			$this->db->where("tbl_user.leg_level_id=1"); 
		}
		$this->db->where("tbl_karyawan.email!=''"); 
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result; 
	} 

	function get_data_spec($conn = NULL, $vendor = NULL, $id_jenis_vendor = NULL, $plant = NULL, $id_jenis_spk = NULL, $lifnr = NULL) {
		if ($conn !== NULL)
			$this->general->connectDbPortal(); 
  
		$this->db->select('vw_spk_zdmvendor.lifnr as id');
		$this->db->select('vw_spk_zdmvendor.*');
		$this->db->from('vw_spk_zdmvendor');    
		$this->db->where("vw_spk_zdmvendor.ekorg like '%".base64_decode($this->session->userdata('-gsber-'))."%'");		
		if ($vendor !== NULL) {   
			$this->db->where("(vw_spk_zdmvendor.name1 like '%".strtoupper($vendor)."%')");
		}
		if ($id_jenis_vendor !== NULL) { 
			$this->db->where("vw_spk_zdmvendor.id_jenis_vendor='$id_jenis_vendor'"); 
		}
		// if ($plant !== NULL) { 
			// $this->db->where("vw_spk_zdmvendor.ekorg='$plant'");
		// }
		if ($id_jenis_spk !== NULL) {
			$this->db->where("vw_spk_zdmvendor.id_jenis_spk='$id_jenis_spk'");
			$this->db->where("vw_spk_zdmvendor.status='Completed'");
		}
		if ($lifnr !== NULL) {
			$this->db->where("vw_spk_zdmvendor.lifnr='$lifnr'");
		}
		 
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	function get_data_user($vendor = NULL) {
		$this->general->connectDbPortal();

		$this->db->select('vw_spk_zdmvendor.*');
		$this->db->from('vw_spk_zdmvendor');
		if ($vendor !== NULL) {
			$this->db->like('vw_spk_zdmvendor.name1', $vendor);
		}
		$this->db->order_by('vw_spk_zdmvendor.name1', 'ASC');
		$query  = $this->db->get();
		$result = $query->result();

		$this->general->closeDb();
		return $result;
	}
	
	function get_data_vendor($conn = NULL, $id_jenis_vendor = NULL, $id_jenis_spk = NULL) {
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select('tbl_leg_zdmvendor_matrix.*');
		$this->db->from('tbl_leg_zdmvendor_matrix');
		if ($id_jenis_vendor !== NULL) {
			$this->db->where('tbl_leg_zdmvendor_matrix.id_jenis_vendor', $id_jenis_vendor);
		}
		if ($id_jenis_spk !== NULL) {
			$this->db->where('tbl_leg_zdmvendor_matrix.id_jenis_spk', $id_jenis_spk);
		}
		$this->db->where('tbl_leg_zdmvendor_matrix.na', 'n');
		$this->db->where('tbl_leg_zdmvendor_matrix.del', 'n');
		$this->db->order_by("tbl_leg_zdmvendor_matrix.lifnr", "asc");
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}

    public function get_spk($params = array())
    {
        $all = isset($params['all']) ? $params['all'] : false;
        $list = isset($params['list']) ? $params['list'] : false;
        $id_spk = isset($params['id_spk']) ? $params['id_spk'] : null;
        $nik = isset($params['nik']) ? $params['nik'] : null;
        $id_divisi = isset($params['id_divisi']) ? $params['id_divisi'] : null;
        $id_status = isset($params['id_status']) ? $params['id_status'] : null;
        $id_status_not = isset($params['id_status_not']) ? $params['id_status_not'] : null;
        $id_plant = isset($params['id_plant']) ? $params['id_plant'] : null;
        $tanggal_berlaku_spk = isset($params['tanggal_berlaku_spk']) ? $params['tanggal_berlaku_spk'] : null;
        $order_by = isset($params['order_by']) ? $params['order_by'] : "tbl_leg_spk.tanggal_edit desc";
		
		$filter_plant = isset($params['filter_plant']) ? $params['filter_plant'] : null;
		$filter_jenis = isset($params['filter_jenis']) ? $params['filter_jenis'] : null;
		$filter_tanggal_berlaku_awal = isset($params['filter_tanggal_berlaku_awal']) ? $params['filter_tanggal_berlaku_awal'] : null;
		$filter_tanggal_berlaku_akhir = isset($params['filter_tanggal_berlaku_akhir']) ? $params['filter_tanggal_berlaku_akhir'] : null;
		$filter_tanggal_berakhir_awal = isset($params['filter_tanggal_berakhir_awal']) ? $params['filter_tanggal_berakhir_awal'] : null;
		$filter_tanggal_berakhir_akhir = isset($params['filter_tanggal_berakhir_akhir']) ? $params['filter_tanggal_berakhir_akhir'] : null;
		$filter_status = isset($params['filter_status']) ? $params['filter_status'] : null;

        $this->db->distinct();  
        $this->db->select('tbl_leg_spk.*'); 
        $this->db->select('tbl_leg_status.status,tbl_leg_status.warna');
        $this->db->select("(select count(*) from tbl_leg_komentar where tbl_leg_komentar.id_spk=tbl_leg_spk.id_spk and tbl_leg_komentar.na='n' and CHARINDEX('".base64_decode($this->session->userdata("-nik-"))."', tbl_leg_komentar.user_read)=0) as jumlah_komentar");
		if (isset($id_spk)){
			$this->db->select("(select top 1 CITY1 from vw_spk_zdmvendor where vw_spk_zdmvendor.lifnr='0000100084') as CITY1");
			$this->db->select("(select top 1 STRAS from vw_spk_zdmvendor where vw_spk_zdmvendor.lifnr='0000100084') as STRAS");
		}
		$this->db->from('tbl_leg_spk');
        $this->db->join('tbl_leg_status', 'tbl_leg_spk.id_status=tbl_leg_status.id_status');

        if (isset($nik)) {
            $this->db->join('tbl_karyawan', 'tbl_leg_spk.plant=tbl_karyawan.gsber', 'left outer');
            $this->db->where('tbl_karyawan.nik', $nik);
        }

        if (isset($id_divisi)) {
            $this->db->join('tbl_leg_oto_divisi', 'tbl_leg_oto_divisi.id_nama_spk=tbl_leg_spk.id_nama_spk', 'left outer');
            $this->db->where('tbl_leg_oto_divisi.id_divisi', $id_divisi);
        }

        if (!$all) {
            if (!$list)
                $this->db->where('tbl_leg_spk.na', 'n');
            $this->db->where('tbl_leg_spk.del', 'n');
        }

        if (isset($id_spk))
            $this->db->where('tbl_leg_spk.id_spk', $id_spk);

        if (isset($id_plant))
            $this->db->where('tbl_leg_spk.id_plant', $id_plant);

        if (isset($id_status)) {
            if (is_array($id_status))
                $this->db->where_in('tbl_leg_spk.id_status', $id_status);
            else
                $this->db->where('tbl_leg_spk.id_status', $id_status);
        }

        if (isset($id_status_not)) {
            if (is_array($id_status_not))
                $this->db->where_not_in('tbl_leg_spk.id_status', $id_status_not);
            else
                $this->db->where('tbl_leg_spk.id_status <>', $id_status_not);
        }

        if (isset($tanggal_berlaku_spk)) {
            if (is_array($tanggal_berlaku_spk)) {
                $this->db->where('tbl_leg_spk.tanggal_berlaku_spk between \'' . $tanggal_berlaku_spk[0] . '\' and \'' . $tanggal_berlaku_spk[1] . '\'');
            } else
                $this->db->where('tbl_leg_spk.tanggal_berlaku_spk =', $tanggal_berlaku_spk);
        }
		//lha
		if($filter_plant != NULL){
			if(is_string($filter_plant)) $filter_plant = explode(",", $filter_plant);
			$this->db->where_in('tbl_leg_spk.plant', $filter_plant);
		}
		if($filter_jenis != NULL){
			if(is_string($filter_jenis)) $filter_jenis = explode(",", $filter_jenis);
			$this->db->where_in('tbl_leg_spk.id_jenis_spk', $filter_jenis);
		}
		if(($filter_tanggal_berlaku_awal != NULL)and($filter_tanggal_berlaku_akhir != NULL)){
			// $this->db->where('tbl_leg_spk.tanggal_berlaku_spk <= \'' . $filter_tanggal_berlaku . '\'');
			$this->db->where("tbl_leg_spk.tanggal_berlaku_spk between '$filter_tanggal_berlaku_awal' and '$filter_tanggal_berlaku_akhir'");
		}
		if(($filter_tanggal_berakhir_awal != NULL)and($filter_tanggal_berakhir_akhir != NULL)){
			// $this->db->where('tbl_leg_spk.tanggal_berlaku_spk <= \'' . $filter_tanggal_berakhir . '\'');
			$this->db->where("tbl_leg_spk.tanggal_berakhir_spk between '$filter_tanggal_berakhir_awal' and '$filter_tanggal_berakhir_akhir'");
		}
		if($filter_status != NULL){
			if(is_string($filter_status)) $filter_status = explode(",", $filter_status);
			$this->db->where_in('tbl_leg_spk.id_status', $filter_status);
		}

        $this->db->order_by($order_by);
		// $this->db->limit(5);
        $query = $this->db->get();

        if (isset($params['single_row']) && $params['single_row'])
            $result = $query->row();
        else
            $result = $query->result();

        return $result;
    }

    public function get_spk_divisi($params = array())
    {
        $all = isset($params['all']) ? $params['all'] : false;
        $list = isset($params['list']) ? $params['list'] : false;
        $id_spk = isset($params['id_spk']) ? $params['id_spk'] : null;
        $id_divisi = isset($params['id_divisi']) ? $params['id_divisi'] : null;
        $id_oto_div = isset($params['id_oto_div']) ? $params['id_oto_div'] : null;

        $this->db->select('tbl_leg_divisi.*');
        $this->db->select('tbl_leg_approval.approve');
        $this->db->select('tbl_leg_approval.tanggal_approve');
        $this->db->from('tbl_leg_divisi');
        $this->db->join('tbl_leg_oto_divisi', 'tbl_leg_divisi.id_divisi = tbl_leg_oto_divisi.id_divisi', 'left outer');
        $this->db->join('tbl_leg_approval', 'tbl_leg_approval.id_oto_div = tbl_leg_oto_divisi.id_oto_divisi', 'left outer');
        $this->db->join('tbl_leg_nama_spk', 'tbl_leg_nama_spk.id_nama_spk = tbl_leg_oto_divisi.id_nama_spk', 'left outer');

        if (!$all) {
            if (!$list)
                $this->db->where('tbl_leg_divisi.na', 'n');
            $this->db->where('tbl_leg_divisi.del', 'n');
        }

        if (isset($id_spk))
            $this->db->where('tbl_leg_approval.id_spk', $id_spk);

        if (isset($id_oto_div))
            $this->db->where('tbl_leg_approval.id_oto_div', $id_oto_div);

        if (isset($id_divisi))
            $this->db->where('tbl_leg_oto_divisi.id_divisi', $id_divisi);

        $query = $this->db->get();

        if (isset($params['single_row']) && $params['single_row'])
            $result = $query->row();
        else
            $result = $query->result();

        return $result;
    }

    public function get_approval($params = array())
    {
        $all = isset($params['all']) ? $params['all'] : false;
        $list = isset($params['list']) ? $params['list'] : false;
        $id_spk = isset($params['id_spk']) ? $params['id_spk'] : null;
        $id_divisi = isset($params['id_divisi']) ? $params['id_divisi'] : null;
        $id_oto_div = isset($params['id_oto_div']) ? $params['id_oto_div'] : null;
        $approve = isset($params['approve']) ? $params['approve'] : null;

        $this->db->select('tbl_leg_approval.*');
        $this->db->from('tbl_leg_approval');
        $this->db->join('tbl_leg_oto_divisi', 'tbl_leg_approval.id_oto_div = tbl_leg_oto_divisi.id_oto_divisi', 'left outer');
        $this->db->join('tbl_leg_spk', 'tbl_leg_spk.id_spk = tbl_leg_approval.id_spk', 'left outer');

        if (!$all) {
            if (!$list)
                $this->db->where('tbl_leg_approval.na', 'n');
            $this->db->where('tbl_leg_approval.del', 'n');
        }

        if (isset($id_spk))
            $this->db->where('tbl_leg_approval.id_spk', $id_spk);

        if (isset($id_oto_div))
            $this->db->where('tbl_leg_approval.id_oto_div', $id_oto_div);

        if (isset($id_divisi))
            $this->db->where('tbl_leg_oto_divisi.id_divisi', $id_divisi);

        if (isset($approve))
            $this->db->where('tbl_leg_approval.approve', $approve);

        $query = $this->db->get();

        if (isset($params['single_row']) && $params['single_row'])
            $result = $query->row();
        else
            $result = $query->result();

        return $result;
    }

    public function get_oto_divisi($params = array())
    {
        $all = isset($params['all']) ? $params['all'] : false;
        $list = isset($params['list']) ? $params['list'] : false;
        $id_spk = isset($params['id_spk']) ? $params['id_spk'] : null;

        $this->db->select('tbl_leg_oto_divisi.*');
        $this->db->from('tbl_leg_oto_divisi');
        $this->db->join('tbl_leg_nama_spk', 'tbl_leg_nama_spk.id_nama_spk = tbl_leg_oto_divisi.id_nama_spk', 'left outer');
        $this->db->join('tbl_leg_spk', 'tbl_leg_spk.id_nama_spk = tbl_leg_nama_spk.id_nama_spk', 'left outer');

        if (!$all) {
            if (!$list)
                $this->db->where('tbl_leg_oto_divisi.na', 'n');
            $this->db->where('tbl_leg_oto_divisi.del', 'n');
        }

        if (isset($id_spk))
            $this->db->where('tbl_leg_spk.id_spk', $id_spk);

        $query = $this->db->get();

        if (isset($params['single_row']) && $params['single_row'])
            $result = $query->row();
        else
            $result = $query->result();

        return $result;
    }

    public function get_spk_vendor($params = array())
    {
        $all = isset($params['all']) ? $params['all'] : false;
        $list = isset($params['list']) ? $params['list'] : false;
        $id_spk = isset($params['id_spk']) ? $params['id_spk'] : null;
        $id_oto_vendor = isset($params['id_oto_vendor']) ? $params['id_oto_vendor'] : null;

        $this->db->distinct();
        $this->db->select('tbl_leg_spk.nama_spk');
        $this->db->select('tbl_leg_oto_vendor.nama_dokumen_vendor as nama_doc');
        $this->db->select('tbl_leg_oto_vendor.mandatory as mandatory_doc');
        $this->db->select('tbl_leg_oto_vendor.id_oto_vendor');
        $this->db->select('tbl_leg_oto_vendor.id_jenis_vendor');
        $this->db->select('tbl_leg_upload_vendor.id_upload_vendor');
        $this->db->select('tbl_leg_upload_vendor.files');
        $this->db->select('tbl_leg_upload_vendor.mandatory');
		$this->db->select('convert(varchar, tbl_leg_upload_vendor.tanggal_edit, 104) as tanggal_edit');
		$this->db->select('tbl_leg_spk.plant');
		$this->db->select('tbl_leg_spk.jenis_spk');
		$this->db->select('tbl_leg_spk.perihal');
		$this->db->select('tbl_leg_spk.SPPKP');
        $this->db->from('tbl_leg_spk');
        $this->db->join('tbl_leg_oto_vendor', 'tbl_leg_spk.id_jenis_vendor = tbl_leg_oto_vendor.id_jenis_vendor');
        $this->db->join(
            'tbl_leg_upload_vendor',
            'tbl_leg_upload_vendor.id_oto_vendor = tbl_leg_oto_vendor.id_oto_vendor and tbl_leg_upload_vendor.id_spk=tbl_leg_spk.id_spk',
            'left outer'
        );

        if (!$all) {
            if (!$list) {
                $this->db->where('tbl_leg_spk.na', 'n');
                $this->db->where('tbl_leg_oto_vendor.na', 'n');
            }
            $this->db->where('tbl_leg_spk.del', 'n');
            $this->db->where('tbl_leg_oto_vendor.del', 'n');
        }

        if (isset($id_spk))
            $this->db->where('tbl_leg_spk.id_spk', $id_spk);

        if (isset($id_oto_vendor))
            $this->db->where('tbl_leg_oto_vendor.id_oto_vendor', $id_oto_vendor);

        $this->db->order_by('tbl_leg_oto_vendor.mandatory');

        $query = $this->db->get();

        if (isset($params['single_row']) && $params['single_row'])
            $result = $query->row();
        else
            $result = $query->result();

        return $result;
    }
	
    public function get_spk_vendor_dokumen($params = array())
    {
        $all = isset($params['all']) ? $params['all'] : false;
        $list = isset($params['list']) ? $params['list'] : false;
        $id_spk = isset($params['id_spk']) ? $params['id_spk'] : null;
        $id_oto_vendor = isset($params['id_oto_vendor']) ? $params['id_oto_vendor'] : null;
		
		$lifnr = isset($params['lifnr']) ? $params['lifnr'] : null;
		$nama_file = "tbl_leg_oto_vendor.nama_dokumen_vendor+' - ".$lifnr."'";

        $this->db->distinct(); 
        $this->db->select('tbl_leg_spk.nama_spk');
        $this->db->select('tbl_leg_oto_vendor.nama_dokumen_vendor as nama_doc');
        $this->db->select('tbl_leg_oto_vendor.mandatory as mandatory_doc');
        $this->db->select('tbl_leg_oto_vendor.id_oto_vendor');
        $this->db->select('tbl_leg_oto_vendor.id_jenis_vendor');
        $this->db->select('tbl_leg_upload_vendor.id_upload_vendor');
        $this->db->select('tbl_leg_upload_vendor.files');
        $this->db->select('tbl_leg_upload_vendor.mandatory');
		// $this->db->select('convert(varchar, tbl_leg_upload_vendor.tanggal_edit, 104) as tanggal_edit');
		$this->db->select('tbl_leg_spk.plant');
		$this->db->select('tbl_leg_spk.jenis_spk'); 
		$this->db->select('tbl_leg_spk.perihal');
		$this->db->select('tbl_leg_spk.SPPKP');
		$this->db->select("(select top 1 tbl_file.link from tbl_file where tbl_file.nama=$nama_file order by tbl_file.id_file desc) as link");
		$this->db->select("(select top 1 convert(varchar, tbl_file.tanggal_buat, 104) from tbl_file where tbl_file.nama=$nama_file order by tbl_file.id_file desc) as tanggal_edit");
        $this->db->from('tbl_leg_spk');
        $this->db->join('tbl_leg_oto_vendor', 'tbl_leg_spk.id_jenis_vendor = tbl_leg_oto_vendor.id_jenis_vendor');
        $this->db->join(
            'tbl_leg_upload_vendor',
            'tbl_leg_upload_vendor.id_oto_vendor = tbl_leg_oto_vendor.id_oto_vendor and tbl_leg_upload_vendor.id_spk=tbl_leg_spk.id_spk',
            'left outer'
        );

        if (!$all) {
            if (!$list) {
                $this->db->where('tbl_leg_spk.na', 'n');
                $this->db->where('tbl_leg_oto_vendor.na', 'n');
            }
            $this->db->where('tbl_leg_spk.del', 'n');
            $this->db->where('tbl_leg_oto_vendor.del', 'n');
        }

        if (isset($id_spk))
            $this->db->where('tbl_leg_spk.id_spk', $id_spk);

        if (isset($id_oto_vendor))
            $this->db->where('tbl_leg_oto_vendor.id_oto_vendor', $id_oto_vendor);

        $this->db->order_by('tbl_leg_oto_vendor.mandatory');

        $query = $this->db->get();

        if (isset($params['single_row']) && $params['single_row'])
            $result = $query->row();
        else
            $result = $query->result();
 
        return $result;
    }    
	public function get_spk_vendor_dokumen_kualifikasi($params = array())
    {
        $all = isset($params['all']) ? $params['all'] : false;
        $list = isset($params['list']) ? $params['list'] : false;
        $id_spk = isset($params['id_spk']) ? $params['id_spk'] : null;
        $id_jenis_spk = isset($params['id_jenis_spk']) ? $params['id_jenis_spk'] : null;
        $id_kualifikasi = isset($params['id_kualifikasi']) ? $params['id_kualifikasi'] : null;
		
		$lifnr = isset($params['lifnr']) ? $params['lifnr'] : null;
		$nama_file = "tbl_leg_kualifikasi_spk.kualifikasi_spk+' - ".$lifnr."'";

		$this->db->select('tbl_leg_kualifikasi_spk.*');
		$this->db->select('tbl_leg_jenis_spk.jenis_spk');
        $this->db->select('tbl_leg_kualifikasi_spk.kualifikasi_spk as nama_doc');
        $this->db->select("'Mandatory' as mandatory_doc"); 
		$this->db->select("(select top 1 tbl_file.link from tbl_file where tbl_file.nama=$nama_file order by tbl_file.id_file desc) as link");
		$this->db->select("(select top 1 convert(varchar, tbl_file.tanggal_buat, 104) from tbl_file where tbl_file.nama=$nama_file order by tbl_file.id_file desc) as tanggal_edit");
		$this->db->from('tbl_leg_kualifikasi_spk');
		$this->db->join('tbl_leg_jenis_spk', 'tbl_leg_kualifikasi_spk.id_jenis_spk=tbl_leg_jenis_spk.id_jenis_spk', 'left outer');

        if (isset($id_jenis_spk))
            $this->db->where('tbl_leg_kualifikasi_spk.id_jenis_spk', $id_jenis_spk);
        if (isset($id_kualifikasi))
            $this->db->where('tbl_leg_kualifikasi_spk.id_kualifikasi_spk', $id_kualifikasi);

		$this->db->where('tbl_leg_kualifikasi_spk.na', 'n');

        $this->db->order_by('tbl_leg_kualifikasi_spk.kualifikasi_spk');

        $query = $this->db->get();

        if (isset($params['single_row']) && $params['single_row'])
            $result = $query->row();
        else
            $result = $query->result();

        return $result;
    }

    public function get_spk_template($params = array())
    {
        $all = isset($params['all']) ? $params['all'] : false;
        $list = isset($params['list']) ? $params['list'] : false;
        $id_spk = isset($params['id_spk']) ? $params['id_spk'] : null;
        $id_oto_jenis = isset($params['id_oto_jenis']) ? $params['id_oto_jenis'] : null;

        $this->db->distinct();
        $this->db->select('tbl_leg_spk.nama_spk');
        $this->db->select('tbl_leg_oto_jenis_spk.nama_dokumen as nama_doc');
        $this->db->select('tbl_leg_oto_jenis_spk.id_oto_jenis');
        $this->db->select('tbl_leg_oto_jenis_spk.id_jenis_spk');
        $this->db->select('tbl_leg_upload_template.id_upload_template');
        $this->db->select('tbl_leg_upload_template.files');
		$this->db->select('convert(varchar, tbl_leg_upload_template.tanggal_edit, 104) as tanggal_edit');
		$this->db->select('tbl_leg_spk.plant');
		$this->db->select('tbl_leg_spk.jenis_spk');
		$this->db->select('tbl_leg_spk.perihal');
		$this->db->select('tbl_leg_spk.SPPKP'); 
        $this->db->from('tbl_leg_spk');
        $this->db->join('tbl_leg_oto_jenis_spk', 'tbl_leg_spk.id_jenis_spk = tbl_leg_oto_jenis_spk.id_jenis_spk', 'inner');
        $this->db->join(
            'tbl_leg_upload_template',
            'tbl_leg_upload_template.id_oto_jenis = tbl_leg_oto_jenis_spk.id_oto_jenis and tbl_leg_upload_template.id_spk=tbl_leg_spk.id_spk',
            'left outer'
        );

        if (!$all) {
            if (!$list) {
                $this->db->where('tbl_leg_spk.na', 'n');
                $this->db->where('tbl_leg_oto_jenis_spk.na', 'n');
            }
            $this->db->where('tbl_leg_spk.del', 'n');
            $this->db->where('tbl_leg_oto_jenis_spk.del', 'n');
        }

        if (isset($id_spk))
            $this->db->where('tbl_leg_spk.id_spk', $id_spk);

        if (isset($id_oto_jenis))
            $this->db->where('tbl_leg_oto_jenis_spk.id_oto_jenis', $id_oto_jenis);

        $query = $this->db->get();

        if (isset($params['single_row']) && $params['single_row'])
            $result = $query->row();
        else
            $result = $query->result();

        return $result;
    }

    public function get_total_spk_template($params = array())
    {
        $id_jenis_spk = isset($params['id_jenis_spk']) ? $params['id_jenis_spk'] : null;

        $this->db->select('count(*) as totaldok');
        $this->db->from('tbl_leg_oto_jenis_spk');

        $this->db->where('tbl_leg_oto_jenis_spk.na', 'n');
        $this->db->where('tbl_leg_oto_jenis_spk.del', 'n');

        if (isset($id_jenis_spk))
            $this->db->where('tbl_leg_oto_jenis_spk.id_jenis_spk', $id_jenis_spk);

        $query = $this->db->get();

        $result = $query->row();

        return $result;
    }

    public function get_total_spk_template_uploaded($params = array())
    {
        $id_spk = isset($params['id_spk']) ? $params['id_spk'] : null;
        $id_jenis_spk = isset($params['id_jenis_spk']) ? $params['id_jenis_spk'] : null;

        $this->db->select('count(*) as totaldokup');
        $this->db->from('tbl_leg_upload_template');

        $this->db->where('tbl_leg_upload_template.na', 'n');
        $this->db->where('tbl_leg_upload_template.del', 'n');

        if (isset($id_spk))
            $this->db->where('tbl_leg_upload_template.id_spk', $id_spk);

        if (isset($id_jenis_spk))
            $this->db->where('tbl_leg_upload_template.id_jenis_spk', $id_jenis_spk);

        $query = $this->db->get();

        $result = $query->row();

        return $result;
    }

    public function get_total_spk_vendor($params = array())
    {
        $id_jenis_vendor = isset($params['id_jenis_vendor']) ? $params['id_jenis_vendor'] : null;

        $this->db->select('COUNT(*) as totalven');
        $this->db->select('COUNT(tb2.id_oto_vendor) as total_ven_mandatory');
        $this->db->from('tbl_leg_oto_vendor');
        $this->db->join(
            'tbl_leg_oto_vendor tb2',
            'tbl_leg_oto_vendor.id_oto_vendor = tb2.id_oto_vendor AND tb2.mandatory = \'Mandatory\'',
            'left'
        );

        $this->db->where('tbl_leg_oto_vendor.na', 'n');
        $this->db->where('tbl_leg_oto_vendor.del', 'n');

        if (isset($id_jenis_vendor))
            $this->db->where('tbl_leg_oto_vendor.id_jenis_vendor', $id_jenis_vendor);

        $query = $this->db->get();

        $result = $query->row();

        return $result;
    }

    public function get_total_spk_vendor_uploaded($params = array())
    {
        $id_spk = isset($params['id_spk']) ? $params['id_spk'] : null;
        $id_jenis_vendor = isset($params['id_jenis_vendor']) ? $params['id_jenis_vendor'] : null;

        $this->db->select('COUNT(*) as totalven');
        $this->db->select('COUNT(ov.id_oto_vendor) as total_ven_mandatory');
        $this->db->from('tbl_leg_upload_vendor');
        $this->db->join(
            'tbl_leg_oto_vendor ov',
            'tbl_leg_upload_vendor.id_oto_vendor = ov.id_oto_vendor AND tbl_leg_upload_vendor.mandatory = \'Mandatory\'',
            'left'
        );

        $this->db->where('tbl_leg_upload_vendor.na', 'n');
        $this->db->where('tbl_leg_upload_vendor.del', 'n');

        if (isset($id_spk))
            $this->db->where('tbl_leg_upload_vendor.id_spk', $id_spk);

        if (isset($id_jenis_vendor))
            $this->db->where('tbl_leg_upload_vendor.id_jenis_vendor', $id_jenis_vendor);

        $query = $this->db->get();

        $result = $query->row();

        return $result;
    }

    public function get_komentar($params = array())
    {
        $all = isset($params['all']) ? $params['all'] : false;
        $list = isset($params['list']) ? $params['list'] : false;
        $id_spk = isset($params['id_spk']) ? $params['id_spk'] : null;

        $this->db->select('tbl_leg_komentar.*');
        $this->db->select('tbl_karyawan.gambar,tbl_karyawan.gender,tbl_karyawan.nik,tbl_karyawan.nama');
        $this->db->from('tbl_leg_komentar');
        $this->db->join('tbl_karyawan', 'tbl_leg_komentar.user_input = tbl_karyawan.nik', 'left');

        if (isset($id_spk))
            $this->db->where('tbl_leg_komentar.id_spk', $id_spk);

        $this->db->order_by('tbl_leg_komentar.id_komentar asc');

        $query = $this->db->get();

        if (isset($params['single_row']) && $params['single_row'])
            $result = $query->row();
        else
            $result = $query->result();

        return $result;
    }
	
}