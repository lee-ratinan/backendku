<?php

namespace App\Controllers;

namespace App\Controllers;

use CodeIgniter\HTTP\ResponseInterface;

class Shavian extends BaseController
{

    public function index(): string
    {
        $data = [
            'page_title' => '𐑖𐑱𐑝𐑾𐑯',
            'slug_group' => 'shavian',
            'slug'       => '/office/shavian',
        ];
        return view('shavian', $data);
    }

    public function ajaxTranslator(): ResponseInterface
    {
        helper('math');
        $mode = $this->request->getPost('mode');
        if ('transcribe' == $mode) {
            $text        = $this->request->getPost('text');
            $error       = '-';
            $transcribed = '-';
            $file        = '-';
            if (empty($text)) {
                $text    = '-';
                $error   = 'Sorry, the text is empty.';
            } else {
                $file = 'dictionary/sh_to_en.php';
                $pattern = '/[A-Za-z]+/';
                if (preg_match($pattern, $text) === 1) {
                    $file = 'dictionary/en_to_sh.php';
                }
                $transcribed = transcribeShavian($text, $file);
            }
            return $this->response->setJSON([
                'mode'                => 'transcribe',
                'original_message'    => $text,
                'transcribed_message' => $transcribed,
                'error'               => $error,
                'file'                => $file,
            ]);
        }
        return $this->response->setJSON([]);
    }
}