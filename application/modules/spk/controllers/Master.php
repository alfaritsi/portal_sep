<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @application  : SPK Master - Controller
 * @author     : Octe Reviyanto Nugroho
 * @contributor  :
 * 1. <insert your fullname> (<insert your nik>) <insert the date>
 * <insert what you have modified>
 * 2. <insert your fullname> (<insert your nik>) <insert the date>
 * <insert what you have modified>
 * etc.
 */
class Master extends MX_Controller
{
    private $data;

    public function __construct()
    {
        parent::__construct();
        $this->data['module'] = "SPK Master";
        $this->data['user'] = $this->general->get_data_user();
        $this->load->model('dmaster');
		$this->load->model('folder/dsettingfolder');
    }

    public function roles()
    {
        $this->general->check_access();

        $this->data['title'] = "Master Role";

        $this->general->connectDbPortal();

        $this->data['list'] = $this->dmaster->get_divisi(array('list' => true));

        $this->data['list'] = $this->general->generate_encrypt_json($this->data['list'], array('id_divisi'));

        $this->load->view('master/role', $this->data);
    }

    public function jenisspk($id = null, $action = null)
    {
        $this->general->check_access('spk/master/jenisspk');

        $this->general->connectDbPortal();

        if (isset($id) && isset($action)) {
            $jenisspk = $this->general->generate_encrypt_json(
                $this->dmaster->get_jenis_spk(
                    array(
                        'id_jenis_spk' => $this->generate->kirana_decrypt($id),
                        'single_row' => true
                    )
                ),
                array('id_jenis_spk')
            );

            if (isset($jenisspk)) {
                $this->data['jenis_spk'] = $jenisspk;

                switch ($action) {
                    case "templates":
                        $this->data['title'] = "Master Jenis SPK - Template";

                        $templates = $this->general->generate_encrypt_json(
                            $jenisspk->templates,
                            array(
                                'id_oto_jenis'
                            )
                        );

                        $this->data['list'] = $templates;
                        break;
                    default:
                        redirect('spk/master/jenisspk');
                        break;
                }

                $this->load->view('master/oto_jenis_spk', $this->data);
            } else
                redirect('spk/master/jenisspk');
        } else {
            $this->data['title'] = "Master Jenis SPK";

            $this->data['list'] = $this->general->generate_encrypt_json(
                $this->dmaster->get_jenis_spk(
                    array(
                        'list' => true
                    )
                ),
                array('id_jenis_spk')
            );
            $this->load->view('master/jenis_spk', $this->data);
        }
    }

    public function jenisvendor($id = null, $action = null)
    {
        $this->general->check_access('spk/master/jenisvendor');

        $this->general->connectDbPortal();

        if (isset($id) && isset($action)) {
            $jenisvendor = $this->general->generate_encrypt_json(
                $this->dmaster->get_jenis_vendor(
                    array(
                        'id_jenis_vendor' => $this->generate->kirana_decrypt($id),
                        'single_row' => true
                    )
                ),
                array('id_jenis_vendor')
            );

            if (isset($jenisvendor)) {
                $this->data['jenisvendor'] = $jenisvendor;

                switch ($action) {
                    case "dokumen":
                        $this->data['title'] = "Master Jenis Vendor - Dokumen";

                        $dokumens = $this->general->generate_encrypt_json(
                            $jenisvendor->dokumens,
                            array(
                                'id_oto_vendor'
                            )
                        );

                        $this->data['list'] = $dokumens;
                        break;
                    default:
                        redirect('spk/master/jenisvendor');
                        break;
                }

                $this->load->view('master/oto_vendor', $this->data);
            } else
                redirect('spk/master/jenisspk');
        } else {
            $this->data['title'] = "Master Jenis Vendor";

            $this->data['list'] = $this->general->generate_encrypt_json(
                $this->dmaster->get_jenis_vendor(
                    array(
                        'list' => true
                    )
                ),
                array('id_jenis_vendor')
            );
            $this->load->view('master/jenis_vendor', $this->data);
        }
    }

    public function namaspk()
    {
        $this->general->check_access();

        $this->general->connectDbPortal();

        $this->data['title'] = "Master Nama SPK";

        $this->data['list'] = $this->general->generate_encrypt_json(
            $this->dmaster->get_nama_spk(array('list' => true)),
            array('id_nama_spk')
        );
        $this->data['jenis_spk'] = $this->general->generate_encrypt_json(
            $this->dmaster->get_jenis_spk(),
            array('id_jenis_spk')
        );
        $this->data['divisis'] = $this->general->generate_encrypt_json(
            $this->dmaster->get_divisi(),
            array('id_divisi')
        );

        $this->load->view('master/nama_spk', $this->data);

    }
    public function kualifikasi()
    {
        $this->general->check_access();

        $this->general->connectDbPortal();

        $this->data['title'] = "Master Kualifikasi SPK";

        $this->data['list'] = $this->general->generate_encrypt_json(
            $this->dmaster->get_kualifikasi_spk(array('list' => true)),
            array('id_kualifikasi_spk')
        );
        $this->data['jenis_spk'] = $this->general->generate_encrypt_json(
            $this->dmaster->get_jenis_spk(),
            array('id_jenis_spk')
        );
	
        $this->load->view('master/kualifikasi_spk', $this->data);

    }
	//add lha 17.02.2020
	public function matrix($param=NULL){
		//====must be initiate in every view function====/
	    $this->general->check_access();
	    $data['generate']   = $this->generate; 
	    $data['module']     = $this->router->fetch_module();
        $data['user']       = $this->general->get_data_user();
	    //===============================================/

		$data['title']    	 = "Upload Dokumen Vendor";
		$data['title_form']  = "Upload Dokumen Vendor";
		// $data['port'] 	 	 = $this->get_port('array');
		// $data['plant']       = $this->dmaster->get_master_plant();
		// $data['pol'] 	 	 = $this->get_pol('array', NULL, NULL, NULL);
		$this->load->view("master/matrix", $data);	
	}
	public function get2($param = NULL, $param2 = NULL) {
		switch ($param) {
			case 'plant': 
				$plant = (isset($_POST['plant']) ? $_POST['plant'] : NULL);
				$this->get_plant(NULL, $plant);
				break;   
			case 'jenis_vendor':
				$id_jenis_vendor = (isset($_POST['id_jenis_vendor']) ? $_POST['id_jenis_vendor'] : NULL);
				$this->get_jenis_vendor2(NULL, $id_jenis_vendor);
				break;
			case 'jenis_spk':  
				$id_jenis_spk = (isset($_POST['id_jenis_spk']) ? $_POST['id_jenis_spk'] : NULL);
				$this->get_jenis_spk2(NULL, $id_jenis_spk);
				break;
			case 'kualifikasi':    
				$id_kualifikasi_spk = (isset($_POST['id_kualifikasi_spk']) ? $_POST['id_kualifikasi_spk'] : NULL);
				$id_jenis_spk 		= (isset($_POST['id_jenis_spk']) ? $_POST['id_jenis_spk'] : NULL);
				$lifnr	= (isset($_POST['lifnr']) ? $_POST['lifnr'] : NULL);
				$vendor	= (isset($_POST['vendor']) ? $_POST['vendor'] : NULL);
				if(isset($_POST['kualifikasi'])){
					$kualifikasi	= array();
					foreach ($_POST['kualifikasi'] as $dt) {
						array_push($kualifikasi, $dt);
					}
				}else{
					$kualifikasi  = NULL;
				}
				
				$this->get_kualifikasi_spk2(NULL, $id_kualifikasi_spk, $id_jenis_spk, $kualifikasi, $lifnr, $vendor);
				break;
			case 'dokumen_vendor':
				$id_jenis_vendor	= (isset($_POST['id_jenis_vendor']) ? $_POST['id_jenis_vendor'] : NULL);
				$lifnr	= (isset($_POST['lifnr']) ? $_POST['lifnr'] : NULL);
				$vendor	= (isset($_POST['vendor']) ? $_POST['vendor'] : NULL);
				$this->get_dokumen_vendor(NULL, $id_jenis_vendor, $lifnr, $vendor);
				break;
			case 'matrix':
				$lifnr    = (isset($_POST['lifnr']) ? $this->generate->kirana_decrypt($_POST['lifnr']) : NULL);
				$ekorg    = (isset($_POST['ekorg']) ? $_POST['ekorg'] : NULL);
				
				if(isset($_POST['plant'])){
					$plant	= array();
					foreach ($_POST['plant'] as $dt) {
						array_push($plant, $dt);
					}
				}else{
					$plant  = NULL;
				}
				if(isset($_POST['status_pkp'])){
					$status_pkp	= array();
					foreach ($_POST['status_pkp'] as $dt) {
						array_push($status_pkp, $dt);
					}
				}else{
					$status_pkp  = NULL;
				}
				if(isset($_POST['status'])){
					$status	= array();
					foreach ($_POST['status'] as $dt) {
						array_push($status, $dt);
					}
				}else{
					$status  = NULL;
				}
				if(isset($_POST['jenis_vendor'])){
					$jenis_vendor	= array();
					foreach ($_POST['jenis_vendor'] as $dt) {
						array_push($jenis_vendor, $dt);
					}
				}else{
					$jenis_vendor  = NULL;
				}
				
				if($param2=='bom'){
					header('Content-Type: application/json');
					$return = $this->dmaster->get_data_matrix_bom('open', $lifnr, $ekorg, $plant, $status_pkp, $jenis_vendor, $status);
					echo $return;
					break;
				}else if($param2=='auto'){
					if (isset($_GET['q'])) {
						$data      = $this->dmaster->get_data_matrix('open', NULL, NULL, strtoupper($_GET['q']));
						$data_json = array(
							"total_count"        => count($data),
							"incomplete_results" => false,
							"items"              => $data
						); 

						$return = json_encode($data_json);
						$return = $this->general->jsonify($data_json);

						echo $return;
						break; 
					}
				}else{
					$this->get_matrix(NULL, $lifnr, $ekorg);
					break;
				}
			default:
				$return = array('sts' => 'NotOK', 'msg' => 'Link tidak ditemukan');
				echo json_encode($return);
				break;
		}
	}
	public function save2($param = NULL) {
		switch ($param) {
			case 'folder':
				$this->save_folder($param);
				break;
			case 'file':
				$this->save_file($param);
				break;
			default:
				$return = array('sts' => 'NotOK', 'msg' => 'Link tidak ditemukan');
				echo json_encode($return);
				break;
		} 
	}
	private function save_folder($param) {
		$datetime 	= date("Y-m-d H:i:s");
		$nama		= $_POST['lifnr']." - ".strtoupper($_POST['vendor']);
		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();
		$ck_folder 		= $this->dmaster->get_data_folder(NULL, 317, $nama);	//set untuk go-live berbeda xxx
		if (count($ck_folder) == 0){ 
			$data_row = array(
				"parent_folder"  => 317,	//set untuk go-live berbeda xxx
				"nama"			 => $nama,
			);
			$data_row = $this->dgeneral->basic_column("insert", $data_row);
			$this->dgeneral->insert("tbl_folder", $data_row);
		}
		if ($this->dgeneral->status_transaction() === false) {
			$this->dgeneral->rollback_transaction();
			$msg = "Periksa kembali data yang dimasukkan";
			$sts = "NotOK";
		} else {
			$this->dgeneral->commit_transaction();
			$msg = "Data berhasil ditambahkan";
			$sts = "OK";
		}
		$this->general->closeDb();  
		$return = array('sts' => $sts, 'msg' => $msg);
		echo json_encode($return);
	}
	
	private function save_file($param) {
		$datetime   = date("Y-m-d H:i:s");
		$divisi 	= base64_decode($this->session->userdata('-id_divisi-'));
		$department = base64_decode($this->session->userdata('-id_departemen-'));
		$id_level 	= base64_decode($this->session->userdata('-id_level-'));
		$id_folder	= $_POST['id_folder'];
		$id_file	= $_POST['id_file'];
		$name		= $_POST['name'];
		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();
		
		$jml_file = count($_FILES['file']['name']);
		if ($jml_file > 1) {
			$this->dgeneral->rollback_transaction();
			$msg    = "You can only upload maximum 1 files";
			$sts    = "NotOK";
			$return = array('sts' => $sts, 'msg' => $msg);
			echo json_encode($return);
			exit();
		}
		//upload file 
		if($_FILES['file']['name'][0]!=''){
			$config['upload_path']   = str_replace('/spk/','/folder/',$this->general->kirana_file_path($this->router->fetch_module()) . '/vendor');
			$config['allowed_types'] = 'pdf|doc|docx|xls|xlsx';
			$config['max_size']      = 0;
			
			$newname	= array(str_replace(' ','_',$name));			
			$file		= $this->general->upload_files($_FILES['file'], str_replace('_-_','-',$newname), $config);
			$nama_file	= str_replace('_',' ',$newname[0]);
			$url_file	= str_replace("assets/", "", $file[0]['url']);
			if($file === NULL){
				$msg        = "Upload files error";
				$sts        = "NotOK";
				$return     = array('sts' => $sts, 'msg' => $msg);
				echo json_encode($return);
				exit();
			}
		}
		if (!empty($id_file)) {
			$data_row = array(
				'id_folder'     	=> $id_folder,
				'nama'     		    => $nama_file,
				'ukuran'        	=> $file[0]['size'],
				'tipe'          	=> pathinfo($url_file, PATHINFO_EXTENSION),
				'link'      		=> $url_file,
				'login_buat'    	=> base64_decode($this->session->userdata("-id_user-")),
				'tanggal_buat'  	=> $datetime,
				'login_edit'    	=> base64_decode($this->session->userdata("-id_user-")),
				'tanggal_edit'  	=> $datetime,
			);
			$this->dgeneral->update("tbl_file", $data_row, array(
				array(
					'kolom' => 'id_file',
					'value' => $id_file
				)
			));
		} else {
			$data_row = array(
				'id_folder'     	=> $id_folder,
				'nama'     		    => $nama_file,
				'ukuran'        	=> $file[0]['size'],
				'tipe'          	=> pathinfo($url_file, PATHINFO_EXTENSION),
				'link'      		=> $url_file,
				'divisi_akses'    	=> NULL, 
				'departemen_akses'  => NULL,
				'divisi_write'    	=> NULL,
				'departemen_write'  => NULL,
				'level_akses'       => NULL,
				'level_write'       => NULL,
				'login_buat'    	=> base64_decode($this->session->userdata("-id_user-")),
				'tanggal_buat'  	=> $datetime,
				'login_edit'    	=> base64_decode($this->session->userdata("-id_user-")),
				'tanggal_edit'  	=> $datetime,
				'na'				=> 'n',
				'del'				=> 'n',
				'lihat'				=> 'y'  
			);
			$this->dgeneral->insert("tbl_file", $data_row);  
			$this->dsettingfolder->update_folder_path(null);
		}
		if ($this->dgeneral->status_transaction() === false) {
			$this->dgeneral->rollback_transaction();
			$msg = "Periksa kembali data yang dimasukkan";
			$sts = "NotOK";
		} else {
			$this->dgeneral->commit_transaction();
			$msg = "Data berhasil ditambahkan";
			$sts = "OK";
		}
		$this->general->closeDb();
		$return = array('sts' => $sts, 'msg' => $msg);
		echo json_encode($return);
	}
	

    public function privileges()
    {
        $this->general->check_access();

        $this->general->connectDbPortal();

        $filters = $this->input->post();

        $this->data['title'] = "Manage Privileges";

        $id_divisi = (isset($filters['id_divisi']) && !empty($filters['id_divisi'])) ? $filters['id_divisi'] : null;

        $this->data['id_divisi'] = $id_divisi;

        if (isset($id_divisi))
            $id_divisi = $this->generate->kirana_decrypt($id_divisi);

        $this->data['list'] = $this->general->generate_encrypt_json(
            $this->dmaster->get_privileges(
                array(
                    'id_divisi' => $id_divisi,
                    'list' => true
                )
            ),
            array('id_user')
        );
        $this->data['leg_divisis'] = $this->general->generate_encrypt_json(
            $this->dmaster->get_divisi(),
            array('id_divisi')
        );
        $this->data['divisis'] = $this->general->generate_encrypt_json(
            $this->dmaster->get_master_divisi(),
            array('id_divisi')
        );

        $this->load->view('master/privileges', $this->data);

    }

    public function get($param)
    {
        $data = $_POST;
        switch ($param) {
            case 'divisi':
                $return = $this->get_divisi($data);
                break;
            case 'jenisspk':
                $return = $this->get_jenis_spk($data);
                break;
            case 'otojenisspk':
                $return = $this->get_oto_jenis_spk($data);
                break;
            case 'namaspk':
                $return = $this->get_nama_spk($data);
                break;
            case 'kualifikasi':
                $return = $this->get_kualifikasi($data);
                break;							   
            case 'privileges':
                $return = $this->get_privileges($data);
                break;
            case 'jenisvendor':
                $return = $this->get_jenis_vendor($data);
                break;
            case 'otovendor':
                $return = $this->get_oto_vendor($data);
                break;
            default:
                $return = array('sts' => 'NotOK', 'msg' => 'Link tidak ditemukan');
                break;
        }
        echo json_encode($return);
    }

    public function save($param)
    {
        $data = $_POST;

        switch ($param) {
            case 'divisi':
                $return = $this->save_divisi($data);
                break;
            case 'jenisspk':
                $return = $this->save_jenis_spk($data);
                break;
            case 'otojenisspk':
                $return = $this->save_oto_jenis_spk($data);
                break;
            case 'namaspk':
                $return = $this->save_nama_spk($data);
                break;
            case 'kualifikasi':
                $return = $this->save_kualifikasi($data);
                break;
            case 'matrix':
                $return = $this->save_matrix($data);
                break;
            case 'privileges':
                $return = $this->save_privileges($data);
                break;
            case 'jenisvendor':
                $return = $this->save_jenis_vendor($data);
                break;
            case 'otovendor':
                $return = $this->save_oto_vendor($data);
                break;
            default:
                $return = array('sts' => 'NotOK', 'msg' => 'Link tidak ditemukan');
                break;
        }

        echo json_encode($return);
    }

    public function set($param)
    {
        $data = $_POST;

        switch ($param) {
            case 'divisi':
                $return = $this->set_divisi($data);
                break;
            case 'jenisspk':
                $return = $this->set_jenis_spk($data);
                break;
            case 'otojenisspk':
                $return = $this->set_oto_jenis_spk($data);
                break;
            case 'namaspk':
                $return = $this->set_nama_spk($data);
                break;
            case 'kualifikasi':
                $return = $this->set_kualifikasi($data);
                break;
            case 'jenisvendor':
                $return = $this->set_jenis_vendor($data);
                break;
            case 'otovendor':
                $return = $this->set_oto_vendor($data);
                break;
            default:
                $return = array('sts' => 'NotOK', 'msg' => 'Link tidak ditemukan');
                break;
        }

        echo json_encode($return);
    }

    private function get_divisi($data)
    {
        if (isset($data['id'])) {
            $id = $this->generate->kirana_decrypt($data['id']);

            $this->general->connectDbPortal();

            $result = $this->dmaster->get_divisi(array(
                'single_row' => true,
                'list' => true,
                'id_divisi' => $id
            ));

            return array('sts' => 'OK', 'data' => $result);
        } else {
            return array('sts' => 'NotOK', 'msg' => 'ID tidak ditemukan');
        }
    }

    private function get_jenis_spk($data)
    {
        if (isset($data['id'])) {
            $id = $this->generate->kirana_decrypt($data['id']);

            $this->general->connectDbPortal();

            $result = $this->dmaster->get_jenis_spk(array(
                'single_row' => true,
                'list' => true,
                'id_jenis_spk' => $id
            ));

            return array('sts' => 'OK', 'data' => $result);
        } else {
            return array('sts' => 'NotOK', 'msg' => 'ID tidak ditemukan');
        }
    }

    private function get_jenis_vendor($data)
    {
        if (isset($data['id'])) {
            $id = $this->generate->kirana_decrypt($data['id']);

            $this->general->connectDbPortal();

            $result = $this->dmaster->get_jenis_vendor(array(
                'single_row' => true,
                'list' => true,
                'id_jenis_vendor' => $id
            ));

            return array('sts' => 'OK', 'data' => $result);
        } else {
            return array('sts' => 'NotOK', 'msg' => 'ID tidak ditemukan');
        }
    }

    private function get_nama_spk($data)
    {
        if (isset($data['id'])) {
            $id = $this->generate->kirana_decrypt($data['id']);

            $this->general->connectDbPortal();

            $result = $this->dmaster->get_nama_spk(array(
                'single_row' => true,
                'list' => true,
                'id_nama_spk' => $id
            ));

            $result = $this->general->generate_encrypt_json(
                $result,
                array('id_nama_spk', 'id_jenis_spk')
            );

            $result->divisis = $this->general->generate_encrypt_json(
                $result->divisis,
                array('id_nama_spk', 'id_oto_divisi', 'id_divisi')
            );

            return array('sts' => 'OK', 'data' => $result);
        } else {
            return array('sts' => 'NotOK', 'msg' => 'ID tidak ditemukan');
        }
    }
    private function get_kualifikasi($data)
    {
        if (isset($data['id'])) {
            $id = $this->generate->kirana_decrypt($data['id']);

            $this->general->connectDbPortal();

            $result = $this->dmaster->get_kualifikasi_spk(array(
                'single_row' => true,
                'list' => true,
                'id_kualifikasi_spk' => $id
            ));

            $result = $this->general->generate_encrypt_json(
                $result,
                array('id_kualifikasi_spk', 'id_jenis_spk')
            );

            return array('sts' => 'OK', 'data' => $result);
        } else {
            return array('sts' => 'NotOK', 'msg' => 'ID tidak ditemukan');
        }
    }
    private function get_privileges($data)
    {
        if (isset($data['id'])) {
            $id = $this->generate->kirana_decrypt($data['id']);
            $id_divisi = (isset($data['id_divisi']) and !empty($data['id_divisi'])) ?
                $this->generate->kirana_decrypt($data['id_divisi']) :
                null;

            $this->general->connectDbPortal();

            $result = $this->dmaster->get_privileges(array(
                'single_row' => true,
                'list' => true,
                'id_user' => $id,
                'id_divisi' => $id_divisi
            ));

            $result = $this->general->generate_encrypt_json(
                $result,
                array('id_user', 'leg_level_id')
            );

            return array('sts' => 'OK', 'data' => $result);
        } else {
            return array('sts' => 'NotOK', 'msg' => 'ID tidak ditemukan');
        }
    }

    private function get_oto_jenis_spk($data)
    {
        if (isset($data['id'])) {
            $id = $this->generate->kirana_decrypt($data['id']);

            $this->general->connectDbPortal();

            $result = $this->dmaster->get_oto_jenis_spk(array(
                'single_row' => true,
                'list' => true,
                'id_oto_jenis' => $id
            ));

            return array('sts' => 'OK', 'data' => $result);
        } else {
            return array('sts' => 'NotOK', 'msg' => 'ID tidak ditemukan');
        }
    }

    private function get_oto_vendor($data)
    {
        if (isset($data['id'])) {
            $id = $this->generate->kirana_decrypt($data['id']);

            $this->general->connectDbPortal();

            $result = $this->dmaster->get_oto_vendor(array(
                'single_row' => true,
                'list' => true,
                'id_oto_vendor' => $id
            ));

            return array('sts' => 'OK', 'data' => $result);
        } else {
            return array('sts' => 'NotOK', 'msg' => 'ID tidak ditemukan');
        }
    }

    private function save_divisi($data)
    {
        $this->general->connectDbPortal();

        $this->db->query("SET ANSI_NULLS ON");
        $this->db->query("SET ANSI_WARNINGS ON");

        $this->dgeneral->begin_transaction();

        $data_row = array(
            'nama_divisi' => $data['nama_divisi']
        );

        if (isset($data['id_divisi']) && !empty($data['id_divisi'])) {
            $id = $this->generate->kirana_decrypt($data['id_divisi']);
            unset($data['id_divisi']);

            $data_row = $this->dgeneral->basic_column('update', $data_row);

            $result = $this->dgeneral->update('tbl_leg_divisi', $data_row, array(
                array(
                    'kolom' => 'id_divisi',
                    'value' => $id
                )
            ));

        } else {
            unset($data['id_divisi']);
            $data_row = $this->dgeneral->basic_column('insert', $data_row);

            $result = $this->dgeneral->insert('tbl_leg_divisi', $data_row);
        }

        if ($this->dgeneral->status_transaction() === FALSE) {
            $this->dgeneral->rollback_transaction();
            $msg = "Periksa kembali data yang dimasukkan";
            $sts = "NotOK";
        } else {
            $this->dgeneral->commit_transaction();
            $msg = "Data berhasil ditambahkan";
            $sts = "OK";
        }

        $return = array('sts' => $sts, 'msg' => $msg);

        return $return;
    }

    private function save_jenis_spk($data)
    {
        $this->general->connectDbPortal();

        $this->db->query("SET ANSI_NULLS ON");
        $this->db->query("SET ANSI_WARNINGS ON");

        $this->dgeneral->begin_transaction();

        $data_row = array(
            'jenis_spk' => $data['jenis_spk']
        );

        if (isset($data['id_jenis_spk']) && !empty($data['id_jenis_spk'])) {
            $id = $this->generate->kirana_decrypt($data['id_jenis_spk']);
            unset($data['id_jenis_spk']);

            $data_row = $this->dgeneral->basic_column('update', $data_row);

            $result = $this->dgeneral->update('tbl_leg_jenis_spk', $data_row, array(
                array(
                    'kolom' => 'id_jenis_spk',
                    'value' => $id
                )
            ));

        } else {
            unset($data['id_jenis_spk']);
            $data_row = $this->dgeneral->basic_column('insert', $data_row);

            $result = $this->dgeneral->insert('tbl_leg_jenis_spk', $data_row);
        }

        if ($this->dgeneral->status_transaction() === FALSE) {
            $this->dgeneral->rollback_transaction();
            $msg = "Periksa kembali data yang dimasukkan";
            $sts = "NotOK";
        } else {
            $this->dgeneral->commit_transaction();
            $msg = "Data berhasil ditambahkan";
            $sts = "OK";
        }

        $return = array('sts' => $sts, 'msg' => $msg);

        return $return;
    }

    private function save_jenis_vendor($data)
    {
        $this->general->connectDbPortal();

        $this->db->query("SET ANSI_NULLS ON");
        $this->db->query("SET ANSI_WARNINGS ON");

        $this->dgeneral->begin_transaction();

        $data_row = array(
            'jenis_vendor' => $data['jenis_vendor']
        );

        if (isset($data['id_jenis_vendor']) && !empty($data['id_jenis_vendor'])) {
            $id = $this->generate->kirana_decrypt($data['id_jenis_vendor']);

            $data_row = $this->dgeneral->basic_column('update', $data_row);

            $result = $this->dgeneral->update('tbl_leg_jenis_vendor', $data_row, array(
                array(
                    'kolom' => 'id_jenis_vendor',
                    'value' => $id
                )
            ));

        } else {
            $data_row = $this->dgeneral->basic_column('insert', $data_row);

            $result = $this->dgeneral->insert('tbl_leg_jenis_vendor', $data_row);
        }

        if ($this->dgeneral->status_transaction() === FALSE) {
            $this->dgeneral->rollback_transaction();
            $msg = "Periksa kembali data yang dimasukkan";
            $sts = "NotOK";
        } else {
            $this->dgeneral->commit_transaction();
            $msg = "Data berhasil ditambahkan";
            $sts = "OK";
        }

        $return = array('sts' => $sts, 'msg' => $msg);

        return $return;
    }

    private function save_privileges($data)
    {
        $this->general->connectDbPortal();

        $this->db->query("SET ANSI_NULLS ON");
        $this->db->query("SET ANSI_WARNINGS ON");

        $this->dgeneral->begin_transaction();

        $leg_level_id = (isset($data['leg_level_id']) && !empty($data['leg_level_id'])) ?
            $this->generate->kirana_decrypt($data['leg_level_id']) :
            null;

        $data_row = array(
            'leg_level_id' => $leg_level_id
        );

        if (isset($data['id_user']) && !empty($data['id_user'])) {
            $id = $this->generate->kirana_decrypt($data['id_user']);

            $data_row = $this->dgeneral->basic_column('update', $data_row);

            $result = $this->dgeneral->update('tbl_user', $data_row, array(
                array(
                    'kolom' => 'id_user',
                    'value' => $id
                )
            ));

        }

        if ($this->dgeneral->status_transaction() === FALSE) {
            $this->dgeneral->rollback_transaction();
            $msg = "Periksa kembali data yang dimasukkan";
            $sts = "NotOK";
        } else {
            $this->dgeneral->commit_transaction();
            $msg = "Data berhasil ditambahkan";
            $sts = "OK";
        }

        $return = array('sts' => $sts, 'msg' => $msg);

        return $return;
    }

    private function save_nama_spk($data)
    {
        $this->general->connectDbPortal();

        $this->db->query("SET ANSI_NULLS ON");
        $this->db->query("SET ANSI_WARNINGS ON");

        $this->dgeneral->begin_transaction();

        $data_row = array(
            'id_jenis_spk' => $this->generate->kirana_decrypt($data['id_jenis_spk']),
            'nama_spk' => $data['nama_spk']
        );

        $divisis = $data['divisis'];
        foreach ($divisis as $i => $divisi)
            $divisis[$i] = $this->generate->kirana_decrypt($divisi);

        if (isset($data['id_nama_spk']) && !empty($data['id_nama_spk'])) {
            $id = $this->generate->kirana_decrypt($data['id_nama_spk']);
            unset($data['id_nama_spk']);

            $data_row = $this->dgeneral->basic_column('update', $data_row);

            $result = $this->dgeneral->update('tbl_leg_nama_spk', $data_row, array(
                array(
                    'kolom' => 'id_nama_spk',
                    'value' => $id
                )
            ));

        } else {
            unset($data['id_jenis_spk']);
            $data_row = $this->dgeneral->basic_column('insert', $data_row);

            $result = $this->dgeneral->insert('tbl_leg_nama_spk', $data_row);

            $id = $this->db->insert_id();
        }

        $divisi_update = $this->dgeneral->basic_column('delete');

        $this->db->where_not_in('id_divisi', $divisis);
        $this->db->where('id_nama_spk', $id);
        $this->db->where('na', 'n');
        $this->db->update('tbl_leg_oto_divisi', $divisi_update);

        foreach ($divisis as $id_divisi) {

            $divisi = $this->dmaster->get_oto_divisi(
                array(
                    'id_divisi' => $id_divisi,
                    'id_nama_spk' => $id,
                    'single_row' => true,
                    'all' => true
                )
            );

            if (isset($divisi)) {
                $divisi_update = $this->dgeneral->basic_column('activate_all');

                $this->dgeneral->update('tbl_leg_oto_divisi', $divisi_update, array(
                    array(
                        'kolom' => 'id_oto_divisi',
                        'value' => $divisi->id_oto_divisi
                    )
                ));
            } else {
                $divisi_insert = $this->dgeneral->basic_column('insert', array(
                    'id_divisi' => $id_divisi,
                    'id_nama_spk' => $id
                ));

                $this->dgeneral->insert('tbl_leg_oto_divisi', $divisi_insert);
            }
        }

        if ($this->dgeneral->status_transaction() === FALSE) {
            $this->dgeneral->rollback_transaction();
            $msg = "Periksa kembali data yang dimasukkan";
            $sts = "NotOK";
        } else {
            $this->dgeneral->commit_transaction();
            $msg = "Data berhasil ditambahkan";
            $sts = "OK";
        }

        $return = array('sts' => $sts, 'msg' => $msg);

        return $return;
    }
	
    private function save_kualifikasi($data)
    {
        $this->general->connectDbPortal();

        $this->db->query("SET ANSI_NULLS ON");
        $this->db->query("SET ANSI_WARNINGS ON");

        $this->dgeneral->begin_transaction();

        if (isset($data['id_kualifikasi_spk']) && !empty($data['id_kualifikasi_spk'])) {
            $id = $this->generate->kirana_decrypt($data['id_kualifikasi_spk']);
            unset($data['id_kualifikasi_spk']);

			$data_row = array(
				'id_jenis_spk' => $this->generate->kirana_decrypt($data['id_jenis_spk']),
				'kualifikasi_spk' => $data['kualifikasi_spk']
			);
            $data_row = $this->dgeneral->basic_column('update', $data_row);
            $result = $this->dgeneral->update('tbl_leg_kualifikasi_spk', $data_row, array(
                array(
                    'kolom' => 'id_kualifikasi_spk',
                    'value' => $id
                )
            ));

        } else {
			$kualifikasi_spk = $this->dmaster->get_data_kualifikasi_spk("open",NULL,$this->generate->kirana_decrypt($data['id_jenis_spk']),NULL,NULL,NULL,$data['kualifikasi_spk']);
			if (count($kualifikasi_spk) > 0) {
				$msg    = "Duplicate data, periksa kembali data yang dimasukkan";
				$sts    = "NotOK";
				$return = array('sts' => $sts, 'msg' => $msg);
				echo json_encode($return);
				exit();
			}
			
			$data_row = array(
				'id_jenis_spk' => $this->generate->kirana_decrypt($data['id_jenis_spk']),
				'kualifikasi_spk' => $data['kualifikasi_spk']
			);
            $data_row = $this->dgeneral->basic_column('insert', $data_row);
            $result = $this->dgeneral->insert('tbl_leg_kualifikasi_spk', $data_row);
 
            $id = $this->db->insert_id();
        }

        $this->db->where('id_kualifikasi_spk', $id);
        $this->db->where('na', 'n');

        if ($this->dgeneral->status_transaction() === FALSE) {
            $this->dgeneral->rollback_transaction();
            $msg = "Periksa kembali data yang dimasukkan";
            $sts = "NotOK";
        } else {
            $this->dgeneral->commit_transaction();
            $msg = "Data berhasil ditambahkan";
            $sts = "OK";
        }

        $return = array('sts' => $sts, 'msg' => $msg);

        return $return;
    }
	
	private function save_matrix($param) {
		$datetime		= date("Y-m-d H:i:s");
		$ekorg 			= (isset($_POST['ekorg']) ? $_POST['ekorg'] : NULL);
		$lifnr 			= (isset($_POST['lifnr']) ? $_POST['lifnr'] : NULL);
		$id_jenis_vendor= (isset($_POST['id_jenis_vendor']) ? $_POST['id_jenis_vendor'] : NULL);
		$id_jenis_spk	= (isset($_POST['id_jenis_spk']) ? $_POST['id_jenis_spk'] : NULL);
		$kualifikasi 	= (isset($_POST['kualifikasi']) ? implode(",", $_POST['kualifikasi']) : NULL);
		
		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();
		
		$matrix = $this->dmaster->ck_data_matrix(NULL, $lifnr, $ekorg);
		if (count($matrix) != 0){
			$data_row = array(
				"id_jenis_vendor"  	=> $id_jenis_vendor,
				"id_jenis_spk"  	=> $id_jenis_spk,
				"kualifikasi"	 	=> $kualifikasi
			);
			$data_row = $this->dgeneral->basic_column("update", $data_row);
			$this->dgeneral->update("tbl_leg_zdmvendor_matrix", $data_row, array(
				array(
					'kolom' => 'lifnr',
					'value' => $lifnr
				), 
				array( 
					'kolom' => 'ekorg', 
					'value' => $ekorg 
				) 
			));
		}else{  
			$data_row = array(
				"lifnr" 		 	=> $lifnr,
				"ekorg" 		 	=> $ekorg,
				"id_jenis_vendor"  	=> $id_jenis_vendor,
				"id_jenis_spk"  	=> $id_jenis_spk,
				"kualifikasi"	 	=> $kualifikasi, 
				"login_edit"	 	=> base64_decode($this->session->userdata("-id_user-")), 
				"tanggal_edit"	 	=> date("Y-m-d H:i:s")
			);
			$data_row = $this->dgeneral->basic_column("insert", $data_row);
			$this->dgeneral->insert("tbl_leg_zdmvendor_matrix", $data_row);
		}

		if ($this->dgeneral->status_transaction() === false) {
			$this->dgeneral->rollback_transaction();
			$msg = "Periksa kembali data yang dimasukkan";
			$sts = "NotOK";
		} else {
			$this->dgeneral->commit_transaction();
			$msg = "Data berhasil ditambahkan";
			$sts = "OK";
		}
		$this->general->closeDb();
		$return = array('sts' => $sts, 'msg' => $msg);
		return $return;
		// echo json_encode($return);
	}
    private function save_oto_jenis_spk($data)
    {
        $this->general->connectDbPortal();

        $this->db->query("SET ANSI_NULLS ON");
        $this->db->query("SET ANSI_WARNINGS ON");

        $this->dgeneral->begin_transaction();

        $data_row = array(
            'nama_dokumen' => $data['nama_dokumen']
        );

        $gambar_old = $data['gambar_old'];
        unset($data['gambar_old']);


        $id = $this->generate->kirana_decrypt($data['id_oto_jenis']);
        unset($data['id_oto_jenis']);

        $data['id_jenis_spk'] = $this->generate->kirana_decrypt($data['id_jenis_spk']);

        if (isset($_FILES['file']) and $_FILES['file']['size'] > 0) {
            $uploaddir = KIRANA_PATH_FILE . SPK_UPLOAD_FOLDER . SPK_UPLOAD_TEMPLATE_FOLDER;
            if (!file_exists($uploaddir)) {
                mkdir($uploaddir, 0777, true);
            }
			// echo $_FILES['file']['type'];
			// exit();
            $config['upload_path'] = $uploaddir;
            $config['allowed_types'] = 'application/pdfjpeg|jpg|png|pdf|dot|doc|docx|xls|xlsx';
            $config['max_size'] = 5000;

            $filename = strtolower($data['nama_dokumen']);
            $config['file_name'] = $filename;

            $this->load->library('upload', $config);

            $upload_error = null;

            if ($this->upload->do_upload('file')) {
                $upload_data = $this->upload->data();
				echo json_encode($upload_data);
				exit();
                $data['files'] = KIRANA_PATH_FILE_FOLDER .
                    SPK_UPLOAD_FOLDER . SPK_UPLOAD_TEMPLATE_FOLDER . $upload_data['file_name'];
                $data['tipe_files'] = substr($upload_data['file_ext'], 1);
                $data['size_files'] = $upload_data['file_size'];
            } else {
                $upload_error = $this->upload->display_errors();
				
            }
        } else if (empty($gambar_old)) {
            if ($_FILES['file']['error'] != 0) {
                switch ($_FILES['file']['error']) {
                    case UPLOAD_ERR_INI_SIZE:
                        $upload_error = 'Berkas yang diunggah melebihi ukuran maksimum yang diperbolehkan.';
                        break;
                    case UPLOAD_ERR_FORM_SIZE:
                        $upload_error = 'Berkas yang diunggah melebihi ukuran maksimum yang diperbolehkan.';
                        break;
                    case UPLOAD_ERR_PARTIAL:
                        $upload_error = 'File ini hanya sebagian terunggah. Harap pilih file lain.';
                        break;
                    case UPLOAD_ERR_EXTENSION:
                        $upload_error = 'Upload berkas dihentikan oleh ekstensi. Harap pilih file lain.';
                        break;
                }
            } else
                $upload_error = "File tidak ada, harap pilih file.";
        }

        if (isset($id) && !empty($id)) {

            $data_row = $this->dgeneral->basic_column('update', $data);

            $result = $this->dgeneral->update('tbl_leg_oto_jenis_spk', $data_row, array(
                array(
                    'kolom' => 'id_oto_jenis',
                    'value' => $id
                )
            ));

        } else {
            $data_row = $this->dgeneral->basic_column('insert', $data);

            $result = $this->dgeneral->insert('tbl_leg_oto_jenis_spk', $data_row);
        }

        if (
        isset($upload_error)
        ) {
            $this->dgeneral->rollback_transaction();
            $msg = $upload_error;
            $sts = "NotOK";
            if (isset($data['files']))
                unlink(KIRANA_PATH_ASSETS . $data['files']);
        } else if ($this->dgeneral->status_transaction() === FALSE) {
            $this->dgeneral->rollback_transaction();
            $msg = "Periksa kembali data yang dimasukkan";
            $sts = "NotOK";
            if (isset($data['files']))
                unlink(KIRANA_PATH_ASSETS . $data['files']);
        } else {
            $this->dgeneral->commit_transaction();
            $msg = "Data berhasil ditambahkan";
            $sts = "OK";
        }

        $return = array('sts' => $sts, 'msg' => $msg);

        return $return;
    }

    private function save_oto_vendor($data)
    {
        $this->general->connectDbPortal();

        $this->db->query("SET ANSI_NULLS ON");
        $this->db->query("SET ANSI_WARNINGS ON");

        $this->dgeneral->begin_transaction();

        $id = $this->generate->kirana_decrypt($data['id_oto_vendor']);
        unset($data['id_oto_vendor']);

        $data['id_jenis_vendor'] = $this->generate->kirana_decrypt($data['id_jenis_vendor']);

        if (isset($id) && !empty($id)) {

            $data_row = $this->dgeneral->basic_column('update', $data);

            $result = $this->dgeneral->update('tbl_leg_oto_vendor', $data_row, array(
                array(
                    'kolom' => 'id_oto_vendor',
                    'value' => $id
                )
            ));

        } else {
            $data_row = $this->dgeneral->basic_column('insert', $data);

            $result = $this->dgeneral->insert('tbl_leg_oto_vendor', $data_row);
        }

        if ($this->dgeneral->status_transaction() === FALSE) {
            $this->dgeneral->rollback_transaction();
            $msg = "Periksa kembali data yang dimasukkan";
            $sts = "NotOK";
            if (isset($data['files']))
                unlink(KIRANA_PATH_ASSETS . $data['files']);
        } else {
            $this->dgeneral->commit_transaction();
            $msg = "Data berhasil ditambahkan";
            $sts = "OK";
        }

        $return = array('sts' => $sts, 'msg' => $msg);

        return $return;
    }

    public function delete($param)
    {
        $data = $_POST;
        switch ($param) {
            case 'divisi':
                if (isset($data['id'])) {
                    $id = $this->generate->kirana_decrypt($data['id']);

                    $this->general->connectDbPortal();
                    $this->dgeneral->begin_transaction();

                    $data_row = $this->dgeneral->basic_column('delete');

                    $this->dgeneral->update('tbl_leg_divisi', $data_row,
                        array(
                            array(
                                'kolom' => 'id_divisi',
                                'value' => $id
                            )
                        )
                    );

                    if ($this->dgeneral->status_transaction() === FALSE) {
                        $this->dgeneral->rollback_transaction();
                        $msg = "Periksa kembali data yang dimasukkan";
                        $sts = "NotOK";
                    } else {
                        $this->dgeneral->commit_transaction();
                        $msg = "Data berhasil dihapus";
                        $sts = "OK";
                    }
                    $this->general->closeDb();
                } else {
                    $sts = "NotOK";
                    $msg = "Tidak ada data yang akan di hapus.";
                }
                $return = array('sts' => $sts, 'msg' => $msg);

                break;
            case 'jenisspk':
                if (isset($data['id'])) {
                    $id = $this->generate->kirana_decrypt($data['id']);

                    $this->general->connectDbPortal();
                    $this->dgeneral->begin_transaction();

                    $data_row = $this->dgeneral->basic_column('delete');

                    $this->dgeneral->update('tbl_leg_jenis_spk', $data_row,
                        array(
                            array(
                                'kolom' => 'id_jenis_spk',
                                'value' => $id
                            )
                        )
                    );

                    if ($this->dgeneral->status_transaction() === FALSE) {
                        $this->dgeneral->rollback_transaction();
                        $msg = "Periksa kembali data yang dimasukkan";
                        $sts = "NotOK";
                    } else {
                        $this->dgeneral->commit_transaction();
                        $msg = "Data berhasil dihapus";
                        $sts = "OK";
                    }
                    $this->general->closeDb();
                } else {
                    $sts = "NotOK";
                    $msg = "Tidak ada data yang akan di hapus.";
                }
                $return = array('sts' => $sts, 'msg' => $msg);

                break;
            case 'namaspk':
                if (isset($data['id'])) {
                    $id = $this->generate->kirana_decrypt($data['id']);

                    $this->general->connectDbPortal();
                    $this->dgeneral->begin_transaction();

                    $data_row = $this->dgeneral->basic_column('delete');

                    $this->dgeneral->update('tbl_leg_nama_spk', $data_row,
                        array(
                            array(
                                'kolom' => 'id_nama_spk',
                                'value' => $id
                            )
                        )
                    );

                    if ($this->dgeneral->status_transaction() === FALSE) {
                        $this->dgeneral->rollback_transaction();
                        $msg = "Periksa kembali data yang dimasukkan";
                        $sts = "NotOK";
                    } else {
                        $this->dgeneral->commit_transaction();
                        $msg = "Data berhasil dihapus";
                        $sts = "OK";
                    }
                    $this->general->closeDb();
                } else {
                    $sts = "NotOK";
                    $msg = "Tidak ada data yang akan di hapus.";
                }
                $return = array('sts' => $sts, 'msg' => $msg);

                break;
            case 'kualifikasi':
                if (isset($data['id'])) {
                    $id = $this->generate->kirana_decrypt($data['id']);

                    $this->general->connectDbPortal();
                    $this->dgeneral->begin_transaction();

                    $data_row = $this->dgeneral->basic_column('delete');

                    $this->dgeneral->update('tbl_leg_kualifikasi_spk', $data_row,
                        array(
                            array(
                                'kolom' => 'id_kualifikasi_spk',
                                'value' => $id
                            )
                        )
                    );

                    if ($this->dgeneral->status_transaction() === FALSE) {
                        $this->dgeneral->rollback_transaction();
                        $msg = "Periksa kembali data yang dimasukkan";
                        $sts = "NotOK";
                    } else {
                        $this->dgeneral->commit_transaction();
                        $msg = "Data berhasil dihapus";
                        $sts = "OK";
                    }
                    $this->general->closeDb();
                } else {
                    $sts = "NotOK";
                    $msg = "Tidak ada data yang akan di hapus.";
                }
                $return = array('sts' => $sts, 'msg' => $msg);

                break;
            case 'otojenisspk':
                if (isset($data['id'])) {
                    $id = $this->generate->kirana_decrypt($data['id']);

                    $this->general->connectDbPortal();
                    $this->dgeneral->begin_transaction();

                    $data_row = $this->dgeneral->basic_column('delete');

                    $this->dgeneral->update('tbl_leg_oto_jenis_spk', $data_row,
                        array(
                            array(
                                'kolom' => 'id_oto_jenis',
                                'value' => $id
                            )
                        )
                    );

                    if ($this->dgeneral->status_transaction() === FALSE) {
                        $this->dgeneral->rollback_transaction();
                        $msg = "Periksa kembali data yang dimasukkan";
                        $sts = "NotOK";
                    } else {
                        $this->dgeneral->commit_transaction();
                        $msg = "Data berhasil dihapus";
                        $sts = "OK";
                    }
                    $this->general->closeDb();
                } else {
                    $sts = "NotOK";
                    $msg = "Tidak ada data yang akan di hapus.";
                }
                $return = array('sts' => $sts, 'msg' => $msg);

                break;
            case 'jenisvendor':
                if (isset($data['id'])) {
                    $id = $this->generate->kirana_decrypt($data['id']);

                    $this->general->connectDbPortal();
                    $this->dgeneral->begin_transaction();

                    $data_row = $this->dgeneral->basic_column('delete');

                    $this->dgeneral->update('tbl_leg_jenis_vendor', $data_row,
                        array(
                            array(
                                'kolom' => 'id_jenis_vendor',
                                'value' => $id
                            )
                        )
                    );

                    if ($this->dgeneral->status_transaction() === FALSE) {
                        $this->dgeneral->rollback_transaction();
                        $msg = "Periksa kembali data yang dimasukkan";
                        $sts = "NotOK";
                    } else {
                        $this->dgeneral->commit_transaction();
                        $msg = "Data berhasil dihapus";
                        $sts = "OK";
                    }
                    $this->general->closeDb();
                } else {
                    $sts = "NotOK";
                    $msg = "Tidak ada data yang akan di hapus.";
                }
                $return = array('sts' => $sts, 'msg' => $msg);

                break;
            case 'otovendor':
                if (isset($data['id'])) {
                    $id = $this->generate->kirana_decrypt($data['id']);

                    $this->general->connectDbPortal();
                    $this->dgeneral->begin_transaction();

                    $data_row = $this->dgeneral->basic_column('delete');

                    $this->dgeneral->update('tbl_leg_oto_vendor', $data_row,
                        array(
                            array(
                                'kolom' => 'id_oto_vendor',
                                'value' => $id
                            )
                        )
                    );

                    if ($this->dgeneral->status_transaction() === FALSE) {
                        $this->dgeneral->rollback_transaction();
                        $msg = "Periksa kembali data yang dimasukkan";
                        $sts = "NotOK";
                    } else {
                        $this->dgeneral->commit_transaction();
                        $msg = "Data berhasil dihapus";
                        $sts = "OK";
                    }
                    $this->general->closeDb();
                } else {
                    $sts = "NotOK";
                    $msg = "Tidak ada data yang akan di hapus.";
                }
                $return = array('sts' => $sts, 'msg' => $msg);

                break;
            default:
                $return = array('sts' => 'NotOK', 'msg' => 'Link tidak ditemukan');
                break;
        }

        echo json_encode($return);
    }

    private function set_divisi($data)
    {
        if (isset($data['id'])) {
            $id = $this->generate->kirana_decrypt($data['id']);

            $this->general->connectDbPortal();
            $this->dgeneral->begin_transaction();

            $data_row = $this->dgeneral->basic_column($data['action']);

            $this->dgeneral->update('tbl_leg_divisi', $data_row,
                array(
                    array(
                        'kolom' => 'id_divisi',
                        'value' => $id
                    )
                )
            );

            if ($this->dgeneral->status_transaction() === FALSE) {
                $this->dgeneral->rollback_transaction();
                $msg = "Periksa kembali data yang dimasukkan";
                $sts = "NotOK";
            } else {
                $this->dgeneral->commit_transaction();
                $msg = "Data berhasil dihapus";
                $sts = "OK";
            }
            $this->general->closeDb();
        } else {
            $sts = "NotOK";
            $msg = "Tidak ada data yang akan di hapus.";
        }
        $return = array('sts' => $sts, 'msg' => $msg);
        return $return;
    }

    private function set_jenis_spk($data)
    {
        if (isset($data['id'])) {
            $id = $this->generate->kirana_decrypt($data['id']);

            $this->general->connectDbPortal();
            $this->dgeneral->begin_transaction();

            $data_row = $this->dgeneral->basic_column($data['action']);

            $this->dgeneral->update('tbl_leg_jenis_spk', $data_row,
                array(
                    array(
                        'kolom' => 'id_jenis_spk',
                        'value' => $id
                    )
                )
            );

            if ($this->dgeneral->status_transaction() === FALSE) {
                $this->dgeneral->rollback_transaction();
                $msg = "Periksa kembali data yang dimasukkan";
                $sts = "NotOK";
            } else {
                $this->dgeneral->commit_transaction();
                $msg = "Data berhasil dihapus";
                $sts = "OK";
            }
            $this->general->closeDb();
        } else {
            $sts = "NotOK";
            $msg = "Tidak ada data yang akan di hapus.";
        }
        $return = array('sts' => $sts, 'msg' => $msg);
        return $return;
    }

    private function set_jenis_vendor($data)
    {
        if (isset($data['id'])) {
            $id = $this->generate->kirana_decrypt($data['id']);

            $this->general->connectDbPortal();
            $this->dgeneral->begin_transaction();

            $data_row = $this->dgeneral->basic_column($data['action']);

            $this->dgeneral->update('tbl_leg_jenis_vendor', $data_row,
                array(
                    array(
                        'kolom' => 'id_jenis_vendor',
                        'value' => $id
                    )
                )
            );

            if ($this->dgeneral->status_transaction() === FALSE) {
                $this->dgeneral->rollback_transaction();
                $msg = "Periksa kembali data yang dimasukkan";
                $sts = "NotOK";
            } else {
                $this->dgeneral->commit_transaction();
                $msg = "Data berhasil diubah";
                $sts = "OK";
            }
            $this->general->closeDb();
        } else {
            $sts = "NotOK";
            $msg = "Tidak ada data yang akan di ubah.";
        }
        $return = array('sts' => $sts, 'msg' => $msg);
        return $return;
    }

    private function set_nama_spk($data)
    {
        if (isset($data['id'])) {
            $id = $this->generate->kirana_decrypt($data['id']);

            $this->general->connectDbPortal();
            $this->dgeneral->begin_transaction();

            $data_row = $this->dgeneral->basic_column($data['action']);

            $this->dgeneral->update('tbl_leg_nama_spk', $data_row,
                array(
                    array(
                        'kolom' => 'id_nama_spk',
                        'value' => $id
                    )
                )
            );

            if ($this->dgeneral->status_transaction() === FALSE) {
                $this->dgeneral->rollback_transaction();
                $msg = "Periksa kembali data yang dimasukkan";
                $sts = "NotOK";
            } else {
                $this->dgeneral->commit_transaction();
                $msg = "Data berhasil dihapus";
                $sts = "OK";
            }
            $this->general->closeDb();
        } else {
            $sts = "NotOK";
            $msg = "Tidak ada data yang akan di hapus.";
        }
        $return = array('sts' => $sts, 'msg' => $msg);
        return $return;
    }
    private function set_kualifikasi($data)
    {
        if (isset($data['id'])) {
            $id = $this->generate->kirana_decrypt($data['id']);

            $this->general->connectDbPortal();
            $this->dgeneral->begin_transaction();

            $data_row = $this->dgeneral->basic_column($data['action']);

            $this->dgeneral->update('tbl_leg_kualifikasi_spk', $data_row,
                array(
                    array(
                        'kolom' => 'id_kualifikasi_spk',
                        'value' => $id
                    )
                )
            );

            if ($this->dgeneral->status_transaction() === FALSE) {
                $this->dgeneral->rollback_transaction();
                $msg = "Periksa kembali data yang dimasukkan";
                $sts = "NotOK";
            } else {
                $this->dgeneral->commit_transaction();
                $msg = "Data berhasil dihapus";
                $sts = "OK";
            }
            $this->general->closeDb();
        } else {
            $sts = "NotOK";
            $msg = "Tidak ada data yang akan di hapus.";
        }
        $return = array('sts' => $sts, 'msg' => $msg);
        return $return;
    }
    private function set_oto_jenis_spk($data)
    {
        if (isset($data['id'])) {
            $id = $this->generate->kirana_decrypt($data['id']);

            $this->general->connectDbPortal();
            $this->dgeneral->begin_transaction();

            $data_row = $this->dgeneral->basic_column($data['action']);

            $this->dgeneral->update('tbl_leg_oto_jenis_spk', $data_row,
                array(
                    array(
                        'kolom' => 'id_oto_jenis',
                        'value' => $id
                    )
                )
            );

            if ($this->dgeneral->status_transaction() === FALSE) {
                $this->dgeneral->rollback_transaction();
                $msg = "Periksa kembali data yang dimasukkan";
                $sts = "NotOK";
            } else {
                $this->dgeneral->commit_transaction();
                $msg = "Data berhasil diubah";
                $sts = "OK";
            }
            $this->general->closeDb();
        } else {
            $sts = "NotOK";
            $msg = "Tidak ada data yang akan di ubah.";
        }
        $return = array('sts' => $sts, 'msg' => $msg);
        return $return;
    }

    private function set_oto_vendor($data)
    {
        if (isset($data['id'])) {
            $id = $this->generate->kirana_decrypt($data['id']);

            $this->general->connectDbPortal();
            $this->dgeneral->begin_transaction();

            $data_row = $this->dgeneral->basic_column($data['action']);

            $this->dgeneral->update('tbl_leg_oto_vendor', $data_row,
                array(
                    array(
                        'kolom' => 'id_oto_vendor',
                        'value' => $id
                    )
                )
            );

            if ($this->dgeneral->status_transaction() === FALSE) {
                $this->dgeneral->rollback_transaction();
                $msg = "Periksa kembali data yang dimasukkan";
                $sts = "NotOK";
            } else {
                $this->dgeneral->commit_transaction();
                $msg = "Data berhasil diubah";
                $sts = "OK";
            }
            $this->general->closeDb();
        } else {
            $sts = "NotOK";
            $msg = "Tidak ada data yang akan di ubah.";
        }
        $return = array('sts' => $sts, 'msg' => $msg);
        return $return;
    }
	private function get_plant($array = NULL, $plant = NULL) {
		$plant 		= $this->dmaster->get_data_plant("open", $plant);
		// $plant 		= $this->general->generate_encrypt_json($plant, array("WERKS"));
		if ($array) {
			return $plant;
		} else {
			echo json_encode($plant);
		}
	}
	private function get_jenis_vendor2($array = NULL, $id_jenis_vendor = NULL) {
		$jenis_vendor 		= $this->dmaster->get_data_jenis_vendor("open", $id_jenis_vendor);
		// $jenis_vendor 		= $this->general->generate_encrypt_json($jenis_vendor, array("id_jenis_vendor"));
		if ($array) {
			return $jenis_vendor;
		} else {
			echo json_encode($jenis_vendor);
		}
	}
	private function get_jenis_spk2($array = NULL, $id_jenis_spk = NULL) {
		$jenis_spk 		= $this->dmaster->get_data_jenis_spk("open", $id_jenis_spk);
		// $jenis_spk 		= $this->general->generate_encrypt_json($jenis_spk, array("id_jenis_spk"));
		if ($array) {
			return $jenis_spk;
		} else { 
			echo json_encode($jenis_spk); 
		}   
	}
	private function get_kualifikasi_spk2($array = NULL, $id_kualifikasi_spk = NULL, $id_jenis_spk = NULL, $kualifikasi = NULL, $lifnr = NULL, $vendor = NULL) {
		$kualifikasi_spk 		= $this->dmaster->get_data_kualifikasi_spk("open", $id_kualifikasi_spk, $id_jenis_spk, $kualifikasi, $lifnr, $vendor);
		// $kualifikasi_spk 		= $this->general->generate_encrypt_json($kualifikasi_spk, array("id_kualifikasi_spk"));
		if ($array) {
			return $kualifikasi_spk;    
		} else {  
			echo json_encode($kualifikasi_spk);
		}
	}
	private function get_dokumen_vendor($array = NULL, $id_jenis_vendor = NULL, $lifnr = NULL, $vendor = NULL) {
		$dokumen_vendor 		= $this->dmaster->get_data_dokumen_vendor("open", $id_jenis_vendor, $lifnr, $vendor);
		// if(!empty($dokumen_vendor)){
			// $file	= $this->dmaster->get_data_file("array", $lifnr);
			// $dokumen_vendor[0]->arr_file = $file;
		// }
		if ($array) {
			return $dokumen_vendor;
		} else {
			echo json_encode($dokumen_vendor);
		} 
	}
	private function get_matrix($array = NULL, $lifnr = NULL, $ekorg = NULL) {
		$matrix 		= $this->dmaster->get_data_matrix("open", $lifnr, $ekorg);
		if ($array) {
			return $matrix;
		} else {
			echo json_encode($matrix);
		}
	}
}