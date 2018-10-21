<?php

namespace SheDied\parser;

use SheDied\parser\AbstractParser;

class JobstreetParser extends AbstractParser {

    protected $posisi_job;
    protected $perusahaan;
    protected $keterangan_lokasi;
    protected $lokasi_kerja;
    protected $job_description;
    protected $experience_req;
    protected $informasi_perusahaan;
    protected $waktu_proses_lamaran;
    protected $industri;
    protected $situs;
    protected $fanpage;
    protected $telepon;
    protected $ukuran_perusahaan;
    protected $waktu_kerja;
    protected $gaya_pakaian;
    protected $tunjangan;
    protected $bahasa_digunakan;
    protected $mengapa_bergabung;
    private $feature_image_id;
    protected $expdate = [];

    const FI_ID_1 = 99;
    const FI_ID_2 = 100;

    public function __construct() {
        $this->feature_image_id = time() % 2 == 0 ? self::FI_ID_1 : self::FI_ID_2;
    }

    protected function getPostDetail() {
        $doc = $this->curlGrabContent();
        if (function_exists('mb_convert_encoding')) {
            $doc = mb_convert_encoding($doc, "HTML-ENTITIES", "UTF-8");
        }
        $html = \phpQuery::newDocument($doc);

        $this->node = pq('body');

        $this->node->find('noscript')->remove();

        $judul_lowongan = $this->node->find('h1.job-position')->text();
        $perusahaan = $this->node->find('div.company_name')->text();
        $lokasi = $this->node->find('p.single_work_location')->text();
        $experience = $this->node->find('span[itemprop="experienceRequirements"]')->text();
        $lokasi_kerja = $this->node->find('p.add-detail-p')->text();
        if (!trim($lokasi_kerja)) {
            if ($lokasi) {
                $lokasi_kerja = trim($lokasi);
            } else {
                $lokasi_kerja = '-';
            }
        }

        $job_description = $this->node->find('div[id="job_description"]');
        $this->cleanContentObject($job_description);

        $waktu_proses_lamaran = $this->node->find('p[id="fast_average_processing_time]')->text();
        $industri = $this->node->find('p[id="company_industry"]')->text();
        $situs = $this->node->find('a[id="company_website"]')->attr('href');
        $fanpage = $this->node->find('a[id="company_facebook"]')->attr('href');
        $telepon = trim($this->node->find('p[itemprop="telephone"]')->text());
        $telepon_2 = trim($this->node->find('p[id="company_contact"]')->text());
        if ($telepon_2) {
            if ($telepon) {
                $telepon = $telepon . ', ' . $telepon_2;
            } else {
                $telepon = $telepon_2;
            }
        }
        $ukuran_perusahaan = $this->node->find('p[itemprop="numberOfEmployees"]')->text();
        $ukuran_perusahaan_2 = $this->node->find('p[id="company_size"]')->text();
        if ($ukuran_perusahaan_2) {
            if ($ukuran_perusahaan) {
                $ukuran_perusahaan = $ukuran_perusahaan . ', ' . $ukuran_perusahaan_2;
            } else {
                $ukuran_perusahaan = $ukuran_perusahaan_2;
            }
        }
        $waktu_kerja = $this->node->find('p[id="work_environment_waktu_bekerja"]')->text();
        $gaya_pakaian = $this->node->find('p[id="work_environment_gaya_berpakaian"]')->text();
        $tunjangan = $this->node->find('p[id="work_environment_tunjangan"]')->text();
        $bahasa_digunakan = $this->node->find('p[id="work_environment_bahasa_yang_digunakan"]')->text();

        $informasi_perusahaan = $this->node->find('div[id="company_overview_all"]');
        $this->cleanContentObject($informasi_perusahaan);

        $why_join = $this->node->find('div[id="why_join_us_all"]');
        $this->cleanContentObject($why_join);

        $this->expdate['open'] = trim($this->node->find('p[id="posting_date"] > span')->text());
        $exp_close = trim($this->node->find('p[id="closing_date"]')->text());
        $this->expdate['close'] = preg_replace("/Tutup\spada\s/", "", $exp_close);

        $this->posisi_job = trim($judul_lowongan);
        $this->perusahaan = trim($perusahaan);
        $this->keterangan_lokasi = trim($lokasi);
        $this->lokasi_kerja = trim($lokasi_kerja);
        $this->experience_req = trim($experience);
        $this->job_description = $job_description->html();
        $this->waktu_proses_lamaran = trim($waktu_proses_lamaran);
        $this->industri = trim($industri);
        $this->situs = trim($situs);
        $this->fanpage = trim($fanpage);
        $this->telepon = $telepon;
        $this->ukuran_perusahaan = trim($ukuran_perusahaan);
        $this->waktu_kerja = trim($waktu_kerja);
        $this->gaya_pakaian = trim($gaya_pakaian);
        $this->tunjangan = trim($tunjangan);
        $this->bahasa_digunakan = trim($bahasa_digunakan);
        $this->informasi_perusahaan = $informasi_perusahaan->html();
        $this->mengapa_bergabung = $why_join->html();
    }

    public function grab() {
        $this->getPostDetail();
        $this->_setContent();
        $this->aggregateContent();
        $this->_getHost();
        $this->setCommentStatus('closed');
        $this->cleanUp();
        $this->_getFeaturedImage();
        $this->generateSeoMetaTitle();
        $this->generateSeoMetaDescription();
        $this->generateSeoMetaKeywords();
        $this->_setTags();
    }

    protected function _setContent() {
        $content = '';
        $content .= '<h2>Informasi Perusahaan</h2>';
        $content .= (!empty($this->informasi_perusahaan)) ? $this->informasi_perusahaan : '<p>tidak ada deskripsi</p>';
        $content .= '<p><strong>Lokasi Kerja: </strong> <br>' . $this->lokasi_kerja . '</p>';
        $content .= '<h2>Deskripsi Pekerjaan dan Persyaratan</h2>';
        $content .= (!empty($this->job_description)) ? $this->job_description : '<p>tidak ada deskripsi</p>';
        $content .= '<p><strong>Syarat Pengalaman Kerja: </strong>' . $this->experience_req . '</p>';
        $content .= '<h2>Mengapa Bergabung Dengan Kami?</h2>';
        $content .= (!empty($this->mengapa_bergabung)) ? $this->mengapa_bergabung : '<p>tidak ada deskripsi</p>';
        $content .= '<h2>Gambaran Perusahaan</h2>';
        $content .= '<p>';
        $content .= (!empty($this->industri)) ? '<strong>Industri/Bidang: </strong>' . $this->industri . '<br />' : '<strong>Industri/Bidang: </strong>-<br />';
        $content .= (!empty($this->ukuran_perusahaan)) ? '<strong>Ukuran Perusahaan: </strong>' . $this->ukuran_perusahaan . '<br />' : '<strong>Ukuran Perusahaan: </strong>-<br />';
        $content .= '<strong>---</strong><br />';
        $content .= (!empty($this->waktu_kerja)) ? '<strong>Waktu Bekerja: </strong>' . $this->waktu_kerja . '<br />' : '<strong>Waktu Bekerja: </strong>-<br />';
        $content .= (!empty($this->bahasa_digunakan)) ? '<strong>Bahasa Digunakan: </strong>' . $this->bahasa_digunakan . '<br />' : '<strong>Bahasa Digunakan: </strong>-<br />';
        $content .= (!empty($this->gaya_pakaian)) ? '<strong>Gaya Pakaian: </strong>' . $this->gaya_pakaian . '<br />' : '<strong>Gaya Pakaian: </strong>-<br />';
        $content .= (!empty($this->tunjangan)) ? '<strong>Tunjangan: </strong>' . $this->tunjangan . '<br />' : '<strong>Tunjangan: </strong>-<br />';
        $content .= '<strong>---</strong><br />';
        $content .= (!empty($this->lokasi_kerja)) ? '<strong>Alamat: </strong>' . $this->lokasi_kerja . '<br />' : '<strong>Alamat: </strong>hubungi perusahaan kami melalui telepon/ website/ fanpage<br />';
        $content .= (!empty($this->telepon)) ? '<strong>Telepon: </strong>' . $this->telepon . '<br />' : '<strong>Telepon: </strong>-<br />';
        $content .= (!empty($this->situs)) ? '<strong>Website: </strong><a href="' . $this->situs . '" target="_blank" rel="nofollow">' . $this->situs . '</a><br />' : '<strong>Website: </strong>-<br />';
        $content .= (!empty($this->fanpage)) ? '<strong>Fan Page: </strong><a href="' . $this->fanpage . '" target="_blank" rel="nofollow">' . $this->fanpage . '</a><br />' : '<strong>Fan Page: </strong>-<br />';
        $content .= '</p>';

        $this->content = $content;
    }

    public function getNamaPerusahaan() {
        return $this->perusahaan;
    }

    protected function generateSeoMetaDescription() {
        $meta_description = 'Lowongan kerja Perusahaan ' . $this->perusahaan . ', Posisi ' . $this->posisi_job . ', Lokasi ' . $this->lokasi_kerja;
        $this->meta_description = $meta_description;
    }

    protected function generateSeoMetaKeywords() {
        $meta_keywords = $this->perusahaan . ',' . $this->posisi_job . ',' . $this->lokasi_kerja;
        $this->meta_keywords = $meta_keywords;
    }

    protected function _getFeaturedImage() {
        if (!$this->no_image) {
            $this->featured_image = $this->node->find('img[id="company_banner"]')->attr('data-original');
        }
    }

    /**
     * set tags untuk loker berdasar kategori source
     */
    protected function _setTags() {
        $this->tags = [(int) $this->source_category];
    }

    public function getDefaultAttachID() {
        return $this->feature_image_id;
    }

    public function getExpDate() {
        return $this->expdate;
    }

}
