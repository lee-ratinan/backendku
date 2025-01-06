<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Employment extends BaseController
{

    /************************************************************************
     * COMPANY
     ************************************************************************/

    /**
     * @return string
     */
    public function index(): string
    {
        $session = session();
        $data    = [
            'page_title'   => 'Employment',
            'slug'         => 'company',
            'user_session' => $session->user,
            'roles'        => $session->roles,
            'current_role' => $session->current_role
        ];
        return view('employment_company', $data);
    }

    /**
     * @return ResponseInterface
     */
    public function companyList(): ResponseInterface
    {
        return $this->response->setJSON([
            'draw'            => $this->request->getPost('draw'),
            'recordsTotal'    => 0,
            'recordsFiltered' => 0,
            'data'            => []
        ]);
    }

    public function companyEdit(string $port_code = 'new')
    {

    }

    public function companySave()
    {

    }

    /************************************************************************
     * SALARY
     ************************************************************************/

    /**
     * @return string
     */
    public function salary(): string
    {
        $session = session();
        $data    = [
            'page_title'   => 'Salary',
            'slug'         => 'salary',
            'user_session' => $session->user,
            'roles'        => $session->roles,
            'current_role' => $session->current_role
        ];
        return view('employment_salary', $data);
    }

    /**
     * @return ResponseInterface
     */
    public function salaryList(): ResponseInterface
    {
        return $this->response->setJSON([
            'draw'            => $this->request->getPost('draw'),
            'recordsTotal'    => 0,
            'recordsFiltered' => 0,
            'data'            => []
        ]);
    }

    public function salaryEdit(string $port_code = 'new')
    {

    }

    public function salarySave()
    {

    }

    /************************************************************************
     * CPF
     ************************************************************************/

    /**
     * @return string
     */
    public function cpf(): string
    {
        $session = session();
        $data    = [
            'page_title'   => 'CPF',
            'slug'         => 'cpf',
            'user_session' => $session->user,
            'roles'        => $session->roles,
            'current_role' => $session->current_role
        ];
        return view('employment_cpf', $data);
    }

    /**
     * @return ResponseInterface
     */
    public function cpfList(): ResponseInterface
    {
        return $this->response->setJSON([
            'draw'            => $this->request->getPost('draw'),
            'recordsTotal'    => 0,
            'recordsFiltered' => 0,
            'data'            => []
        ]);
    }

    public function cpfEdit(string $port_code = 'new')
    {

    }

    public function cpfSave()
    {

    }

    /************************************************************************
     * Freelance
     ************************************************************************/

    /**
     * @return string
     */
    public function freelance(): string
    {
        $session = session();
        $data    = [
            'page_title'   => 'Freelance',
            'slug'         => 'freelance',
            'user_session' => $session->user,
            'roles'        => $session->roles,
            'current_role' => $session->current_role
        ];
        return view('employment_freelance', $data);
    }

    /**
     * @return ResponseInterface
     */
    public function freelanceList(): ResponseInterface
    {
        return $this->response->setJSON([
            'draw'            => $this->request->getPost('draw'),
            'recordsTotal'    => 0,
            'recordsFiltered' => 0,
            'data'            => []
        ]);
    }

    public function freelanceEdit(string $port_code = 'new')
    {

    }

    public function freelanceSave()
    {

    }

}