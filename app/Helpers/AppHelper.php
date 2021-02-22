<?php

    const LIMIT = 10;
    const CA_SI = 'ca_si';
    const BAI_HAT = 'bai_hat';
    const VIDEO = 'video';
    const TAC_GIA = 'tac_gia';
    const DOI_TAC = 'doi_tac';

    const CONTRACT_TYPE = [
        CA_SI   => 'Ca sĩ',
        VIDEO   => 'Video',
        TAC_GIA => 'Tác Giả',
        DOI_TAC => 'Đối Tác',
    ];

    const CONTENT_TYPE = [
        BAI_HAT   => 'Bài hát',
        VIDEO   => 'Video',
        TAC_GIA => 'Tác Giả',
    ];

    const NHAP = 'nhap';
    const CON_HAN = 'con_han';
    const HET_HAN = 'het_han';
    const SAP_HET_HAN = 'sap_het_han';
    const DA_THANH_LY = 'da_thanh_ly';
    const CHUA_THANH_TOAN = 'chua_thanh_toan';
    const DA_THANH_TOAN = 'da_thanh_toan';
    const STATUS_PUBLIC = 'public';
    const STATUS_BLOCK = 'block';


    const USER_STATUS  = [
        NHAP=>"Nháp",
        STATUS_PUBLIC=>"Public",
        STATUS_BLOCK=>"Block",
    ];
    const CONTRACT_STATUS = [
        NHAP        => 'Nháp',
        CON_HAN     => 'Còn hạn',
        HET_HAN     => 'Hết hạn',
        SAP_HET_HAN     => 'Sắp Hết hạn',
        DA_THANH_LY => 'Đã thanh lý',
    ];
    const INVOICE_STATUS =[
      NHAP=>'Nháp',
      CHUA_THANH_TOAN=>'Chưa thanh toán',
      DA_THANH_TOAN=>'Đã thanh toán',
    ];

    const INVOICE_TYPE_PERSONAL = 'personal';
    const INVOICE_TYPE_COMPANY = 'company';

    const INVOICE_TYPES = [
        INVOICE_TYPE_PERSONAL=>'Ca sỹ, tác giả',
        INVOICE_TYPE_COMPANY=>'Công ty',
    ];

    const ITEMS_PROVIDED  =[
        'Nhạc chờ',
        'nhạc số',
        'video'
    ];
    const SCOPE_OF_SUPPLY  =[
        'Viettel',
        'MobiFone',
        'Vina',
        'Youtube',
        'Sportify',
        'Sportify',
        'Believe',
    ];
    function get_file_url($file_id,$size="thumb",$resize = true){
        if(empty($file_id)) return null;
        return \Modules\Media\Helpers\FileHelper::url($file_id,$size,$resize);
    }

    function contract_status_html($value)
    {
        $html = '';
        switch ($value) {
            case "con_han":
                $html = '<span class="badge badge-success">Còn hạn</span>';
                break;
            case "het_han":
                $html = '<span class="badge badge-danger">Hết hạn</span>';
                break;
            case "da_thanh_ly":
                $html = '<span class="badge badge-secondary="><i></i></span>';
                break;
            default:
                $html = '<span class="badge badge-secondary">Nháp</span>';
                break;
        }
        return $html;
    }
