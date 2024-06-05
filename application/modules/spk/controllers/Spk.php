<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @application  : SPK Transaction - Controller
 * @author     : Octe Reviyanto Nugroho
 * @contributor  :
 * 1. Lukman Hakim (7143) 28.03.2019
 * CR#1883 -> http://10.0.0.18/home/pdfviewer.php?q=crpdf/cr/CR_1883.pdf&n=CR_1883.pdf
 * 2. <insert your fullname> (<insert your nik>) <insert the date>
 * <insert what you have modified>
 * etc.
 */
class Spk extends MX_Controller
{
    private $data;

    public function __construct()
    {
        parent::__construct();
        $this->data['module'] = "Manage SPK";
        $this->data['user'] = $this->general->get_data_user();
        $this->load->model('dmaster');
        $this->load->model('dspk');
    }

    public function test(){
            $divisis = $this->dspk->get_spk_divisi(
                array(
                    'id_spk' => 798
                )
            );
		
		// setlocale(LC_ALL, 'id_ID', 'IND', 'id_ID.UTF8', 'id_ID.UTF-8', 'id_ID.8859-1', 'IND.UTF8', 'IND.UTF-8', 'IND.8859-1', 'Indonesian.UTF8', 'Indonesian.UTF-8', 'Indonesian.8859-1', 'Indonesian', 'Indonesia', 'id', 'ID');
		// $config['protocol'] = 'smtp';
		// $config['smtp_host'] = 'mail.kiranamegatara.com';
		// $config['smtp_user'] = 'no-reply@kiranamegatara.com';
		// $config['smtp_pass'] = '1234567890';
		// $config['smtp_port'] = '465';
		// $config['smtp_crypto'] = 'ssl';
		// $config['charset'] = 'iso-8859-1';
		// $config['wordwrap'] = true;
		// $config['mailtype'] = 'html';
		
        // $spk = $this->dspk->get_spk(
            // array(
                // 'id_spk' => 1,
                // 'single_row' => true
            // )
        // ); 
		// $data_email = $this->get_email('array');
		// foreach($data_email as $dt){ 
			// // $subject = 'Konfirmasi Drop SPK';
			// // $this->load->library('email', $config);
			// // $this->email->from('no-reply@kiranamegatara.com', 'PT. KIRANAMEGATARA');
			// // $this->email->subject($subject);
			// // $this->email->to($dt->email);
			// $message =	'<p><b>Kepada Bpk/Ibu '.$dt->nama.'</b></p>'; 
			// $message .=	'<p>Berikut adalah konfirmasi Drop SPK dari:</p>';
			// $message .=	'<table>';
			// $message .=	'	<tr><td>Plant</td><td>: '.$spk->plant.'</td></tr>';
			// $message .=	'	<tr><td>Jenis SPK</td><td>: '.$spk->jenis_spk.'</td></tr>';
			// $message .=	'	<tr><td>Perihal</td><td>: '.$spk->perihal.'</td></tr>';
			// $message .=	'	<tr><td>SPPKP</td><td>: '.$spk->SPPKP.'</td></tr>';
			// $message .=	'</table>';
			// echo $message; 	
			// // $this->email->message($message);
			// // $this->email->send();
		// }	
	}

    public function manage() 
    {
		$this->general->check_access();

        $this->data['title'] = "Manage SPK";

        $this->general->connectDbPortal();

        $leg_level_id = $this->data['user']->leg_level_id;
        $plant = $this->data['user']->gsber;


        $this->data['leg_level_id'] = $leg_level_id;
        $this->data['plant'] = $plant;
		
		//lha
		if(isset($_POST['filter_plant'])){
			$filter_plant	= array();
			foreach ($_POST['filter_plant'] as $dt) {
				array_push($filter_plant, $dt);
			}
		}else{
			if (($this->data['user']->ho='n') and  (($this->data['user']->gsber=='KJP1')or($this->data['user']->gsber=='KJP2'))){
				$filter_plant	= array("KJP1","KJP2"); 
			}else{
				$filter_plant  = NULL;
			}
		}
		if(isset($_POST['filter_jenis'])){
			$filter_jenis	= array();
			foreach ($_POST['filter_jenis'] as $dt) {
				array_push($filter_jenis, $dt);
			}
		}else{
			$filter_jenis  = NULL;
		}
		$filter_tanggal_berlaku_awal = (!empty($_POST['filter_tanggal_berlaku_awal']))?date('Y-m-d', strtotime($_POST['filter_tanggal_berlaku_awal'])):NULL;
		$filter_tanggal_berlaku_akhir = (!empty($_POST['filter_tanggal_berlaku_akhir']))?date('Y-m-d', strtotime($_POST['filter_tanggal_berlaku_akhir'])):NULL;
		$filter_tanggal_berakhir_awal = (!empty($_POST['filter_tanggal_berakhir_awal']))?date('Y-m-d', strtotime($_POST['filter_tanggal_berakhir_awal'])):NULL;
		$filter_tanggal_berakhir_akhir = (!empty($_POST['filter_tanggal_berakhir_akhir']))?date('Y-m-d', strtotime($_POST['filter_tanggal_berakhir_akhir'])):NULL;
		if(isset($_POST['filter_status'])){
			$filter_status	= array();
			foreach ($_POST['filter_status'] as $dt) {
				array_push($filter_status, $dt);
			}
		}else{
			$filter_status  = NULL;
		}

        switch ($leg_level_id) {
            case 1:
                $list_spk = $this->dspk->get_spk(
                    array(
                        'id_status_not' => array(7),
						'filter_plant' => $filter_plant,
						'filter_jenis' => $filter_jenis,
						'filter_tanggal_berlaku_awal' => $filter_tanggal_berlaku_awal,
						'filter_tanggal_berlaku_akhir' => $filter_tanggal_berlaku_akhir,
						'filter_tanggal_berakhir_awal' => $filter_tanggal_berakhir_awal,
						'filter_tanggal_berakhir_akhir' => $filter_tanggal_berakhir_akhir,
						'filter_status' => $filter_status
                    )
                );
                break;
            case 2:
                $list_spk = $this->dspk->get_spk(
                    array(
                        'nik' => $this->data['user']->nik,
						'filter_plant' => $filter_plant,
						'filter_jenis' => $filter_jenis,
						'filter_tanggal_berlaku_awal' => $filter_tanggal_berlaku_awal,
						'filter_tanggal_berlaku_akhir' => $filter_tanggal_berlaku_akhir,
						'filter_tanggal_berakhir_awal' => $filter_tanggal_berakhir_awal,
						'filter_tanggal_berakhir_akhir' => $filter_tanggal_berakhir_akhir,
						'filter_status' => $filter_status
                    )
                );
                break;
            default:
                $list_spk = $this->dspk->get_spk(
                    array(
                        'id_divisi' => $leg_level_id,
                        // 'id_status' => array(1, 9, 10, 11, 12),
                        'id_status' => array(1, 9, 10, 11, 12, 17, 18),	//add canceled and droped
 						'filter_plant' => $filter_plant,
						'filter_jenis' => $filter_jenis,
						'filter_tanggal_berlaku_awal' => $filter_tanggal_berlaku_awal,
						'filter_tanggal_berlaku_akhir' => $filter_tanggal_berlaku_akhir,
						'filter_tanggal_berakhir_awal' => $filter_tanggal_berakhir_awal,
						'filter_tanggal_berakhir_akhir' => $filter_tanggal_berakhir_akhir,
						'filter_status' => $filter_status
                    )
                );

                break;
        }

        foreach ($list_spk as $list) {
            $divisis = $this->dspk->get_spk_divisi(
                array(
                    'id_spk' => $list->id_spk
                )
            );

            $list->id_spk = $this->generate->kirana_encrypt($list->id_spk);

            $list->table_divisi = $this->get_spk_table_divisi($list->id_spk, $divisis);

           // $list->table_dokumen = $this->get_spk_table_dokumen($list->id_spk, $vendors, $templates);

            /** Generate url gambar */
            $list->final = null;
            if (isset($list->files)) {
                $list->files = site_url('spk/view_file?file=' . $list->files);
                $list->final = "<a href='$list->files' data-fancybox><span class='badge bg-red-gradient'><i class='fa fa-file-pdf-o'></i></span> </a>";
            }

            /** Edit link */
            $linkEdit = "";
            if ($leg_level_id == 2 and $list->id_status == 7)
                $linkEdit = "<li>"
                    . "<a href='javascript:void(0)' class='spk-edit' data-id_spk='"
                    . $list->id_spk . "'>Edit</a>"
                    . "</li>";

            /** Approve link by divisi, bukan untuk PIC HO & Pabrik */
            $linkApprove = "";
            if (!in_array($leg_level_id, array(1, 2))) {
                if ($list->id_status == 1)
                    $linkApprove = "<li>"
                        . "<a href='javascript:void(0)' class='spk-approve' data-id_spk='"
                        . $list->id_spk . "'>Approve SPK</a>"
                        . "</li>";
            }

            /** Final SPK link */
            $linkFinalSpk = "";
            $linkViewFinalSpk = "";
            if (!empty($list->files_1) && $list->id_status == 11) {
                if (isset($list->files_1))
                    $list->files_1 = site_url('spk/view_file?file=' . $list->files_1);

                if ($list->login_buat <> $list->login_edit)
                    $linkViewFinalSpk = "<li>"
                        . "<a href='"
                        . $list->files_1 . "' data-fancybox>View Final Draft</a>"
                        . "</li>";
                else
                    $linkFinalSpk = "<li>"
                        . "<a href='javascript:void(0)' class='spk-final-spk' data-id_spk='"
                        . $list->id_spk . "'>Scan SPK</a>"
                        . "</li>";
            }

            /** Review, Set divisi, Final draft, delete(untuk status pending) Links untuk HO */
            $linkReview = "";
            $linkAssign = "";
            $linkFinalDraft = "";
            $linkDelete = "";
            if ($leg_level_id == 1) {
                if ($list->id_status == 2)
                    $linkReview = "<li>"
                        . "<a href='javascript:void(0)' class='spk-review' data-id_spk='"
                        . $list->id_spk . "'>Review SPK</a>"
                        . "</li>";

                if ($list->id_status == 3 or $list->id_status == 13)
                    $linkAssign = "<li>"
                        . "<a href='javascript:void(0)' class='spk-assign' data-id_spk='"
                        . $list->id_spk . "'>Assign Divisi</a>"
                        . "</li>";

                if ($list->id_status == 9)
                    $linkFinalDraft = "<li>"
                        . "<a href='javascript:void(0)' class='spk-final-draft' data-id_spk='"
                        . $list->id_spk . "' data-nama_spk='"
                        . $list->nama_spk . "' data-nomor_spk='" . $list->nomor_spk . "'>Final Draft</a>"
                        . "</li>";

                if ($list->id_status == 11)
                    $linkFinalDraft = "<li>"
                        . "<a href='javascript:void(0)' class='spk-final-draft' data-id_spk='"
                        . $list->id_spk . "' data-nama_spk='"
                        . $list->nama_spk . "' data-nomor_spk='" . $list->nomor_spk . "'>Edit Final Draft</a>"
                        . "</li>";

                if ($list->id_status == 13)
                    $linkDelete = "<li>"
                        . "<a href='javascript:void(0)' class='spk-delete' data-id_spk='"
                        . $list->id_spk . "'>Delete</a>"
                        . "</li>";
            }

            /** Final SPK, submit SPK, Links */
            $linkSubmit = "";
            if ($leg_level_id == 2) {
                $linkFinalSpk = "";
                if ($list->id_status == 11)
                    $linkFinalSpk = "<li>"
                        . "<a href='javascript:void(0)' class='spk-final-spk' data-id_spk='"
                        . $list->id_spk . "'>Scan SPK</a>"
                        . "</li>";
                if ($list->id_status == 7)
                    $linkSubmit = "<li>"
                        . "<a href='javascript:void(0)' class='spk-submit' data-id_spk='"
                        . $list->id_spk . "'>Submit SPK</a>"
                        . "</li>";
                if ($list->id_status == 2 || $list->id_status == 7)
                    $linkDelete = "<li>"
                        . "<a href='javascript:void(0)' class='spk-delete' data-id_spk='"
                        . $list->id_spk . "'>Delete</a>"
                        . "</li>";
                else
                    $linkDelete = "";
            }
			$jumlah_komentar = ($list->jumlah_komentar!=0)?'('.$list->jumlah_komentar.')':'';
            $linkKomentar = "<li>"
                . "<a href='javascript:void(0)' class='spk-komentar' data-jumlah_komentar='".$list->jumlah_komentar."' data-id_spk='"
                . $list->id_spk . "'>Komentar ".$jumlah_komentar."</a>"
                . "</li>";
			//lha  	
			if (($leg_level_id == 1)and($list->id_status != 17)and($list->id_status != 18)) {
				// if ($list->id_status == 12){	//completed 
					// $linkCancel = "<li>"
						// . "<a href='javascript:void(0)' class='spk_drop' data-id_spk='"
						// . $list->id_spk . "' data-nama_spk='". $list->nama_spk . "' data-nomor_spk='" . $list->nomor_spk . "'>Drop</a>"
						// . "</li>";
				// }else{
					// $linkCancel = "<li>"
						// . "<a href='javascript:void(0)' class='spk_cancel' data-id_spk='"
						// . $list->id_spk . "' data-nama_spk='". $list->nama_spk . "' data-nomor_spk='" . $list->nomor_spk . "'>Cancel</a>"
						// . "</li>";
				// }
				if ($list->id_status != 12){
					$linkCancel = "<li>"
						. "<a href='javascript:void(0)' class='spk_cancel' data-id_spk='"
						. $list->id_spk . "' data-status_akhir='". $list->status . "' data-nama_spk='". $list->nama_spk . "' data-nomor_spk='" . $list->nomor_spk . "'>Cancel</a>"
						. "</li>";
				}else{
					$linkCancel = "";
				}
				
			}else{
				$linkCancel = "";
				
			}
            /** Generate Links */
            $list->links = $linkSubmit
                . $linkReview
                . $linkKomentar
                . $linkApprove
                . $linkAssign
                . $linkFinalDraft
                . $linkFinalSpk
                . $linkViewFinalSpk
                . $linkEdit
                . $linkDelete
				. $linkCancel;
        }

        $canAddSPK = false;
        $canDownloadTemplate = false;

        if ($leg_level_id == 1 and $leg_level_id == 2) {
            $canAddSPK = true;
            $canDownloadTemplate = true;
        }

        array_push($this->data, compact('canAddSPK', 'canDownloadTemplate'));

        $this->data['list'] = $list_spk;

        /** Data Form SPK */
		if (($this->data['user']->ho='n') and  (($this->data['user']->gsber=='KJP1')or($this->data['user']->gsber=='KJP2'))){
			$ck_plant	= array("KJP1","KJP2"); 
		}else{
			$ck_plant	= ($leg_level_id==2)?$this->data['user']->gsber:NULL;
		}
		
        $this->data['filter_plant'] = $this->dgeneral->get_master_plant($ck_plant,NULL,NULL,'ERP');
		
        $this->data['filter_jenis'] = $this->dmaster->get_jenis_spk();
        $this->data['filter_status'] = $this->dmaster->get_status_spk();
		
        $this->data['jenis_spk'] = $this->general->generate_encrypt_json(
            $this->dmaster->get_jenis_spk(),
            array('id_jenis_spk')
        );


        $this->data['jenis_vendor'] = $this->general->generate_encrypt_json(
            $this->dmaster->get_jenis_vendor(),
            array('id_jenis_vendor')
        ); 
		
        $this->load->view('transaction/manage', $this->data);
    }

    private function get_spk_table_divisi($id_spk = null, $divisis = array())
    {
        return $this->load->view('transaction/includes/table_divisi', compact('divisis', 'id_spk'), true);
    }

    private function get_spk_table_dokumen($id_spk = null, $data = array(), $tipe = 'template')
    {
        if ($tipe == 'template')
            return $this->load->view('transaction/includes/table_dokumen_template', compact('data', 'id_spk'), true);
        elseif (($tipe == 'vendor')or($tipe == 'vendor_dokumen'))
            return $this->load->view('transaction/includes/table_dokumen_vendor', compact('data', 'id_spk'), true);
        else
            return $this->load->view('transaction/includes/table_dokumen_kualifikasi', compact('data', 'id_spk'), true);
    }

    public function save($param) 
    {
        $data = $_POST;

        switch ($param) {
            case 'dokumen':
                $return = $this->save_dokumen($data);
                break;
            case 'spk':
                $return = $this->save_spk($data);
                break;
            case 'submitspk':
                $return = $this->save_submit_spk($data);
                break;
            case 'reviewspk':
                $return = $this->save_review_spk($data);
                break;
            case 'assignspk':
                $return = $this->save_assign_spk($data);
                break;
            case 'approvespk':
                $return = $this->save_approve_spk($data);
                break;
            case 'finaldraft':
                $return = $this->save_final_draft($data);
                break;
            case 'final':
                $return = $this->save_final($data);
                break;
            case 'komentar':
                $return = $this->save_komentar($data);
                break;
            case 'dropspk':
                $return = $this->save_drop_spk($data);
                break;
            case 'cancelspk':
                $return = $this->save_cancel_spk($data);
                break;
            default:
                $return = array('sts' => 'NotOK', 'msg' => 'Link tidak ditemukan');
                break;
        }

        echo json_encode($return);
    }

    public function get($param)
    {
        $data = $_POST;

        switch ($param) {
            case 'jenisspk':
                $return = $this->get_jenis_spk($data);
                break;
            case 'namaspk':
                $return = $this->get_nama_spk($data);
                break;
            case 'spk':
                $return = $this->get_spk($data);
                break;
            case 'submitspk':
                $return = $this->get_submit_spk($data);
                break;
            case 'approvespk':
                $return = $this->get_approve_spk($data);
                break;
            case 'komentar':
                $return = $this->get_komentar($data);
                break;
            case 'attachments':
                $return = $this->get_attachments($data);
                break;
			// //lha	
            // case 'plant':
                // $return = $this->dgeneral->get_master_plant(NULL);
                // break;
            default:
                $return = array('sts' => 'NotOK', 'msg' => 'Link tidak ditemukan');
                break;
        }

        echo json_encode($return);
    }
	public function get2($param = NULL, $param2 = NULL) { 
		switch ($param) {    
			case 'vendor': 
			if (isset($_GET['q'])) { 
				$plant				= isset($_GET['plant'])?$_GET['plant']:null;
				$id_jenis_spk		= isset($_GET['id_jenis_spk'])?$this->generate->kirana_decrypt($_GET['id_jenis_spk']):null;
				$id_jenis_vendor	= isset($_GET['id_jenis_vendor'])?$this->generate->kirana_decrypt($_GET['id_jenis_vendor']):null;
				$data      			= $this->dspk->get_data_spec('open', $_GET['q'], $id_jenis_vendor, $plant, $id_jenis_spk);
				// $data 	   = $this->general->generate_encrypt_json($data, array("LIFNR"));
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
			case 'kualifikasi_vendor':
				$lifnr 	= (isset($_POST['lifnr']) ? $_POST['lifnr'] : NULL);
				$plant 	= (isset($_POST['plant']) ? $_POST['plant'] : NULL);
				$data	= $this->dspk->get_data_spec('open', NULL, NULL, $plant, NULL, $lifnr);
				$return = json_encode($data);
				echo $return; 
				break;
			default:
				$return = array('sts' => 'NotOK', 'msg' => 'Link tidak ditemukan');
				echo json_encode($return);
				break;
		}
	}

    public function download($param, $id = null)
    {
        switch ($param) {
            case 'template':
                $return = $this->get_template($id);
                break;
            default:
                $return = array('sts' => 'NotOK', 'msg' => 'Link tidak ditemukan');
                break;
        }

        echo json_encode($return);
    }

    private function save_dokumen($data)
    {
		$this->general->connectDbPortal();

        $this->db->query("SET ANSI_NULLS ON");
        $this->db->query("SET ANSI_WARNINGS ON");

        $this->dgeneral->begin_transaction();

        $id_spk = $this->generate->kirana_decrypt($data['id_spk']);
        $id_oto = $this->generate->kirana_decrypt($data['id_oto']);

        if ($data['tipe'] == 'template') {
            $spk = $this->dspk->get_spk_template(
                array(
                    'id_spk' => $id_spk,
                    'id_oto_jenis' => $id_oto,
                    'single_row' => true
                )
            );
            $folder = SPK_UPLOAD_FOLDER . SPK_UPLOAD_TEMPLATE_FOLDER;
        } else {
            $spk = $this->dspk->get_spk_vendor(
                array(
                    'id_spk' => $id_spk,
                    'id_oto_vendor' => $id_oto,
                    'single_row' => true
                )
            );
            $folder = SPK_UPLOAD_FOLDER . SPK_UPLOAD_TEMPLATE_FOLDER;
        }


        $data_row = array();

        $upload_error = $this->general->check_upload_file('dokumen', true);

        if (isset($_FILES['dokumen']) and empty($upload_error)) {
            $uploaddir = KIRANA_PATH_FILE . $folder;
            if (!file_exists($uploaddir)) {
                mkdir($uploaddir, 0777, true);
            }

            $config['upload_path'] = $uploaddir;
            $config['allowed_types'] = 'jpeg|jpg|png|pdf|dot|doc|docx|xls|xlsx';
            $config['max_size'] = 5000;
            $config['remove_spaces'] = true;

            $filename = strtolower($id_spk . '_' . $data['tipe'] . '_' . $spk->nama_spk . '_' . $spk->nama_doc);
            $config['file_name'] = $filename;

            $this->load->library('upload', $config);

            $upload_error = null;

            if ($this->upload->do_upload('dokumen')) {
                $upload_data = $this->upload->data();
                $data_row['files'] = KIRANA_PATH_FILE_FOLDER . $folder . $upload_data['file_name'];
                $data_row['tipe_files'] = substr($upload_data['file_ext'], 1);
                $data_row['size_files'] = $upload_data['file_size'];
            } else {
                $upload_error = $this->upload->display_errors('', '');
            }
        }

        if (isset($data['id_upload']) && !empty($data['id_upload'])) {
            $id = $this->generate->kirana_decrypt($data['id_upload']);

            $data_row = $this->dgeneral->basic_column('update', $data_row);

            /** Tipe upload dokumen template atau vendor */
            if ($data['tipe'] == 'template') {
                $result = $this->dgeneral->update('tbl_leg_upload_template', $data_row, array(
                    array(
                        'kolom' => 'id_upload_template',
                        'value' => $id
                    )
                ));
            } else {
                $result = $this->dgeneral->update('tbl_leg_upload_vendor', $data_row, array(
                    array(
                        'kolom' => 'id_upload_vendor',
                        'value' => $id
                    )
                ));
            }
        } else {

			$data_row = $this->dgeneral->basic_column('insert', $data_row);

            /** Tipe upload dokumen template atau vendor */
            if ($data['tipe'] == 'template') {
                $data_row['id_spk'] = $id_spk;
                $data_row['id_jenis_spk'] = $spk->id_jenis_spk;
                $data_row['id_oto_jenis'] = $spk->id_oto_jenis;
                $data_row['nama_dok_template'] 	= $spk->nama_doc;
                $data_row['login_edit'] 		= base64_decode($this->session->userdata("-id_user-"));
                $data_row['tanggal_edit'] 		= date("Y-m-d H:i:s");

                $result = $this->dgeneral->insert('tbl_leg_upload_template', $data_row);
            } else {
                $data_row['id_spk'] = $id_spk;
                $data_row['id_jenis_vendor'] = $spk->id_jenis_vendor;
                $data_row['id_oto_vendor'] = $spk->id_oto_vendor;
                $data_row['nama_dokumen'] = $spk->nama_doc;
                $data_row['mandatory'] = $spk->mandatory_doc;
                $data_row['login_edit'] 		= base64_decode($this->session->userdata("-id_user-"));
                $data_row['tanggal_edit'] 		= date("Y-m-d H:i:s");

                $result = $this->dgeneral->insert('tbl_leg_upload_vendor', $data_row);
            }
        }

        if (isset($upload_error)) {
            $this->dgeneral->rollback_transaction();
            $msg = $upload_error;
            $sts = "NotOK";
            if (isset($data_row['files']))
                unlink(KIRANA_PATH_ASSETS . $data_row['files']);
        } else if ($this->dgeneral->status_transaction() === FALSE) {
            $this->dgeneral->rollback_transaction();
            $msg = "Periksa kembali data yang dimasukkan";
            $sts = "NotOK";
        } else {
            $this->dgeneral->commit_transaction();
            $msg = "Data berhasil ditambahkan";
            $sts = "OK";
			//jika yang melakukan edit adalah pic legal pabrik, mengirim email
			//sent email dari sini
			if($this->data['user']->leg_level_id==2){
				setlocale(LC_ALL, 'id_ID', 'IND', 'id_ID.UTF8', 'id_ID.UTF-8', 'id_ID.8859-1', 'IND.UTF8', 'IND.UTF-8', 'IND.8859-1', 'Indonesian.UTF8', 'Indonesian.UTF-8', 'Indonesian.8859-1', 'Indonesian', 'Indonesia', 'id', 'ID');
				$config['protocol'] = 'smtp';
				$config['smtp_host'] = 'mail.kiranamegatara.com';
				$config['smtp_user'] = 'no-reply@kiranamegatara.com';
				$config['smtp_pass'] = '1234567890';
				$config['smtp_port'] = '465';
				$config['smtp_crypto'] = 'ssl';
				$config['charset'] = 'iso-8859-1';
				$config['wordwrap'] = true;
				$config['mailtype'] = 'html';
				
				try {
					$data_email = $this->get_email('array');
					foreach($data_email as $dt){
						$subject = 'Konfirmasi Perubahan File Vendor/Template SPK';
						$this->load->library('email', $config);
						$this->email->from('no-reply@kiranamegatara.com', 'PT. KIRANAMEGATARA');
						$this->email->subject($subject);
						
						$this->email->to($dt->email);
						// $this->email->to('mutia.ariani@kiranamegatara.com');
						// $this->email->to('frans.darmawan@kiranamegatara.com');
						// $this->email->to('lukman.hakim@kiranamegatara.com');
						$message =	'<p><b>Kepada Bpk/Ibu</b></p>';
						$message .=	'<p>Berikut terlampir perubahan vendor/template dari team legal pabrik dari:</p>';
						$message .=	'<table>';
						$message .=	'	<tr><td>Plant</td><td>: '.$spk->plant.'</td></tr>';
						$message .=	'	<tr><td>Jenis SPK</td><td>: '.$spk->jenis_spk.'</td></tr>';
						$message .=	'	<tr><td>Nama SPK</td><td>: '.$spk->nama_spk.'</td></tr>';
						$message .=	'	<tr><td>Perihal</td><td>: '.$spk->perihal.'</td></tr>';
						$message .=	'	<tr><td>SPPKP</td><td>: '.$spk->SPPKP.'</td></tr>';
						$message .=	'	<tr><td>File Template</td><td>: '.$spk->nama_doc.'</td></tr>';
						$message .=	'</table>';
						$message .=	'<p>Mohon dapat dicek kembali.</p>';
						
						$this->email->message($message);
						$this->email->send();
					}	
				} catch (Exception $e) {
					$msg = $e->getMessage();
					$sts = "NotOK";
					$return = array('sts' => $sts, 'msg' => $msg);
					echo json_encode($return);
					exit();
				}			
			}
			//sent email sampe sini
        }

        $return = array('sts' => $sts, 'msg' => $msg);

        return $return;
    }

    private function save_spk($data)
    {
        $this->general->connectDbPortal();

        $this->db->query("SET ANSI_NULLS ON");
        $this->db->query("SET ANSI_WARNINGS ON");

        $this->dgeneral->begin_transaction();

        $plant = $this->general->get_master_plant(array($this->data['user']->gsber),null, null, 'ERP');

        $leg_level_id = $this->data['user']->leg_level_id;

        $jenis_spk = $this->dmaster->get_jenis_spk(
            array(
                'id_jenis_spk' => $this->generate->kirana_decrypt($data['id_jenis_spk']),
                'single_row' => true
            )
        );

        $nama_spk = $this->dmaster->get_nama_spk(
            array(
                'id_nama_spk' => $this->generate->kirana_decrypt($data['id_nama_spk']),
                'single_row' => true
            )
        );

        $jenis_vendor = $this->dmaster->get_jenis_vendor(
            array(
                'id_jenis_vendor' => $this->generate->kirana_decrypt($data['id_jenis_vendor']),
                'single_row' => true
            )
        );

        if ($leg_level_id == 1) {
            $id_status = 13;
        } else if ($leg_level_id == 2) {
            $id_status = 7;
        }

        $data['tanggal_perjanjian'] = date('Y-m-d', strtotime($data['tanggal_perjanjian']));
        $data['tanggal_berlaku_spk'] = date('Y-m-d', strtotime($data['tanggal_berlaku_spk']));
        $data['tanggal_berakhir_spk'] = date('Y-m-d', strtotime($data['tanggal_berakhir_spk']));

        $data['id_jenis_spk'] = $this->generate->kirana_decrypt($data['id_jenis_spk']);
        $data['id_nama_spk'] = $this->generate->kirana_decrypt($data['id_nama_spk']);
        $data['id_jenis_vendor'] = $this->generate->kirana_decrypt($data['id_jenis_vendor']);

        $data['nama_spk'] = $nama_spk->nama_spk;
        $data['jenis_spk'] = $jenis_spk->jenis_spk;
        $data['jenis_vendor'] = $jenis_vendor->jenis_vendor;
		
		$data['nama_vendor'] = $data['nama_vendor'];
		$data['id_kualifikasi'] = $data['id_kualifikasi'];
		
        if (isset($data['id_spk']) && !empty($data['id_spk'])) {
            $id = $this->generate->kirana_decrypt($data['id_spk']);

            unset($data['id_spk']); 

            $data_row = $this->dgeneral->basic_column('update', $data);

            $result = $this->dgeneral->update('tbl_leg_spk', $data_row, array(
                array(
                    'kolom' => 'id_spk',
                    'value' => $id
                )
            ));
 
        } else {
            unset($data['id_spk']);

            $data['plant'] = $plant[0]->plant;
            $data['id_plant'] = $plant[0]->id_pabrik;

            $data_row = $this->dgeneral->basic_column('insert_full', $data);
            $data_row['id_status'] = $id_status;

            $result = $this->dgeneral->insert('tbl_leg_spk', $data_row);
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

    private function get_nama_spk($data)
    {
        if (isset($data['id'])) {
            $id = $this->generate->kirana_decrypt($data['id']);

            $this->general->connectDbPortal();

            $result = $this->dmaster->get_nama_spk(array(
                'id_jenis_spk' => $id
            ));

            $result = $this->general->generate_encrypt_json(
                $result,
                array('id_nama_spk', 'id_jenis_spk')
            );

            return array('sts' => 'OK', 'data' => $result);
        } else {
            return array('sts' => 'NotOK', 'msg' => 'ID tidak ditemukan');
        }
    }

    private function get_jenis_spk($data)
    {
        $this->general->connectDbPortal();

        $result = $this->dmaster->get_jenis_spk();

        foreach ($result as $jenis) {
            $jenis->link = base_url('spk/download/template/' . $jenis->id_jenis_spk);
        }

        $result = $this->general->generate_encrypt_json(
            $result,
            array('id_jenis_spk')
        );

        return array('sts' => 'OK', 'data' => $result); 
    }

    private function get_spk($data)
    {
        if (isset($data['id'])) {
            $id = $this->generate->kirana_decrypt($data['id']);

            $this->general->connectDbPortal();

            $result = $this->general->generate_encrypt_json(
                $this->dspk->get_spk(array(
                    'id_spk' => $id,
                    'single_row' => true
                )),
                array('id_spk', 'id_jenis_spk', 'id_jenis_vendor', 'id_nama_spk')
            );

            return array('sts' => 'OK', 'data' => $result);
        } else {
            return array('sts' => 'NotOK', 'msg' => 'ID tidak ditemukan');
        }
    }
    public function delete($param)
    {
        $data = $_POST;
        switch ($param) {
            case 'spk':
                if (isset($data['id'])) {
                    $id = $this->generate->kirana_decrypt($data['id']);

                    $this->general->connectDbPortal();
                    $this->dgeneral->begin_transaction();

                    $data_row = $this->dgeneral->basic_column('delete');

                    $this->dgeneral->update('tbl_leg_spk', $data_row,
                        array(
                            array(
                                'kolom' => 'id_spk',
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

    private function get_submit_spk($data)
    {
        if (isset($data['id'])) {
            $id = $this->generate->kirana_decrypt($data['id']);

            $this->general->connectDbPortal();

            $spk = $this->dspk->get_spk(array(
                'single_row' => true,
                'id_spk' => $id
            ));
			//untuk data yang belum upload dokumen vendor(spk lama)
			if($spk->id_kualifikasi==null){
				$template_count = $this->dspk->get_total_spk_template(array(
					'single_row' => true,
					'id_jenis_spk' => $spk->id_jenis_spk
				));

				$template_up_count = $this->dspk->get_total_spk_template_uploaded(array(
					'single_row' => true,
					'id_spk' => $id,
					'id_jenis_spk' => $spk->id_jenis_spk
				));

				$vendor_count = $this->dspk->get_total_spk_vendor(array(
					'single_row' => true,
					'id_jenis_vendor' => $spk->id_jenis_vendor
				));

				$vendor_up_count = $this->dspk->get_total_spk_vendor_uploaded(array(
					'single_row' => true,
					'id_spk' => $id,
					'id_jenis_vendor' => $spk->id_jenis_vendor
				));

				if (
					$template_count->totaldok <= $template_up_count->totaldokup and
					$vendor_count->total_ven_mandatory <= $vendor_up_count->total_ven_mandatory
				) {
					return array('sts' => 'OK');
				} else {
					return array('sts' => 'NotOK', 'msg' => 'Submit SPK gagal! Mohon Lengkapi dokumen yang harus di Upload.');
				}
				
			}else{
				$template_count = $this->dspk->get_total_spk_template(array(
					'single_row' => true,
					'id_jenis_spk' => $spk->id_jenis_spk
				));

				$template_up_count = $this->dspk->get_total_spk_template_uploaded(array(
					'single_row' => true,
					'id_spk' => $id,
					'id_jenis_spk' => $spk->id_jenis_spk
				));
				if ($template_count->totaldok <= $template_up_count->totaldokup) {
					return array('sts' => 'OK');
				} else {
					return array('sts' => 'NotOK', 'msg' => 'Submit SPK gagal! Mohon Lengkapi dokumen yang harus di Upload.');
				}
			}
        } else {
            return array('sts' => 'NotOK', 'msg' => 'ID tidak ditemukan');
        }
    }

    private function get_approve_spk($data)
    {
        if (isset($data['id'])) {
            $id = $this->generate->kirana_decrypt($data['id']);

            $this->general->connectDbPortal();

            $leg_level_id = $this->data['user']->leg_level_id;

            $spk_approved = $this->dspk->get_spk_divisi(array(
                'single_row' => true,
                'id_spk' => $id,
                'id_divisi' => $leg_level_id
            ));

            if (isset($spk_approved)) {
                if ($spk_approved->approve != 'y') {
                    if ($leg_level_id == 3) {
                        return array('sts' => 'OK', 'msg' => 'Apakah anda sudah mengisi beban pajak SPK dan yakin untuk melakukan approve SPK ini?');
                    } else {

                        return array('sts' => 'OK', 'msg' => 'Apakah anda yakin untuk melakukan approve SPK ini?');
                    }
                } else {
                    return array('sts' => 'NotOK', 'msg' => 'SPK ini telah di approve oleh divisi anda.');
                }
            } else
                return array('sts' => 'NotOK', 'msg' => 'Anda tidak memiliki hak akses untuk approval SPK ini.');
        } else {
            return array('sts' => 'NotOK', 'msg' => 'ID tidak ditemukan');
        }
    }

    private function save_submit_spk($data)
    {
        $this->general->connectDbPortal();

        $this->db->query("SET ANSI_NULLS ON");
        $this->db->query("SET ANSI_WARNINGS ON");

        if (isset($data['id']) && !empty($data['id'])) {

            $this->dgeneral->begin_transaction();

            $id = $this->generate->kirana_decrypt($data['id']);

            $data_row = $this->dgeneral->basic_column(
                'update',
                array('id_status' => 2)
            );

            $result = $this->dgeneral->update('tbl_leg_spk', $data_row, array(
                array(
                    'kolom' => 'id_spk',
                    'value' => $id
                )
            ));

            if ($this->dgeneral->status_transaction() === FALSE) {
                $this->dgeneral->rollback_transaction();
                $msg = "Periksa kembali data yang dimasukkan";
                $sts = "NotOK";
            } else {
                $this->dgeneral->commit_transaction();
                $msg = "SPK berhasil di submit.";
                $sts = "OK";
                $this->send_email_konfirmasi($id);
            }

        } else {
            $msg = "Periksa kembali data yang dimasukkan";
            $sts = "NotOK";
        }

        $return = array('sts' => $sts, 'msg' => $msg);

        return $return;
    }

    private function save_review_spk($data)
    {
        $this->general->connectDbPortal();

        $this->db->query("SET ANSI_NULLS ON");
        $this->db->query("SET ANSI_WARNINGS ON");

        if (isset($data['id']) && !empty($data['id'])) {

            $this->dgeneral->begin_transaction();

            $id = $this->generate->kirana_decrypt($data['id']);

            $status = 4;
            if ($data['action'] == 'approve')
                $status = 3;

            $data_row = $this->dgeneral->basic_column(
                'update',
                array(
                    'id_status' => $status,
                    'tanggal_approve' => date('Y-m-d')
                )
            );

            $result = $this->dgeneral->update('tbl_leg_spk', $data_row, array(
                array(
                    'kolom' => 'id_spk',
                    'value' => $id
                )
            ));

            if ($this->dgeneral->status_transaction() === FALSE) {
                $this->dgeneral->rollback_transaction();
                $msg = "Periksa kembali data yang dimasukkan";
                $sts = "NotOK";
            } else {
                $this->dgeneral->commit_transaction();
                $msg = "SPK berhasil di " . $data['action'] . ".";
                $sts = "OK";
            }

        } else {
            $msg = "Periksa kembali data yang dimasukkan";
            $sts = "NotOK";
        }

        $return = array('sts' => $sts, 'msg' => $msg);

        return $return;
    }

    private function save_assign_spk($data)
    {
        $this->general->connectDbPortal();

        $this->db->query("SET ANSI_NULLS ON");
        $this->db->query("SET ANSI_WARNINGS ON");

        if (isset($data['id']) && !empty($data['id'])) {

            $this->dgeneral->begin_transaction();

            $id = $this->generate->kirana_decrypt($data['id']);

            $spk = $this->dspk->get_spk(array(
                'id_spk' => $id,
                'single_row' => true
            ));


            $owner = $this->dmaster->get_karyawan(
                array(
                    'id_user' => $spk->login_buat,
                    'single_row' => true
                )
            );

            if ($owner->leg_level_id == 1)
                $data_row = $this->dgeneral->basic_column(
                    'update',
                    array(
                        'id_status' => 1,
                        'tanggal_approve' => date('Y-m-d')
                    )
                );
            else
                $data_row = $this->dgeneral->basic_column(
                    'update',
                    array(
                        'id_status' => 1
                    )
                );

            $result = $this->dgeneral->update('tbl_leg_spk', $data_row, array(
                array(
                    'kolom' => 'id_spk',
                    'value' => $id
                )
            ));

            $assigned_divisi = $this->dspk->get_oto_divisi(
                array(
                    'id_spk' => $id
                )
            );

            foreach ($assigned_divisi as $divisi) {
                $check_approval = $this->dspk->get_spk_divisi(
                    array(
                        'id_spk' => $id,
                        'id_oto_div' => $divisi->id_oto_divisi,
                        'single_row' => true
                    )
                );

                if (!isset($check_approval)) {
                    $data_div = $this->dgeneral->basic_column(
                        'insert',
                        array(
                            'id_spk' => $id,
                            'id_oto_div' => $divisi->id_oto_divisi
                        )
                    );
                    $insert_divisi = $this->dgeneral->insert('tbl_leg_approval', $data_div);
                }
            }

            if ($this->dgeneral->status_transaction() === FALSE) {
                $this->dgeneral->rollback_transaction();
                $msg = "Periksa kembali data yang dimasukkan";
                $sts = "NotOK";
            } else {
                $this->dgeneral->commit_transaction();
                $msg = "SPK berhasil di assign Perijinan ke divisi terkait.";
                $sts = "OK";
                /** Send email ke karyawan-karyawan di divisi terkait yang memiliki hak akses*/
                $this->send_assign_divisi($id);
            }

        } else {
            $msg = "Periksa kembali data yang dimasukkan";
            $sts = "NotOK";
        }

        $return = array('sts' => $sts, 'msg' => $msg);

        return $return;
    }

    public function test_email($id)
    {
        echo json_encode($this->send_assign_divisi($id));
    }

    private function send_email_konfirmasi($id = null)
    {
        if (isset($id)) {
            $spk = $this->dspk->get_spk(array(
                'id_spk' => $id,
                'single_row' => true
            ));

            if (isset($spk)) {
                $owner = $this->dmaster->get_karyawan(
                    array(
                        'id_user' => $spk->login_buat,
                        'single_row' => true
                    )
                );

                $subject = 'Permohonan Pembuatan SPK ' . $spk->nama_spk . '.';

                $karyawans = $this->dmaster->get_karyawan(
                    array(
                        'leg_level_id' => 1
                    )
                );

                foreach ($karyawans as $karyawan) {
                    if (isset($karyawan->email)) {
                        $email = SPK_EMAIL_DEBUG_MODE ? json_decode(SPK_EMAIL_TESTER) : $karyawan->email;
                        $emailOri = $karyawan->email;
                        $message = $this->load->view('emails/spk_konfirmasi', compact('spk', 'owner', 'emailOri'), true);

                        $return = $this->general->send_email_new(
                            array(
                                'subject' => $subject,
                                'from_alias' => 'KiranaKu SPK',
                                'message' => $message,
                                'to' => $email
                            )
                        );
                        if ($return['sts'] == 'NotOK') {
                            return $return;
                            break;
                        }
                    }
                }
                return true;
            } else
                return false;
        } else
            return false;
    }

    private function send_assign_divisi($id = null)
    {
        if (isset($id)) {
            $spk = $this->dspk->get_spk(array(
                'id_spk' => $id,
                'single_row' => true
            ));

            if (isset($spk)) {
                $legalPabrik = array();
                if ($this->data['user']->leg_level_id == 2)
                    $legalPabrik = $this->dmaster->get_karyawan(
                        array(
                            'leg_level_id' => 2,
                            'plant' => $spk->plant
                        )
                    );

                $subject = 'Permohonan Konfirmasi SPK dari Divisi Terkait ' . $spk->nama_spk . '.';

                $divisTerkait = $this->dmaster->get_karyawan(
                    array(
                        'id_spk' => $id
                    )
                );

                $karyawans = array_merge($legalPabrik, $divisTerkait);

                foreach ($karyawans as $karyawan) {
                    if (isset($karyawan->email)) {
                        $email = SPK_EMAIL_DEBUG_MODE ? json_decode(SPK_EMAIL_TESTER) : $karyawan->email;
                        $emailOri = $karyawan->email;
                        $message = $this->load->view('emails/spk_assign_divisi', compact('spk', 'emailOri'), true);
                        $return = $this->general->send_email_new(
                            array(
                                'subject' => $subject,
                                'from_alias' => 'KiranaKu SPK',
                                'message' => $message,
                                'to' => $email
                            )
                        );
                        if ($return['sts'] == 'NotOK') {
                            return $return;
                            break;
                        }
                    }
                }
                return true;
            } else
                return false;
        } else
            return false;
    }

    private function send_approval_divisi($id = null)
    {
        if (isset($id)) {
            $spk = $this->dspk->get_spk(array(
                'id_spk' => $id,
                'single_row' => true
            ));

            if (isset($spk)) {
                $owner = $this->dmaster->get_karyawan(
                    array(
                        'id_user' => $spk->login_buat,
                        'single_row' => true
                    )
                );

                $subject = 'Konfirmasi SPK dari Divisi Terkait ' . $spk->nama_spk . '.';

                $legalPabrik = $this->dmaster->get_karyawan(
                    array(
                        'leg_level_id' => 2,
                        'plant' => $spk->plant
                    )
                );

                $legalHO = $this->dmaster->get_karyawan(
                    array(
                        'leg_level_id' => 1
                    )
                );

                $karyawans = array_merge($legalHO, $legalPabrik);

                foreach ($karyawans as $karyawan) {
                    if (isset($karyawan->email)) {
                        $email = SPK_EMAIL_DEBUG_MODE ? json_decode(SPK_EMAIL_TESTER) : $karyawan->email;
                        $emailOri = $karyawan->email;
                        $message = $this->load->view('emails/spk_approval_divisi', compact('spk', 'emailOri', 'owner'), true);
                        $return = $this->general->send_email_new(
                            array(
                                'subject' => $subject,
                                'from_alias' => 'KiranaKu SPK',
                                'message' => $message,
                                'to' => $email
                            )
                        );
                        if ($return['sts'] == 'NotOK') {
                            return $return;
                            break;
                        }
                    }
                }
                return true;
            } else
                return false;
        } else
            return false;
    }

    private function send_konfirmasi_final_draft($id = null)
    {
        if (isset($id)) {
            $spk = $this->dspk->get_spk(array(
                'id_spk' => $id,
                'single_row' => true
            ));

            if (isset($spk)) {

                $subject = 'Pemberitahuan Finalisasi Draft SPK ' . $spk->nama_spk . '.';

                $divisiTerkait = $this->dmaster->get_karyawan(
                    array(
                        'id_spk' => $id
                    )
                );

                $legalPabrik = $this->dmaster->get_karyawan(
                    array(
                        'leg_level_id' => 2,
                        'plant' => $spk->plant
                    )
                );

                $karyawans = array_merge($divisiTerkait, $legalPabrik);

                foreach ($karyawans as $karyawan) {
                    if (isset($karyawan->email) && !empty($karyawan->email)) {
                        $email = SPK_EMAIL_DEBUG_MODE ? json_decode(SPK_EMAIL_TESTER) : $karyawan->email;
                        $emailOri = $karyawan->email;
                        $message = $this->load->view('emails/spk_final_draft', compact('spk', 'emailOri'), true);
                        $return = $this->general->send_email_new(
                            array(
                                'subject' => $subject,
                                'from_alias' => 'KiranaKu SPK',
                                'message' => $message,
                                'to' => $email,
                                'attachment' => base_url('assets/' . $spk->files_1)
                            )
                        );
                        if ($return['sts'] == 'NotOK') {
                            return $return;
                            break;
                        }
                    }
                }
                return true;
            } else
                return false;
        } else
            return false;
    }

    private function send_konfirmasi_final($id = null)
    {
        if (isset($id)) {
            $spk = $this->dspk->get_spk(array(
                'id_spk' => $id,
                'single_row' => true
            ));

            if (isset($spk)) {
                $owner = $this->dmaster->get_karyawan(
                    array(
                        'id_user' => $spk->login_buat,
                        'single_row' => true
                    )
                );

                $subject = 'Pemberitahuan Finalisasi SPK ' . $spk->nama_spk . '.';

                $karyawans = $this->dmaster->get_karyawan(
                    array(
                        'leg_level_id' => 1
                    )
                );

                foreach ($karyawans as $karyawan) {
                    if (isset($karyawan->email)) {
                        $email = SPK_EMAIL_DEBUG_MODE ? json_decode(SPK_EMAIL_TESTER) : $karyawan->email;
                        $emailOri = $karyawan->email;
                        $message = $this->load->view('emails/spk_final', compact('spk', 'emailOri', 'owner'), true);
                        $return = $this->general->send_email_new(
                            array(
                                'subject' => $subject,
                                'from_alias' => 'KiranaKu SPK',
                                'message' => $message,
                                'to' => $email,
//                                'attachment' => base_url('assets/' . $spk->files_1)
                            )
                        );
                        if ($return['sts'] == 'NotOK') {
                            return $return;
                            break;
                        }
                    }
                }
                return true;
            } else
                return false;
        } else
            return false;
    }

    private function save_approve_spk($data)
    {
        $this->general->connectDbPortal();

        $this->db->query("SET ANSI_NULLS ON");
        $this->db->query("SET ANSI_WARNINGS ON");

        if (isset($data['id']) && !empty($data['id'])) {

            $id = $this->generate->kirana_decrypt($data['id']);

            $leg_level_id = $this->data['user']->leg_level_id;

            $approval = $this->dspk->get_approval(
                array(
                    'single_row' => true,
                    'id_spk' => $id,
                    'id_divisi' => $leg_level_id
                )
            );

            if (isset($approval)) {

                $this->dgeneral->begin_transaction();

                $data_row = $this->dgeneral->basic_column(
                    'update',
                    array(
                        'approve' => 'y',
                        'tanggal_approve' => date('Y-m-d')
                    )
                );

                $result = $this->dgeneral->update('tbl_leg_approval', $data_row, array(
                    array(
                        'kolom' => 'id_spk',
                        'value' => $id
                    ),
                    array(
                        'kolom' => 'id_oto_div',
                        'value' => $approval->id_oto_div
                    )
                ));

                $divisis = $this->dspk->get_spk_divisi(
                    array(
                        'id_spk' => $id
                    )
                );

                $approved = $this->dspk->get_approval(
                    array(
                        'id_spk' => $id,
                        'approve' => 'y'
                    )
                );

                /** @var bool $send_email send email konfirmasi spk ketika approval dr divisi sudah selesai semua */
                $send_email = false;

                /** Jika approval sudah disetujui semua divisi, status dirubah menjadi 9 (Confirmed Div Teknis) */
                if (count($divisis) <= count($approved)) {
                    $data_update = $this->dgeneral->basic_column(
                        'update',
                        array('id_status' => 9)
                    );

                    $result_update_spk = $this->dgeneral->update('tbl_leg_spk', $data_update, array(
                        array(
                            'kolom' => 'id_spk',
                            'value' => $id
                        )
                    ));

                    $send_email = true;
                }

                if ($this->dgeneral->status_transaction() === FALSE) {
                    $this->dgeneral->rollback_transaction();
                    $msg = "Periksa kembali data yang dimasukkan";
                    $sts = "NotOK";
                } else {
                    $this->dgeneral->commit_transaction();
                    $msg = "SPK berhasil di approve.";
                    $sts = "OK";
                    if ($send_email)
                        $this->send_approval_divisi($id);
                }
            } else {
                $msg = "Approval SPK untuk divisi anda tidak tersedia.";
                $sts = "NotOK";
            }

        } else {
            $msg = "Periksa kembali data yang dimasukkan";
            $sts = "NotOK";
        }

        $return = array('sts' => $sts, 'msg' => $msg);

        return $return;
    }
	
	// private function save_file($param) {
	private function save_final_draft_xx($data){
        $id_spk = $this->generate->kirana_decrypt($data['id_spk']);
        $spk = $this->dspk->get_spk(
            array(
                'id_spk' => $id_spk,
                'single_row' => true
            )
        );
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
			$folder = SPK_UPLOAD_FOLDER . SPK_UPLOAD_FINAL_DRAFT_FOLDER;
            $uploaddir = KIRANA_PATH_FILE . $folder;
			
			$config['upload_path']   = $uploaddir;
			$config['allowed_types'] = 'pdf|doc|docx|xls|xlsx|7z';
			$config['max_size']      = 5000;
			
			$newname	= array(strtolower($id_spk . '_' . $spk->jenis_spk . '_' . $spk->nama_spk));			
			$file		= $this->general->upload_files($_FILES['file'], $newname, $config);
			$url_file	= str_replace("assets/", "", $file[0]['url']);
			if($file === NULL){
				$msg        = "Upload files error";
				$sts        = "NotOK";
				$return     = array('sts' => $sts, 'msg' => $msg);
				echo json_encode($return);
				exit();
			}
		}
		
		if($spk->tanggal_final==null){ 
			$datetime	= date("Y-m-d H:i:s");
			$data_row = array(
				'files_1' 		=> $url_file,
				'tipe_files1' 	=> pathinfo($url_file, PATHINFO_EXTENSION),
				'size_files1' 	=> $file[0]['size'],
				'nomor_spk' 	=> $data['nomor_spk'],
				'id_status' 	=> 11,
				'tanggal_final' => $datetime
			);
		}else{
			$data_row = array(
				'files_1' 		=> $url_file,
				'tipe_files1' 	=> pathinfo($url_file, PATHINFO_EXTENSION),
				'size_files1' 	=> $file[0]['size'],
				'nomor_spk' 	=> $data['nomor_spk'],
				'id_status'		=> 11
			);
		}
		
		$data_row = $this->dgeneral->basic_column('update', $data_row);
		$this->dgeneral->update("tbl_leg_spk", $data_row, array(
			array(
				'kolom' => 'id_spk',
				'value' => $id_spk
			)
		));
		
		if ($this->dgeneral->status_transaction() === false) {
			$this->dgeneral->rollback_transaction();
			$msg = "Periksa kembali data yang dimasukkan";
			$sts = "NotOK";
		} else { 
            $this->dgeneral->commit_transaction();
            $msg = "Final draft berhasil ditambahkan";
            $sts = "OK";
            $resEmail = $this->send_konfirmasi_final_draft($id_spk);
		}
		$this->general->closeDb();
        $return = array('sts' => $sts, 'msg' => $msg);
        return $return;
	}

    private function save_final_draft($data)
    {
        $this->general->connectDbPortal();

        $this->db->query("SET ANSI_NULLS ON");
        $this->db->query("SET ANSI_WARNINGS ON");

        $this->dgeneral->begin_transaction();

        $id_spk = $this->generate->kirana_decrypt($data['id_spk']);
        $spk = $this->dspk->get_spk(
            array(
                'id_spk' => $id_spk,
                'single_row' => true
            )
        );

        $folder = SPK_UPLOAD_FOLDER . SPK_UPLOAD_FINAL_DRAFT_FOLDER;

        if($spk->tanggal_final==null){
			$datetime	= date("Y-m-d H:i:s");
			$data_row = array(
				'nomor_spk' => $data['nomor_spk'],
				'id_status' => 11,
				'tanggal_final' => $datetime
			);
		}else{
			$data_row = array(
				'nomor_spk' => $data['nomor_spk'],
				'id_status' => 11
			);
		}

        $upload_error = $this->general->check_upload_file('dokumen', true);

        if (isset($_FILES['dokumen']) and empty($upload_error)) {
            $uploaddir = KIRANA_PATH_FILE . $folder;
            if (!file_exists($uploaddir)) {
                mkdir($uploaddir, 0777, true);
            }

            $config['upload_path'] = $uploaddir;
            $config['allowed_types'] = 'jpeg|jpg|png|pdf|dot|doc|docx|xls|xlsx|zip|rar|7zip|7z';
            $config['max_size'] = 5000;
            $config['remove_spaces'] = true;
            $config['overwrite'] = true;

            $filename = strtolower($id_spk . '_' . $spk->jenis_spk . '_' . $spk->nama_spk);
            $config['file_name'] = str_replace('.','_',$filename);

            $this->load->library('upload', $config);

            $upload_error = null;

            if ($this->upload->do_upload('dokumen')) {
                $upload_data = $this->upload->data();
                $data_row['files_1'] = KIRANA_PATH_FILE_FOLDER . $folder . $upload_data['file_name'];
                $data_row['tipe_files1'] = substr($upload_data['file_ext'], 1);
                $data_row['size_files1'] = $upload_data['file_size'];
            } else {
                $upload_error = $this->upload->display_errors('', '');
            }
        }

        $data_row = $this->dgeneral->basic_column('update', $data_row);
        $result = $this->dgeneral->update('tbl_leg_spk', $data_row, array(
            array(
                'kolom' => 'id_spk',
                'value' => $id_spk
            )
        ));

        if (isset($upload_error)) {
            $this->dgeneral->rollback_transaction();
            $msg = $upload_error;
            $sts = "NotOK";
            if (isset($data_row['files_1']))
                unlink(KIRANA_PATH_ASSETS . $data_row['files_1']);
        } else if ($this->dgeneral->status_transaction() === FALSE) {
            $this->dgeneral->rollback_transaction();
            $msg = "Periksa kembali data yang dimasukkan";
            $sts = "NotOK";
        } else {
            $this->dgeneral->commit_transaction();
            $msg = "Final draft berhasil ditambahkan";
            $sts = "OK";
            $resEmail = $this->send_konfirmasi_final_draft($id_spk);
        }

        $return = array('sts' => $sts, 'msg' => $msg);

        return $return;
    }
	
    private function save_drop_spk($data)
    { 
		$datetime 	= date("Y-m-d H:i:s");
		$id_spk		= (isset($_POST['id_spk']) ? $this->generate->kirana_decrypt($_POST['id_spk']) : NULL);
		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();
		$data_row = array(
			'id_spk' 		=> $id_spk,
			'id_oto_div'	=> 0,
			'approve' 		=> 'n',
			'id_status'		=> 18
		);

		$data_row = $this->dgeneral->basic_column("insert", $data_row);
		$this->dgeneral->insert("tbl_leg_approval", $data_row);  

		//xx gambar baru
		if (isset($_FILES['gambar'])) {
			$jml_file = count($_FILES['gambar']['name']);
			if ($jml_file > 3) {
				$this->dgeneral->rollback_transaction();
				$msg    = "You can only upload maximum 3 files";  
				$sts    = "NotOK";
				$return = array('sts' => $sts, 'msg' => $msg);
				echo json_encode($return);
				exit();
			}
			
			$config['upload_path']   = $this->general->kirana_file_path($this->router->fetch_module());
			$config['allowed_types'] = 'doc|docx|xls|xlsx|pdf';
			$newname = array();
			for ($i = 0; $i < $jml_file; $i++) {
				if (isset($_FILES['gambar']) && $_FILES['gambar']['error'][$i] == 0 && $_FILES['gambar']['name'][$i] !== "") {
					array_push($newname, "CANCEL_". $id_spk . "_" . $i);
				}
			}

			if (count($newname) > 0) {
				$file_img = $this->general->upload_files($_FILES['gambar'], $newname, $config);
				if ($file_img) {
					$data_batch = array(); 
					foreach ($file_img as $dt) {
						$data_row     = array(
							"id_status"		=> 18,
							"file_cancel"	=> str_replace('assets/file/','file/',$dt['url']),
							"tipe_file_cancel"	=> pathinfo($dt['url'], PATHINFO_EXTENSION),
						); 
					}
					$result = $this->dgeneral->update('tbl_leg_spk', $data_row, array(
						array(
							'kolom' => 'id_spk',
							'value' => $id_spk
						)
					)); 
				}
			}
		}
		//xx gambar baru sampe sini
		if ($this->dgeneral->status_transaction() === false) {
			$this->dgeneral->rollback_transaction();
			$msg = "Periksa kembali data yang dimasukkan";
			$sts = "NotOK";
		} else {
			$this->dgeneral->commit_transaction();
			$msg = "Data berhasil di hapus";
			$sts = "OK";
		}
		
		//sent email dari sini
		setlocale(LC_ALL, 'id_ID', 'IND', 'id_ID.UTF8', 'id_ID.UTF-8', 'id_ID.8859-1', 'IND.UTF8', 'IND.UTF-8', 'IND.8859-1', 'Indonesian.UTF8', 'Indonesian.UTF-8', 'Indonesian.8859-1', 'Indonesian', 'Indonesia', 'id', 'ID');
		$config['protocol'] = 'smtp';
		$config['smtp_host'] = 'mail.kiranamegatara.com';
		$config['smtp_user'] = 'no-reply@kiranamegatara.com';
		$config['smtp_pass'] = '1234567890';
		$config['smtp_port'] = '465';
		$config['smtp_crypto'] = 'ssl';
		$config['charset'] = 'iso-8859-1';
		$config['wordwrap'] = true;
		$config['mailtype'] = 'html';
		
		try {
			$spk = $this->dspk->get_spk(
				array(
					'id_spk' => $id_spk,
					'single_row' => true
				)
			); 
			$data_email = $this->get_email('array', $spk->plant, $spk->id_status, $id_spk);	//18 status spk drop
			foreach($data_email as $dt){
				$subject = 'Konfirmasi Drop SPK';
				$this->load->library('email', $config);
				$this->email->from('no-reply@kiranamegatara.com', 'PT. KIRANAMEGATARA');
				$this->email->subject($subject); 
				// $this->email->to($dt->email);
				$this->email->to('frans.darmawan@kiranamegatara.com');
				$message =	'<p><b>Kepada Bpk/Ibu '.$dt->nama.'</b></p>'; 
				$message .=	'<p>Berikut adalah konfirmasi Drop SPK dari:</p>';
				$message .=	'<table>';
				$message .=	'	<tr><td>Plant</td><td>: '.$spk->plant.'</td></tr>';
				$message .=	'	<tr><td>Jenis SPK</td><td>: '.$spk->jenis_spk.'</td></tr>';
				$message .=	'	<tr><td>Perihal</td><td>: '.$spk->perihal.'</td></tr>';
				$message .=	'	<tr><td>SPPKP</td><td>: '.$spk->SPPKP.'</td></tr>';
				$message .=	'</table>';
				// echo $message; 	
				$this->email->message($message);
				$this->email->send();
			}	
		} catch (Exception $e) {
			$msg = $e->getMessage();
			$sts = "NotOK";
			$return = array('sts' => $sts, 'msg' => $msg);
			echo json_encode($return);
			exit();
		}			
		//sent email sampe sini
		
		$this->general->closeDb();
		$return = array('sts' => $sts, 'msg' => $msg);
        return $return;
    }

    private function save_final($data)
    {
        $this->general->connectDbPortal();

        $this->db->query("SET ANSI_NULLS ON");
        $this->db->query("SET ANSI_WARNINGS ON");

        $this->dgeneral->begin_transaction();

        $id_spk = $this->generate->kirana_decrypt($data['id_spk']);
        $spk = $this->dspk->get_spk(
            array(
                'id_spk' => $id_spk,
                'single_row' => true
            )
        );

        if (isset($spk)) {
            $folder = SPK_UPLOAD_FOLDER . SPK_UPLOAD_SPK_FOLDER;

            $data_row = array(
                'tanggal_kirim' => date_create($data['tanggal_kirim'])->format('Y-m-d'),
                'no_resi' => $data['no_resi'],
                'id_status' => 12
            );

            $upload_error = $this->general->check_upload_file('dokumen', false);

            if (isset($_FILES['dokumen']) and empty($upload_error) and $_FILES['dokumen']['size'] > 0) {
                $uploaddir = KIRANA_PATH_FILE . $folder;
                if (!file_exists($uploaddir)) {
                    mkdir($uploaddir, 0777, true);
                }

                $config['upload_path'] = $uploaddir;
                $config['allowed_types'] = 'jpeg|jpg|png|pdf|dot|doc|docx|xls|xlsx|zip|rar|7z';
                $config['max_size'] = 5000;
                $config['remove_spaces'] = true;
                $config['overwrite'] = true;

                $filename = strtolower($id_spk . '_' . $spk->jenis_spk . '_' . $spk->nama_spk);
                $config['file_name'] = str_replace('.','_',$filename);

                $this->load->library('upload', $config);

                $upload_error = null;

                if ($this->upload->do_upload('dokumen')) {
                    $upload_data = $this->upload->data();
                    $data_row['files'] = KIRANA_PATH_FILE_FOLDER . $folder . $upload_data['file_name'];
                    $data_row['tipe_files'] = substr($upload_data['file_ext'], 1);
                    $data_row['size_files'] = $upload_data['file_size'];
                } else {
                    $upload_error = $this->upload->display_errors('', '');
                }
            }

            $data_row = $this->dgeneral->basic_column('update', $data_row);
            $result = $this->dgeneral->update('tbl_leg_spk', $data_row, array(
                array(
                    'kolom' => 'id_spk',
                    'value' => $id_spk
                )
            ));

            if (isset($upload_error)) {
                $this->dgeneral->rollback_transaction();
                $msg = $upload_error;
                $sts = "NotOK";
                if (isset($data_row['files']))
                    unlink(KIRANA_PATH_ASSETS . $data_row['files']);
            } else if ($this->dgeneral->status_transaction() === FALSE) {
                $this->dgeneral->rollback_transaction();
                $msg = "Periksa kembali data yang dimasukkan";
                $sts = "NotOK";
            } else {
                $this->dgeneral->commit_transaction();
                $msg = "Final SPK berhasil ditambahkan";
                $sts = "OK";
                $this->send_konfirmasi_final($id_spk);
            }
        } else {
            $msg = "Periksa kembali data yang dimasukkan";
            $sts = "NotOK";
        }


        $return = array('sts' => $sts, 'msg' => $msg);

        return $return;
    }
    private function save_cancel_spk($data)
    { 
		$datetime 	= date("Y-m-d H:i:s");
		$id_spk		= (isset($_POST['id_spk']) ? $this->generate->kirana_decrypt($_POST['id_spk']) : NULL);
		$alasan		= (isset($_POST['alasan']) ? $_POST['alasan'] : NULL);
		$keterangan	= (isset($_POST['keterangan']) ? $_POST['keterangan'] : NULL);
		$status_akhir	= (isset($_POST['status_akhir']) ? $_POST['status_akhir'] : NULL);
		
		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();
		$data_row_log = array(
			'id_spk' 		=> $id_spk, 
			'id_oto_div'	=> 0,
			'approve' 		=> 'n',
			'id_status'		=> 17
		); 

		$data_row_log = $this->dgeneral->basic_column("insert", $data_row_log);
		$this->dgeneral->insert("tbl_leg_approval", $data_row_log);

		$data_row = array(
			'id_status'		=> 17, 
			'alasan_cancel'	=> $alasan,
			'keterangan_cancel'	=> $keterangan,
			'status_akhir'	=> $status_akhir
		);
		$data_row = $this->dgeneral->basic_column('update', $data_row);
		$result = $this->dgeneral->update('tbl_leg_spk', $data_row, array(
			array(
				'kolom' => 'id_spk',
				'value' => $id_spk 
			)
		)); 
		if ($this->dgeneral->status_transaction() === false) {
			$this->dgeneral->rollback_transaction();
			$msg = "Periksa kembali data yang dimasukkan";
			$sts = "NotOK";
		} else {
			$this->dgeneral->commit_transaction();
			$msg = "Data berhasil di cancel";
			$sts = "OK";
		}
		
		//sent email dari sini
		setlocale(LC_ALL, 'id_ID', 'IND', 'id_ID.UTF8', 'id_ID.UTF-8', 'id_ID.8859-1', 'IND.UTF8', 'IND.UTF-8', 'IND.8859-1', 'Indonesian.UTF8', 'Indonesian.UTF-8', 'Indonesian.8859-1', 'Indonesian', 'Indonesia', 'id', 'ID');
		$config['protocol'] = 'smtp';
		$config['smtp_host'] = 'mail.kiranamegatara.com';
		$config['smtp_user'] = 'no-reply@kiranamegatara.com';
		$config['smtp_pass'] = '1234567890';
		$config['smtp_port'] = '465';
		$config['smtp_crypto'] = 'ssl';
		$config['charset'] = 'iso-8859-1';
		$config['wordwrap'] = true;
		$config['mailtype'] = 'html';
		
		try {
			$spk = $this->dspk->get_spk(
				array(
					'id_spk' => $id_spk,
					'single_row' => true 
				)
			); 
			$data_email = $this->get_email('array', $spk->plant, $spk->id_status, $id_spk);	//17 status cancel
			foreach($data_email as $dt){
				$subject = 'Konfirmasi Cancel SPK';
				$this->load->library('email', $config);
				$this->email->from('no-reply@kiranamegatara.com', 'PT. KIRANAMEGATARA');
				$this->email->subject($subject); 
				// $this->email->to($dt->email);
				 
				// $this->email->to('HENDRA.SITORUS@KIRANAMEGATARA.COM');   
				$this->email->to('FRANS.DARMAWAN@KIRANAMEGATARA.COM');  
				// $this->email->to('AIRIZA.PERDANA@KIRANAMEGATARA.COM');  
				// $this->email->to('skygod.shohoku@gmail.com');  
				// $this->email->to('lukman.hakim@kiranamegatara.com');
				// $this->email->to('lnn.hakim@gmail.com');
				$message =	'<p><b>Kepada Bpk/Ibu '.$dt->nama.'</b></p>'; 
				$message .=	'<p>Berikut adalah konfirmasi Cancel SPK dari:</p>';
				$message .=	'<table>';
				$message .=	'	<tr><td>Plant</td><td>: '.$spk->plant.'</td></tr>';
				$message .=	'	<tr><td>Jenis SPK</td><td>: '.$spk->jenis_spk.'</td></tr>';
				$message .=	'	<tr><td>Perihal</td><td>: '.$spk->perihal.'</td></tr>';  
				$message .=	'	<tr><td>SPPKP</td><td>: '.$spk->SPPKP.'</td></tr>';
				$message .=	'</table>';
				// echo $message; 	
				$this->email->message($message);
				$this->email->send();
			}	
		} catch (Exception $e) {
			$msg = $e->getMessage();
			$sts = "NotOK";
			$return = array('sts' => $sts, 'msg' => $msg);
			echo json_encode($return);
			exit();
		}			
		//sent email sampe sini
		
		$this->general->closeDb();
		$return = array('sts' => $sts, 'msg' => $msg);
		echo json_encode($return);
    }

    private function get_komentar($data)
    {
        if (isset($data['id'])) {
            $id = $this->generate->kirana_decrypt($data['id']);
            $nik = $this->data['user']->nik;
			$jumlah_komentar  = (isset($_POST['jumlah_komentar']) ? $_POST['jumlah_komentar'] : 0);

            $this->general->connectDbPortal();


            $spk = $this->dspk->get_spk(
                array(
                    'id_spk' => $id,
                    'single_row' => true
                )
            );

            $result = $this->general->generate_encrypt_json(
                $this->dspk->get_komentar(array(
                    'id_spk' => $id
                )),
                array('id_spk')
            );

            foreach ($result as $komentar) {
				//update user_read tbl_leg_komentar
				if($jumlah_komentar!=0){
					$arr_read 	 = explode('.',$komentar->user_read);
					if (in_array($nik, $arr_read) == false) {
						$user_read	 = $komentar->user_read.'.'.$nik;
						$data_update = array(
							"user_read"    => $user_read
						);
						$data_update = $this->dgeneral->basic_column("update", $data_update);
						$this->dgeneral->update("tbl_leg_komentar", $data_update, array(
							array(
								'kolom' => 'id_komentar',
								'value' => $komentar->id_komentar
							)
						));
					} 
				}
				
				
                if ($komentar->gender == "l") {
                    $image = base_url() . "assets/apps/img/avatar5.png";
                } else {
                    $image = base_url() . "assets/apps/img/avatar2.png";
                }

                if ($komentar->gambar) {
                    $data_image = "http://kiranaku.kiranamegatara.com/home/" . strtolower($komentar->gambar);
                    $headers = get_headers($data_image);
                    if ($headers[0] == "HTTP/1.1 200 OK") {
                        $image = $data_image;
                    } else {
                        $links = explode("/", $komentar->gambar);
                        $data_image = "http://kiranaku.kiranamegatara.com/home/" . $links[0] . "/" . $links[1] . "/" . strtoupper($links[2]);
                        $headers = get_headers($data_image);
                        if ($headers[0] == "HTTP/1.1 200 OK") {
                            $image = $data_image;
                        }
                    }
                }

                $komentar->me = false;
                if ($nik == $komentar->nik)
                    $komentar->me = true;

                $komentar->gambar = $image;
				$komentar->komentar = nl2br($komentar->komentar);
            }

            return array('sts' => 'OK', 'data' => $result, 'spk' => $spk);
        } else {
            return array('sts' => 'NotOK', 'msg' => 'ID tidak ditemukan');
        }
    }

    private function save_komentar($data)
    {
        $this->general->connectDbPortal();

        $this->db->query("SET ANSI_NULLS ON");
        $this->db->query("SET ANSI_WARNINGS ON");

        $this->dgeneral->begin_transaction();

        $id_spk = $this->generate->kirana_decrypt($data['id_spk']);
        $spk = $this->dspk->get_spk(
            array(
                'id_spk' => $id_spk,
                'single_row' => true
            )
        );

        if (isset($spk)) {

            $data_row = array(
                'jam' => date('H:i:s'),
                'user_input' => $this->data['user']->nik,
                'id_spk' => $id_spk,
                'komentar' => $data['komentar'],
				'user_read' => $this->data['user']->nik
            );

            $data_row = $this->dgeneral->basic_column('insert', $data_row);

            $result = $this->dgeneral->insert('tbl_leg_komentar', $data_row);

            if ($this->dgeneral->status_transaction() === FALSE) {
                $this->dgeneral->rollback_transaction();
                $msg = "Periksa kembali data yang dimasukkan";
                $sts = "NotOK";
            } else {
                $this->dgeneral->commit_transaction();

                return $this->get_komentar(array(
                    'id' => $data['id_spk']
                ));
            }
        } else {
            $msg = "Periksa kembali data yang dimasukkan";
            $sts = "NotOK";
        }

        $return = array('sts' => $sts, 'msg' => $msg);

        return $return;
    }

    private function get_template($id)
    {
        $this->load->library('zip');

        $jenis_spk = $this->dmaster->get_jenis_spk(
            array(
                'id_jenis_spk' => $id,
                'single_row' => true
            )
        );

        if (isset($jenis_spk)) {
            $templates = $this->dmaster->get_oto_jenis_spk(
                array(
                    'id_jenis_spk' => $id
                )
            );

            foreach ($templates as $template) {
                $this->zip->read_file(KIRANA_PATH_ASSETS . $template->files);
            }

            $this->zip->download($jenis_spk->jenis_spk . '-draft-template.zip');
        }
    }

    private function get_attachments($data)
    {
        if (isset($data['id'])) {
            $id = $this->generate->kirana_decrypt($data['id']);

            $this->general->connectDbPortal();

            $list = $this->dspk->get_spk(array(
                'id_spk' => $id,
                'single_row' => true
            ));

            $leg_level_id = $this->data['user']->leg_level_id;

            if ($data['tipe'] == 'template') {
                $datas = $this->general->generate_encrypt_json(
                    $this->dspk->get_spk_template(
                        array(
                            'id_spk' => $list->id_spk
                        )
                    ),
                    array('id_oto_jenis', 'id_upload_template')
                );

                $list->id_spk = $this->generate->kirana_encrypt($list->id_spk);

                foreach ($datas as $template) {
                    if (isset($template->files))
                        $template->files = site_url('spk/view_file?file=' . $template->files);

                    if (empty($template->id_upload_template)) {
                        $uploadStatus = false;

                        $linkAttach = "";
                        if ($leg_level_id == 1 || $leg_level_id == 2) {
                            $linkAttach = "<li>"
                                . "<a href='javascript:void(0)' class='spk-upload' data-tipe='template' data-id_spk='"
                                . $list->id_spk . "' data-id_oto_jenis='"
                                . $template->id_oto_jenis . "'>Upload</a>"
                                . "</li>";
                        }
                    } else {
                        $uploadStatus = true;
                        $linkAttach = "<li><a href='" . $template->files . "' data-fancybox>View attachment</a></li>";
                    }

                    if ($leg_level_id == 1) {
                        $linkEdit = "";

                        if (in_array($list->id_status, array(1, 2, 3, 7, 9, 11, 13))) {
                            if (isset($template->id_upload_template))
                                $linkEdit = "<li>"
                                    . "<a href='javascript:void(0)' class='spk-edit-upload' data-tipe='template' "
                                    . " data-id_spk='" . $list->id_spk . "'"
                                    . " data-id_oto_jenis='" . $template->id_oto_jenis . "'"
                                    . " data-id_upload='" . $template->id_upload_template . "'"
                                    . ">Edit</a>"
                                    . "</li>";
                        }

                        $template->links = $linkAttach . $linkEdit;
					}else if ($leg_level_id == 2) { 
                        $linkEdit = ""; 
                        // if ($list->id_status == 7) { 
						if (in_array($list->id_status, array(1,2,7))) {
                            if (isset($template->id_upload_template)) 
                                $linkEdit = "<li>" 
                                    . "<a href='javascript:void(0)' class='spk-edit-upload' data-tipe='template' " 
                                    . " data-id_spk='" . $list->id_spk . "'" 
                                    . " data-id_oto_jenis='" . $template->id_oto_jenis . "'" 
                                    . " data-id_upload='" . $template->id_upload_template . "'" 
                                    . ">Edit</a>" 
                                    . "</li>"; 
                        } 
 
                        $template->links = $linkAttach . $linkEdit; 
                    }else{
                        $linkEdit = "";
                        if (in_array($list->id_status, array(1, 2, 7))) {
                            if (isset($template->id_upload_template))
                                $linkEdit = "<li>"
                                    . "<a href='javascript:void(0)' class='spk-edit-upload' data-tipe='template' "
                                    . " data-id_spk='" . $list->id_spk . "'"
                                    . " data-id_oto_jenis='" . $template->id_oto_jenis . "'"
                                    . " data-id_upload='" . $template->id_upload_template . "'"
                                    . ">Edit</a>"
                                    . "</li>";
                        }

                        $template->links = $linkAttach . $linkEdit;
                    }
                    $template->uploadStatus = $uploadStatus;
                }
            } else if ($data['tipe'] == 'vendor'){
                $datas = $this->general->generate_encrypt_json(
                    $this->dspk->get_spk_vendor(
                        array(
                            'id_spk' => $list->id_spk
                        )
                    ),
                    array('id_oto_vendor', 'id_upload_vendor')
                );

                $list->id_spk = $this->generate->kirana_encrypt($list->id_spk);

                foreach ($datas as $vendor) {
                    if (isset($vendor->files))
                        $vendor->files = site_url('spk/view_file?file=' . $vendor->files);

                    if (empty($vendor->id_upload_vendor)) {
                        $uploadStatus = false;
                        $linkAttach = "";
                        if ($leg_level_id == 1 or $leg_level_id == 2)
                            $linkAttach = "<li>"
                                . "<a href='javascript:void(0)' class='spk-upload' data-tipe='vendor' data-id_spk='"
                                . $list->id_spk . "' data-id_oto_vendor='"
                                . $vendor->id_oto_vendor . "'>Upload</a>"
                                . "</li>";
                    } else {
                        $uploadStatus = true;
                        $linkAttach = "<li><a href='" . $vendor->files . "' data-fancybox>View attachment</a></li>";
                    }

                    if ($leg_level_id == 1) {
                        $linkEdit = "";
                        if (in_array($list->id_status, array(1, 2, 3, 7, 9, 11, 13))) {
                            if (isset($vendor->id_upload_vendor))
                                $linkEdit = "<li>"
                                    . "<a href='javascript:void(0)' class='spk-edit-upload' data-tipe='vendor' "
                                    . " data-id_spk='" . $list->id_spk . "'"
                                    . " data-id_oto_vendor='" . $vendor->id_oto_vendor . "'"
                                    . " data-id_upload='" . $vendor->id_upload_vendor . "'>Edit</a>"
                                    . "</li>";
                        }

                        $vendor->links = $linkAttach . $linkEdit;
                    }else if ($leg_level_id == 2) {
                        $linkEdit = "";
                        // if (in_array($list->id_status, array(7))) {
                        if (in_array($list->id_status, array(1,2,7))) {
                            if (isset($vendor->id_upload_vendor))
                                $linkEdit = "<li>"
                                    . "<a href='javascript:void(0)' class='spk-edit-upload' data-tipe='vendor' "
                                    . " data-id_spk='" . $list->id_spk . "'"
                                    . " data-id_oto_vendor='" . $vendor->id_oto_vendor . "'"
                                    . " data-id_upload='" . $vendor->id_upload_vendor . "'>Edit</a>"
                                    . "</li>";
                        }

                        $vendor->links = $linkAttach . $linkEdit;
                    } 
					else
                        $vendor->links = $linkAttach;

                    $vendor->uploadStatus = $uploadStatus;
                }
            } else if($data['tipe'] == 'vendor_dokumen'){
                $datas = $this->general->generate_encrypt_json(
                    $this->dspk->get_spk_vendor_dokumen(
                        array(
                            'id_spk' 	=> $list->id_spk,
							'lifnr' 	=> $list->lifnr  
                        )
                    ), 
                    array('id_oto_vendor', 'id_upload_vendor')
                ); 
                foreach ($datas as $vendor) {
					$vendor->files = site_url('spk/view_file?file=' . $vendor->link);
					if($vendor->link!=null){
						$vendor->links = "<li><a href='" . $vendor->files . "' data-fancybox>View attachment</a></li>";
						$vendor->uploadStatus = true;
					}else{
						$vendor->links = "";
						$vendor->uploadStatus = false;
					}
                }
            } else {
                $datas = $this->general->generate_encrypt_json(
                    $this->dspk->get_spk_vendor_dokumen_kualifikasi( 
                        array(
                            'id_spk' 		=> $list->id_spk,
                            'id_jenis_spk' 	=> $list->id_jenis_spk,
							'lifnr' 		=> $list->lifnr,
							'id_kualifikasi'=> $list->id_kualifikasi	
                        )
                    ), 
                    array('id_kualifikasi_spk')
                );
                foreach ($datas as $vendor) {
					$vendor->files = site_url('spk/view_file?file=' . $vendor->link);
					if($vendor->link!=null){
						$vendor->links = "<li><a href='" . $vendor->files . "' data-fancybox>View attachment</a></li>";
						$vendor->uploadStatus = true;
					}else{
						$vendor->links = NULL; 
						$vendor->uploadStatus = false;
					}
                }
            }

            $list->table_dokumen = $this->get_spk_table_dokumen($list->id_spk, $datas, $data['tipe']);

            return array('sts' => 'OK', 'data' => $list->table_dokumen);
        } else {
            return array('sts' => 'NotOK', 'msg' => 'ID tidak ditemukan');
        }
    }

    public function view_file()
    {
        $file = $this->input->get('file');
        if (isset($file) && !empty($file)) {
            $data_image = site_url('assets/' . $file);
            $headers = get_headers($data_image);
            if ($headers[0] == "HTTP/1.1 200 OK") {
                $image = $data_image;
                redirect($image);
            } else {
                $data_image = "http://10.0.0.18/home/" . $file;
                $headers = get_headers($data_image);
                if ($headers[0] == "HTTP/1.1 200 OK") {
                    $image = $data_image;
                    redirect($image);
                } else
                    show_404();
            }
        } else
            show_404();
    }
	private function get_email($array = NULL, $plant = NULL, $id_status = NULL, $id_spk = NULL) {
		$email	= $this->dspk->get_data_email("open", $plant, $id_status, $id_spk);
		if ($array) {
			return $email;
		} else {
			echo json_encode($email);
		}
	}

}