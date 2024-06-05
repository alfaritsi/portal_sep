<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @application  : SPK Report - Controller
 * @author     : Octe Reviyanto Nugroho
 * @contributor  :
 * 1. <insert your fullname> (<insert your nik>) <insert the date>
 * <insert what you have modified>
 * 2. <insert your fullname> (<insert your nik>) <insert the date>
 * <insert what you have modified>
 * etc.
 */
class Report extends MX_Controller
{
    private $data;

    public function __construct()
    {
        parent::__construct();
        $this->data['module'] = "Report";
        $this->data['user'] = $this->general->get_data_user();
        $this->load->model('dmaster');
        $this->load->model('dspk');
    }

    public function dokumen()
    {
        $this->general->check_access();

        $this->data['title'] = "Report Dokumen";

        $this->general->connectDbPortal();

        $leg_level_id = base64_decode($_SESSION['-leg_level_id-']);

        $filter = $this->input->post();

        $tanggal_awal = date('Y-m-d', strtotime('-3 months'));
        $tanggal_akhir = date('Y-m-d');

        if (isset($filter['tanggal_awal']))
            $tanggal_awal = date_create($filter['tanggal_awal'])->format('Y-m-d');

        if (isset($filter['tanggal_akhir']))
            $tanggal_akhir = date_create($filter['tanggal_akhir'])->format('Y-m-d');

        $this->data['tanggal_awal'] = $tanggal_awal;
        $this->data['tanggal_akhir'] = $tanggal_akhir;

        $id_plant = (isset($filter['id_plant']) and !empty($filter['id_plant'])) ? $filter['id_plant'] : null;

        $this->data['id_plant'] = $id_plant;

        if ($leg_level_id == 2) {
            if(!isset($id_plant))
            {
                $plant = $this->general->get_master_plant(array($this->data['user']->gsber), null, null, 'ERP');
                $id_plant = $plant[0]->id_pabrik;
            }
            $list = $this->dspk->get_spk(
                array(
//                    'id_status' => 12,
                    'tanggal_berlaku_spk' => array($tanggal_awal, $tanggal_akhir),
                    'id_plant' => $id_plant,
                    'nik' => $this->data['user']->nik
                )
            );
        } else {
            $list = $this->dspk->get_spk(
                array(
//                    'id_status' => 12,
                    'tanggal_berlaku_spk' => array($tanggal_awal, $tanggal_akhir),
                    'id_plant' => $id_plant
                )
            );
        }

        foreach ($list as $spk) {
            $divisis = $this->dspk->get_spk_divisi(
                array(
                    'id_spk' => $spk->id_spk
                )
            );

            $spk->id_spk = $this->generate->kirana_encrypt($spk->id_spk);

            $spk->table_divisi = $this->get_spk_table_divisi($spk->id_spk, $divisis);
        }

        $this->data['list'] = $list;

        // $plants = $this->dgeneral->get_master_plant();
        $plants = $this->dgeneral->get_master_plant(null, null, null, 'ERP');

        $this->data['plants'] = $plants;

        $this->load->view('reports/reportdokumen', $this->data);
    }


    private function get_spk_table_divisi($id_spk = null, $divisis = array())
    {
        return $this->load->view('transaction/includes/table_divisi', compact('divisis', 'id_spk'), true);
    }
}