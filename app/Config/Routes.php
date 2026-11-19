<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

$routes->get('/', 'Auth::index');

// Auth
$routes->get('login',       'Auth::index');
$routes->post('login/auth', 'Auth::authenticate');
$routes->get('logout',      'Auth::logout');

// Dashboard sementara
$routes->get('dashboard', 'Dashboard::index');

// Kerusakan
$routes->group('kerusakan', function ($routes) {
    $routes->get('/',        'Kerusakan::index');
    $routes->post('simpan',  'Kerusakan::simpan');
    $routes->post('update',  'Kerusakan::update');
    $routes->post('hapus/(:num)', 'Kerusakan::hapus/$1');
});

// Pencatatan Komputer
$routes->get('pencatatan', 'Pencatatan::index');
$routes->post('pencatatan/simpan', 'Pencatatan::simpan');
$routes->post('pencatatan/update', 'Pencatatan::update');
$routes->post('pencatatan/hapus/(:num)', 'Pencatatan::hapus/$1');
$routes->post('pencatatan/pindah_ruangan', 'Pencatatan::pindah_ruangan');
$routes->get('riwayatmutasi', 'RiwayatMutasi::index');

// Pengadaan Barang PDE
$routes->group('pengadaan', function ($routes) {
    $routes->get('/',        'Pengadaan::index');
    
    // Utama
    $routes->post('simpan',  'Pengadaan::simpan');
    $routes->post('update',  'Pengadaan::update');
    $routes->post('hapus/(:num)', 'Pengadaan::hapus/$1');
    
    // Incoming
    $routes->post('simpan_incoming',  'Pengadaan::simpan_incoming');
    $routes->post('update_incoming',  'Pengadaan::update_incoming');
    $routes->post('hapus_incoming/(:num)', 'Pengadaan::hapus_incoming/$1');
    
    // Outgoing
    $routes->post('simpan_outgoing',  'Pengadaan::simpan_outgoing');
    $routes->post('update_outgoing',  'Pengadaan::update_outgoing');
    $routes->post('hapus_outgoing/(:num)', 'Pengadaan::hapus_outgoing/$1');
});

// Service Report
$routes->group('service_report', function ($routes) {
    $routes->get('/',        'ServiceReport::index');
    $routes->post('simpan',  'ServiceReport::simpan');
    $routes->post('update',  'ServiceReport::update');
    $routes->post('hapus/(:num)', 'ServiceReport::hapus/$1');
    $routes->get('print/(:num)', 'ServiceReport::print/$1');
});

// Arsip Dokumen
$routes->group('arsip', function ($routes) {
    $routes->get('/',        'Arsip::index');
    $routes->post('simpan',  'Arsip::simpan');
    $routes->post('update',  'Arsip::update');
    $routes->post('hapus/(:num)', 'Arsip::hapus/$1');
});

// Zebra Label
$routes->group('zebra_label', function ($routes) {
    $routes->get('/',        'ZebraLabel::index');
    $routes->post('save',    'ZebraLabel::save');
    $routes->post('delete/(:num)', 'ZebraLabel::delete/$1');
});

// Fitur Tambahan
$routes->get('stok_sparepart', 'StokSparepart::index');

$routes->group('antrian_sparepart', function ($routes) {
    $routes->get('/',        'AntrianSparepart::index');
    $routes->post('simpan',  'AntrianSparepart::simpan');
    $routes->post('update',  'AntrianSparepart::update');
    $routes->post('hapus/(:num)', 'AntrianSparepart::hapus/$1');
});

// Pengaturan
$routes->group('pengaturan', function ($routes) {
    $routes->get('/',        'Pengaturan::index');
    $routes->post('update_profil', 'Pengaturan::update_profil');
    $routes->post('update_password', 'Pengaturan::update_password');
});

// Approval Super Admin
$routes->group('approval', function ($routes) {
    $routes->get('/',        'Approval::index');
    $routes->post('setujui/(:num)', 'Approval::setujui/$1');
    $routes->post('tolak/(:num)', 'Approval::tolak/$1');
});