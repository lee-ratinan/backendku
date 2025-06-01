<?php

namespace App\Controllers;

use App\Models\DocumentMasterModel;
use App\Models\DocumentVersionModel;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\HTTP\ResponseInterface;

class Document extends BaseController
{

    /**
     * @return string
     */
    public function index(): string
    {
        $session = session();
        $data    = [
            'page_title'   => 'Document',
            'slug_group'   => 'document',
            'slug'         => '/office/document',
            'user_session' => $session->user,
            'roles'        => $session->roles,
            'current_role' => $session->current_role
        ];
        return view('document', $data);
    }

    public function list(): ResponseInterface
    {
        $model              = new DocumentMasterModel();
        $columns            = [
            'doc_title',
            'created_at',
            'updated_at',
            '',
            '',
            '',
        ];
        $order              = $this->request->getPost('order');
        $search             = $this->request->getPost('search');
        $start              = $this->request->getPost('start');
        $length             = $this->request->getPost('length');
        $order_column_index = $order[0]['column'] ?? 0;
        $order_column       = $columns[$order_column_index];
        $order_direction    = $order[0]['dir'] ?? 'desc';
        $search_value       = $search['value'];
        $result             = $model->getDataTables($start, $length, $order_column, $order_direction, $search_value);
        return $this->response->setJSON([
            'draw'            => $this->request->getPost('draw'),
            'recordsTotal'    => $result['recordsTotal'],
            'recordsFiltered' => $result['recordsFiltered'],
            'data'            => $result['data']
        ]);
    }

    /**
     * @param int|string $id
     * @return string
     */
    public function edit(int|string $id = 0): string
    {
        $session       = session();
        $doc_model     = new DocumentMasterModel();
        $page_title    = 'New Document';
        $mode          = 'new';
        $published     = [];
        if ('new' != $id && is_numeric($id)) {
            $id         = $id/$doc_model::ID_NONCE;
            $document   = $doc_model->find($id);
            $page_title = 'Edit [' . $document['doc_title'] . ']';
            $mode       = 'edit';
            // retrieve published versions
            $version_model = new DocumentVersionModel();
            $published     = $version_model->getVersions($id);
        } else {
            $document   = [];
        }
        $data    = [
            'page_title'   => $page_title,
            'slug_group'   => 'document',
            'slug'         => '/office/document',
            'user_session' => $session->user,
            'roles'        => $session->roles,
            'current_role' => $session->current_role,
            'mode'         => $mode,
            'document'     => $document,
            'published'    => $published,
            'config'       => $doc_model->getConfigurations()
        ];
        return view('document_edit', $data);
    }

    public function save(): ResponseInterface
    {
        return $this->response->setJSON([]);
    }

    /**
     * @param string $mode
     * @param string $slug
     * @param string $version
     * @return string
     */
    public function view(string $mode, string $slug, string $version = ''): string
    {
        $doc_model = new DocumentMasterModel();
        $document  = $doc_model->getDocumentVersion('doc_slug', $slug, $version);
        if (!$document) {
            throw PageNotFoundException::forPageNotFound();
        }
        echo '<pre>';
        print_r($document);
        echo '</pre>';
        if ('public' === $mode) {
            return '1';
        }
        // internal mode
        return '0';
    }
}