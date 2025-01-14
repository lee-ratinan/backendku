<?php

namespace App\Controllers;

use App\Models\ProfileIdentityModel;
use CodeIgniter\HTTP\ResponseInterface;
use Spipu\Html2Pdf\Exception\Html2PdfException;
use Spipu\Html2Pdf\Html2Pdf;

class Profile extends BaseController
{

    const PERMISSION_REQUIRED = 'profile';

    /**
     * @param string $content
     * @param string $file_name
     * @return ResponseInterface|string
     */
    private function generatePdf(string $content, string $file_name): ResponseInterface|string
    {
        try {
            $html2pdf = new Html2Pdf('P', 'A4', 'en', true, 'UTF-8', [15, 15, 15, 15]);
            $html2pdf->setDefaultFont('Arial');
            $html2pdf->pdf->SetDisplayMode('fullpage');
            $html2pdf->pdf->SetTitle($file_name);
            $html2pdf->pdf->SetAuthor('Ratinan L.');
            $html2pdf->pdf->SetCreator('Ratinan L.');
            $html2pdf->pdf->SetSubject('Resume');
            $html2pdf->pdf->SetKeywords('resume, curriculum vitae, cv');
            $html2pdf->writeHTML($content);
            $file_content = $html2pdf->output($file_name);
            return $this->response
                ->setHeader('Content-Type', 'application/pdf')
                ->setBody($file_content);
        } catch (Html2PdfException $e) {
            return $e->getMessage();
        }
    }

    /**
     * @return string
     */
    public function index(): string
    {
        if (PERMISSION_NOT_PERMITTED == retrieve_permission_for_user(self::PERMISSION_REQUIRED)) {
            return permission_denied();
        }
        $session = session();
        $model   = new ProfileIdentityModel();
        $data    = [
            'page_title'       => 'Profile',
            'slug'             => 'profile-data',
            'user_session'     => $session->user,
            'roles'            => $session->roles,
            'current_role'     => $session->current_role,
            'document_types'   => $model->getDocumentTypes(),
            'documents'        => $model->findAll()
        ];
        return view('profile_data', $data);
    }

    /**
     * @return string
     */
    public function resume(): string
    {
        if (PERMISSION_NOT_PERMITTED == retrieve_permission_for_user(self::PERMISSION_REQUIRED)) {
            return permission_denied();
        }
        $session = session();
        $data    = [
            'page_title'       => 'Resume',
            'slug'             => 'resume',
            'user_session'     => $session->user,
            'roles'            => $session->roles,
            'current_role'     => $session->current_role
        ];
        return view('profile_resume', $data);
    }

    /**
     * @return ResponseInterface|string
     */
    public function resumeBuilder(): ResponseInterface|string
    {
        if (PERMISSION_NOT_PERMITTED == retrieve_permission_for_user(self::PERMISSION_REQUIRED)) {
            return permission_denied();
        }
        $return = $this->request->getPost('return');
        $data   = [
            'job_title'  => ucwords(strtolower($this->request->getPost('job_title'))),
            'summary'    => $this->request->getPost('summary'),
            'skills'     => $this->request->getPost('skills'),
            'experience' => $this->request->getPost('experience'),
        ];
        $file_name   = 'Ratinan L - ' . $data['job_title'] . ' - Resume.pdf';
        $resume_html = view('profile_resume_builder', $data);
        if ('html' == $return) {
            return $resume_html;
        }
        return $this->generatePdf($resume_html, $file_name);
    }

    /**
     * @return ResponseInterface|string
     */
    public function resumeCoverLetter(): ResponseInterface|string
    {
        if (PERMISSION_NOT_PERMITTED == retrieve_permission_for_user(self::PERMISSION_REQUIRED)) {
            return permission_denied();
        }
        $return = $this->request->getPost('return');
        $data   = [
            'company_name'   => ucwords(strtolower($this->request->getPost('company_name'))),
            'hiring_manager' => ucwords(strtolower($this->request->getPost('hiring_manager'))),
            'position'       => ucwords(strtolower($this->request->getPost('position'))),
            'paragraph_1'    => $this->request->getPost('paragraph-1'),
            'paragraph_2'    => $this->request->getPost('paragraph-2'),
            'paragraph_3'    => $this->request->getPost('paragraph-3'),
            'paragraph_4'    => $this->request->getPost('paragraph-4'),
            'return'         => $return
        ];
        $file_name   = 'Ratinan L - ' . $data['company_name'] . ' - Cover Letter.pdf';
        $letter_html = view('profile_cover_letter', $data);
        if ('html' == $return) {
            return $letter_html;
        }
        return $this->generatePdf($letter_html, $file_name);
    }
}