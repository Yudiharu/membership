<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Route::get('/', function () {
//    return view('welcome');
//});

Auth::routes();

Route::redirect('/','home');
Route::get('/home', 'HomeController@index')->name('home');
Route::get('start','StartController@index')->name('start');
Route::post('start', 'StartController@go_to')->name('start.go_to');
// Route::post('login', 'LoginController@check')->name('login.check');
Route::post('/home', 'HomeController@savechat')->name('home.savechat');

// Auth()->loginUsingId(1);
// dd(Auth()->user()->kode_company);
// Route::get('testing', function ()
// {
//     dd(Auth()->user()->kode_company);
// });


Route::middleware(['auth'])->prefix('admin')->group(function () {

    Route::get('export', 'MyController@export')->name('export');
    Route::get('importExportView', 'MyController@importExportView');
    Route::post('import', 'MyController@import')->name('import');

    /**
     * Re-Open
     */
    Route::post('reopen/change2', 'ReopenController@change2')->name('reopen.change2');
    Route::post('reopen/change', 'ReopenController@change')->name('reopen.change');
    Route::get('reopen/anydata', 'ReopenController@anyData')->name('reopen.data');
    Route::post('reopen/updateAjax', 'ReopenController@updateAjax')->name('reopen.ajaxupdate');
    Route::resource('reopen', 'ReopenController');

    /**
     * Endofmonth
     */
    Route::post('endofmonth/change', 'EndofmonthController@change')->name('endofmonth.change');
    Route::get('endofmonth/anydata', 'EndofmonthController@anyData')->name('endofmonth.data');
    Route::post('endofmonth/updateAjax', 'EndofmonthController@updateAjax')->name('endofmonth.ajaxupdate');
    Route::resource('endofmonth', 'EndofmonthController');
    
     /**
     * Satuan
     */
    Route::get('satuan/anydata', 'SatuanController@anyData')->name('satuan.data');
    Route::post('satuan/updateAjax', 'SatuanController@updateAjax')->name('satuan.ajaxupdate');
    Route::post('satuan/hapus_satuan', 'SatuanController@hapus_satuan')->name('satuan.hapus_satuan');
    Route::post('satuan/edit_satuan', 'SatuanController@edit_satuan')->name('satuan.edit_satuan');
    Route::resource('satuan', 'SatuanController');

    /**
     * Vendor
     */
    Route::get('vendor/anydata', 'VendorController@anyData')->name('vendor.data');
    Route::post('vendor/getcoa', 'VendorController@getcoa')->name('vendor.getcoa');
    Route::post('vendor/updateAjax', 'VendorController@updateAjax')->name('vendor.ajaxupdate');
    Route::post('vendor/hapus_vendor', 'VendorController@hapus_vendor')->name('vendor.hapus_vendor');
    Route::post('vendor/edit_vendor', 'VendorController@edit_vendor')->name('vendor.edit_vendor');
    Route::resource('vendor', 'VendorController');

    /**
     * Member
     */
    Route::get('membership/anydata', 'MembershipController@anyData')->name('membership.data');
    Route::get('membership/exportexcel','MembershipController@exportexcel')->name('membership.exportexcel');
    Route::get('membership/exportpdf','MembershipController@exportPDF')->name('membership.export');
    Route::post('membership/ttd_buat', 'MembershipController@ttd_buat')->name('membership.ttd_buat');
    Route::post('membership/updateAjax', 'MembershipController@updateAjax')->name('membership.ajaxupdate');
    Route::post('membership/hapus_customer', 'MembershipController@hapus_customer')->name('membership.hapus_customer');
    Route::post('membership/edit_customer', 'MembershipController@edit_customer')->name('membership.edit_customer');
    Route::post('membership/uploado', 'MembershipController@uploado')->name('membership.uploado');
    Route::post('membership/uploadi', 'MembershipController@uploadi')->name('membership.uploadi');
    Route::post('membership/uploade', 'MembershipController@uploade')->name('membership.uploade');
    Route::get('membership/{customer}/detail', 'MembershipController@detail')->name('membership.detail');
    Route::resource('membership', 'MembershipController');

    /**
     * Membership Customer
     */
    Route::get('membershipcustomer/anydata', 'MembershipCustomerController@anyData')->name('membershipcustomer.data');
    Route::post('membershipcustomer/store_detail', 'MembershipCustomerController@store_detail')->name('membershipcustomer.store_detail');
    Route::post('membershipcustomer/updateAjax', 'MembershipCustomerController@updateAjax')->name('membershipcustomer.ajaxupdate');
    Route::post('membershipcustomer/updateAjax_detail', 'MembershipCustomerController@updateAjax_detail')->name('membershipcustomer.ajaxupdate_detail');
    Route::post('membershipcustomer/hapus_customer', 'MembershipCustomerController@hapus_customer')->name('membershipcustomer.hapus_customer');
    Route::post('membershipcustomer/edit_customer', 'MembershipCustomerController@edit_customer')->name('membershipcustomer.edit_customer');
    Route::get('membershipcustomer/{customer}/detail', 'MembershipCustomerController@detail')->name('membershipcustomer.detail');
    Route::get('membershipcustomer/getDatabyID', 'MembershipCustomerController@getDatabyID')->name('membershipcustomer.dataDetail');
    Route::post('membershipcustomer/edit_detail', 'MembershipCustomerController@edit_detail')->name('membershipcustomer.edit_detail');
    Route::post('membershipcustomer/hapus_detail', 'MembershipCustomerController@hapus_detail')->name('membershipcustomer.hapus_detail');
    Route::resource('membershipcustomer', 'MembershipCustomerController');
    
    /**
     * Customer Internal
     */
    Route::get('customerinternal/anydata', 'CustomerInternalController@anyData')->name('customerinternal.data');
    Route::post('customerinternal/updateAjax', 'CustomerInternalController@updateAjax')->name('customerinternal.ajaxupdate');
    Route::post('customerinternal/hapus_customer', 'CustomerInternalController@hapus_customer')->name('customerinternal.hapus_customer');
    Route::post('customerinternal/edit_customer', 'CustomerInternalController@edit_customer')->name('customerinternal.edit_customer');
    Route::resource('customerinternal', 'CustomerInternalController');

    /**
     * Kegiatan
     */
    Route::get('kegiatan/anydata', 'KegiatanController@anyData')->name('kegiatan.data');
    Route::post('kegiatan/updateAjax', 'KegiatanController@updateAjax')->name('kegiatan.ajaxupdate');
    Route::post('kegiatan/hapus_kegiatan', 'KegiatanController@hapus_kegiatan')->name('kegiatan.hapus_kegiatan');
    Route::post('kegiatan/edit_kegiatan', 'KegiatanController@edit_kegiatan')->name('kegiatan.edit_kegiatan');
    Route::get('kegiatan/{kode}/detail', 'KegiatanController@detail')->name('kegiatan.detail');
    Route::resource('kegiatan', 'KegiatanController');

    /**
     * Tarif Umum
     */
    Route::get('tarifumum/getDatabyID', 'TarifUmumController@getDatabyID')->name('tarifumum.getDatabyID');
    Route::get('tarifumum/getDatabyID2', 'TarifUmumController@getDatabyID2')->name('tarifumum.getDatabyID2');
    Route::post('tarifumum/updateAjax', 'TarifUmumController@updateAjax')->name('tarifumum.ajaxupdate');
    Route::post('tarifumum/hapus_tarif', 'TarifUmumController@hapus_tarif')->name('tarifumum.hapus_tarif');
    Route::post('tarifumum/edit_tarif', 'TarifUmumController@edit_tarif')->name('tarifumum.edit_tarif');
    Route::post('tarifumum/store_tarif', 'TarifUmumController@store_tarif')->name('tarifumum.store_tarif');
    Route::resource('tarifumum', 'TarifUmumController');

    /**
     * Tarif Kegiatan
     */
    Route::get('tarifkegiatan/anydata', 'TarifKegiatanController@anyData')->name('tarifkegiatan.data');
    Route::get('tarifkegiatan/getDatabyID', 'TarifKegiatanController@getDatabyID')->name('tarifkegiatan.getDatabyID');
    Route::get('tarifkegiatan/getDatabyID2', 'TarifKegiatanController@getDatabyID2')->name('tarifkegiatan.dataDetail2');
    Route::get('tarifkegiatan/getDataSize', 'TarifKegiatanController@getDataSize')->name('tarifkegiatan.getDataSize');
    Route::get('tarifkegiatan/{kode}/detail', 'TarifKegiatanController@detail')->name('tarifkegiatan.detail');

    Route::get('tarifkegiatan/GetDataPallet', 'TarifKegiatanController@GetDataPallet')->name('tarifkegiatan.GetDataPallet');
    Route::get('tarifkegiatan/GetDataAlat', 'TarifKegiatanController@GetDataAlat')->name('tarifkegiatan.GetDataAlat');

    Route::post('tarifkegiatan/selectcfs', 'TarifKegiatanController@selectcfs')->name('tarifkegiatan.selectcfs');
    Route::post('tarifkegiatan/selectcontainer', 'TarifKegiatanController@selectcontainer')->name('tarifkegiatan.selectcontainer');
    Route::post('tarifkegiatan/selectnoncontainer', 'TarifKegiatanController@selectnoncontainer')->name('tarifkegiatan.selectnoncontainer');

    Route::post('tarifkegiatan/tipe', 'TarifKegiatanController@tipe')->name('tarifkegiatan.tipe');

    Route::post('tarifkegiatan/updateAjax', 'TarifKegiatanController@updateAjax')->name('tarifkegiatan.ajaxupdate');
    Route::post('tarifkegiatan/store_detail', 'TarifKegiatanController@store_detail')->name('tarifkegiatan.store_detail');
    Route::post('tarifkegiatan/edit_detail', 'TarifKegiatanController@edit_detail')->name('tarifkegiatan.edit_detail');
    Route::post('tarifkegiatan/hapus_detail', 'TarifKegiatanController@hapus_detail')->name('tarifkegiatan.hapus_detail');

    Route::post('tarifkegiatan/store_detail_cfs', 'TarifKegiatanController@store_detail_cfs')->name('tarifkegiatan.store_detail_cfs');
    Route::post('tarifkegiatan/store_detail_alat', 'TarifKegiatanController@store_detail_alat')->name('tarifkegiatan.store_detail_alat');

    Route::post('tarifkegiatan/store_alat', 'TarifKegiatanController@store_alat')->name('tarifkegiatan.store_alat');
    Route::post('tarifkegiatan/edit_alat', 'TarifKegiatanController@edit_alat')->name('tarifkegiatan.edit_alat');
    Route::post('tarifkegiatan/hapus_alat', 'TarifKegiatanController@hapus_alat')->name('tarifkegiatan.hapus_alat');

    Route::post('tarifkegiatan/store_size', 'TarifKegiatanController@store_size')->name('tarifkegiatan.store_size');
    Route::post('tarifkegiatan/edit_size', 'TarifKegiatanController@edit_size')->name('tarifkegiatan.edit_size');
    Route::post('tarifkegiatan/hapus_size', 'TarifKegiatanController@hapus_size')->name('tarifkegiatan.hapus_size');

    Route::post('tarifkegiatan/edit_pallet', 'TarifKegiatanController@edit_pallet')->name('tarifkegiatan.edit_pallet');

    Route::post('tarifkegiatan/edit_detail_cfs', 'TarifKegiatanController@edit_detail_cfs')->name('tarifkegiatan.edit_detail_cfs');
    Route::post('tarifkegiatan/edit_detail_container', 'TarifKegiatanController@edit_detail_container')->name('tarifkegiatan.edit_detail_container');
    Route::post('tarifkegiatan/edit_detail_non', 'TarifKegiatanController@edit_detail_non')->name('tarifkegiatan.edit_detail_non');

    Route::post('tarifkegiatan/updateAjax_detail', 'TarifKegiatanController@updateAjax_detail')->name('tarifkegiatan.ajaxupdate_detail');

    Route::post('tarifkegiatan/hapus_detail_tarif', 'TarifKegiatanController@hapus_detail_tarif')->name('tarifkegiatan.hapus_detail_tarif');

    Route::resource('tarifkegiatan', 'TarifKegiatanController');

    /**
     * Alat
     */
    Route::get('alat/anydata', 'AlatController@anyData')->name('alat.data');
    Route::get('alat/getDatapremi', 'AlatController@getDatapremi')->name('alat.getDatapremi');
    Route::get('alat/getDatapremi2', 'AlatController@getDatapremi2')->name('alat.getDatapremi2');
    Route::get('alat/getDatapremi3', 'AlatController@getDatapremi3')->name('alat.getDatapremi3');
    Route::post('alat/storepremi', 'AlatController@storepremi')->name('alat.storepremi');
    Route::post('alat/editpremi', 'AlatController@editpremi')->name('alat.editpremi');
    Route::post('alat/hapuspremi', 'AlatController@hapuspremi')->name('alat.hapuspremi');
    Route::post('alat/storepremi2', 'AlatController@storepremi2')->name('alat.storepremi2');
    Route::post('alat/editpremi2', 'AlatController@editpremi2')->name('alat.editpremi2');
    Route::post('alat/hapuspremi2', 'AlatController@hapuspremi2')->name('alat.hapuspremi2');
    Route::post('alat/storepremi3', 'AlatController@storepremi3')->name('alat.storepremi3');
    Route::post('alat/editpremi3', 'AlatController@editpremi3')->name('alat.editpremi3');
    Route::post('alat/hapuspremi3', 'AlatController@hapuspremi3')->name('alat.hapuspremi3');
    Route::post('alat/updateAjax', 'AlatController@updateAjax')->name('alat.ajaxupdate');
    Route::post('alat/hapus_alat', 'AlatController@hapus_alat')->name('alat.hapus_alat');
    Route::post('alat/edit_alat', 'AlatController@edit_alat')->name('alat.edit_alat');
    Route::get('alat/{kode}/detaillokasi', 'AlatController@detaillokasi')->name('alat.detaillokasi');
    Route::post('alat/store_lokasi', 'AlatController@store_lokasi')->name('alat.store_lokasi');
    Route::get('alat/getDatabyID', 'AlatController@getDatabyID')->name('alat.dataDetail');
    Route::resource('alat', 'AlatController');
    
    /**
     * Permintaan Kasbon
     */
    Route::get('kasbon/anydata', 'KasbonController@anyData')->name('kasbon.data');
    Route::get('kasbon/exportpdf','KasbonController@exportPDF')->name('kasbon.export');
    Route::post('kasbon/updateAjax', 'KasbonController@updateAjax')->name('kasbon.ajaxupdate');
    Route::post('kasbon/hapus_kasbon', 'KasbonController@hapus_kasbon')->name('kasbon.hapus_kasbon');
    Route::post('kasbon/edit_kasbon', 'KasbonController@edit_kasbon')->name('kasbon.edit_kasbon');
    Route::post('kasbon/approve', 'KasbonController@approve')->name('kasbon.approve');
    Route::post('kasbon/Post', 'KasbonController@Post')->name('kasbon.post');
    Route::post('kasbon/Unpost', 'KasbonController@Unpost')->name('kasbon.unpost');
    Route::resource('kasbon', 'KasbonController');

    /**
     * Company
     */
    Route::get('company/anydata', 'CompanyController@anyData')->name('company.data');
    Route::post('company/updateAjax', 'CompanyController@updateAjax')->name('company.ajaxupdate');
    Route::post('company/hapus_company', 'CompanyController@hapus_company')->name('company.hapus_company');
    Route::post('company/edit_company', 'CompanyController@edit_company')->name('company.edit_company');
    Route::resource('company', 'CompanyController');

    // /**
    //  * Master Lokasi
    //  */
    Route::get('masterlokasi/anydata', 'MasterLokasiController@anyData')->name('masterlokasi.data');
    Route::post('masterlokasi/updateAjax', 'MasterLokasiController@updateAjax')->name('masterlokasi.updateajax');
    Route::post('masterlokasi/hapus_lokasi', 'MasterLokasiController@hapus_lokasi')->name('masterlokasi.hapus_lokasi');
    Route::post('masterlokasi/edit_lokasi', 'MasterLokasiController@edit_lokasi')->name('masterlokasi.edit_lokasi');
    Route::resource('masterlokasi','MasterLokasiController');
    
    //**
    //  * Laporan Member
    //  */
    Route::get('laporanmember/exportpdf','LaporanMemberController@exportPDF')->name('laporanmember.export');
    Route::resource('laporanmember', 'LaporanMemberController');

    //**
    //  * Laporan Rekap Container/JO
    //  */
    Route::get('laporanrekapcontainer/exportpdf','LaporanrekapcontainerController@exportPDF')->name('laporanrekapcontainer.export');
    Route::post('laporanrekapcontainer/getdata', 'LaporanrekapcontainerController@getdata')->name('laporanrekapcontainer.getdata');
    Route::resource('laporanrekapcontainer', 'LaporanrekapcontainerController');

    //**
    //  * Laporan Rekap Container/Tanggal
    //  */
    Route::get('laporanrekap_pertgl/exportpdf','Laporanrekap_pertglController@exportPDF')->name('laporanrekap_pertgl.export');
    Route::resource('laporanrekap_pertgl', 'Laporanrekap_pertglController');

    //**
    //  * Laporan Rekap JO
    //  */
    Route::get('laporanrekapjo/exportpdf','LaporanrekapjoController@exportPDF')->name('laporanrekapjo.export');
    Route::resource('laporanrekapjo', 'LaporanrekapjoController');

    //**
    //  * Laporan Rekap Premi
    //  */
    Route::get('laporanpremi/exportpdf','LaporanpremiController@exportPDF')->name('laporanpremi.export');
    Route::resource('laporanpremi', 'LaporanpremiController');
    
    //**
    //  * Laporan Rekap HBU
    //  */
    Route::get('laporanrekaphbu/exportpdf','LaporanrekaphbuController@exportPDF')->name('laporanrekaphbu.export');
    Route::resource('laporanrekaphbu', 'LaporanrekaphbuController');
    
    //**
    //  * Laporan LR Mobil
    //  */
    Route::get('laporanlrmobil/exportpdf','LaporanlrMobilController@exportPDF')->name('laporanlrmobil.export');
    Route::resource('laporanlrmobil', 'LaporanlrMobilController');

    //**
    //  * Upload JOB
    //  */
    Route::get('uploadjob/exportpdf','UploadjobController@exportPDF')->name('uploadjob.export');
    Route::resource('uploadjob', 'UploadjobController');

     //**
    //  * Laporan Rekap SPB/Container
    //  */
    Route::get('laporanrekap_percontainer/exportpdf','Laporanrekap_percontainerController@exportPDF')->name('laporanrekap_percontainer.export');
    Route::resource('laporanrekap_percontainer', 'Laporanrekap_percontainerController');
    
    //**
    //  * Laporan Rekap Pembayaran
    //  */
    Route::get('laporanpembayaran/exportpdf','LaporanPembayaranController@exportPDF')->name('laporanpembayaran.export');
    Route::resource('laporanpembayaran', 'LaporanPembayaranController');

     //**
    //  * Laporan Customer
    //  */
    Route::get('laporancustomer/exportpdf','LaporancustomerController@exportPDF')->name('laporancustomer.export');
    Route::resource('laporancustomer', 'LaporancustomerController');

    /**
     * Type Cargo
     */
    Route::get('typecargo/anydata', 'TypeCargoController@anyData')->name('typecargo.data');
    Route::post('typecargo/getkode', 'TypeCargoController@getkode')->name('typecargo.getkode');
    Route::post('typecargo/updateAjax', 'TypeCargoController@updateAjax')->name('typecargo.ajaxupdate');
    Route::post('typecargo/hapus_kapal', 'TypeCargoController@hapus_kapal')->name('typecargo.hapus_kapal');
    Route::post('typecargo/edit_kapal', 'TypeCargoController@edit_kapal')->name('typecargo.edit_kapal');
    Route::resource('typecargo', 'TypeCargoController');

    /**
     * Kapal
     */
    Route::get('kapal/anydata', 'KapalController@anyData')->name('kapal.data');
    Route::post('kapal/getkode', 'KapalController@getkode')->name('kapal.getkode');
    Route::post('kapal/updateAjax', 'KapalController@updateAjax')->name('kapal.ajaxupdate');
    Route::post('kapal/hapus_kapal', 'KapalController@hapus_kapal')->name('kapal.hapus_kapal');
    Route::post('kapal/edit_kapal', 'KapalController@edit_kapal')->name('kapal.edit_kapal');
    Route::get('kapal/{kode}/detaillokasi', 'KapalController@detaillokasi')->name('kapal.detaillokasi');
    Route::post('kapal/store_lokasi', 'KapalController@store_lokasi')->name('kapal.store_lokasi');
    Route::get('kapal/getDatabyID', 'KapalController@getDatabyID')->name('kapal.dataDetail');
    Route::resource('kapal', 'KapalController');

    /**
     * Signature
     */
    Route::get('signature/anydata', 'SignatureController@anyData')->name('signature.data');
    Route::post('signature/updateAjax', 'SignatureController@updateAjax')->name('signature.ajaxupdate');
    Route::post('signature/hapus_signature', 'SignatureController@hapus_signature')->name('signature.hapus_signature');
    Route::post('signature/edit_signature', 'SignatureController@edit_signature')->name('signature.edit_signature');
    Route::resource('signature', 'SignatureController');

    /**
     * Catatan PO
     */
    Route::get('catatanpo/anydata', 'CatatanpoController@anyData')->name('catatanpo.data');
    Route::post('catatanpo/updateAjax', 'CatatanpoController@updateAjax')->name('catatanpo.ajaxupdate');
    Route::post('catatanpo/hapus_catatanpo', 'CatatanpoController@hapus_catatanpo')->name('catatanpo.hapus_catatanpo');
    Route::post('catatanpo/edit_catatanpo', 'CatatanpoController@edit_catatanpo')->name('catatanpo.edit_catatanpo');
    Route::resource('catatanpo', 'CatatanpoController');
    
    /**
     * Tanggal Setup
     */
    Route::get('tanggalsetup/anydata', 'TanggalController@anyData')->name('tanggalsetup.data');
    Route::post('tanggalsetup/updateAjax', 'TanggalController@updateAjax')->name('tanggalsetup.ajaxupdate');
    Route::post('tanggalsetup/hapus_tanggalsetup', 'TanggalController@hapus_tanggalsetup')->name('tanggalsetup.hapus_tanggalsetup');
    Route::post('tanggalsetup/edit_tanggalsetup', 'TanggalController@edit_tanggalsetup')->name('tanggalsetup.edit_tanggalsetup');
    Route::resource('tanggalsetup', 'TanggalController');

    /**
     * Jenis Mobil
     */
    Route::get('jenismobil/anydata', 'JenismobilController@anyData')->name('jenismobil.data');
    Route::post('jenismobil/updateAjax', 'JenismobilController@updateAjax')->name('jenismobil.ajaxupdate');
    Route::post('jenismobil/hapus_jenismobil', 'JenismobilController@hapus_jenismobil')->name('jenismobil.hapus_jenismobil');
    Route::post('jenismobil/edit_jenismobil', 'JenismobilController@edit_jenismobil')->name('jenismobil.edit_jenismobil');
    Route::resource('jenismobil', 'JenismobilController');

    /**
     * Mobil
     */
    Route::get('mobil/anydata', 'MobilController@anyData')->name('mobil.data');
    Route::post('mobil/getkode', 'MobilController@getkode')->name('mobil.getkode');
    Route::post('mobil/updateAjax', 'MobilController@updateAjax')->name('mobil.ajaxupdate');
    Route::post('mobil/hapus_mobil', 'MobilController@hapus_mobil')->name('mobil.hapus_mobil');
    Route::post('mobil/edit_mobil', 'MobilController@edit_mobil')->name('mobil.edit_mobil');
    Route::get('mobil/getDatabyID', 'MobilController@getDatabyID')->name('mobil.dataDetail');
    Route::get('mobil/{kode}/detaillokasi', 'MobilController@detaillokasi')->name('mobil.detaillokasi');
    Route::post('mobil/store_lokasi', 'MobilController@store_lokasi')->name('mobil.store_lokasi');
    Route::resource('mobil', 'MobilController');

    /**
     * Transaksi Setup
     */
    Route::get('transaksisetup/anydata', 'TransaksisetupController@anyData')->name('transaksisetup.data');
    Route::post('transaksisetup/updateAjax', 'TransaksisetupController@updateAjax')->name('transaksisetup.ajaxupdate');
    Route::post('transaksisetup/hapus_transaksisetup', 'TransaksisetupController@hapus_transaksisetup')->name('transaksisetup.hapus_transaksisetup');
    Route::post('transaksisetup/edit_transaksisetup', 'TransaksisetupController@edit_transaksisetup')->name('transaksisetup.edit_transaksisetup');
    Route::resource('transaksisetup', 'TransaksisetupController');

    /**
     * Tax Setup
     */
    Route::get('taxsetup/anydata', 'TaxSetupController@anyData')->name('taxsetup.data');
    Route::post('taxsetup/updateAjax', 'TaxSetupController@updateAjax')->name('taxsetup.ajaxupdate');
    Route::post('taxsetup/hapus_taxsetup', 'TaxSetupController@hapus_taxsetup')->name('taxsetup.hapus_taxsetup');
    Route::post('taxsetup/edit_taxsetup', 'TaxSetupController@edit_taxsetup')->name('taxsetup.edit_taxsetup');
    Route::resource('taxsetup', 'TaxSetupController');

    /**
     * Port
     */
    Route::get('port/anydata', 'PortController@anyData')->name('port.data');
    Route::post('port/updateAjax', 'PortController@updateAjax')->name('port.ajaxupdate');
    Route::post('port/hapus_port', 'PortController@hapus_port')->name('port.hapus_port');
    Route::post('port/edit_port', 'PortController@edit_port')->name('port.edit_port');
    Route::resource('port', 'PortController');

    /**
     * Sopir
     */
    Route::get('sopir/anydata', 'SopirController@anyData')->name('sopir.data');
    Route::post('sopir/getcoa', 'SopirController@getcoa')->name('sopir.getcoa');
    Route::post('sopir/updateAjax', 'SopirController@updateAjax')->name('sopir.ajaxupdate');
    Route::post('sopir/hapus_sopir', 'SopirController@hapus_sopir')->name('sopir.hapus_sopir');
    Route::post('sopir/edit_sopir', 'SopirController@edit_sopir')->name('sopir.edit_sopir');
    Route::resource('sopir', 'SopirController');

    /**
     * Operator
     */
    Route::get('operator/anydata', 'OperatorController@anyData')->name('operator.data');
    Route::post('operator/getcoa', 'OperatorController@getcoa')->name('operator.getcoa');
    Route::post('operator/updateAjax', 'OperatorController@updateAjax')->name('operator.ajaxupdate');
    Route::post('operator/hapus_operator', 'OperatorController@hapus_operator')->name('operator.hapus_operator');
    Route::post('operator/edit_operator', 'OperatorController@edit_operator')->name('operator.edit_operator');
    Route::resource('operator', 'OperatorController');

    /**
     * Helper
     */
    Route::get('helper/anydata', 'HelperController@anyData')->name('helper.data');
    Route::post('helper/getcoa', 'HelperController@getcoa')->name('helper.getcoa');
    Route::post('helper/updateAjax', 'HelperController@updateAjax')->name('helper.ajaxupdate');
    Route::post('helper/hapus_helper', 'HelperController@hapus_helper')->name('helper.hapus_helper');
    Route::post('helper/edit_helper', 'HelperController@edit_helper')->name('helper.edit_helper');
    Route::resource('helper', 'HelperController');

    /**
     * Jenis Alat
     */
    Route::get('jenisalat/anydata', 'JenisAlatController@anyData')->name('jenisalat.data');
    Route::post('jenisalat/getcoa', 'JenisAlatController@getcoa')->name('jenisalat.getcoa');
    Route::post('jenisalat/updateAjax', 'JenisAlatController@updateAjax')->name('jenisalat.ajaxupdate');
    Route::post('jenisalat/hapus_jenis', 'JenisAlatController@hapus_jenis')->name('jenisalat.hapus_jenis');
    Route::post('jenisalat/edit_jenis', 'JenisAlatController@edit_jenis')->name('jenisalat.edit_jenis');
    Route::resource('jenisalat', 'JenisAlatController');

    /**
     * Jenis Harga
     */
    Route::get('jenisharga/anydata', 'JenisHargaController@anyData')->name('jenisharga.data');
    Route::post('jenisharga/getcoa', 'JenisHargaController@getcoa')->name('jenisharga.getcoa');
    Route::post('jenisharga/updateAjax', 'JenisHargaController@updateAjax')->name('jenisharga.ajaxupdate');
    Route::post('jenisharga/hapus_jenis', 'JenisHargaController@hapus_jenis')->name('jenisharga.hapus_jenis');
    Route::post('jenisharga/edit_jenis', 'JenisHargaController@edit_jenis')->name('jenisharga.edit_jenis');
    Route::resource('jenisharga', 'JenisHargaController');

    /**
     * Pemilik
     */
    Route::get('pemilik/anydata', 'PemilikController@anyData')->name('pemilik.data');
    Route::post('pemilik/getkode', 'PemilikController@getkode')->name('pemilik.getkode');
    Route::post('pemilik/getcom', 'PemilikController@getcom')->name('pemilik.getcom');
    Route::post('pemilik/updateAjax', 'PemilikController@updateAjax')->name('pemilik.ajaxupdate');
    Route::get('pemilik/{pemilik}/detail', 'PemilikController@detail')->name('pemilik.detail');
    Route::post('pemilik/showdetail', 'PemilikController@Showdetail')->name('pemilik.showdetail');
    Route::post('pemilik/hapus_pemilik', 'PemilikController@hapus_pemilik')->name('pemilik.hapus_pemilik');
    Route::post('pemilik/edit_pemilik', 'PemilikController@edit_pemilik')->name('pemilik.edit_pemilik');
    Route::resource('pemilik', 'PemilikController');

    /**
     * Pemilik Detail
     */
    Route::post('pemilikdetail/getjenis', 'PemilikdetailController@getjenis')->name('pemilikdetail.getjenis');
    Route::post('pemilikdetail/getjenis2', 'PemilikdetailController@getjenis2')->name('pemilikdetail.getjenis2');
    Route::get('pemilikdetail/getDatabyID', 'PemilikdetailController@getDatabyID')->name('pemilikdetail.dataDetail');
    Route::post('pemilikdetail/updateAjax', 'PemilikdetailController@updateAjax')->name('pemilikdetail.updateajax');
    Route::resource('pemilikdetail', 'PemilikdetailController');

    /**
     * Gudang
     */
    Route::get('gudang/anydata', 'GudangController@anyData')->name('gudang.data');
    Route::post('gudang/getkode', 'GudangController@getkode')->name('gudang.getkode');
    Route::post('gudang/updateAjax', 'GudangController@updateAjax')->name('gudang.ajaxupdate');
    Route::get('gudang/{gudang}/detail', 'GudangController@detail')->name('gudang.detail');
    Route::post('gudang/showdetail', 'GudangController@Showdetail')->name('gudang.showdetail');
    Route::post('gudang/hapus_gudang', 'GudangController@hapus_gudang')->name('gudang.hapus_gudang');
    Route::post('gudang/edit_gudang', 'GudangController@edit_gudang')->name('gudang.edit_gudang');
    Route::resource('gudang', 'GudangController');

    /**
     * Gudang Detail
     */
    Route::get('gudangdetail/getDatatarif', 'GudangdetailController@getDatatarif')->name('gudangdetail.getDatatarif');
    Route::post('gudangdetail/edit_gudangdetail', 'GudangdetailController@edit_gudangdetail')->name('gudangdetail.edit_gudangdetail');
    Route::post('gudangdetail/hapus_gudangdetail', 'GudangdetailController@hapus_gudangdetail')->name('gudangdetail.hapus_gudangdetail');
    Route::post('gudangdetail/hapus_tarifdetail', 'GudangdetailController@hapus_tarifdetail')->name('gudangdetail.hapus_tarifdetail');
    Route::get('gudangdetail/getDatabyID', 'GudangdetailController@getDatabyID')->name('gudangdetail.dataDetail');
    Route::post('gudangdetail/store2', 'GudangdetailController@store2')->name('gudangdetail.store2');
    Route::post('gudangdetail/updateAjax', 'GudangdetailController@updateAjax')->name('gudangdetail.updateajax');
    Route::resource('gudangdetail', 'GudangdetailController');

    /**
     * Size Container
     */
    Route::get('sizecontainer/anydata', 'SizecontainerController@anyData')->name('sizecontainer.data');
    Route::post('sizecontainer/getkode', 'SizecontainerController@getkode')->name('sizecontainer.getkode');
    Route::post('sizecontainer/updateAjax', 'SizecontainerController@updateAjax')->name('sizecontainer.ajaxupdate');
    Route::post('sizecontainer/hapus_sizecontainer', 'SizecontainerController@hapus_sizecontainer')->name('sizecontainer.hapus_sizecontainer');
    Route::post('sizecontainer/edit_sizecontainer', 'SizecontainerController@edit_sizecontainer')->name('sizecontainer.edit_sizecontainer');
    Route::resource('sizecontainer', 'SizecontainerController');

    // /**
    //  * Job Order
    //  */
    Route::get('joborder/getDatapreview', 'JoborderController@getDatapreview')->name('joborder.getDatapreview');
    Route::get('joborder/previewpo', 'JoborderController@previewpo')->name('joborder.previewpo');
    Route::get('joborder/printpreview','JoborderController@printpreview')->name('joborder.printpreview');
    Route::post('joborder/ttd_buat', 'JoborderController@ttd_buat')->name('joborder.ttd_buat');
    Route::post('joborder/ttd_periksa', 'JoborderController@ttd_periksa')->name('joborder.ttd_periksa');
    Route::post('joborder/ttd_setuju', 'JoborderController@ttd_setuju')->name('joborder.ttd_setuju');
    Route::post('joborder/ttd_terima', 'JoborderController@ttd_terima')->name('joborder.ttd_terima');
    Route::get('joborder/anydata', 'JoborderController@anyData')->name('joborder.data');
    Route::get('joborder/exportpdf','JoborderController@exportPDF')->name('joborder.export');
    Route::get('joborder/exportpdf2','JoborderController@exportPDF2')->name('joborder.export2');
    Route::post('joborder/getkode', 'JoborderController@getkode')->name('joborder.getkode');
    Route::post('joborder/edit_joborder', 'JoborderController@edit_joborder')->name('joborder.edit_joborder');
    Route::post('joborder/hapus_joborder', 'JoborderController@hapus_joborder')->name('joborder.hapus_joborder');
    Route::post('joborder/updateAjax', 'JoborderController@updateAjax')->name('joborder.updateajax');
    Route::get('joborder/{joborder}/detail', 'JoborderController@detail')->name('joborder.detail');
    Route::get('joborder/{joborder}/detail2', 'JoborderController@detail2')->name('joborder.detail2');
    Route::post('joborder/Post', 'JoborderController@Post')->name('joborder.post');
    Route::post('joborder/Unpost', 'JoborderController@Unpost')->name('joborder.unpost');
    Route::post('joborder/showdetail', 'JoborderController@Showdetail')->name('joborder.showdetail');
    Route::post('joborder/showdetailjor', 'JoborderController@Showdetailjor')->name('joborder.showdetailjor');
    Route::resource('joborder','JoborderController');

    /**
     * Job Order Detail
     */
    Route::get('joborderdetail/getDatajor', 'JoborderdetailController@getDatajor')->name('joborderdetail.getDatajor');
    Route::post('joborderdetail/edit_joborderdetail', 'JoborderdetailController@edit_joborderdetail')->name('joborderdetail.edit_joborderdetail');
    Route::post('joborderdetail/hapus_joborderdetail', 'JoborderdetailController@hapus_joborderdetail')->name('joborderdetail.hapus_joborderdetail');
    Route::post('joborderdetail/store2', 'JoborderdetailController@store2')->name('joborderdetail.store2');
    Route::post('joborderdetail/store', 'JoborderdetailController@store')->name('joborderdetail.store');
    Route::post('joborderdetail/hapus_noreqjo', 'JoborderdetailController@hapus_noreqjo')->name('joborderdetail.hapus_noreqjo');
    Route::post('joborderdetail/edit_noreqjo', 'JoborderdetailController@edit_noreqjo')->name('joborderdetail.edit_noreqjo');
    Route::get('joborderdetail/getDatabyID', 'JoborderdetailController@getDatabyID')->name('joborderdetail.dataDetail');
    Route::post('joborderdetail/updateAjax', 'JoborderdetailController@updateAjax')->name('joborderdetail.updateajax');
    Route::post('joborderdetail/showdetailjobreq', 'JoborderdetailController@Showdetailjobreq')->name('joborderdetail.showdetailjobreq');
    Route::post('joborder/showdetailaju', 'JoborderController@Showdetailaju')->name('joborder.showdetailaju');
    Route::post('joborder/showdetailkasbon', 'JoborderController@Showdetailkasbon')->name('joborder.showdetailkasbon');
    Route::post('joborder/showdetailbiaya', 'JoborderController@Showdetailbiaya')->name('joborder.showdetailbiaya');
    Route::post('joborder/showdetailpenyelesaian', 'JoborderController@Showdetailpenyelesaian')->name('joborder.showdetailpenyelesaian');
    Route::resource('joborderdetail', 'JoborderdetailController');

    /**
     * Job Request Detail
     */
    Route::get('jobrequestdetail/getDatajor', 'JobrequestdetailController@getDatajor')->name('jobrequestdetail.getDatajor');
    Route::post('jobrequestdetail/tarif', 'JobrequestdetailController@tarif')->name('jobrequestdetail.tarif');
    Route::post('jobrequestdetail/edit_jobrequest', 'JobrequestdetailController@edit_jobrequest')->name('jobrequestdetail.edit_jobrequest');
    Route::post('jobrequestdetail/hapus_jobrequest', 'JobrequestdetailController@hapus_jobrequest')->name('jobrequestdetail.hapus_jobrequest');
    Route::post('jobrequestdetail/store2', 'JobrequestdetailController@store2')->name('jobrequestdetail.store2');
    Route::post('jobrequestdetail/store', 'JobrequestdetailController@store')->name('jobrequestdetail.store');
    Route::post('jobrequestdetail/hapus_noreqjo', 'JobrequestdetailController@hapus_noreqjo')->name('jobrequestdetail.hapus_noreqjo');
    Route::post('jobrequestdetail/edit_noreqjo', 'JobrequestdetailController@edit_noreqjo')->name('jobrequestdetail.edit_noreqjo');
    Route::get('jobrequestdetail/getDatabyID', 'JobrequestdetailController@getDatabyID')->name('jobrequestdetail.dataDetail');
    Route::post('jobrequestdetail/updateAjax', 'JobrequestdetailController@updateAjax')->name('jobrequestdetail.updateajax');
    Route::post('jobrequestdetail/showdetailjobreq', 'JobrequestdetailController@Showdetailjobreq')->name('jobrequestdetail.showdetailjobreq');
    Route::resource('jobrequestdetail', 'JobrequestdetailController');


    // /**
    //  * Pemakaian Alat Berat
    //  */
    Route::get('pemakaianalat/anydata', 'PemakaianAlatController@anyData')->name('pemakaianalat.data');
    Route::get('pemakaianalat/exportpdf','PemakaianAlatController@exportPDF')->name('pemakaianalat.export');
    Route::post('pemakaianalat/getkode', 'PemakaianAlatController@getkode')->name('pemakaianalat.getkode');
    Route::post('pemakaianalat/getjo', 'PemakaianAlatController@getjo')->name('pemakaianalat.getjo');
    Route::post('pemakaianalat/getjo2', 'PemakaianAlatController@getjo2')->name('pemakaianalat.getjo2');
    Route::post('pemakaianalat/edit_pemakaian', 'PemakaianAlatController@edit_pemakaian')->name('pemakaianalat.edit_pemakaian');
    Route::post('pemakaianalat/hapus_pemakaian', 'PemakaianAlatController@hapus_pemakaian')->name('pemakaianalat.hapus_pemakaian');
    Route::post('pemakaianalat/updateAjax', 'PemakaianAlatController@updateAjax')->name('pemakaianalat.updateajax');
    Route::get('pemakaianalat/{joborder}/detail', 'PemakaianAlatController@detail')->name('pemakaianalat.detail');
    Route::post('pemakaianalat/Post', 'PemakaianAlatController@Post')->name('pemakaianalat.post');
    Route::post('pemakaianalat/Unpost', 'PemakaianAlatController@Unpost')->name('pemakaianalat.unpost');
    Route::post('pemakaianalat/showdetail', 'PemakaianAlatController@Showdetail')->name('pemakaianalat.showdetail');
    Route::post('pemakaianalat/showdetailjor', 'PemakaianAlatController@Showdetailjor')->name('pemakaianalat.showdetailjor');
    Route::resource('pemakaianalat','PemakaianAlatController');

    /**
     * Pemakaian Alat Berat Detail
     */
    Route::get('pemakaianalatdetail/getDatajor', 'PemakaianAlatDetailController@getDatajor')->name('pemakaianalatdetail.getDatajor');
    Route::post('pemakaianalatdetail/cekts', 'PemakaianAlatDetailController@cekts')->name('pemakaianalatdetail.cekts');
    Route::post('pemakaianalatdetail/edit_detail', 'PemakaianAlatDetailController@edit_detail')->name('pemakaianalatdetail.edit_detail');
    Route::post('pemakaianalatdetail/hapus_detail', 'PemakaianAlatDetailController@hapus_detail')->name('pemakaianalatdetail.hapus_detail');
    Route::post('pemakaianalatdetail/store2', 'PemakaianAlatDetailController@store2')->name('pemakaianalatdetail.store2');
    Route::post('pemakaianalatdetail/hapus_noreqjo', 'PemakaianAlatDetailController@hapus_noreqjo')->name('pemakaianalatdetail.hapus_noreqjo');
    Route::post('pemakaianalatdetail/edit_noreqjo', 'PemakaianAlatDetailController@edit_noreqjo')->name('pemakaianalatdetail.edit_noreqjo');
    Route::get('pemakaianalatdetail/getDatabyID', 'PemakaianAlatDetailController@getDatabyID')->name('pemakaianalatdetail.dataDetail');
    Route::post('pemakaianalatdetail/updateAjax', 'PemakaianAlatDetailController@updateAjax')->name('pemakaianalatdetail.updateajax');
    Route::post('pemakaianalatdetail/showdetailjobreq', 'PemakaianAlatDetailController@Showdetailjobreq')->name('pemakaianalatdetail.showdetailjobreq');
    Route::resource('pemakaianalatdetail', 'PemakaianAlatDetailController');

    // /**
    //  * Insentif Operator
    //  */
    Route::get('insentifoperator/anydata', 'InsentifoperatorController@anyData')->name('insentifoperator.data');
    Route::get('insentifoperator/exportpdf','InsentifoperatorController@exportPDF')->name('insentifoperator.export');
    Route::post('insentifoperator/getkode', 'InsentifoperatorController@getkode')->name('insentifoperator.getkode');
    Route::post('insentifoperator/edit_insentif', 'InsentifoperatorController@edit_insentif')->name('insentifoperator.edit_insentif');
    Route::post('insentifoperator/hapus_insentif', 'InsentifoperatorController@hapus_insentif')->name('insentifoperator.hapus_insentif');
    Route::post('insentifoperator/updateAjax', 'InsentifoperatorController@updateAjax')->name('insentifoperator.updateajax');
    Route::get('insentifoperator/{joborder}/detail', 'InsentifoperatorController@detail')->name('insentifoperator.detail');
    Route::post('insentifoperator/Post', 'InsentifoperatorController@Post')->name('insentifoperator.post');
    Route::post('insentifoperator/Unpost', 'InsentifoperatorController@Unpost')->name('insentifoperator.unpost');
    Route::post('insentifoperator/showdetail', 'InsentifoperatorController@Showdetail')->name('insentifoperator.showdetail');
    Route::post('insentifoperator/showdetailjor', 'InsentifoperatorController@Showdetailjor')->name('insentifoperator.showdetailjor');
    Route::resource('insentifoperator','InsentifoperatorController');

    /**
     * Insentif Operator Detail
     */
    Route::post('insentifoperatordetail/getpakai', 'InsentifoperatorDetailController@getpakai')->name('insentifoperatordetail.getpakai');
    Route::get('insentifoperatordetail/getDatabyID', 'InsentifoperatorDetailController@getDatabyID')->name('insentifoperatordetail.dataDetail');
    Route::post('insentifoperatordetail/show_alat', 'InsentifoperatorDetailController@show_alat')->name('insentifoperatordetail.show_alat');
    Route::post('insentifoperatordetail/hapusall', 'InsentifoperatorDetailController@hapusall')->name('insentifoperatordetail.hapusall');
    Route::post('insentifoperatordetail/hapusdetail', 'InsentifoperatorDetailController@hapusdetail')->name('insentifoperatordetail.hapusdetail');
    Route::resource('insentifoperatordetail', 'InsentifoperatorDetailController');

    // /**
    //  * Insentif Helper
    //  */
    Route::get('insentifhelper/anydata', 'InsentifhelperController@anyData')->name('insentifhelper.data');
    Route::get('insentifhelper/exportpdf','InsentifhelperController@exportPDF')->name('insentifhelper.export');
    Route::post('insentifhelper/getkode', 'InsentifhelperController@getkode')->name('insentifhelper.getkode');
    Route::post('insentifhelper/edit_insentif', 'InsentifhelperController@edit_insentif')->name('insentifhelper.edit_insentif');
    Route::post('insentifhelper/hapus_insentif', 'InsentifhelperController@hapus_insentif')->name('insentifhelper.hapus_insentif');
    Route::post('insentifhelper/updateAjax', 'InsentifhelperController@updateAjax')->name('insentifhelper.updateajax');
    Route::get('insentifhelper/{joborder}/detail', 'InsentifhelperController@detail')->name('insentifhelper.detail');
    Route::post('insentifhelper/Post', 'InsentifhelperController@Post')->name('insentifhelper.post');
    Route::post('insentifhelper/Unpost', 'InsentifhelperController@Unpost')->name('insentifhelper.unpost');
    Route::post('insentifhelper/showdetail', 'InsentifhelperController@Showdetail')->name('insentifhelper.showdetail');
    Route::post('insentifhelper/showdetailjor', 'InsentifhelperController@Showdetailjor')->name('insentifhelper.showdetailjor');
    Route::resource('insentifhelper','InsentifhelperController');


        /**
     * Insentif Helper Detail
     */
    Route::post('insentifhelperdetail/getpakai', 'InsentifhelperDetailController@getpakai')->name('insentifhelperdetail.getpakai');
    Route::post('insentifhelperdetail/show_alat', 'InsentifhelperDetailController@show_alat')->name('insentifhelperdetail.show_alat');
    Route::post('insentifhelperdetail/edit_detail', 'InsentifhelperDetailController@edit_detail')->name('insentifhelperdetail.edit_detail');
    Route::post('insentifhelperdetail/hapus_detail', 'InsentifhelperDetailController@hapus_detail')->name('insentifhelperdetail.hapus_detail');
    Route::post('insentifhelperdetail/store2', 'InsentifhelperDetailController@store2')->name('insentifhelperdetail.store2');
    Route::post('insentifhelperdetail/hapusall', 'InsentifhelperDetailController@hapusall')->name('insentifhelperdetail.hapusall');
    Route::post('insentifhelperdetail/hapus_noreqjo', 'InsentifhelperDetailController@hapus_noreqjo')->name('insentifhelperdetail.hapus_noreqjo');
    Route::post('insentifhelperdetail/edit_noreqjo', 'InsentifhelperDetailController@edit_noreqjo')->name('insentifhelperdetail.edit_noreqjo');
    Route::get('insentifhelperdetail/getDatabyID', 'InsentifhelperDetailController@getDatabyID')->name('insentifhelperdetail.dataDetail2');
    Route::post('insentifhelperdetail/updateAjax', 'InsentifhelperDetailController@updateAjax')->name('insentifhelperdetail.updateajax');
    Route::resource('insentifhelperdetail', 'InsentifhelperDetailController');

    // /**
    //  * Trucking
    //  */
    Route::get('trucking/export3','TruckingController@export3')->name('trucking.export3');
    Route::get('trucking/export2','TruckingController@export2')->name('trucking.export2');
    Route::post('trucking/getkode', 'TruckingController@getkode')->name('trucking.getkode');
    Route::post('trucking/getdata', 'TruckingController@getdata')->name('trucking.getdata');
    Route::post('trucking/getdata2', 'TruckingController@getdata2')->name('trucking.getdata2');
    Route::get('trucking/anydata', 'TruckingController@anyData')->name('trucking.data');
    Route::post('trucking/edit_trucking', 'TruckingController@edit_trucking')->name('trucking.edit_trucking');
    Route::post('trucking/hapus_trucking', 'TruckingController@hapus_trucking')->name('trucking.hapus_trucking');
    Route::post('trucking/updateAjax', 'TruckingController@updateAjax')->name('trucking.updateajax');
    Route::get('trucking/{trucking}/detail', 'TruckingController@detail')->name('trucking.detail');
    Route::post('trucking/Post', 'TruckingController@Post')->name('trucking.post');
    Route::post('trucking/Unpost', 'TruckingController@Unpost')->name('trucking.unpost');
    Route::post('trucking/showdetail', 'TruckingController@Showdetail')->name('trucking.showdetail');
    Route::post('trucking/showdetailspb', 'TruckingController@Showdetailspb')->name('trucking.showdetailspb');
    Route::resource('trucking','TruckingController');

    /**
     * Trucking Detail
     */
    Route::post('truckingdetail/pemilik', 'TruckingdetailController@pemilik')->name('truckingdetail.pemilik');
    Route::post('truckingdetail/pemilik2', 'TruckingdetailController@pemilik2')->name('truckingdetail.pemilik2');
    Route::post('truckingdetail/gettarif', 'TruckingdetailController@gettarif')->name('truckingdetail.gettarif');
    Route::post('truckingdetail/gettariftgl', 'TruckingdetailController@gettariftgl')->name('truckingdetail.gettariftgl');
    Route::post('truckingdetail/getjor', 'TruckingdetailController@getjor')->name('truckingdetail.getjor');
    Route::post('truckingdetail/edit_trucking', 'TruckingdetailController@edit_trucking')->name('truckingdetail.edit_trucking');
    Route::post('truckingdetail/hapus_trucking', 'TruckingdetailController@hapus_trucking')->name('truckingdetail.hapus_trucking');
    Route::get('truckingdetail/getDatabyID', 'TruckingdetailController@getDatabyID')->name('truckingdetail.dataDetail');
    Route::post('truckingdetail/add_spb', 'TruckingdetailController@add_spb')->name('truckingdetail.add_spb');
    Route::post('truckingdetail/edit_spb', 'TruckingdetailController@edit_spb')->name('truckingdetail.edit_spb');
    Route::post('truckingdetail/updateAjax', 'TruckingdetailController@updateAjax')->name('truckingdetail.updateajax');
    Route::post('truckingdetail/updateAjax2', 'TruckingdetailController@updateAjax2')->name('truckingdetail.updateajax2');
    Route::post('truckingdetail/showdetailspb', 'TruckingdetailController@Showdetailspb')->name('truckingdetail.showdetailspb');
    Route::resource('truckingdetail', 'TruckingdetailController');

    // /**
    //  * Trucking Non Container
    //  */
    Route::get('truckingnon/export2','TruckingnonController@export2')->name('truckingnon.export2');
    Route::get('truckingnon/export3','TruckingnonController@export3')->name('truckingnon.export3');
    Route::post('truckingnon/bigprint', 'TruckingnonController@bigprint')->name('truckingnon.bigprint');
    Route::post('truckingnon/getkode', 'TruckingnonController@getkode')->name('truckingnon.getkode');
    Route::post('truckingnon/getdata', 'TruckingnonController@getdata')->name('truckingnon.getdata');
    Route::post('truckingnon/getdata2', 'TruckingnonController@getdata2')->name('truckingnon.getdata2');
    Route::get('truckingnon/anydata', 'TruckingnonController@anyData')->name('truckingnon.data');
    Route::post('truckingnon/edit_truckingnon', 'TruckingnonController@edit_truckingnon')->name('truckingnon.edit_truckingnon');
    Route::post('truckingnon/hapus_truckingnon', 'TruckingnonController@hapus_truckingnon')->name('truckingnon.hapus_truckingnon');
    Route::post('truckingnon/updateAjax', 'TruckingnonController@updateAjax')->name('truckingnon.updateajax');
    Route::get('truckingnon/{truckingnon}/detail', 'TruckingnonController@detail')->name('truckingnon.detail');
    Route::post('truckingnon/Post', 'TruckingnonController@Post')->name('truckingnon.post');
    Route::post('truckingnon/Unpost', 'TruckingnonController@Unpost')->name('truckingnon.unpost');
    Route::post('truckingnon/showdetail', 'TruckingnonController@Showdetail')->name('truckingnon.showdetail');
    Route::post('truckingnon/showdetailspbnc', 'TruckingnonController@showdetailspbnc')->name('truckingnon.showdetailspbnc');
    Route::resource('truckingnon','TruckingnonController');

    /**
     * Trucking Non Container Detail
     */
    Route::post('truckingnondetail/get_aset', 'TruckingnondetailController@get_aset')->name('truckingnondetail.get_aset');
    Route::post('truckingnondetail/pemilik', 'TruckingnondetailController@pemilik')->name('truckingnondetail.pemilik');
    Route::get('truckingnondetail/getDataspb', 'TruckingnondetailController@getDataspb')->name('truckingnondetail.getDataspb');
    Route::post('truckingnondetail/createspbnon', 'TruckingnondetailController@createspbnon')->name('truckingnondetail.createspbnon');
    Route::get('truckingnondetail/getDatabyID', 'TruckingnondetailController@getDatabyID')->name('truckingnondetail.dataDetail');
    Route::post('truckingnondetail/store2', 'TruckingnondetailController@store2')->name('truckingnondetail.store2');
    Route::post('truckingnondetail/updateAjax', 'TruckingnondetailController@updateAjax')->name('truckingnondetail.updateajax');
    Route::post('truckingnondetail/hapus_spbnon', 'TruckingnondetailController@hapus_spbnon')->name('truckingnondetail.hapus_spbnon');
    Route::post('truckingnondetail/edit_spbnon', 'TruckingnondetailController@edit_spbnon')->name('truckingnondetail.edit_spbnon');
    Route::post('truckingnondetail/edit_truckingnon', 'TruckingnondetailController@edit_truckingnon')->name('truckingnondetail.edit_truckingnon');
    Route::post('truckingnondetail/edit_truckingnon2', 'TruckingnondetailController@edit_truckingnon2')->name('truckingnondetail.edit_truckingnon2');
    Route::post('truckingnondetail/hapus_truckingnon', 'TruckingnondetailController@hapus_truckingnon')->name('truckingnondetail.hapus_truckingnon');
    Route::post('truckingnondetail/updateAjax3', 'TruckingnondetailController@updateAjax3')->name('truckingnondetail.updateajax3');
    Route::resource('truckingnondetail', 'TruckingnondetailController');

    // /**
    //  * Pembayaran
    //  */
    Route::get('pembayaran/export2','PembayaranController@export2')->name('pembayaran.export2');
    Route::get('pembayaran/anydata', 'PembayaranController@anyData')->name('pembayaran.data');
    Route::post('pembayaran/getkode', 'PembayaranController@getkode')->name('pembayaran.getkode');
    Route::post('pembayaran/edit_pembayaran', 'PembayaranController@edit_pembayaran')->name('pembayaran.edit_pembayaran');
    Route::post('pembayaran/hapus_pembayaran', 'PembayaranController@hapus_pembayaran')->name('pembayaran.hapus_pembayaran');
    Route::post('pembayaran/updateAjax', 'PembayaranController@updateAjax')->name('pembayaran.updateajax');
    Route::get('pembayaran/{pembayaran}/detail', 'PembayaranController@detail')->name('pembayaran.detail');
    Route::post('pembayaran/Post', 'PembayaranController@Post')->name('pembayaran.post');
    Route::post('pembayaran/Unpost', 'PembayaranController@Unpost')->name('pembayaran.unpost');
    Route::post('pembayaran/showdetail', 'PembayaranController@Showdetail')->name('pembayaran.showdetail');
    Route::resource('pembayaran','PembayaranController');

    /**
     * Pembayaran Detail
     */
    Route::post('pembayarandetail/getspb', 'PembayarandetailController@getspb')->name('pembayarandetail.getspb');
    Route::post('pembayarandetail/hapusall', 'PembayarandetailController@hapusall')->name('pembayarandetail.hapusall');
    Route::post('pembayarandetail/hapus_pembayaran', 'PembayarandetailController@hapus_pembayaran')->name('pembayarandetail.hapus_pembayaran');
    Route::get('pembayarandetail/getDatabyID', 'PembayarandetailController@getDatabyID')->name('pembayarandetail.dataDetail');
    Route::resource('pembayarandetail', 'PembayarandetailController');

    // /**
    //  * Hasil Bagi Usaha Sopir
    //  */
    Route::get('hasilbagi/export2','HasilbagiController@export2')->name('hasilbagi.export2');
    Route::post('hasilbagi/getkode', 'HasilbagiController@getkode')->name('hasilbagi.getkode');
    Route::post('hasilbagi/getdata', 'HasilbagiController@getdata')->name('hasilbagi.getdata');
    Route::post('hasilbagi/getdata2', 'HasilbagiController@getdata2')->name('hasilbagi.getdata2');
    Route::get('hasilbagi/anydata', 'HasilbagiController@anyData')->name('hasilbagi.data');
    Route::post('hasilbagi/edit_hasilbagi', 'HasilbagiController@edit_hasilbagi')->name('hasilbagi.edit_hasilbagi');
    Route::post('hasilbagi/hapus_hasilbagi', 'HasilbagiController@hapus_hasilbagi')->name('hasilbagi.hapus_hasilbagi');
    Route::post('hasilbagi/updateAjax', 'HasilbagiController@updateAjax')->name('hasilbagi.updateajax');
    Route::get('hasilbagi/{hasilbagi}/detail', 'HasilbagiController@detail')->name('hasilbagi.detail');
    Route::post('hasilbagi/Post', 'HasilbagiController@Post')->name('hasilbagi.post');
    Route::post('hasilbagi/Unpost', 'HasilbagiController@Unpost')->name('hasilbagi.unpost');
    Route::post('hasilbagi/showdetail', 'HasilbagiController@Showdetail')->name('hasilbagi.showdetail');
    Route::resource('hasilbagi','HasilbagiController');

    /**
     * Hasil Bagi Usaha Sopir Detail
     */
    Route::post('hasilbagidetail/createspbnon', 'HasilbagidetailController@createspbnon')->name('hasilbagidetail.createspbnon');
    Route::post('hasilbagidetail/getspb', 'HasilbagidetailController@getspb')->name('hasilbagidetail.getspb');
    Route::get('hasilbagidetail/getDatabyID', 'HasilbagidetailController@getDatabyID')->name('hasilbagidetail.dataDetail');
    Route::post('hasilbagidetail/hapusall', 'HasilbagidetailController@hapusall')->name('hasilbagidetail.hapusall');
    Route::post('hasilbagidetail/hapus_hasilbagi', 'HasilbagidetailController@hapus_hasilbagi')->name('hasilbagidetail.hapus_hasilbagi');
    Route::post('hasilbagidetail/showdetailspbnc', 'HasilbagidetailController@Showdetailspbnc')->name('hasilbagidetail.showdetailspbnc');
    Route::resource('hasilbagidetail', 'HasilbagidetailController');

     /**
     * Users
     */
    Route::resource('users', 'UsersController');

    /*
     * Roles
     */
    Route::resource('roles', 'RolesController');

    /*
    * Permissions
    */
    Route::resource('permissions', 'PermissionsController');

});